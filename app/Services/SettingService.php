<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Exception;

/**
 * Setting Service - Handles all site configuration and settings
 */
class SettingService
{
    const CACHE_DURATION = 1800; // 30 minutes

    const UPLOAD_PATHS = [
        'logo' => 'logo/',
        'favicon' => 'favicon/',
        'background' => 'background_setting/',
        'about_image' => 'images/about/',
        'profile_image' => 'images/profile/',
        'og_image' => 'images/og/',
    ];

    const IMAGE_SETTINGS = [
        'logo' => ['max_size' => 2048, 'dimensions' => '400x200', 'types' => ['png', 'jpg', 'jpeg', 'svg']],
        'favicon' => ['max_size' => 512, 'dimensions' => '32x32', 'types' => ['png', 'ico', 'jpg', 'jpeg']],
        'background' => ['max_size' => 5120, 'dimensions' => '1920x1080', 'types' => ['jpg', 'jpeg', 'png', 'webp']],
        'about_image' => ['max_size' => 3072, 'dimensions' => '800x600', 'types' => ['jpg', 'jpeg', 'png', 'webp']],
        'profile_image' => ['max_size' => 2048, 'dimensions' => '500x500', 'types' => ['jpg', 'jpeg', 'png', 'webp']],
        'og_image' => ['max_size' => 1024, 'dimensions' => '1200x630', 'types' => ['jpg', 'jpeg', 'png']],
    ];

    /**
     * Get site configuration with caching
     */
    public static function getConfig()
    {
        return Setting::getConfig();
    }

    /**
     * Update site settings with file handling
     */
    public static function updateSettings(array $data, array $files = [])
    {
        try {
            $setting = Setting::first();
            if (!$setting) {
                throw new Exception('Settings record not found');
            }

            // Store original file paths
            $originalFiles = [
                'logo_setting' => $setting->logo_setting,
                'favicon_setting' => $setting->favicon_setting,
                'bg_tentang_setting' => $setting->bg_tentang_setting,
                'about_section_image' => $setting->about_section_image,
                'profile_image' => $setting->profile_image,
                'og_image' => $setting->og_image,
            ];

            // Prepare update data
            $updateData = self::prepareSettingsData($data);

            // Handle file uploads
            $uploadedFiles = self::handleFileUploads($files, $originalFiles);
            $updateData = array_merge($updateData, $uploadedFiles);

            // Filter out non-existent columns
            $updateData = self::filterValidColumns($updateData);

            // Update settings
            $setting->update($updateData);

            // Clear cache
            $setting->clearSettingsCache();

            Log::info('Settings updated successfully', [
                'setting_id' => $setting->id_setting,
                'uploaded_files' => array_keys($uploadedFiles)
            ]);

            return $setting;

        } catch (Exception $e) {
            // Clean up any uploaded files if update failed
            if (isset($uploadedFiles)) {
                self::cleanupUploadedFiles($uploadedFiles);
            }

            Log::error('Settings update failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Update single setting
     */
    public static function updateSetting($key, $value)
    {
        try {
            $setting = Setting::updateSetting($key, $value);

            if ($setting) {
                Log::info('Single setting updated', ['key' => $key, 'value' => $value]);
                return $setting;
            }

            throw new Exception('Failed to update setting');

        } catch (Exception $e) {
            Log::error('Single setting update failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Get social links for display
     */
    public static function getSocialLinks()
    {
        return Setting::getSocialLinksForFooter();
    }

    /**
     * Get contact details
     */
    public static function getContactDetails()
    {
        return Setting::getContactDetails();
    }

    /**
     * Get company information
     */
    public static function getCompanyInfo()
    {
        return Setting::getCompanyInfo();
    }

    /**
     * Get statistics for display
     */
    public static function getStatistics()
    {
        $setting = self::getConfig();
        return $setting ? $setting->formatted_statistics : [];
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        $setting = self::getConfig();
        return $setting ? $setting->isMaintenanceMode() : false;
    }

    /**
     * Toggle maintenance mode
     */
    public static function toggleMaintenanceMode($enabled = null)
    {
        try {
            $setting = Setting::first();
            if (!$setting) {
                throw new Exception('Settings record not found');
            }

            $newStatus = $enabled !== null ? $enabled : !$setting->maintenance_mode;
            $setting->update(['maintenance_mode' => $newStatus]);

            Log::info('Maintenance mode toggled', ['enabled' => $newStatus]);

            return $newStatus;

        } catch (Exception $e) {
            Log::error('Failed to toggle maintenance mode', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get site meta information
     */
    public static function getSiteMeta()
    {
        $setting = self::getConfig();
        return $setting ? $setting->site_meta : [
            'title' => 'Portfolio',
            'description' => 'Professional Portfolio Website',
            'keywords' => 'portfolio, professional, web development',
            'author' => 'Portfolio Owner',
        ];
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaultSettings()
    {
        try {
            // Check if settings already exist
            if (Setting::count() > 0) {
                return Setting::first();
            }

            // Create default settings
            $defaultSettings = [
                'instansi_setting' => 'ALI PORTFOLIO',
                'pimpinan_setting' => 'Ali Sadikin',
                'tentang_setting' => 'Professional portfolio showcasing expertise in web development and design.',
                'misi_setting' => 'To deliver exceptional digital solutions that drive business growth.',
                'visi_setting' => 'To be a leading provider of innovative web solutions.',
                'keyword_setting' => 'portfolio, web development, design, freelance',
                'alamat_setting' => 'Jakarta, Indonesia',
                'email_setting' => 'contact@aliportfolio.com',
                'no_hp_setting' => '+62 123 456 7890',
                'profile_title' => 'Professional Web Developer',
                'profile_content' => 'Passionate about creating exceptional digital experiences.',
                'primary_button_title' => 'View Portfolio',
                'primary_button_link' => '/portfolio',
                'secondary_button_title' => 'Contact Me',
                'secondary_button_link' => '/contact',
                'years_experience' => 5,
                'project_delivered' => 50,
                'happy_clients' => 25,
                'success_rate' => 98.5,
                'about_section_title' => 'About Me',
                'about_section_subtitle' => 'Professional Background',
                'about_section_description' => 'Experienced web developer with a passion for creating innovative solutions.',
                'award_section_title' => 'Awards & Recognition',
                'award_section_subtitle' => 'Professional Achievements',
                'enable_contact_form' => true,
                'show_social_links' => true,
                'show_statistics' => true,
                'enable_animations' => true,
            ];

            $setting = Setting::create($defaultSettings);

            Log::info('Default settings initialized', ['setting_id' => $setting->id_setting]);

            return $setting;

        } catch (Exception $e) {
            Log::error('Failed to initialize default settings', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Prepare settings data for database
     */
    private static function prepareSettingsData(array $data)
    {
        $preparedData = [];

        // Basic information
        $basicFields = [
            'instansi_setting', 'pimpinan_setting', 'keyword_setting',
            'alamat_setting', 'email_setting', 'no_hp_setting', 'maps_setting'
        ];

        foreach ($basicFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // Social media links
        $socialFields = [
            'instagram_setting', 'youtube_setting', 'tiktok_setting',
            'facebook_setting', 'linkedin_setting', 'github_setting',
            'twitter_setting', 'behance_setting', 'dribbble_setting'
        ];

        foreach ($socialFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // Profile section
        $profileFields = [
            'profile_title', 'profile_subtitle', 'profile_content',
            'primary_button_title', 'primary_button_link',
            'secondary_button_title', 'secondary_button_link',
            'tertiary_button_title', 'tertiary_button_link'
        ];

        foreach ($profileFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // Statistics
        $statisticFields = [
            'years_experience', 'followers_count', 'project_delivered',
            'cost_savings', 'success_rate', 'happy_clients',
            'awards_won', 'technologies_mastered'
        ];

        foreach ($statisticFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = is_numeric($data[$field]) ? $data[$field] : 0;
            }
        }

        // Content sections
        $contentFields = [
            'tentang_setting', 'misi_setting', 'visi_setting',
            'about_section_title', 'about_section_subtitle', 'about_section_description',
            'award_section_title', 'award_section_subtitle', 'award_section_description',
            'services_section_title', 'services_section_subtitle', 'services_section_description',
            'portfolio_section_title', 'portfolio_section_subtitle', 'portfolio_section_description',
            'testimonials_section_title', 'testimonials_section_subtitle', 'testimonials_section_description',
            'gallery_section_title', 'gallery_section_subtitle', 'gallery_section_description',
            'articles_section_title', 'articles_section_subtitle', 'articles_section_description',
            'contact_section_title', 'contact_section_subtitle', 'contact_section_description'
        ];

        foreach ($contentFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // SEO & Meta
        $seoFields = [
            'site_title', 'site_description', 'site_keywords',
            'meta_author', 'google_analytics_id', 'facebook_pixel_id'
        ];

        foreach ($seoFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // URLs
        $urlFields = [
            'view_cv_url', 'portfolio_pdf_url', 'company_brochure_url',
            'about_section_video_url'
        ];

        foreach ($urlFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        // Feature flags
        $featureFields = [
            'maintenance_mode', 'show_social_links', 'show_statistics',
            'enable_dark_mode', 'enable_animations', 'enable_contact_form',
            'enable_newsletter', 'enable_live_chat'
        ];

        foreach ($featureFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Appearance
        $appearanceFields = ['primary_color', 'secondary_color', 'accent_color', 'theme_mode'];

        foreach ($appearanceFields as $field) {
            if (isset($data[$field])) {
                $preparedData[$field] = trim($data[$field]);
            }
        }

        return $preparedData;
    }

    /**
     * Handle file uploads
     */
    private static function handleFileUploads(array $files, array $originalFiles)
    {
        $uploadedFiles = [];

        foreach ($files as $fieldName => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            // Validate file
            if (!self::validateUploadedFile($file, $fieldName)) {
                throw new Exception("Invalid file for {$fieldName}: " . $file->getClientOriginalName());
            }

            // Delete old file if exists
            $originalFile = $originalFiles[$fieldName] ?? null;
            if ($originalFile) {
                self::deleteOldFile($fieldName, $originalFile);
            }

            // Generate filename and upload
            $filename = self::generateFilename($file, $fieldName);
            $uploadPath = self::getUploadPath($fieldName);

            // Ensure directory exists
            if (!File::exists(public_path($uploadPath))) {
                File::makeDirectory(public_path($uploadPath), 0755, true);
            }

            // Move file
            if ($file->move(public_path($uploadPath), $filename)) {
                $uploadedFiles[$fieldName] = $filename;

                Log::info('File uploaded successfully', [
                    'field' => $fieldName,
                    'filename' => $filename,
                    'path' => $uploadPath
                ]);
            } else {
                throw new Exception("Failed to upload file for {$fieldName}");
            }
        }

        return $uploadedFiles;
    }

    /**
     * Validate uploaded file
     */
    private static function validateUploadedFile($file, $fieldName)
    {
        // Get field type (remove _setting suffix)
        $fileType = str_replace('_setting', '', $fieldName);
        $fileType = str_replace('_section_image', '', $fileType);

        // Default to general image settings if specific type not found
        $settings = self::IMAGE_SETTINGS[$fileType] ?? self::IMAGE_SETTINGS['logo'];

        // Check file size
        if ($file->getSize() > $settings['max_size'] * 1024) {
            return false;
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $settings['types'])) {
            return false;
        }

        return true;
    }

    /**
     * Generate filename for uploaded file
     */
    private static function generateFilename($file, $fieldName)
    {
        $extension = $file->getClientOriginalExtension();
        $prefix = str_replace(['_setting', '_section_image'], '', $fieldName);
        return $prefix . '_' . time() . '.' . $extension;
    }

    /**
     * Get upload path for field
     */
    private static function getUploadPath($fieldName)
    {
        // Map field names to upload paths
        $pathMap = [
            'logo_setting' => 'logo/',
            'favicon_setting' => 'favicon/',
            'bg_tentang_setting' => 'background_setting/',
            'about_section_image' => 'images/about/',
            'profile_image' => 'images/profile/',
            'og_image' => 'images/og/',
        ];

        return $pathMap[$fieldName] ?? 'images/settings/';
    }

    /**
     * Delete old file
     */
    private static function deleteOldFile($fieldName, $filename)
    {
        $uploadPath = self::getUploadPath($fieldName);
        $fullPath = public_path($uploadPath . $filename);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    /**
     * Clean up uploaded files (used when update fails)
     */
    private static function cleanupUploadedFiles(array $uploadedFiles)
    {
        foreach ($uploadedFiles as $fieldName => $filename) {
            self::deleteOldFile($fieldName, $filename);
        }
    }

    /**
     * Filter out columns that don't exist in database
     */
    private static function filterValidColumns(array $data)
    {
        try {
            $validColumns = Schema::getColumnListing('setting');
            return array_filter($data, function($key) use ($validColumns) {
                return in_array($key, $validColumns);
            }, ARRAY_FILTER_USE_KEY);

        } catch (Exception $e) {
            Log::warning('Could not validate columns, using all data', ['error' => $e->getMessage()]);
            return $data;
        }
    }

    /**
     * Export settings as JSON
     */
    public static function exportSettings()
    {
        $setting = self::getConfig();
        if (!$setting) {
            throw new Exception('No settings found to export');
        }

        return $setting->toArray();
    }

    /**
     * Import settings from array
     */
    public static function importSettings(array $settingsData)
    {
        try {
            $setting = Setting::first();
            if (!$setting) {
                $setting = Setting::create($settingsData);
            } else {
                $setting->update($settingsData);
            }

            Log::info('Settings imported successfully');
            return $setting;

        } catch (Exception $e) {
            Log::error('Settings import failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}