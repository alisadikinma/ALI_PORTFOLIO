<?php

namespace App\Services;

use App\Models\LookupData;
use Illuminate\Support\Facades\Cache;

class HomepageSectionService
{
    /**
     * Get all homepage section configurations
     */
    public static function getAllSectionConfigs()
    {
        return Cache::remember('homepage_section_configs', 1800, function() {
            try {
                return LookupData::getHomepageConfiguration();
            } catch (\Exception $e) {
                \Log::error('Failed to get homepage section configs: ' . $e->getMessage());
                return self::getDefaultSectionConfigs();
            }
        });
    }

    /**
     * Get section configuration by code
     */
    public static function getSectionConfig($sectionCode)
    {
        $allConfigs = self::getAllSectionConfigs();
        return $allConfigs[$sectionCode] ?? self::getDefaultSectionConfig($sectionCode);
    }

    /**
     * Check if section is active
     */
    public static function isSectionActive($sectionCode)
    {
        $config = self::getSectionConfig($sectionCode);
        return $config['is_active'] ?? false;
    }

    /**
     * Get section title
     */
    public static function getSectionTitle($sectionCode)
    {
        $config = self::getSectionConfig($sectionCode);
        return $config['title'] ?? ucfirst($sectionCode);
    }

    /**
     * Get section description
     */
    public static function getSectionDescription($sectionCode)
    {
        $config = self::getSectionConfig($sectionCode);
        return $config['description'] ?? '';
    }

    /**
     * Get section sort order
     */
    public static function getSectionOrder($sectionCode)
    {
        $config = self::getSectionConfig($sectionCode);
        return $config['sort_order'] ?? 999;
    }

    /**
     * Get active sections in order
     */
    public static function getActiveSectionsInOrder()
    {
        $allConfigs = self::getAllSectionConfigs();
        $activeSections = [];

        foreach ($allConfigs as $code => $config) {
            if ($config['is_active']) {
                $activeSections[$code] = $config;
            }
        }

        // Sort by sort_order
        uasort($activeSections, function($a, $b) {
            return ($a['sort_order'] ?? 999) <=> ($b['sort_order'] ?? 999);
        });

        return $activeSections;
    }

    /**
     * Default section configurations (fallback)
     */
    private static function getDefaultSectionConfigs()
    {
        return [
            'about' => [
                'is_active' => true,
                'sort_order' => 1,
                'title' => 'About',
                'description' => 'About section description',
                'icon' => 'fas fa-user',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'services' => [
                'is_active' => true,
                'sort_order' => 2,
                'title' => 'Services',
                'description' => 'Services & Offering',
                'icon' => 'fas fa-cogs',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'portfolio' => [
                'is_active' => true,
                'sort_order' => 3,
                'title' => 'Portfolio',
                'description' => 'Project showcase',
                'icon' => 'fas fa-briefcase',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'awards' => [
                'is_active' => true,
                'sort_order' => 4,
                'title' => 'Awards',
                'description' => 'Achievements and recognitions',
                'icon' => 'fas fa-trophy',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'testimonials' => [
                'is_active' => true,
                'sort_order' => 5,
                'title' => 'Testimonials',
                'description' => 'Client reviews',
                'icon' => 'fas fa-quote-left',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'gallery' => [
                'is_active' => true,
                'sort_order' => 6,
                'title' => 'Gallery',
                'description' => 'Gallery',
                'icon' => 'fas fa-images',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'articles' => [
                'is_active' => true,
                'sort_order' => 7,
                'title' => 'Articles',
                'description' => 'Blog posts',
                'icon' => 'fas fa-newspaper',
                'color' => '#fbbf24',
                'metadata' => null
            ],
            'contact' => [
                'is_active' => true,
                'sort_order' => 8,
                'title' => 'Contact',
                'description' => 'Contact form',
                'icon' => 'fas fa-envelope',
                'color' => '#fbbf24',
                'metadata' => null
            ]
        ];
    }

    /**
     * Get default section configuration for a specific section
     */
    private static function getDefaultSectionConfig($sectionCode)
    {
        $defaults = self::getDefaultSectionConfigs();
        return $defaults[$sectionCode] ?? [
            'is_active' => false,
            'sort_order' => 999,
            'title' => ucfirst($sectionCode),
            'description' => '',
            'icon' => 'fas fa-circle',
            'color' => '#fbbf24',
            'metadata' => null
        ];
    }
}
