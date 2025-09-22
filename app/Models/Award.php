<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Award extends Model
{
    use HasFactory;

    protected $table = 'award';
    protected $primaryKey = 'id_award';
    public $timestamps = true;

    protected $fillable = [
        'nama_award',
        'company',
        'period',
        'award_date',
        'gambar_award',
        'keterangan_award',
        'slug_award',
        'award_category',
        'award_level', // local, national, international
        'certificate_url',
        'verification_url',
        'issuer_name',
        'issuer_logo',
        'sequence',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'is_featured' => 'boolean',
        'award_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'image_url',
        'issuer_logo_url',
        'formatted_date',
        'award_age',
        'share_url',
        'level_badge',
    ];

    /**
     * Relationships
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_awards', 'award_id', 'project_id', 'id_award', 'id_project');
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sequence', 'asc')
                    ->orderBy('award_date', 'desc');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, $category): Builder
    {
        return $query->where('award_category', $category);
    }

    public function scopeByLevel(Builder $query, $level): Builder
    {
        return $query->where('award_level', $level);
    }

    public function scopeByYear(Builder $query, $year): Builder
    {
        return $query->whereYear('award_date', $year);
    }

    public function scopeRecent(Builder $query, $days = 365): Builder
    {
        return $query->where('award_date', '>=', Carbon::now()->subDays($days));
    }

    public function scopeByCompany(Builder $query, $company): Builder
    {
        return $query->where('company', 'LIKE', "%{$company}%")
                    ->orWhere('issuer_name', 'LIKE', "%{$company}%");
    }

    /**
     * Accessors
     */
    public function getImageUrlAttribute()
    {
        if ($this->gambar_award) {
            return asset('images/awards/' . $this->gambar_award);
        }
        return asset('images/default/award.png');
    }

    public function getIssuerLogoUrlAttribute()
    {
        if ($this->issuer_logo) {
            return asset('images/issuers/' . $this->issuer_logo);
        }
        return null;
    }

    public function getFormattedDateAttribute()
    {
        if ($this->award_date) {
            return $this->award_date->format('F Y');
        }
        return $this->period ?? 'Date not specified';
    }

    public function getAwardAgeAttribute()
    {
        if ($this->award_date) {
            $age = $this->award_date->diffInYears(now());
            return $age === 0 ? 'Recent' : $age . ' year' . ($age > 1 ? 's' : '') . ' ago';
        }
        return null;
    }

    public function getShareUrlAttribute()
    {
        return route('award.detail', $this->slug_award ?? $this->id_award);
    }

    public function getLevelBadgeAttribute()
    {
        $badges = [
            'international' => ['text' => 'International', 'color' => 'bg-purple-500', 'icon' => 'fas fa-globe'],
            'national' => ['text' => 'National', 'color' => 'bg-blue-500', 'icon' => 'fas fa-flag'],
            'local' => ['text' => 'Local', 'color' => 'bg-green-500', 'icon' => 'fas fa-map-marker-alt'],
        ];

        return $badges[$this->award_level] ?? ['text' => 'Award', 'color' => 'bg-gray-500', 'icon' => 'fas fa-award'];
    }

    /**
     * Methods
     */
    public function generateSlug()
    {
        $title = $this->nama_award;
        $slug = Str::slug($title);

        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug_award', $slug)
                    ->where('id_award', '!=', $this->id_award ?? 0)
                    ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function deleteImage()
    {
        if ($this->gambar_award) {
            $path = public_path('images/awards/' . $this->gambar_award);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function deleteIssuerLogo()
    {
        if ($this->issuer_logo) {
            $path = public_path('images/issuers/' . $this->issuer_logo);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function isRecent($months = 12)
    {
        if (!$this->award_date) return false;
        return $this->award_date->diffInMonths(now()) <= $months;
    }

    public function hasVerification()
    {
        return !empty($this->verification_url) || !empty($this->certificate_url);
    }

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($award) {
            // Auto-generate slug if not provided
            if (!$award->slug_award && $award->nama_award) {
                $award->slug_award = $award->generateSlug();
            }

            // Set default values
            $award->is_featured = $award->is_featured ?? false;
            $award->sequence = $award->sequence ?? 999;
            $award->award_level = $award->award_level ?? 'local';

            // Auto-generate meta fields if not provided
            if (!$award->meta_title && $award->nama_award) {
                $award->meta_title = $award->nama_award . ' | Award';
            }

            if (!$award->meta_description && $award->keterangan_award) {
                $award->meta_description = Str::limit($award->keterangan_award, 155);
            }
        });

        static::updating(function ($award) {
            // Re-generate slug if name changed
            if ($award->isDirty('nama_award')) {
                $award->slug_award = $award->generateSlug();
            }

            // Update meta fields if main fields changed
            if ($award->isDirty('nama_award') && !$award->isDirty('meta_title')) {
                $award->meta_title = $award->nama_award . ' | Award';
            }

            if ($award->isDirty('keterangan_award') && !$award->isDirty('meta_description')) {
                $award->meta_description = Str::limit($award->keterangan_award, 155);
            }
        });

        static::deleting(function ($award) {
            $award->deleteImage();
            $award->deleteIssuerLogo();

            // Clear related caches
            Cache::forget('featured_awards');
            Cache::forget('homepage_awards');
            Cache::forget('recent_awards');
        });

        static::saved(function ($award) {
            // Clear relevant caches when award is saved
            Cache::forget('homepage_awards');
            Cache::forget('featured_awards');
            Cache::forget('recent_awards');
            if ($award->is_featured) {
                Cache::forget('featured_awards');
            }
        });
    }

    /**
     * Static methods for common queries
     */
    public static function getFeaturedAwards($limit = 6)
    {
        return Cache::remember('featured_awards', 3600, function() use ($limit) {
            return static::featured()
                        ->active()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getRecentAwards($limit = 6)
    {
        return Cache::remember('recent_awards', 1800, function() use ($limit) {
            return static::active()
                        ->recent()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getAwardsByLevel($level, $limit = null)
    {
        $query = static::active()
                      ->byLevel($level)
                      ->ordered();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function getAwardsByCategory($category, $limit = null)
    {
        $query = static::active()
                      ->byCategory($category)
                      ->ordered();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function getAwardsForHomepage($limit = 6)
    {
        return Cache::remember('homepage_awards', 1800, function() use ($limit) {
            return static::active()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function searchAwards($search, $filters = [])
    {
        $query = static::active();

        // Search in multiple fields
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_award', 'LIKE', "%{$search}%")
                  ->orWhere('company', 'LIKE', "%{$search}%")
                  ->orWhere('issuer_name', 'LIKE', "%{$search}%")
                  ->orWhere('keterangan_award', 'LIKE', "%{$search}%")
                  ->orWhere('award_category', 'LIKE', "%{$search}%");
            });
        }

        // Apply filters
        if (isset($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['level'])) {
            $query->byLevel($filters['level']);
        }

        if (isset($filters['year'])) {
            $query->byYear($filters['year']);
        }

        if (isset($filters['featured'])) {
            $query->featured();
        }

        return $query->ordered()->paginate($filters['per_page'] ?? 12);
    }

    public static function getAwardStatistics()
    {
        return Cache::remember('award_statistics', 3600, function() {
            return [
                'total_awards' => static::active()->count(),
                'international_awards' => static::active()->byLevel('international')->count(),
                'national_awards' => static::active()->byLevel('national')->count(),
                'local_awards' => static::active()->byLevel('local')->count(),
                'recent_awards' => static::active()->recent(30)->count(),
                'featured_awards' => static::active()->featured()->count(),
                'categories' => static::active()->distinct('award_category')->pluck('award_category')->filter()->count(),
            ];
        });
    }
}
