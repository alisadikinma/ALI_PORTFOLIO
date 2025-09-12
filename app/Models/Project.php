<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    protected $primaryKey = 'id_project';
    public $timestamps = true;
    
    protected $fillable = [
        'project_name',
        'client_name', 
        'location',
        'description',
        'info_project',
        'project_category',
        'url_project',
        'slug_project',
        'images',
        'featured_image',
        'status',
        'sequence',
    ];

    protected $casts = [
        'images' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('images/projects/' . $this->featured_image);
        }
        return null;
    }

    /**
     * Get all project images URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            return asset('images/projects/' . $image);
        })->toArray();
    }

    /**
     * Get the first image as main image
     */
    public function getMainImageAttribute()
    {
        if ($this->featured_image) {
            return $this->featured_image_url;
        }

        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('images/projects/' . $this->images[0]);
        }

        return asset('images/placeholder/project-placeholder.jpg');
    }

    /**
     * Scope for active projects
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for ordering by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Auto-generate slug when saving
     */
    protected static function booted()
    {
        static::creating(function ($project) {
            if (!$project->slug_project && $project->project_name) {
                $project->slug_project = Str::slug($project->project_name);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('project_name')) {
                $project->slug_project = Str::slug($project->project_name);
            }
        });

        static::deleting(function ($project) {
            // Delete featured image
            if ($project->featured_image && file_exists(public_path('images/projects/' . $project->featured_image))) {
                unlink(public_path('images/projects/' . $project->featured_image));
            }

            // Delete all images
            if ($project->images && is_array($project->images)) {
                foreach ($project->images as $image) {
                    if (file_exists(public_path('images/projects/' . $image))) {
                        unlink(public_path('images/projects/' . $image));
                    }
                }
            }
        });
    }
}
