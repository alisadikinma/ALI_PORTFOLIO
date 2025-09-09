<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

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
        'tentang_setting'        => $request->tentang_setting,
        'misi_setting'           => $request->misi_setting,
        'visi_setting'           => $request->visi_setting,
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
    
    return redirect()->back()->with('Sukses', 'Berhasil Edit Konfigurasi Website');
}


    public function sections()
    {
        $title = 'Section Visibility Management';
        
        try {
            $konf = Setting::first();
            
            if (!$konf) {
                // Create default setting if doesn't exist
                $konf = Setting::create([
                    'instansi_setting' => 'Portfolio Website',
                    'pimpinan_setting' => 'Admin',
                    'about_section_active' => 1,
                    'services_section_active' => 1,
                    'portfolio_section_active' => 1,
                    'testimonials_section_active' => 1,
                    'gallery_section_active' => 1,
                    'articles_section_active' => 1,
                    'awards_section_active' => 1,
                    'contact_section_active' => 1,
                ]);
            }
            
            return view('setting.sections', compact('konf', 'title'));
            
        } catch (\Exception $e) {
            \Log::error('Error loading sections page: ' . $e->getMessage());
            
            // Create a default object to prevent errors
            $konf = (object) [
                'about_section_active' => 1,
                'services_section_active' => 1,
                'portfolio_section_active' => 1,
                'testimonials_section_active' => 1,
                'gallery_section_active' => 1,
                'articles_section_active' => 1,
                'awards_section_active' => 1,
                'contact_section_active' => 1,
            ];
            
            return view('setting.sections', compact('konf', 'title'))
                ->with('error', 'There was an issue loading settings. Using default values.');
        }
    }

    public function updateSections(Request $request)
    {
        try {
            $setting = Setting::first();
            
            if (!$setting) {
                return redirect()->back()->with('error', 'Settings not found. Please contact administrator.');
            }
            
            // Update section visibility
            $updated = $setting->update([
                'about_section_active' => $request->has('about_section_active') ? 1 : 0,
                'services_section_active' => $request->has('services_section_active') ? 1 : 0,
                'portfolio_section_active' => $request->has('portfolio_section_active') ? 1 : 0,
                'testimonials_section_active' => $request->has('testimonials_section_active') ? 1 : 0,
                'gallery_section_active' => $request->has('gallery_section_active') ? 1 : 0,
                'articles_section_active' => $request->has('articles_section_active') ? 1 : 0,
                'awards_section_active' => $request->has('awards_section_active') ? 1 : 0,
                'contact_section_active' => $request->has('contact_section_active') ? 1 : 0,
            ]);

            if (!$updated) {
                return redirect()->back()->with('error', 'Failed to update section settings. Please try again.');
            }

            // Clear cache untuk memastikan perubahan langsung terlihat
            Cache::forget('site_config');
            Cache::forget('homepage_data');
            
            // Clear view cache juga
            if (function_exists('artisan')) {
                \Artisan::call('view:clear');
            }
            
            return redirect()->back()->with('success', 'Section visibility settings updated successfully! Changes are now live on your homepage.');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update section settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating settings. Please try again or contact support.');
        }
    }

    public function storeImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
