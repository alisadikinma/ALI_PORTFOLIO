<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class ProjectController extends Controller
{
    /**
     * Get project categories from lookup_data table
     */
    private function getProjectCategories()
    {
        try {
            Log::info('Getting project categories - Starting query');
            
            // Simple direct query - get all project categories that are active
            $result = DB::table('lookup_data')
                    ->where('lookup_type', 'project_category')
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('lookup_name', 'asc')
                    ->get();
            
            Log::info('Project categories query result: ' . $result->count() . ' categories found');
            
            if ($result->count() > 0) {
                Log::info('Categories loaded successfully', [
                    'count' => $result->count(),
                    'first_category' => $result->first()->lookup_name ?? 'No name'
                ]);
            } else {
                Log::warning('No project categories found in lookup_data table');
                
                // Check if there's any data at all
                $totalRecords = DB::table('lookup_data')->count();
                $projectCategoryRecords = DB::table('lookup_data')->where('lookup_type', 'project_category')->count();
                
                Log::warning('Lookup data stats', [
                    'total_records' => $totalRecords,
                    'project_category_records' => $projectCategoryRecords
                ]);
            }
            
            return $result;
            
        } catch (Exception $e) {
            Log::error('Error loading project categories: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return empty collection on error
            return collect([]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $title = 'Data Portfolio';
            
            // Test database connection first
            try {
                $connectionTest = DB::connection()->getPdo();
                Log::info('Database connection successful');
            } catch (Exception $dbError) {
                Log::error('Database connection failed: ' . $dbError->getMessage());
                throw new Exception('Database connection failed. Please check your database settings.');
            }
            
            // Check if project table exists
            if (!DB::getSchemaBuilder()->hasTable('project')) {
                throw new Exception('Project table does not exist in database');
            }
            
            // Simple query without join first
            $project = DB::table('project')
                        ->orderBy('sequence', 'asc')
                        ->orderBy('id_project', 'desc')
                        ->get();
            
            Log::info('Project Index - Projects found: ' . $project->count());
            
            // Add category_name field manually for compatibility
            $project = $project->map(function($item) {
                $item->category_name = $item->project_category ?? 'N/A';
                return $item;
            });
            
            return view('project.index', compact('project', 'title'));
            
        } catch (Exception $e) {
            Log::error('Project Index Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Ultra-safe fallback
            $project = collect([]);
            $title = 'Data Portfolio';
            
            return view('project.index', compact('project', 'title'))
                   ->with('error', 'Error loading projects: ' . $e->getMessage() . '. Please check your database connection and ensure XAMPP MySQL is running.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Tambah Portfolio';
            
            // Get categories from database
            $projectCategories = $this->getProjectCategories();
            
            Log::info('Project Create - Categories for view', [
                'categories_count' => $projectCategories->count(),
                'has_categories' => $projectCategories->count() > 0
            ]);
            
            // Ensure directories exist
            try {
                $imageDir = public_path('images/projects');
                if (!File::exists($imageDir)) {
                    File::makeDirectory($imageDir, 0755, true);
                }
                
                $editorDir = public_path('images/editor');
                if (!File::exists($editorDir)) {
                    File::makeDirectory($editorDir, 0755, true);
                }
            } catch (Exception $dirError) {
                Log::warning('Project Create - Directory creation warning: ' . $dirError->getMessage());
            }
            
            return view('project.create', compact('title', 'projectCategories'));
            
        } catch (Exception $e) {
            Log::error('Project Create Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Fallback with empty collection
            $title = 'Tambah Portfolio';
            $projectCategories = collect([]);
            
            return view('project.create', compact('title', 'projectCategories'))
                   ->with('error', 'Warning: Could not load project categories from database.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'project_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.\(\)]+$/',
                'client_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.\(\)]+$/',
                'location' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.,]+$/',
                'description' => 'required|string|max:10000',
                'summary_description' => 'nullable|string|max:500',
                'project_category' => 'required|string|max:255|alpha_dash',
                'slug_project' => 'required|string|max:255|unique:project,slug_project|regex:/^[a-z0-9\-]+$/',
                'url_project' => 'nullable|url|max:255',
                'images' => 'required|array|min:1|max:10',
                'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
                'sequence' => 'nullable|integer|min:0|max:9999'
            ];

            $messages = [
                'required' => ':attribute wajib diisi!',
                'string' => ':attribute harus berupa teks!',
                'max' => ':attribute maksimal :max karakter!',
                'project_category.required' => 'Kategori project wajib diisi!',
                'slug_project.unique' => 'Slug project sudah digunakan, silakan gunakan slug yang lain!',
                'images.required' => 'Minimal harus mengunggah satu gambar project!',
                'images.array' => 'Format gambar tidak valid!',
                'images.min' => 'Minimal harus mengunggah satu gambar!',
                'images.*.image' => 'File harus berupa gambar!',
                'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp!',
                'images.*.max' => 'Ukuran gambar maksimal 2MB!',
            ];

            $validatedData = $request->validate($rules, $messages);

            // Create project directory if it doesn't exist
            $projectDir = public_path('images/projects');
            if (!File::exists($projectDir)) {
                File::makeDirectory($projectDir, 0755, true);
            }

            // Handle image uploads
            $uploadedImages = [];
            $featuredImage = null;

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    // Enhanced security: Validate file type by content, not just extension
                    $mimeType = $image->getMimeType();
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

                    if (!in_array($mimeType, $allowedMimes)) {
                        throw new Exception('Invalid file type detected: ' . $mimeType);
                    }

                    // Generate secure filename
                    $extension = strtolower($image->getClientOriginalExtension());
                    $filename = 'project_' . hash('sha256', uniqid() . time() . $index) . '.' . $extension;
                    
                    // Move file to destination
                    if ($image->move($projectDir, $filename)) {
                        $uploadedImages[] = $filename;

                        // Set featured image
                        if ($request->input('featured_image_index') == $index || ($index == 0 && !$featuredImage)) {
                            $featuredImage = $filename;
                        }
                    } else {
                        throw new Exception('Failed to upload image: ' . $image->getClientOriginalName());
                    }
                }
            }

            if (empty($uploadedImages)) {
                throw new Exception('No images were uploaded successfully');
            }

            // Generate unique slug if empty or validate uniqueness
            $slug = trim($request->slug_project);
            if (empty($slug)) {
                // Fallback auto-generation if somehow empty
                $baseSlug = Str::slug($request->project_name);
                $slug = $baseSlug;
                $counter = 1;
                
                while (DB::table('project')->where('slug_project', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
            } else {
                // Clean the provided slug
                $slug = Str::slug($slug);
            }

            // Handle other projects - enhanced validation and sanitization
            $otherProjectsArray = $request->input('other_projects', []);
            $otherProjectsData = null;
            
            if (!empty($otherProjectsArray) && is_array($otherProjectsArray)) {
                // Filter out empty values, trim each item, and remove duplicates
                $cleanedProjects = array_unique(
                    array_filter(
                        array_map(function($item) {
                            return trim(strip_tags($item)); // Also remove HTML tags for security
                        }, $otherProjectsArray), 
                        function($item) {
                            return !empty($item) && strlen($item) > 0;
                        }
                    )
                );
                
                if (!empty($cleanedProjects)) {
                    // Store as JSON array for multiple projects
                    $otherProjectsData = json_encode(array_values($cleanedProjects)); // Re-index array
                }
            }

            // Sanitize and prepare data for insertion
            $insertData = [
                'project_name' => htmlspecialchars(trim($request->project_name), ENT_QUOTES, 'UTF-8'),
                'client_name' => htmlspecialchars(trim($request->client_name), ENT_QUOTES, 'UTF-8'),
                'location' => htmlspecialchars(trim($request->location), ENT_QUOTES, 'UTF-8'),
                'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
                'summary_description' => $request->summary_description ? htmlspecialchars(trim($request->summary_description), ENT_QUOTES, 'UTF-8') : null,
                'project_category' => htmlspecialchars(trim($request->project_category), ENT_QUOTES, 'UTF-8'),
                'url_project' => $request->url_project ? filter_var(trim($request->url_project), FILTER_SANITIZE_URL) : null,
                'slug_project' => $slug,
                'images' => json_encode($uploadedImages),
                'featured_image' => $featuredImage,
                'sequence' => (int) $request->input('sequence', 0),
                'other_projects' => $otherProjectsData,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Use prepared statement for secure database insertion
            $result = DB::table('project')->insert($insertData);

            if ($result) {
                return redirect()->route('project.index')->with('Sukses', 'Berhasil menambah project: ' . $request->project_name);
            } else {
                throw new Exception('Failed to save project to database');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors - stay on create page with errors
            return redirect()->route('project.create')
                           ->withErrors($e->errors())
                           ->withInput();
                           
        } catch (Exception $e) {
            // Log error for debugging
            Log::error('Project Store Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['images', '_token', 'password'])
            ]);

            // Clean up uploaded files if database insert failed
            if (isset($uploadedImages)) {
                foreach ($uploadedImages as $image) {
                    $filePath = public_path('images/projects/' . $image);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            return redirect()->route('project.create')
                           ->withErrors(['error' => 'Error creating project: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    /**
     * Display the specified resource for public view.
     */
    public function show($slug)
    {
        try {
            // Use simple query without join since category_lookup_id doesn't exist
            $portfolio = DB::table('project')
                        ->where('slug_project', $slug)
                        ->where('status', 'Active')
                        ->first();
            
            if (!$portfolio) {
                abort(404, 'Project not found');
            }
            
            // Set default values for missing properties
            $portfolio->category_name = $portfolio->project_category ?? 'N/A';
            $portfolio->category_icon = null;
            $portfolio->category_color = null;
            $portfolio->category_description = null;
            
            $konf = DB::table('setting')->first();
            
            return view('portfolio_detail', compact('portfolio', 'konf'));
        } catch (Exception $e) {
            Log::error('Project Show Error: ' . $e->getMessage());
            abort(404, 'Project not found');
        }
    }

    /**
     * Display the specified resource for admin view.
     */
    public function showAdmin($id)
    {
        try {
            // Use simple query without join since category_lookup_id doesn't exist
            $project = DB::table('project')
                      ->where('id_project', $id)
                      ->first();
            
            if (!$project) {
                return redirect()->route('project.index')->with('error', 'Project not found');
            }
            
            // Set default values for missing properties
            $project->category_name = $project->project_category ?? 'N/A';
            $project->category_icon = null;
            $project->category_color = null;
            $project->category_description = null;
            
            $title = 'Detail Portfolio';
            return view('project.show', compact('project', 'title'));
        } catch (Exception $e) {
            Log::error('Project ShowAdmin Error: ' . $e->getMessage());
            return redirect()->route('project.index')->with('error', 'Error loading project: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $project = DB::table('project')
                      ->where('id_project', $id)
                      ->first();
            
            if (!$project) {
                return redirect()->route('project.index')->with('error', 'Project not found');
            }

            $title = 'Edit Portfolio';
            $projectCategories = $this->getProjectCategories();
            
            Log::info('Project Edit - Categories for view', [
                'categories_count' => $projectCategories->count(),
                'project_id' => $id
            ]);
            
            return view('project.edit', compact('project', 'title', 'projectCategories'));
            
        } catch (Exception $e) {
            Log::error('Project Edit Error: ' . $e->getMessage());
            return redirect()->route('project.index')->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'project_name' => 'required|string|max:255',
                'client_name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'description' => 'required|string',
                'summary_description' => 'nullable|string|max:500',
                'project_category' => 'required|string|max:255',
                'slug_project' => 'required|string|max:255|unique:project,slug_project,' . $id . ',id_project',
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            ];

            $messages = [
                'required' => ':attribute wajib diisi!',
                'string' => ':attribute harus berupa teks!',
                'max' => ':attribute maksimal :max karakter!',
                'project_category.required' => 'Kategori project wajib diisi!',
                'slug_project.unique' => 'Slug project sudah digunakan, silakan gunakan slug yang lain!',
                'images.*.image' => 'File harus berupa gambar!',
                'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp!',
                'images.*.max' => 'Ukuran gambar maksimal 2MB!',
            ];

            $request->validate($rules, $messages);

            $project = DB::table('project')->where('id_project', $id)->first();
            if (!$project) {
                throw new Exception('Project not found');
            }

            $projectDir = public_path('images/projects');
            if (!File::exists($projectDir)) {
                File::makeDirectory($projectDir, 0755, true);
            }

            // Get current images
            $currentImages = $project->images ? json_decode($project->images, true) : [];
            $uploadedImages = $currentImages;
            $featuredImage = $project->featured_image;

            // Handle image deletions
            if ($request->has('delete_images')) {
                $deleteImages = $request->delete_images;
                foreach ($deleteImages as $imageToDelete) {
                    if (in_array($imageToDelete, $currentImages)) {
                        // Delete physical file
                        $filePath = $projectDir . '/' . $imageToDelete;
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                        // Remove from array
                        $uploadedImages = array_values(array_diff($uploadedImages, [$imageToDelete]));
                        
                        if ($featuredImage == $imageToDelete) {
                            $featuredImage = null;
                        }
                    }
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'project_' . time() . '_' . (count($uploadedImages) + $index) . '_' . uniqid() . '.' . $extension;
                    if ($image->move($projectDir, $filename)) {
                        $uploadedImages[] = $filename;
                    }
                }
            }

            // Set featured image
            if ($request->has('featured_image_index') && isset($uploadedImages[$request->featured_image_index])) {
                $featuredImage = $uploadedImages[$request->featured_image_index];
            } elseif (!$featuredImage && !empty($uploadedImages)) {
                $featuredImage = $uploadedImages[0];
            }

            // Handle slug update
            $slug = trim($request->slug_project);
            if (empty($slug)) {
                // Fallback auto-generation if somehow empty
                $baseSlug = Str::slug($request->project_name);
                $newSlug = $baseSlug;
                $counter = 1;
                while (DB::table('project')->where('slug_project', $newSlug)->where('id_project', '!=', $id)->exists()) {
                    $newSlug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $slug = $newSlug;
            } else {
                // Clean the provided slug
                $slug = Str::slug($slug);
            }

            // Handle other projects - enhanced validation and sanitization
            $otherProjectsArray = $request->input('other_projects', []);
            $otherProjectsData = null;
            
            if (!empty($otherProjectsArray) && is_array($otherProjectsArray)) {
                // Filter out empty values, trim each item, and remove duplicates
                $cleanedProjects = array_unique(
                    array_filter(
                        array_map(function($item) {
                            return trim(strip_tags($item)); // Also remove HTML tags for security
                        }, $otherProjectsArray), 
                        function($item) {
                            return !empty($item) && strlen($item) > 0;
                        }
                    )
                );
                
                if (!empty($cleanedProjects)) {
                    // Store as JSON array for multiple projects
                    $otherProjectsData = json_encode(array_values($cleanedProjects)); // Re-index array
                }
            }

            // Update data
            $updateData = [
                'project_name' => trim($request->project_name),
                'client_name' => trim($request->client_name),
                'location' => trim($request->location),
                'description' => trim($request->description),
                'summary_description' => $request->summary_description ? trim($request->summary_description) : null,
                'project_category' => trim($request->project_category),
                'url_project' => $request->url_project ? trim($request->url_project) : null,
                'slug_project' => $slug,
                'images' => json_encode($uploadedImages),
                'featured_image' => $featuredImage,
                'sequence' => $request->input('sequence', $project->sequence),
                'other_projects' => $otherProjectsData,
                'updated_at' => now()
            ];

            $result = DB::table('project')->where('id_project', $id)->update($updateData);

            return redirect()->route('project.index')->with('Sukses', 'Berhasil mengupdate project: ' . $request->project_name);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('project.edit', $id)->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Project Update Error: ' . $e->getMessage());
            return redirect()->route('project.edit', $id)->withErrors(['error' => 'Error updating project: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $project = DB::table('project')->where('id_project', $id)->first();
            
            if (!$project) {
                throw new Exception('Project not found');
            }

            // Delete all project images
            if ($project->images) {
                $images = json_decode($project->images, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imagePath = public_path('images/projects/' . $image);
                        if (File::exists($imagePath)) {
                            File::delete($imagePath);
                        }
                    }
                }
            }
            
            // Delete project record
            DB::table('project')->where('id_project', $id)->delete();
            
            return redirect()->route('project.index')->with('Sukses', 'Berhasil menghapus project: ' . $project->project_name);
        } catch (Exception $e) {
            Log::error('Project Destroy Error: ' . $e->getMessage());
            return redirect()->route('project.index')->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Delete single image
     */
    public function deleteImage(Request $request, $id)
    {
        try {
            $project = DB::table('project')->where('id_project', $id)->first();
            $imageName = $request->input('image_name');
            
            if (!$project || !$imageName) {
                return response()->json(['success' => false, 'message' => 'Project or image not found']);
            }

            $currentImages = $project->images ? json_decode($project->images, true) : [];
            
            if (!in_array($imageName, $currentImages)) {
                return response()->json(['success' => false, 'message' => 'Image not found in project']);
            }

            // Delete physical file
            $imagePath = public_path('images/projects/' . $imageName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Update project images
            $updatedImages = array_values(array_diff($currentImages, [$imageName]));
            
            // Reset featured image if it was the deleted one
            $featuredImage = $project->featured_image;
            if ($featuredImage == $imageName) {
                $featuredImage = !empty($updatedImages) ? $updatedImages[0] : null;
            }

            DB::table('project')->where('id_project', $id)->update([
                'images' => json_encode($updatedImages),
                'featured_image' => $featuredImage,
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);

        } catch (Exception $e) {
            Log::error('Project Delete Image Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting image: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle image upload for CKEditor
     */
    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048|dimensions:min_width=50,min_height=50,max_width=4000,max_height=4000'
            ], [
                'file.required' => 'File is required',
                'file.image' => 'File must be an image',
                'file.mimes' => 'File must be: jpeg, jpg, png, or webp only',
                'file.max' => 'File size must be less than 2MB',
                'file.dimensions' => 'Image dimensions must be between 50x50 and 4000x4000 pixels'
            ]);

            $editorDir = public_path('images/editor');
            if (!File::exists($editorDir)) {
                File::makeDirectory($editorDir, 0755, true);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

                if (!in_array($mimeType, $allowedMimes)) {
                    throw new Exception('Invalid file MIME type: ' . $mimeType);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $filename = 'editor_' . hash('sha256', uniqid() . time() . rand(1000, 9999)) . '.' . $extension;
                
                if ($file->move($editorDir, $filename)) {
                    $imageUrl = asset('images/editor/' . $filename);
                    
                    return response()->json([
                        'success' => true,
                        'url' => $imageUrl,
                        'message' => 'Image uploaded successfully',
                        'filename' => $filename
                    ]);
                } else {
                    throw new Exception('Failed to move uploaded file');
                }
            }

            throw new Exception('No file was uploaded');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search projects for autocomplete (AJAX)
     */
    public function searchProjects(Request $request)
    {
        try {
            $query = trim($request->get('query', ''));
            $currentId = $request->get('current_id', null);

            if (strlen($query) < 3 || strlen($query) > 100) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');

            if ($currentId !== null && (!is_numeric($currentId) || $currentId < 1)) {
                $currentId = null;
            }

            $searchTerm = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $query) . '%';
            $projects = DB::table('project')
                ->select('id_project', 'project_name', 'client_name', 'slug_project', 'project_category')
                ->where('status', '=', 'Active')
                ->where(function($q) use ($searchTerm) {
                    $q->where('project_name', 'LIKE', $searchTerm)
                      ->orWhere('client_name', 'LIKE', $searchTerm)
                      ->orWhere('project_category', 'LIKE', $searchTerm);
                });

            if ($currentId) {
                $projects->where('id_project', '!=', $currentId);
            }

            $results = $projects->orderBy('project_name')
                              ->limit(10)
                              ->get();

            $formattedResults = $results->map(function($project) {
                return [
                    'id' => $project->id_project,
                    'text' => $project->project_name,
                    'subtitle' => $project->client_name . ' - ' . ($project->project_category ?? 'No Category'),
                    'slug' => $project->slug_project
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedResults
            ]);

        } catch (Exception $e) {
            Log::error('Project Search Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * API: Get projects for frontend (AJAX)
     */
    public function getProjects(Request $request)
    {
        try {
            $projects = DB::table('project')
                       ->where('status', 'Active')
                       ->orderBy('sequence', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->get();

            $projects = $projects->map(function($project) {
                $project->category_name = $project->project_category ?? 'N/A';
                $project->category_icon = null;
                $project->category_color = null;
                $project->category_code = null;
                $project->category_description = null;
                return $project;
            });

            return response()->json([
                'success' => true,
                'projects' => $projects
            ]);

        } catch (Exception $e) {
            Log::error('Get Projects API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading projects: ' . $e->getMessage(),
                'projects' => []
            ], 500);
        }
    }
}
