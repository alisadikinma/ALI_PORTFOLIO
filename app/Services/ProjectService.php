<?php

namespace App\Services;

use App\Models\Project;
use App\Models\LookupData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

/**
 * Project Service - Handles all project-related business logic
 */
class ProjectService
{
    const CACHE_DURATION = [
        'homepage_projects' => 1800, // 30 minutes
        'featured_projects' => 3600, // 1 hour
        'project_categories' => 7200, // 2 hours
        'project_statistics' => 1800, // 30 minutes
    ];

    const IMAGE_SETTINGS = [
        'max_size' => 5120, // 5MB in KB
        'allowed_types' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
        'max_width' => 1920,
        'max_height' => 1080,
        'quality' => 85,
    ];

    /**
     * Get projects for homepage with caching
     */
    public static function getHomepageProjects($limit = 9)
    {
        return Cache::remember('homepage_projects', self::CACHE_DURATION['homepage_projects'], function() use ($limit) {
            return Project::forHomepage($limit)->get();
        });
    }

    /**
     * Get featured projects with caching
     */
    public static function getFeaturedProjects($limit = 6)
    {
        return Project::getFeaturedProjects($limit);
    }

    /**
     * Get popular projects with caching
     */
    public static function getPopularProjects($limit = 6)
    {
        return Project::getPopularProjects($limit);
    }

    /**
     * Get recent projects with caching
     */
    public static function getRecentProjects($limit = 6)
    {
        return Project::getRecentProjects($limit);
    }

    /**
     * Get project by slug with view increment
     */
    public static function getProjectBySlug($slug, $incrementView = false)
    {
        $project = Project::active()
                         ->withCategory()
                         ->where('slug_project', $slug)
                         ->first();

        if (!$project) {
            return null;
        }

        if ($incrementView) {
            // Use session to prevent multiple views from same user
            $sessionKey = 'viewed_project_' . $project->id_project;
            if (!session()->has($sessionKey)) {
                $project->incrementViews();
                session()->put($sessionKey, true);
            }
        }

        return $project;
    }

    /**
     * Create new project with image handling
     */
    public static function createProject(array $data, $imageFiles = [])
    {
        DB::beginTransaction();

        try {
            // Process and upload images
            $processedImages = self::processImages($imageFiles);

            // Prepare project data
            $projectData = self::prepareProjectData($data);
            $projectData['images'] = $processedImages['images'];
            $projectData['featured_image'] = $processedImages['featured_image'];

            // Create project
            $project = Project::create($projectData);

            // Clear relevant caches
            self::clearProjectCaches();

            DB::commit();

            Log::info('Project created successfully', [
                'project_id' => $project->id_project,
                'project_name' => $project->project_name,
                'images_count' => count($processedImages['images'])
            ]);

            return $project;

        } catch (Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if project creation failed
            if (isset($processedImages)) {
                self::cleanupImages($processedImages['images']);
            }

            Log::error('Project creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Update existing project with image handling
     */
    public static function updateProject(Project $project, array $data, $imageFiles = [], $deleteImages = [])
    {
        DB::beginTransaction();

        try {
            $oldImages = $project->images ?? [];
            $currentImages = $oldImages;

            // Handle image deletions
            if (!empty($deleteImages)) {
                foreach ($deleteImages as $imageToDelete) {
                    if (in_array($imageToDelete, $currentImages)) {
                        self::deleteImageFile($imageToDelete);
                        $currentImages = array_values(array_diff($currentImages, [$imageToDelete]));
                    }
                }
            }

            // Process new images if any
            if (!empty($imageFiles)) {
                $processedImages = self::processImages($imageFiles);
                $currentImages = array_merge($currentImages, $processedImages['images']);
            }

            // Prepare project data
            $projectData = self::prepareProjectData($data);
            $projectData['images'] = $currentImages;

            // Handle featured image
            if (isset($data['featured_image_index']) && isset($currentImages[$data['featured_image_index']])) {
                $projectData['featured_image'] = $currentImages[$data['featured_image_index']];
            } elseif (!$project->featured_image && !empty($currentImages)) {
                $projectData['featured_image'] = $currentImages[0];
            }

            // Update project
            $project->update($projectData);

            // Clear relevant caches
            self::clearProjectCaches();

            DB::commit();

            Log::info('Project updated successfully', [
                'project_id' => $project->id_project,
                'project_name' => $project->project_name,
                'images_count' => count($currentImages)
            ]);

            return $project;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Project update failed', [
                'project_id' => $project->id_project,
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Delete project with cleanup
     */
    public static function deleteProject(Project $project)
    {
        DB::beginTransaction();

        try {
            $projectName = $project->project_name;
            $projectId = $project->id_project;

            // Delete associated images
            $project->deleteImages();

            // Delete the project
            $project->delete();

            // Clear relevant caches
            self::clearProjectCaches();

            DB::commit();

            Log::info('Project deleted successfully', [
                'project_id' => $projectId,
                'project_name' => $projectName
            ]);

            return true;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Project deletion failed', [
                'project_id' => $project->id_project,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Search projects with filters
     */
    public static function searchProjects($search = null, $filters = [])
    {
        return Project::searchProjects($search, $filters);
    }

    /**
     * Get projects by category
     */
    public static function getProjectsByCategory($categoryCode, $limit = null)
    {
        return Project::getProjectsByCategory($categoryCode, $limit);
    }

    /**
     * Get project categories from lookup data
     */
    public static function getProjectCategories()
    {
        return Cache::remember('project_categories', self::CACHE_DURATION['project_categories'], function() {
            return LookupData::getProjectCategories();
        });
    }

    /**
     * Get project statistics
     */
    public static function getProjectStatistics()
    {
        return Cache::remember('project_statistics', self::CACHE_DURATION['project_statistics'], function() {
            return [
                'total_projects' => Project::active()->count(),
                'featured_projects' => Project::active()->featured()->count(),
                'categories_count' => LookupData::byType('project_category')->active()->count(),
                'total_views' => Project::active()->sum('views_count'),
                'total_likes' => Project::active()->sum('likes_count'),
                'recent_projects' => Project::active()->recent(30)->count(),
                'projects_by_year' => Project::active()
                    ->selectRaw('project_year, COUNT(*) as count')
                    ->whereNotNull('project_year')
                    ->groupBy('project_year')
                    ->orderBy('project_year', 'desc')
                    ->pluck('count', 'project_year')
                    ->toArray(),
                'projects_by_category' => Project::active()
                    ->join('lookup_data', 'project.category_lookup_id', '=', 'lookup_data.id_lookup_data')
                    ->selectRaw('lookup_data.lookup_name as category, COUNT(*) as count')
                    ->groupBy('lookup_data.lookup_name')
                    ->orderBy('count', 'desc')
                    ->pluck('count', 'category')
                    ->toArray(),
            ];
        });
    }

    /**
     * Process uploaded images
     */
    private static function processImages($imageFiles)
    {
        if (empty($imageFiles)) {
            return ['images' => [], 'featured_image' => null];
        }

        $uploadedImages = [];
        $featuredImage = null;

        // Ensure project directory exists
        $projectDir = public_path('images/projects');
        if (!File::exists($projectDir)) {
            File::makeDirectory($projectDir, 0755, true);
        }

        foreach ($imageFiles as $index => $image) {
            // Validate image
            if (!self::validateImage($image)) {
                throw new Exception("Invalid image file: " . $image->getClientOriginalName());
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = 'project_' . time() . '_' . $index . '_' . uniqid() . '.' . $extension;

            // Move file to destination
            if ($image->move($projectDir, $filename)) {
                $uploadedImages[] = $filename;

                // Set first image as featured if none specified
                if ($index === 0 && !$featuredImage) {
                    $featuredImage = $filename;
                }
            } else {
                throw new Exception('Failed to upload image: ' . $image->getClientOriginalName());
            }
        }

        return [
            'images' => $uploadedImages,
            'featured_image' => $featuredImage
        ];
    }

    /**
     * Validate uploaded image
     */
    private static function validateImage($image)
    {
        // Check if file is valid
        if (!$image->isValid()) {
            return false;
        }

        // Check file size
        if ($image->getSize() > self::IMAGE_SETTINGS['max_size'] * 1024) {
            return false;
        }

        // Check file type
        $extension = strtolower($image->getClientOriginalExtension());
        if (!in_array($extension, self::IMAGE_SETTINGS['allowed_types'])) {
            return false;
        }

        return true;
    }

    /**
     * Prepare project data for database
     */
    private static function prepareProjectData(array $data)
    {
        return [
            'project_name' => trim($data['project_name']),
            'client_name' => trim($data['client_name']),
            'location' => trim($data['location']),
            'description' => trim($data['description']),
            'summary_description' => isset($data['summary_description']) ? trim($data['summary_description']) : null,
            'project_category' => trim($data['project_category']),
            'category_lookup_id' => $data['category_lookup_id'] ?? null,
            'url_project' => isset($data['url_project']) ? trim($data['url_project']) : null,
            'technologies_used' => $data['technologies_used'] ?? null,
            'project_duration' => $data['project_duration'] ?? null,
            'project_year' => $data['project_year'] ?? null,
            'sequence' => $data['sequence'] ?? 999,
            'is_featured' => $data['is_featured'] ?? false,
            'status' => $data['status'] ?? 'Active',
            'other_projects' => isset($data['other_projects']) ? self::processOtherProjects($data['other_projects']) : null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
        ];
    }

    /**
     * Process other projects data
     */
    private static function processOtherProjects($otherProjects)
    {
        if (empty($otherProjects) || !is_array($otherProjects)) {
            return null;
        }

        $cleanedProjects = array_unique(
            array_filter(
                array_map(function($item) {
                    return trim(strip_tags($item));
                }, $otherProjects),
                function($item) {
                    return !empty($item) && strlen($item) > 0;
                }
            )
        );

        return !empty($cleanedProjects) ? array_values($cleanedProjects) : null;
    }

    /**
     * Delete image file
     */
    private static function deleteImageFile($filename)
    {
        $path = public_path('images/projects/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Cleanup uploaded images (used when project creation fails)
     */
    private static function cleanupImages($images)
    {
        if (empty($images)) return;

        foreach ($images as $image) {
            self::deleteImageFile($image);
        }
    }

    /**
     * Clear project-related caches
     */
    private static function clearProjectCaches()
    {
        $cacheKeys = [
            'homepage_projects',
            'featured_projects',
            'popular_projects',
            'recent_projects',
            'project_statistics',
            'homepage_data',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Bulk operations
     */
    public static function bulkUpdateSequence(array $sequenceData)
    {
        DB::beginTransaction();

        try {
            foreach ($sequenceData as $projectId => $sequence) {
                Project::where('id_project', $projectId)
                       ->update(['sequence' => $sequence]);
            }

            self::clearProjectCaches();

            DB::commit();

            Log::info('Bulk sequence update completed', ['updated_count' => count($sequenceData)]);

            return true;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Bulk sequence update failed', [
                'error' => $e->getMessage(),
                'data' => $sequenceData
            ]);

            throw $e;
        }
    }

    public static function bulkToggleStatus(array $projectIds, $status = 'Active')
    {
        DB::beginTransaction();

        try {
            $updated = Project::whereIn('id_project', $projectIds)
                             ->update(['status' => $status]);

            self::clearProjectCaches();

            DB::commit();

            Log::info('Bulk status update completed', [
                'updated_count' => $updated,
                'status' => $status
            ]);

            return $updated;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Bulk status update failed', [
                'error' => $e->getMessage(),
                'project_ids' => $projectIds,
                'status' => $status
            ]);

            throw $e;
        }
    }

    /**
     * Generate project report data
     */
    public static function generateProjectReport($filters = [])
    {
        $query = Project::query();

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category'])) {
            $query->where('project_category', $filters['category']);
        }

        if (isset($filters['year'])) {
            $query->where('project_year', $filters['year']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $projects = $query->with('category')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return [
            'projects' => $projects,
            'summary' => [
                'total_count' => $projects->count(),
                'active_count' => $projects->where('status', 'Active')->count(),
                'featured_count' => $projects->where('is_featured', true)->count(),
                'total_views' => $projects->sum('views_count'),
                'total_likes' => $projects->sum('likes_count'),
                'avg_rating' => $projects->avg('rating'),
            ]
        ];
    }
}