<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';
    protected $primaryKey = 'id_setting';
    public $timestamps = true;

    protected $fillable = [
        // Company Information
        'instansi_setting',
        'pimpinan_setting',
        'logo_setting',
        'favicon_setting',
        'tentang_setting',
        'misi_setting',
        'visi_setting',
        'keyword_setting',

        // Contact Information
        'alamat_setting',
        'email_setting',
        'no_hp_setting',
        'maps_setting',

        // Social Media Links
        'instagram_setting',
        'youtube_setting',
        'tiktok_setting',
        'facebook_setting',
        'linkedin_setting',
        'github_setting',
        'twitter_setting',
        'behance_setting',
        'dribbble_setting',

        // Profile Section
        'profile_title',
        'profile_subtitle',
        'profile_content',
        'profile_image',
        'primary_button_title',
        'primary_button_link',
        'secondary_button_title',
        'secondary_button_link',
        'tertiary_button_title',
        'tertiary_button_link',

        // Statistics
        'years_experience',
        'followers_count',
        'project_delivered',
        'cost_savings',
        'success_rate',
        'happy_clients',
        'awards_won',
        'technologies_mastered',

        // About Section
        'about_section_title',
        'about_section_subtitle',
        'about_section_description',
        'about_section_image',
        'about_section_video_url',

        // Award Section
        'award_section_title',
        'award_section_subtitle',
        'award_section_description',

        // Services Section
        'services_section_title',
        'services_section_subtitle',
        'services_section_description',

        // Portfolio Section
        'portfolio_section_title',
        'portfolio_section_subtitle',
        'portfolio_section_description',

        // Testimonials Section
        'testimonials_section_title',
        'testimonials_section_subtitle',
        'testimonials_section_description',

        // Gallery Section
        'gallery_section_title',
        'gallery_section_subtitle',
        'gallery_section_description',

        // Articles Section
        'articles_section_title',
        'articles_section_subtitle',
        'articles_section_description',

        // Contact Section
        'contact_section_title',
        'contact_section_subtitle',
        'contact_section_description',
        'contact_form_email',
        'contact_success_message',

        // SEO & Meta
        'site_title',
        'site_description',
        'site_keywords',
        'meta_author',
        'og_image',
        'google_analytics_id',
        'facebook_pixel_id',

        // Appearance
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_setting',
        'bg_tentang_setting',
        'theme_mode', // light, dark, auto

        // Files & URLs
        'view_cv_url',
        'portfolio_pdf_url',
        'company_brochure_url',

        // Feature Flags
        'maintenance_mode',
        'show_social_links',
        'show_statistics',
        'enable_dark_mode',
        'enable_animations',
        'enable_contact_form',
        'enable_newsletter',
        'enable_live_chat',

        // Third-party Integrations
        'recaptcha_site_key',
        'recaptcha_secret_key',
        'mailchimp_api_key',
        'mailchimp_list_id',
        'google_maps_api_key',
        'stripe_public_key',
        'stripe_secret_key',
    ];

    protected $casts = [
        'years_experience' => 'integer',
        'followers_count' => 'integer',
        'project_delivered' => 'integer',
        'happy_clients' => 'integer',
        'awards_won' => 'integer',
        'technologies_mastered' => 'integer',
        'maintenance_mode' => 'boolean',
        'show_social_links' => 'boolean',
        'show_statistics' => 'boolean',
        'enable_dark_mode' => 'boolean',
        'enable_animations' => 'boolean',
        'enable_contact_form' => 'boolean',
        'enable_newsletter' => 'boolean',
        'enable_live_chat' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'logo_url',
        'favicon_url',
        'profile_image_url',
        'about_image_url',
        'og_image_url',
        'social_links',
        'formatted_statistics',
        'contact_info',
    ];

    /**
     * Accessors
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_setting) {
            return asset('logo/' . $this->logo_setting);
        }
        return asset('images/default/logo.png');
    }

    public function getFaviconUrlAttribute()
    {
        if ($this->favicon_setting) {
            return asset('favicon/' . $this->favicon_setting);
        }
        return asset('images/default/favicon.png');
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('images/profile/' . $this->profile_image);
        }
        return asset('images/default/profile.jpg');
    }

    public function getAboutImageUrlAttribute()
    {
        if ($this->about_section_image) {
            return asset('images/about/' . $this->about_section_image);
        }
        return asset('images/default/about.jpg');
    }

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return asset('images/og/' . $this->og_image);
        }
        return $this->logo_url;
    }

    public function getSocialLinksAttribute()
    {
        return [
            'instagram' => $this->instagram_setting,
            'youtube' => $this->youtube_setting,
            'tiktok' => $this->tiktok_setting,
            'facebook' => $this->facebook_setting,
            'linkedin' => $this->linkedin_setting,
            'github' => $this->github_setting,
            'twitter' => $this->twitter_setting,
            'behance' => $this->behance_setting,
            'dribbble' => $this->dribbble_setting,
        ];
    }

    public function getFormattedStatisticsAttribute()
    {
        return [
            [
                'label' => 'Years Experience',
                'value' => $this->years_experience ?? 0,
                'suffix' => '+',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#3b82f6'
            ],
            [
                'label' => 'Projects Delivered',
                'value' => $this->project_delivered ?? 0,
                'suffix' => '+',
                'icon' => 'fas fa-project-diagram',
                'color' => '#10b981'
            ],
            [
                'label' => 'Happy Clients',
                'value' => $this->happy_clients ?? 0,
                'suffix' => '+',
                'icon' => 'fas fa-smile',
                'color' => '#f59e0b'
            ],
            [
                'label' => 'Success Rate',
                'value' => $this->success_rate ?? '99%',
                'suffix' => '',
                'icon' => 'fas fa-chart-line',
                'color' => '#ef4444'
            ],
            [
                'label' => 'Cost Savings',
                'value' => $this->cost_savings ?? '$250K',
                'suffix' => '+',
                'icon' => 'fas fa-dollar-sign',
                'color' => '#8b5cf6'
            ],
        ];
    }

    public function getContactInfoAttribute()
    {
        return [
            'address' => $this->alamat_setting,
            'email' => $this->email_setting,
            'phone' => $this->no_hp_setting,
            'maps' => $this->maps_setting,
        ];
    }

    public function getCompanyNameAttribute()
    {
        return $this->instansi_setting ?? 'ALI PORTFOLIO';
    }

    public function getSiteMetaAttribute()
    {
        return [
            'title' => $this->site_title ?? $this->instansi_setting ?? 'Portfolio',
            'description' => $this->site_description ?? Str::limit($this->tentang_setting, 155),
            'keywords' => $this->site_keywords ?? $this->keyword_setting,
            'author' => $this->meta_author ?? $this->pimpinan_setting,
        ];
    }

    /**
     * Methods
     */
    public function clearSettingsCache()
    {
        $cacheTags = [
            'site_config',
            'homepage_data',
            'homepage_sections',
            'setting_' . $this->id_setting,
        ];

        foreach ($cacheTags as $tag) {
            Cache::forget($tag);
        }
    }

    public function getSocialLink($platform)
    {
        $field = $platform . '_setting';
        return $this->$field ?? null;
    }

    public function hasSocialLink($platform)
    {
        return !empty($this->getSocialLink($platform));
    }

    public function getActiveSocialLinks()
    {
        $socialPlatforms = ['instagram', 'youtube', 'tiktok', 'facebook', 'linkedin', 'github', 'twitter', 'behance', 'dribbble'];
        $activeLinks = [];

        foreach ($socialPlatforms as $platform) {
            if ($this->hasSocialLink($platform)) {
                $activeLinks[$platform] = [
                    'url' => $this->getSocialLink($platform),
                    'icon' => $this->getSocialIcon($platform),
                    'name' => ucfirst($platform),
                ];
            }
        }

        return $activeLinks;
    }

    private function getSocialIcon($platform)
    {
        $icons = [
            'instagram' => 'fab fa-instagram',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'facebook' => 'fab fa-facebook',
            'linkedin' => 'fab fa-linkedin',
            'github' => 'fab fa-github',
            'twitter' => 'fab fa-twitter',
            'behance' => 'fab fa-behance',
            'dribbble' => 'fab fa-dribbble',
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }

    public function isMaintenanceMode()
    {
        return $this->maintenance_mode ?? false;
    }

    public function shouldShowSection($section)
    {
        $field = $section . '_section_active';
        return $this->$field ?? true;
    }

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::saving(function ($setting) {
            // Auto-generate site meta if not provided
            if (!$setting->site_title && $setting->instansi_setting) {
                $setting->site_title = $setting->instansi_setting . ' | Portfolio';
            }

            if (!$setting->site_description && $setting->tentang_setting) {
                $setting->site_description = Str::limit(strip_tags($setting->tentang_setting), 155);
            }

            if (!$setting->meta_author && $setting->pimpinan_setting) {
                $setting->meta_author = $setting->pimpinan_setting;
            }
        });

        static::saved(function ($setting) {
            $setting->clearSettingsCache();
        });
    }

    /**
     * Static methods
     */
    public static function getConfig()
    {
        return Cache::remember('site_config', 1800, function() {
            return static::first();
        });
    }

    public static function updateSetting($key, $value)
    {
        $setting = static::first();
        if ($setting) {
            $setting->update([$key => $value]);
            return $setting;
        }
        return null;
    }

    public static function getSocialLinksForFooter()
    {
        $setting = static::getConfig();
        return $setting ? $setting->getActiveSocialLinks() : [];
    }

    public static function getContactDetails()
    {
        $setting = static::getConfig();
        return $setting ? $setting->contact_info : [];
    }

    public static function getCompanyInfo()
    {
        $setting = static::getConfig();
        if (!$setting) return [];

        return [
            'name' => $setting->company_name,
            'leader' => $setting->pimpinan_setting,
            'about' => $setting->tentang_setting,
            'mission' => $setting->misi_setting,
            'vision' => $setting->visi_setting,
            'logo' => $setting->logo_url,
            'contact' => $setting->contact_info,
        ];
    }
}