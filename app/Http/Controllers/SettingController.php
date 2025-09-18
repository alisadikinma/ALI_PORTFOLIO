<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Setting';
        $setting = Setting::first();
        return view('setting.index', compact('setting', 'title'));
    }

    public function about()
    {
        $setting = Setting::first();
        $title = "Tentang Sistem";
        return view('setting.about', compact('setting', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $setting = Setting::findOrFail($id);

    // Simpan nama file lama
    $logo     = $setting->logo_setting;
    $favicon  = $setting->favicon_setting;
    $bg       = $setting->bg_tentang_setting ?? null;
    $aboutImage = $setting->about_section_image ?? null;

    // === Update logo ===
    if ($request->hasFile('logo_setting')) {
        if ($logo && file_exists(public_path('logo/' . $logo))) {
            unlink(public_path('logo/' . $logo));
        }
        $logoFile = $request->file('logo_setting');
        $logo = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
        $logoFile->move('logo/', $logo);
    }

    // === Update favicon ===
    if ($request->hasFile('favicon_setting')) {
        if ($favicon && file_exists(public_path('favicon/' . $favicon))) {
            unlink(public_path('favicon/' . $favicon));
        }
        $faviconFile = $request->file('favicon_setting');
        $favicon = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
        $faviconFile->move('favicon/', $favicon);
    }

    // === Update background tentang ===
    if ($request->hasFile('bg_tentang_setting')) {
        if ($bg && file_exists(public_path('background_setting/' . $bg))) {
            unlink(public_path('background_setting/' . $bg));
        }
        $bgFile = $request->file('bg_tentang_setting');
        $bg = 'bg_tentang_' . time() . '.' . $bgFile->getClientOriginalExtension();
        $bgFile->move('background_setting/', $bg);
    }

    // === Update about section image ===
    if ($request->hasFile('about_section_image')) {
        if ($aboutImage && file_exists(public_path('images/about/' . $aboutImage))) {
            unlink(public_path('images/about/' . $aboutImage));
        }
        // Create directory if it doesn't exist
        if (!file_exists(public_path('images/about'))) {
            mkdir(public_path('images/about'), 0755, true);
        }
        $aboutFile = $request->file('about_section_image');
        $aboutImage = 'about_' . time() . '.' . $aboutFile->getClientOriginalExtension();
        $aboutFile->move('images/about/', $aboutImage);
    }

    // === Prepare update data (excluding non-existent columns) ===
    $updateData = [
        'instansi_setting'       => $request->instansi_setting,
        'pimpinan_setting'       => $request->pimpinan_setting,
        'logo_setting'           => $logo,
        'favicon_setting'        => $favicon,
        'keyword_setting'        => $request->keyword_setting,
        'alamat_setting'         => $request->alamat_setting,
        'instagram_setting'      => $request->instagram_setting,
        'youtube_setting'        => $request->youtube_setting,
        'email_setting'          => $request->email_setting,
        'no_hp_setting'          => $request->no_hp_setting,
        'tiktok_setting'         => $request->tiktok_setting,
        'facebook_setting'       => $request->facebook_setting,
        'linkedin_setting'       => $request->linkedin_setting,
        'maps_setting'           => $request->maps_setting,
        'profile_title'          => $request->profile_title,
        'profile_content'        => $request->profile_content,
        'primary_button_title'   => $request->primary_button_title,
        'primary_button_link'    => $request->primary_button_link,
        'secondary_button_title' => $request->secondary_button_title,
        'secondary_button_link'  => $request->secondary_button_link,
        'years_experience'       => $request->years_experience,
        'followers_count'        => $request->followers_count,
        'project_delivered'      => $request->project_delivered,
        'cost_savings'           => $request->cost_savings,
        'success_rate'           => $request->success_rate,
    ];

    // Add optional columns only if they exist in the table
    $optionalColumns = [
        'bg_tentang_setting' => $bg,
        'about_section_title' => $request->about_section_title,
        'about_section_subtitle' => $request->about_section_subtitle,
        'about_section_description' => $request->about_section_description,
        'about_section_image' => $aboutImage,
        'award_section_title' => $request->award_section_title,
        'award_section_subtitle' => $request->award_section_subtitle,
    ];

    // Check which columns exist and add them
    foreach ($optionalColumns as $column => $value) {
        try {
            if (\Schema::hasColumn('setting', $column)) {
                $updateData[$column] = $value;
            }
        } catch (\Exception $e) {
            // Column doesn't exist, skip it
            continue;
        }
    }

    // === Update database ===
    $setting->update($updateData);

    // Clear cache untuk memastikan perubahan langsung terlihat
    Cache::forget('site_config');
    Cache::forget('homepage_data');
    Cache::forget('homepage_sections');
    
    return redirect()->back()->with('Sukses', 'Berhasil Edit Konfigurasi Website');
}

    /**
     * Show sections management page
     */
    public function sections()
    {
        // Get title from settings or use default
        $setting = Setting::first();
        $title = ($setting && $setting->instansi_setting) 
                ? 'Manage Sections - ' . $setting->instansi_setting 
                : 'Section Visibility Management';
        
        try {
            // Get homepage sections from lookup_data
            $sections = DB::table('lookup_data')
                ->where('lookup_type', 'homepage_section')
                ->orderBy('sort_order', 'asc')
                ->get();
            
            if ($sections->isEmpty()) {
                // Create default sections if they don't exist
                $this->createDefaultSections();
                $sections = DB::table('lookup_data')
                    ->where('lookup_type', 'homepage_section')
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }
            
            return view('setting.sections', compact('sections', 'title', 'setting'));
            
        } catch (\Exception $e) {
            \Log::error('Error loading sections page: ' . $e->getMessage());
            
            // Create empty sections for fallback
            $sections = collect([
                (object)[
                    'id' => 0,
                    'lookup_code' => 'about',
                    'lookup_name' => 'About Section',
                    'lookup_description' => 'About me, mission, vision content',
                    'lookup_icon' => 'fas fa-user',
                    'lookup_color' => '#059669',
                    'sort_order' => 1,
                    'is_active' => 1
                ]
            ]);
            
            return view('setting.sections', compact('sections', 'title', 'setting'))
                ->with('error', 'There was an issue loading sections. Using default values.');
        }
    }

    /**
     * Update sections visibility and order
     */
    public function updateSections(Request $request)
    {
        try {
            \Log::info('Section update request:', $request->all());
            
            // Get all sections to update
            $sections = DB::table('lookup_data')
                ->where('lookup_type', 'homepage_section')
                ->orderBy('sort_order', 'asc')
                ->get();
            
            if ($sections->isEmpty()) {
                return redirect()->back()->with('error', 'No sections found. Please contact administrator.');
            }
            
            $updated = 0;
            
            // Update each section
            foreach ($sections as $section) {
                $sectionCode = $section->lookup_code;
                
                // Check if section is active
                $isActive = $request->has($sectionCode . '_section_active') ? 1 : 0;
                
                // Get order from request (fallback to current order)
                $order = $request->get($sectionCode . '_section_order', $section->sort_order);
                
                \Log::info("Updating section {$sectionCode}: active={$isActive}, order={$order}");
                
                // Update section
                $affected = DB::table('lookup_data')
                    ->where('id', $section->id)
                    ->update([
                        'is_active' => $isActive,
                        'sort_order' => intval($order),
                        'updated_at' => now()
                    ]);
                    
                $updated += $affected;
            }
            
            \Log::info("Total sections updated: {$updated}");

            // Clear relevant caches
            Cache::forget('site_config');
            Cache::forget('homepage_data');
            Cache::forget('homepage_sections');
            Cache::forget('active_sections');
            
            // Clear view cache
            if (function_exists('artisan')) {
                \Artisan::call('view:clear');
            }
            
            return redirect()->back()->with('success', "Section settings updated successfully! {$updated} sections were modified. Changes are now live on your homepage.");
            
        } catch (\Exception $e) {
            \Log::error('Failed to update section settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating settings. Please try again or contact support.');
        }
    }

    /**
     * Create default sections in lookup_data
     */
    private function createDefaultSections()
    {
        $defaultSections = [
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'about',
                'lookup_name' => 'About Section',
                'lookup_description' => 'About me, mission, vision content',
                'lookup_icon' => 'fas fa-user',
                'lookup_color' => '#059669',
                'sort_order' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'services',
                'lookup_name' => 'Services Section',
                'lookup_description' => 'Services and offerings',
                'lookup_icon' => 'fas fa-cogs',
                'lookup_color' => '#3b82f6',
                'sort_order' => 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'portfolio',
                'lookup_name' => 'Portfolio Section',
                'lookup_description' => 'Project showcase and work samples',
                'lookup_icon' => 'fas fa-briefcase',
                'lookup_color' => '#8b5cf6',
                'sort_order' => 3,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'awards',
                'lookup_name' => 'Awards Section',
                'lookup_description' => 'Achievements and recognitions',
                'lookup_icon' => 'fas fa-trophy',
                'lookup_color' => '#f59e0b',
                'sort_order' => 4,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'testimonials',
                'lookup_name' => 'Testimonials Section',
                'lookup_description' => 'Client reviews and feedback',
                'lookup_icon' => 'fas fa-quote-left',
                'lookup_color' => '#10b981',
                'sort_order' => 5,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'gallery',
                'lookup_name' => 'Gallery Section',
                'lookup_description' => 'Image gallery and visual content',
                'lookup_icon' => 'fas fa-images',
                'lookup_color' => '#ef4444',
                'sort_order' => 6,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'articles',
                'lookup_name' => 'Articles Section',
                'lookup_description' => 'Blog posts and written content',
                'lookup_icon' => 'fas fa-newspaper',
                'lookup_color' => '#6366f1',
                'sort_order' => 7,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lookup_type' => 'homepage_section',
                'lookup_code' => 'contact',
                'lookup_name' => 'Contact Section',
                'lookup_description' => 'Contact form and information',
                'lookup_icon' => 'fas fa-envelope',
                'lookup_color' => '#06b6d4',
                'sort_order' => 8,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($defaultSections as $section) {
            DB::table('lookup_data')->insert($section);
        }
    }

    /**
     * Get active homepage sections for frontend
     */
    public function getActiveSections()
    {
        return Cache::remember('active_sections', 1800, function() {
            return DB::table('lookup_data')
                ->where('lookup_type', 'homepage_section')
                ->where('is_active', 1)
                ->orderBy('sort_order', 'asc')
                ->pluck('lookup_code')
                ->toArray();
        });
    }

    public function storeImage(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120' // 5MB max
            ]);

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');
                $originName = $file->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;

                // Create directory if it doesn't exist
                $uploadPath = public_path('images/uploads');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Move file to uploads directory
                $file->move($uploadPath, $fileName);

                $url = asset('images/uploads/' . $fileName);

                // Check if this is a CKEditor 5 request (JSON response)
                if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
                    return response()->json([
                        'success' => true,
                        'url' => $url,
                        'filename' => $fileName,
                        'message' => 'Image uploaded successfully'
                    ]);
                }

                // Legacy CKEditor 4 support
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $msg = 'Image uploaded successfully';
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

                @header('Content-type: text/html; charset=utf-8');
                echo $response;
                return;
            }

            // No file uploaded
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded'
                ], 400);
            }

            $response = "<script>window.parent.CKEDITOR.tools.callFunction({$request->input('CKEditorFuncNum')}, '', 'No file uploaded')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $message = isset($errors['upload']) ? $errors['upload'][0] : 'Validation failed';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            $response = "<script>window.parent.CKEDITOR.tools.callFunction({$request->input('CKEditorFuncNum')}, '', '$message')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;

        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            $message = 'Failed to upload image. Please try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            $response = "<script>window.parent.CKEDITOR.tools.callFunction({$request->input('CKEditorFuncNum')}, '', '$message')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}