<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupData extends Model
{
    use HasFactory;

    protected $table = 'lookup_data';
    protected $primaryKey = 'id';
    public $timestamps = false; // Assuming no created_at/updated_at columns

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
}
