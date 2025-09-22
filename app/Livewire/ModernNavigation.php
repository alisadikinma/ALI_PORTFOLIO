<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * PHASE 3: MODERN NAVIGATION COMPONENT
 * Advanced responsive navigation with section detection
 */
class ModernNavigation extends Component
{
    public $currentSection = 'home';

    public function mount()
    {
        $this->detectCurrentSection();
    }

    public function getMenuItemsProperty()
    {
        return Cache::remember('navigation.menu_items', 300, function () {
            return DB::table('lookup_data')
                ->where('lookup_type', 'homepage_section')
                ->where('is_active', 1)
                ->orderBy('sort_order', 'asc')
                ->get();
        });
    }

    public function getKonfProperty()
    {
        return Cache::remember('navigation.settings', 300, function () {
            return DB::table('setting')
                ->select([
                    'pimpinan_setting',
                    'logo_setting',
                    'linkedin_setting',
                    'instagram_setting',
                    'youtube_setting',
                    'tiktok_setting'
                ])
                ->first();
        });
    }

    public function detectCurrentSection()
    {
        $currentPath = request()->path();
        $routeName = request()->route() ? request()->route()->getName() : '';

        // Detect section based on URL
        if ($currentPath === '/') {
            $this->currentSection = 'home';
        } elseif (str_contains($currentPath, 'portfolio') || str_contains($currentPath, 'project/')) {
            $this->currentSection = 'portfolio';
        } elseif (str_contains($currentPath, 'article')) {
            $this->currentSection = 'articles';
        } elseif (str_contains($currentPath, 'gallery')) {
            $this->currentSection = 'gallery';
        } elseif (str_contains($currentPath, 'contact')) {
            $this->currentSection = 'contact';
        } else {
            // Check if it's a section anchor
            $fragment = request()->get('section');
            if ($fragment) {
                $this->currentSection = $fragment;
            }
        }
    }

    public function isActiveSection($sectionCode)
    {
        // Check for portfolio/project pages
        if ($sectionCode === 'portfolio') {
            return str_contains(request()->path(), 'portfolio') ||
                   str_contains(request()->path(), 'project/') ||
                   in_array(request()->route()?->getName(), ['portfolio.detail', 'project.public.show', 'portfolio', 'portfolio.all']);
        }

        // Check for article pages
        if ($sectionCode === 'articles') {
            return str_contains(request()->path(), 'article');
        }

        // Check for gallery pages
        if ($sectionCode === 'gallery') {
            return str_contains(request()->path(), 'gallery');
        }

        // Check for contact pages
        if ($sectionCode === 'contact') {
            return str_contains(request()->path(), 'contact');
        }

        // Default section detection
        return $this->currentSection === $sectionCode;
    }

    public function setActiveSection($section)
    {
        $this->currentSection = $section;
        $this->dispatch('sectionChanged', $section);
    }

    public function getListeners()
    {
        return [
            'sectionInView' => 'handleSectionInView',
            'navigationUpdate' => 'updateNavigation'
        ];
    }

    public function handleSectionInView($sectionId)
    {
        $this->currentSection = $sectionId;
    }

    public function updateNavigation()
    {
        // Clear navigation cache
        Cache::forget('navigation.menu_items');
        Cache::forget('navigation.settings');
    }

    public function render()
    {
        return view('livewire.modern-navigation');
    }
}