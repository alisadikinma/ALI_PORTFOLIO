<?php

namespace App\Config;

class FallbackConfiguration
{
    /**
     * Get fallback data for homepage when HomeWebController fails
     *
     * @return array
     */
    public static function getHomePageData(): array
    {
        return [
            'konf' => (object) [
                'instansi_setting' => config('app.fallback_company_name', 'Portfolio Website'),
                'pimpinan_setting' => config('app.fallback_owner_name', 'Site Owner'),
                'logo_setting' => 'default-logo.png',
                'favicon_setting' => 'favicon.png',
                'profile_title' => 'PORTFOLIO WEBSITE - TEMPORARY MODE',
                'profile_content' => 'This page is running in emergency fallback mode. Some features may be limited.',
                'primary_button_title' => 'Contact',
                'primary_button_link' => '#contact',
                'secondary_button_title' => 'About',
                'secondary_button_link' => '#about',
                'years_experience' => 'N/A',
                'followers_count' => 'N/A',
                'project_delivered' => 'N/A',
                'cost_savings' => 'N/A',
                'success_rate' => 'N/A',
                'about_section_title' => 'System Status',
                'about_section_subtitle' => 'Emergency Mode Active',
                'about_section_description' => 'We\'re experiencing temporary technical difficulties. Please check back later or contact us directly.',
                'email_setting' => config('mail.from.address', 'admin@portfolio.com'),
                'no_hp_setting' => config('app.fallback_phone', 'Contact for details'),
                'alamat_setting' => config('app.fallback_address', 'Contact for details'),
                'instagram_setting' => config('app.fallback_social.instagram', ''),
                'linkedin_setting' => config('app.fallback_social.linkedin', ''),
                'youtube_setting' => config('app.fallback_social.youtube', ''),
                'tiktok_setting' => config('app.fallback_social.tiktok', '')
            ],
            'layanan' => collect([]),
            'testimonial' => collect([]),
            'galeri' => collect([]),
            'article' => collect([]),
            'award' => collect([]),
            'projects' => collect([]),
            'projectCategories' => collect([]),
            'homepageSections' => ['about', 'services', 'portfolio', 'awards', 'testimonials', 'gallery', 'articles', 'contact'],
            'sectionConfigs' => [
                'about' => ['title' => 'System Status', 'is_active' => true, 'description' => 'Current Status'],
                'services' => ['title' => 'Services', 'is_active' => true, 'description' => 'Our Services'],
                'portfolio' => ['title' => 'Portfolio', 'is_active' => true, 'description' => 'Our Work'],
                'awards' => ['title' => 'Recognition', 'is_active' => true, 'description' => 'Achievements'],
                'testimonials' => ['title' => 'Testimonials', 'is_active' => true, 'description' => 'Client Feedback'],
                'gallery' => ['title' => 'Gallery', 'is_active' => true, 'description' => 'Project Gallery'],
                'articles' => ['title' => 'Articles', 'is_active' => true, 'description' => 'Latest Updates'],
                'contact' => ['title' => 'Contact', 'is_active' => true, 'description' => 'Get In Touch']
            ]
        ];
    }

    /**
     * Get error-specific fallback message
     *
     * @param string $environment
     * @return string
     */
    public static function getFallbackMessage(string $environment = 'production'): string
    {
        if ($environment === 'production') {
            return 'System temporarily unavailable. Please try again later.';
        }

        return 'Emergency fallback mode - HomeWebController has issues';
    }

    /**
     * Get user-friendly error message
     *
     * @param string $environment
     * @return string
     */
    public static function getUserFriendlyError(string $environment = 'production'): string
    {
        if ($environment === 'production') {
            return 'We\'re currently experiencing technical difficulties. Please try again in a few minutes.';
        }

        return 'Development mode: Controller initialization failed';
    }
}