<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    protected $primaryKey = 'id_project';
    public $timestamps = true;

    protected $fillable = [
        'project_name',
        'client_name',
        'location',
        'description',
        'info_project',
        'summary_description',
        'project_category',
        'category_lookup_id',
        'url_project',
        'slug_project',
        'images',
        'featured_image',
        'other_projects',
        'technologies_used',
        'project_duration',
        'project_year',
        'status',
        'sequence',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'likes_count',
    ];

    protected $casts = [
        'images' => 'array',
        'other_projects' => 'array',
        'technologies_used' => 'array',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'sequence' => 'integer',
        'project_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'featured_image_url',
        'image_urls',
        'main_image',
        'category_info',
        'formatted_duration',
        'reading_time',
        'share_url',
    ];

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('images/projects/' . $this->featured_image);
        }
        return null;
    }

    /**
     * Get all project images URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            return asset('images/projects/' . $image);
        })->toArray();
    }

    /**
     * Get the first image as main image
     */
    public function getMainImageAttribute()
    {
        if ($this->featured_image) {
            return $this->featured_image_url;
        }

        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('images/projects/' . $this->images[0]);
        }

        return asset('images/placeholder/project-placeholder.jpg');
    }

    /**
     * Scope for active projects
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for ordering by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(LookupData::class, 'category_lookup_id', 'id')
                    ->where('lookup_type', 'project_category')
                    ->where('is_active', 1);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'project_id', 'id_project');
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'project_awards', 'project_id', 'award_id', 'id_project', 'id_award');
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sequence', 'asc')->orderBy('created_at', 'desc');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_lookup_id', $categoryId);
    }

    public function scopeByCategoryCode(Builder $query, $categoryCode): Builder
    {
        return $query->whereHas('category', function($q) use ($categoryCode) {
            $q->where('lookup_code', $categoryCode);
        });
    }

    public function scopeByYear(Builder $query, $year): Builder
    {
        return $query->where('project_year', $year);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('views_count', 'desc')
                    ->orderBy('likes_count', 'desc');
    }

    public function scopeRecent(Builder $query, $days = 30): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeWithCategory(Builder $query): Builder
    {
        return $query->with(['category:id,lookup_name,lookup_code,lookup_icon,lookup_color']);
    }

    public function scopeForHomepage(Builder $query, $limit = 9): Builder
    {
        return $query->active()
                    ->ordered()
                    ->withCategory()
                    ->limit($limit);
    }

    /**
     * Enhanced Accessors
     */
    public function getCategoryInfoAttribute()
    {
        if ($this->category) {
            return [
                'name' => $this->category->lookup_name,
                'code' => $this->category->lookup_code,
                'icon' => $this->category->lookup_icon,
                'color' => $this->category->lookup_color,
            ];
        }

        return [
            'name' => $this->project_category ?? 'Uncategorized',
            'code' => 'other',
            'icon' => 'fas fa-folder',
            'color' => '#6b7280',
        ];
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->project_duration) {
            return null;
        }

        // Parse duration (e.g., "3 months", "2 weeks", "1 year")
        return $this->project_duration;
    }

    public function getReadingTimeAttribute()
    {
        if (!$this->description) {
            return 1;
        }

        $wordCount = str_word_count(strip_tags($this->description));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    public function getShareUrlAttribute()
    {
        return route('portfolio.detail', $this->slug_project);
    }

    public function getTechnologiesListAttribute()
    {
        if (!$this->technologies_used || !is_array($this->technologies_used)) {
            return [];
        }

        return array_filter($this->technologies_used);
    }

    public function getEstimatedReadTimeAttribute()
    {
        $content = strip_tags($this->description . ' ' . ($this->info_project ?? ''));
        $wordCount = str_word_count($content);
        return max(1, ceil($wordCount / 200));
    }

    /**
     * Enhanced Methods
     */
    public function incrementViews()
    {
        $this->increment('views_count');
        Cache::forget('popular_projects');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
        Cache::forget('popular_projects');
    }

    public function isPopular()
    {
        return $this->views_count > 100 || $this->likes_count > 10;
    }

    public function isRecent($days = 30)
    {
        return $this->created_at->diffInDays(now()) <= $days;
    }

    public function generateSlug($title = null)
    {
        $title = $title ?? $this->project_name;
        $slug = Str::slug($title);

        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug_project', $slug)
                    ->where('id_project', '!=', $this->id_project ?? 0)
                    ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function deleteImages()
    {
        // Delete featured image
        if ($this->featured_image) {
            $this->deleteImageFile($this->featured_image);
        }

        // Delete all images
        if ($this->images && is_array($this->images)) {
            foreach ($this->images as $image) {
                $this->deleteImageFile($image);
            }
        }
    }

    private function deleteImageFile($filename)
    {
        $path = public_path('images/projects/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Auto-generate slug and handle images when saving
     */
    protected static function booted()
    {
        static::creating(function ($project) {
            // Auto-generate slug if not provided
            if (!$project->slug_project && $project->project_name) {
                $project->slug_project = $project->generateSlug();
            }

            // Set default values
            $project->views_count = $project->views_count ?? 0;
            $project->likes_count = $project->likes_count ?? 0;
            $project->is_featured = $project->is_featured ?? false;
            $project->sequence = $project->sequence ?? 999;

            // Auto-generate meta fields if not provided
            if (!$project->meta_title && $project->project_name) {
                $project->meta_title = $project->project_name . ' | Portfolio';
            }

            if (!$project->meta_description && $project->summary_description) {
                $project->meta_description = Str::limit($project->summary_description, 155);
            }
        });

        static::updating(function ($project) {
            // Re-generate slug if project name changed
            if ($project->isDirty('project_name')) {
                $project->slug_project = $project->generateSlug();
            }

            // Update meta fields if main fields changed
            if ($project->isDirty('project_name') && !$project->isDirty('meta_title')) {
                $project->meta_title = $project->project_name . ' | Portfolio';
            }

            if ($project->isDirty('summary_description') && !$project->isDirty('meta_description')) {
                $project->meta_description = Str::limit($project->summary_description, 155);
            }
        });

        static::deleting(function ($project) {
            $project->deleteImages();

            // Clear related caches
            Cache::forget('popular_projects');
            Cache::forget('featured_projects');
            Cache::forget('homepage_projects');
            Cache::forget('recent_projects');
        });

        static::saved(function ($project) {
            // Clear relevant caches when project is saved
            Cache::forget('homepage_projects');
            Cache::forget('featured_projects');
            Cache::forget('recent_projects');
            if ($project->is_featured) {
                Cache::forget('featured_projects');
            }
        });
    }

    /**
     * Static methods for common queries
     */
    public static function getFeaturedProjects($limit = 6)
    {
        return Cache::remember('featured_projects', 3600, function() use ($limit) {
            return static::featured()
                        ->active()
                        ->withCategory()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getPopularProjects($limit = 6)
    {
        return Cache::remember('popular_projects', 1800, function() use ($limit) {
            return static::active()
                        ->withCategory()
                        ->popular()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getRecentProjects($limit = 6)
    {
        return Cache::remember('recent_projects', 900, function() use ($limit) {
            return static::active()
                        ->withCategory()
                        ->orderBy('created_at', 'desc')
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getProjectsByCategory($categoryCode, $limit = null)
    {
        $query = static::active()
                      ->byCategoryCode($categoryCode)
                      ->withCategory()
                      ->ordered();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function searchProjects($search, $filters = [])
    {
        $query = static::active()->withCategory();

        // Search in multiple fields
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('project_name', 'LIKE', "%{$search}%")
                  ->orWhere('client_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('summary_description', 'LIKE', "%{$search}%")
                  ->orWhere('project_category', 'LIKE', "%{$search}%");
            });
        }

        // Apply filters
        if (isset($filters['category'])) {
            $query->byCategoryCode($filters['category']);
        }

        if (isset($filters['year'])) {
            $query->byYear($filters['year']);
        }

        if (isset($filters['featured'])) {
            $query->featured();
        }

        return $query->ordered()->paginate($filters['per_page'] ?? 12);
    }
}
