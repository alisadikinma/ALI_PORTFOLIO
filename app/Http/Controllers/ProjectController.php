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
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $title = 'Data Portfolio';
            $project = DB::table('project')->orderBy('id_project', 'desc')->get();
            return view('project.index', compact('project', 'title'));
        } catch (Exception $e) {
            Log::error('Project Index Error: ' . $e->getMessage());
            return view('project.index', [
                'project' => collect([]), 
                'title' => 'Data Portfolio'
            ])->with('error', 'Error loading projects: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Tambah Portfolio';
            
            // Ensure images directory exists
            $imageDir = public_path('images/projects');
            if (!File::exists($imageDir)) {
                File::makeDirectory($imageDir, 0755, true);
            }
            
            // Ensure editor directory exists
            $editorDir = public_path('images/editor');
            if (!File::exists($editorDir)) {
                File::makeDirectory($editorDir, 0755, true);
            }
            
            // Verify database connection
            DB::connection()->getPdo();
            
            return view('project.create', compact('title'));
        } catch (Exception $e) {
            Log::error('Project Create Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Try to return to project index instead of using back()
            try {
                return redirect()->route('project.index')->with('error', 'Error loading create form: ' . $e->getMessage());
            } catch (Exception $redirectError) {
                // If even redirect fails, return a simple error view
                return response()->view('errors.500', [
                    'error' => 'System Error: Unable to load create form. ' . $e->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'project_name' => 'required|string|max:255',
                'client_name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'description' => 'required|string',
                'summary_description' => 'nullable|string|max:500',
                'project_category' => 'required',
                'slug_project' => 'required|string|max:255|unique:project,slug_project',
                'images' => 'required|array|min:1',
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            ];

            $messages = [
                'required' => ':attribute wajib diisi!',
                'string' => ':attribute harus berupa teks!',
                'max' => ':attribute maksimal :max karakter!',
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
                    // Generate unique filename
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'project_' . time() . '_' . $index . '_' . uniqid() . '.' . $extension;
                    
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

            // Handle other projects - now expecting an array
            $otherProjectsArray = $request->input('other_projects', []);
            $otherProjectsData = null;
            
            if (!empty($otherProjectsArray) && is_array($otherProjectsArray)) {
                // Filter out empty values and trim each item
                $cleanedProjects = array_filter(array_map('trim', $otherProjectsArray), function($item) {
                    return !empty($item);
                });
                
                if (!empty($cleanedProjects)) {
                    // Store as JSON array for multiple projects
                    $otherProjectsData = json_encode($cleanedProjects);
                }
            }

            // Prepare data for insertion
            $insertData = [
                'project_name' => trim($request->project_name),
                'client_name' => trim($request->client_name),
                'location' => trim($request->location),
                'description' => trim($request->description),
                'summary_description' => $request->summary_description ? trim($request->summary_description) : null,
                'project_category' => $request->project_category,
                'url_project' => $request->url_project ? trim($request->url_project) : null,
                'slug_project' => $slug,
                'images' => json_encode($uploadedImages),
                'featured_image' => $featuredImage,
                'sequence' => $request->input('sequence', 0),
                'other_projects' => $otherProjectsData,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Insert to database
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
            $portfolio = DB::table('project')
                        ->where('slug_project', $slug)
                        ->where('status', 'Active')
                        ->first();
            
            if (!$portfolio) {
                abort(404, 'Project not found');
            }
            
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
            $project = DB::table('project')->where('id_project', $id)->first();
            
            if (!$project) {
                return redirect()->route('project.index')->with('error', 'Project not found');
            }
            
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
            $project = DB::table('project')->where('id_project', $id)->first();
            
            if (!$project) {
                return redirect()->route('project.index')->with('error', 'Project not found');
            }

            $title = 'Edit Portfolio';
            return view('project.edit', compact('project', 'title'));
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
                'project_category' => 'required',
                'slug_project' => 'required|string|max:255|unique:project,slug_project,' . $id . ',id_project',
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            ];

            $messages = [
                'required' => ':attribute wajib diisi!',
                'string' => ':attribute harus berupa teks!',
                'max' => ':attribute maksimal :max karakter!',
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

            // Handle other projects - now expecting an array
            $otherProjectsArray = $request->input('other_projects', []);
            $otherProjectsData = null;
            
            if (!empty($otherProjectsArray) && is_array($otherProjectsArray)) {
                // Filter out empty values and trim each item
                $cleanedProjects = array_filter(array_map('trim', $otherProjectsArray), function($item) {
                    return !empty($item);
                });
                
                if (!empty($cleanedProjects)) {
                    // Store as JSON array for multiple projects
                    $otherProjectsData = json_encode($cleanedProjects);
                }
            }

            // Update data
            $updateData = [
                'project_name' => trim($request->project_name),
                'client_name' => trim($request->client_name),
                'location' => trim($request->location),
                'description' => trim($request->description),
                'summary_description' => $request->summary_description ? trim($request->summary_description) : null,
                'project_category' => $request->project_category,
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
            // Debug logging
            Log::info('Editor Image Upload Started', [
                'has_file' => $request->hasFile('file'),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'all_files' => $request->allFiles(),
                'csrf_token' => $request->header('X-CSRF-TOKEN')
            ]);

            // Validate the uploaded file
            $request->validate([
                'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
            ], [
                'file.required' => 'File is required',
                'file.image' => 'File must be an image',
                'file.mimes' => 'File must be: jpeg, jpg, png, gif, or webp',
                'file.max' => 'File size must be less than 2MB'
            ]);

            // Create editor directory if it doesn't exist
            $editorDir = public_path('images/editor');
            if (!File::exists($editorDir)) {
                File::makeDirectory($editorDir, 0755, true);
                Log::info('Created editor directory: ' . $editorDir);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid()
                ]);

                $extension = $file->getClientOriginalExtension();
                $filename = 'editor_' . time() . '_' . uniqid() . '.' . $extension;
                
                // Move file to editor directory
                if ($file->move($editorDir, $filename)) {
                    $imageUrl = asset('images/editor/' . $filename);
                    
                    Log::info('Image uploaded successfully', [
                        'filename' => $filename,
                        'url' => $imageUrl
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'url' => $imageUrl,
                        'message' => 'Image uploaded successfully',
                        'filename' => $filename
                    ]);
                } else {
                    throw new Exception('Failed to move uploaded file to: ' . $editorDir . '/' . $filename);
                }
            }

            throw new Exception('No file was uploaded');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Editor Image Upload Validation Error', [
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            Log::error('Editor Image Upload Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'debug_info' => [
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Search projects for autocomplete (AJAX)
     */
    public function searchProjects(Request $request)
    {
        try {
            $query = $request->get('query', '');
            $currentId = $request->get('current_id', null);
            
            if (strlen($query) < 3) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $projects = DB::table('project')
                ->select('id_project', 'project_name', 'client_name', 'project_category', 'slug_project')
                ->where('status', 'Active')
                ->where(function($q) use ($query) {
                    $q->where('project_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('client_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('project_category', 'LIKE', '%' . $query . '%');
                });

            // Exclude current project if editing
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
                    'subtitle' => $project->client_name . ' - ' . $project->project_category,
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
}
