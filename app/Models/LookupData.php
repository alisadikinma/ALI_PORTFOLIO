<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupData extends Model
{
    use HasFactory;

    protected $table = 'lookup_data';
    protected $primaryKey = 'id_lookup_data';
    public $timestamps = true;

    protected $fillable = [
        'lookup_type',
        'lookup_code',
        'lookup_name',
        'lookup_description',
        'lookup_icon',
        'lookup_color',
        'lookup_metadata',
        'parent_id',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'lookup_metadata' => 'array' // Cast JSON to array
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('lookup_type', $type);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('lookup_code', $code);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Static methods for common queries
    public static function getHomepageSections()
    {
        return self::byType('homepage_section')
            ->active()
            ->ordered()
            ->get();
    }

    public static function getActiveHomepageSectionCodes()
    {
        return self::byType('homepage_section')
            ->active()
            ->ordered()
            ->pluck('lookup_code')
            ->toArray();
    }

    public static function getHomepageSectionByCode($code)
    {
        return self::byType('homepage_section')
            ->byCode($code)
            ->active()
            ->first();
    }

    public static function getProjectCategories()
    {
        return self::byType('project_category')
            ->active()
            ->ordered()
            ->get();
    }

    // Helper method to get section status and order
    public static function getSectionConfig($sectionCode)
    {
        $section = self::getHomepageSectionByCode($sectionCode);
        
        return [
            'is_active' => $section ? $section->is_active : false,
            'sort_order' => $section ? $section->sort_order : 999,
            'title' => $section ? $section->lookup_name : ucfirst($sectionCode),
            'description' => $section ? $section->lookup_description : null,
            'icon' => $section ? $section->lookup_icon : null,
            'color' => $section ? $section->lookup_color : null,
            'metadata' => $section ? $section->lookup_metadata : null
        ];
    }

    // Get all homepage sections with their configurations
    public static function getHomepageConfiguration()
    {
        $sections = self::getHomepageSections();
        $config = [];

        foreach ($sections as $section) {
            $config[$section->lookup_code] = [
                'is_active' => $section->is_active,
                'sort_order' => $section->sort_order,
                'title' => $section->lookup_name,
                'description' => $section->lookup_description,
                'icon' => $section->lookup_icon,
                'color' => $section->lookup_color,
                'metadata' => $section->lookup_metadata
            ];
        }

        return $config;
    }

    /**
     * Additional static methods for enhanced functionality
     */
    public static function createLookupType($type, $items = [])
    {
        $createdItems = [];

        foreach ($items as $index => $item) {
            $lookupItem = self::create([
                'lookup_type' => $type,
                'lookup_code' => $item['code'],
                'lookup_name' => $item['name'],
                'lookup_description' => $item['description'] ?? null,
                'lookup_icon' => $item['icon'] ?? null,
                'lookup_color' => $item['color'] ?? null,
                'lookup_metadata' => $item['metadata'] ?? null,
                'parent_id' => $item['parent_id'] ?? null,
                'sort_order' => $item['sort_order'] ?? ($index + 1),
                'is_active' => $item['is_active'] ?? 1,
            ]);

            $createdItems[] = $lookupItem;
        }

        return $createdItems;
    }

    public static function updateLookupItem($type, $code, $updates)
    {
        return self::byType($type)
                  ->byCode($code)
                  ->first()
                  ?->update($updates);
    }

    public static function getLookupOptions($type, $includeInactive = false)
    {
        $query = self::byType($type);

        if (!$includeInactive) {
            $query->active();
        }

        return $query->ordered()
                    ->pluck('lookup_name', 'lookup_code')
                    ->toArray();
    }

    public static function getHierarchicalData($type, $parentId = null)
    {
        $items = self::byType($type)
                    ->where('parent_id', $parentId)
                    ->active()
                    ->ordered()
                    ->get();

        $result = [];

        foreach ($items as $item) {
            $itemData = $item->toArray();
            $itemData['children'] = self::getHierarchicalData($type, $item->id);
            $result[] = $itemData;
        }

        return $result;
    }

    public static function initializeDefaultData()
    {
        $defaultData = [
            'homepage_section' => [
                ['code' => 'about', 'name' => 'About Section', 'icon' => 'fas fa-user', 'color' => '#3b82f6', 'sort_order' => 1],
                ['code' => 'services', 'name' => 'Services Section', 'icon' => 'fas fa-cogs', 'color' => '#10b981', 'sort_order' => 2],
                ['code' => 'portfolio', 'name' => 'Portfolio Section', 'icon' => 'fas fa-briefcase', 'color' => '#8b5cf6', 'sort_order' => 3],
                ['code' => 'awards', 'name' => 'Awards Section', 'icon' => 'fas fa-trophy', 'color' => '#f59e0b', 'sort_order' => 4],
                ['code' => 'testimonials', 'name' => 'Testimonials Section', 'icon' => 'fas fa-quote-left', 'color' => '#ef4444', 'sort_order' => 5],
                ['code' => 'gallery', 'name' => 'Gallery Section', 'icon' => 'fas fa-images', 'color' => '#06b6d4', 'sort_order' => 6],
                ['code' => 'articles', 'name' => 'Articles Section', 'icon' => 'fas fa-newspaper', 'color' => '#6366f1', 'sort_order' => 7],
                ['code' => 'contact', 'name' => 'Contact Section', 'icon' => 'fas fa-envelope', 'color' => '#84cc16', 'sort_order' => 8],
            ],
            'project_category' => [
                ['code' => 'web_development', 'name' => 'Web Development', 'icon' => 'fas fa-code', 'color' => '#3b82f6', 'sort_order' => 1],
                ['code' => 'mobile_app', 'name' => 'Mobile App', 'icon' => 'fas fa-mobile-alt', 'color' => '#10b981', 'sort_order' => 2],
                ['code' => 'ui_ux_design', 'name' => 'UI/UX Design', 'icon' => 'fas fa-palette', 'color' => '#8b5cf6', 'sort_order' => 3],
                ['code' => 'branding', 'name' => 'Branding', 'icon' => 'fas fa-trademark', 'color' => '#f59e0b', 'sort_order' => 4],
                ['code' => 'digital_marketing', 'name' => 'Digital Marketing', 'icon' => 'fas fa-bullhorn', 'color' => '#ef4444', 'sort_order' => 5],
                ['code' => 'consulting', 'name' => 'Consulting', 'icon' => 'fas fa-handshake', 'color' => '#06b6d4', 'sort_order' => 6],
            ],
            'award_category' => [
                ['code' => 'design_excellence', 'name' => 'Design Excellence', 'icon' => 'fas fa-award', 'color' => '#f59e0b', 'sort_order' => 1],
                ['code' => 'innovation', 'name' => 'Innovation Award', 'icon' => 'fas fa-lightbulb', 'color' => '#3b82f6', 'sort_order' => 2],
                ['code' => 'client_satisfaction', 'name' => 'Client Satisfaction', 'icon' => 'fas fa-star', 'color' => '#10b981', 'sort_order' => 3],
                ['code' => 'technical_achievement', 'name' => 'Technical Achievement', 'icon' => 'fas fa-cog', 'color' => '#8b5cf6', 'sort_order' => 4],
            ],
            'service_category' => [
                ['code' => 'development', 'name' => 'Development Services', 'icon' => 'fas fa-code', 'color' => '#3b82f6', 'sort_order' => 1],
                ['code' => 'design', 'name' => 'Design Services', 'icon' => 'fas fa-palette', 'color' => '#8b5cf6', 'sort_order' => 2],
                ['code' => 'consulting', 'name' => 'Consulting Services', 'icon' => 'fas fa-handshake', 'color' => '#10b981', 'sort_order' => 3],
                ['code' => 'support', 'name' => 'Support Services', 'icon' => 'fas fa-life-ring', 'color' => '#f59e0b', 'sort_order' => 4],
            ],
        ];

        foreach ($defaultData as $type => $items) {
            // Only create if type doesn't exist
            if (self::byType($type)->count() === 0) {
                self::createLookupType($type, $items);
            }
        }
    }

    public static function clearLookupCache($type = null)
    {
        $cacheKeys = [
            'homepage_sections',
            'homepage_configuration',
            'project_categories',
            'active_homepage_sections',
        ];

        if ($type) {
            $cacheKeys[] = "lookup_type_{$type}";
            $cacheKeys[] = "lookup_options_{$type}";
        }

        foreach ($cacheKeys as $key) {
            \Cache::forget($key);
        }
    }

    /**
     * Boot method for model events
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            self::clearLookupCache($model->lookup_type);
        });

        static::deleted(function ($model) {
            self::clearLookupCache($model->lookup_type);
        });
    }

    /**
     * Enhanced accessors
     */
    public function getIconHtmlAttribute()
    {
        if (!$this->lookup_icon) {
            return '<i class="fas fa-circle"></i>';
        }

        return sprintf('<i class="%s" style="color: %s;"></i>', $this->lookup_icon, $this->lookup_color ?? '#6b7280');
    }

    public function getColorStyleAttribute()
    {
        return $this->lookup_color ? "color: {$this->lookup_color};" : 'color: #6b7280;';
    }

    public function getBackgroundStyleAttribute()
    {
        return $this->lookup_color ? "background-color: {$this->lookup_color};" : 'background-color: #6b7280;';
    }

    /**
     * Methods
     */
    public function hasChildren()
    {
        return self::where('parent_id', $this->id)->exists();
    }

    public function getChildren()
    {
        return self::where('parent_id', $this->id)
                  ->active()
                  ->ordered()
                  ->get();
    }

    public function getParent()
    {
        if (!$this->parent_id) {
            return null;
        }

        return self::find($this->parent_id);
    }

    public function getBreadcrumb()
    {
        $breadcrumb = [$this->lookup_name];
        $parent = $this->getParent();

        while ($parent) {
            array_unshift($breadcrumb, $parent->lookup_name);
            $parent = $parent->getParent();
        }

        return implode(' > ', $breadcrumb);
    }
}
