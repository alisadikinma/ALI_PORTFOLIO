<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonial';
    protected $primaryKey = 'id_testimonial';
    public $timestamps = true;

    protected $fillable = [
        'client_name', // renamed from judul_testimonial for clarity
        'company_name',
        'jabatan', // position/job title
        'email',
        'phone',
        'deskripsi_testimonial',
        'gambar_testimonial',
        'client_logo', // company logo
        'rating', // 1-5 star rating
        'project_id', // link to specific project
        'testimonial_date',
        'location',
        'website_url',
        'linkedin_url',
        'is_featured',
        'is_verified',
        'display_order',
        'status',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'display_order' => 'integer',
        'testimonial_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'client_image_url',
        'company_logo_url',
        'formatted_date',
        'star_rating',
        'excerpt',
        'client_full_name',
    ];

    /**
     * Relationships
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id_project');
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc')
                    ->orderBy('testimonial_date', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeHighRated(Builder $query, $minRating = 4): Builder
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeByRating(Builder $query, $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent(Builder $query, $days = 90): Builder
    {
        return $query->where('testimonial_date', '>=', Carbon::now()->subDays($days));
    }

    public function scopeFromProject(Builder $query, $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeFromCompany(Builder $query, $company): Builder
    {
        return $query->where('company_name', 'LIKE', "%{$company}%");
    }

    /**
     * Accessors
     */
    public function getClientImageUrlAttribute()
    {
        if ($this->gambar_testimonial) {
            return asset('images/testimonials/' . $this->gambar_testimonial);
        }
        return $this->getGravatarUrl();
    }

    public function getCompanyLogoUrlAttribute()
    {
        if ($this->client_logo) {
            return asset('images/companies/' . $this->client_logo);
        }
        return asset('images/default/company.png');
    }

    public function getFormattedDateAttribute()
    {
        if ($this->testimonial_date) {
            return $this->testimonial_date->format('F Y');
        }
        return $this->created_at->format('F Y');
    }

    public function getStarRatingAttribute()
    {
        $rating = $this->rating ?? 5;
        $stars = [];

        for ($i = 1; $i <= 5; $i++) {
            $stars[] = [
                'filled' => $i <= $rating,
                'half' => false // Could implement half-stars later
            ];
        }

        return [
            'stars' => $stars,
            'rating' => $rating,
            'percentage' => ($rating / 5) * 100
        ];
    }

    public function getExcerptAttribute()
    {
        return Str::limit($this->deskripsi_testimonial, 150);
    }

    public function getClientFullNameAttribute()
    {
        // Handle legacy data where client_name might be empty but judul_testimonial has the name
        return $this->client_name ?? $this->judul_testimonial ?? 'Anonymous Client';
    }

    /**
     * Methods
     */
    private function getGravatarUrl()
    {
        if ($this->email) {
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
        }
        return asset('images/default/avatar.png');
    }

    public function deleteImages()
    {
        // Delete client image
        if ($this->gambar_testimonial) {
            $path = public_path('images/testimonials/' . $this->gambar_testimonial);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Delete company logo
        if ($this->client_logo) {
            $path = public_path('images/companies/' . $this->client_logo);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function isHighRated($threshold = 4)
    {
        return $this->rating >= $threshold;
    }

    public function isRecent($days = 90)
    {
        $compareDate = $this->testimonial_date ?? $this->created_at;
        return $compareDate->diffInDays(now()) <= $days;
    }

    public function hasContactInfo()
    {
        return !empty($this->email) || !empty($this->phone) || !empty($this->linkedin_url);
    }

    public function getWordCount()
    {
        return str_word_count(strip_tags($this->deskripsi_testimonial));
    }

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($testimonial) {
            // Set default values
            $testimonial->rating = $testimonial->rating ?? 5;
            $testimonial->is_featured = $testimonial->is_featured ?? false;
            $testimonial->is_verified = $testimonial->is_verified ?? false;
            $testimonial->status = $testimonial->status ?? 'Active';
            $testimonial->display_order = $testimonial->display_order ?? 999;
            $testimonial->testimonial_date = $testimonial->testimonial_date ?? now();

            // Auto-generate meta fields if not provided
            if (!$testimonial->meta_title && $testimonial->client_full_name) {
                $testimonial->meta_title = 'Testimonial from ' . $testimonial->client_full_name;
            }

            if (!$testimonial->meta_description && $testimonial->deskripsi_testimonial) {
                $testimonial->meta_description = Str::limit($testimonial->deskripsi_testimonial, 155);
            }
        });

        static::updating(function ($testimonial) {
            // Update meta fields if main fields changed
            if ($testimonial->isDirty('client_name') && !$testimonial->isDirty('meta_title')) {
                $testimonial->meta_title = 'Testimonial from ' . $testimonial->client_full_name;
            }

            if ($testimonial->isDirty('deskripsi_testimonial') && !$testimonial->isDirty('meta_description')) {
                $testimonial->meta_description = Str::limit($testimonial->deskripsi_testimonial, 155);
            }
        });

        static::deleting(function ($testimonial) {
            $testimonial->deleteImages();

            // Clear related caches
            Cache::forget('featured_testimonials');
            Cache::forget('homepage_testimonials');
            Cache::forget('high_rated_testimonials');
        });

        static::saved(function ($testimonial) {
            // Clear relevant caches when testimonial is saved
            Cache::forget('homepage_testimonials');
            Cache::forget('featured_testimonials');
            Cache::forget('high_rated_testimonials');
            if ($testimonial->is_featured) {
                Cache::forget('featured_testimonials');
            }
        });
    }

    /**
     * Static methods for common queries
     */
    public static function getFeaturedTestimonials($limit = 6)
    {
        return Cache::remember('featured_testimonials', 3600, function() use ($limit) {
            return static::featured()
                        ->active()
                        ->verified()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getHighRatedTestimonials($limit = 6, $minRating = 4)
    {
        return Cache::remember("high_rated_testimonials_{$minRating}", 1800, function() use ($limit, $minRating) {
            return static::active()
                        ->highRated($minRating)
                        ->verified()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getTestimonialsForHomepage($limit = 6)
    {
        return Cache::remember('homepage_testimonials', 1800, function() use ($limit) {
            return static::active()
                        ->verified()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function getTestimonialsByProject($projectId, $limit = null)
    {
        $query = static::active()
                      ->fromProject($projectId)
                      ->verified()
                      ->ordered();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function getRecentTestimonials($limit = 6)
    {
        return Cache::remember('recent_testimonials', 900, function() use ($limit) {
            return static::active()
                        ->recent()
                        ->verified()
                        ->ordered()
                        ->limit($limit)
                        ->get();
        });
    }

    public static function searchTestimonials($search, $filters = [])
    {
        $query = static::active()->verified();

        // Search in multiple fields
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi_testimonial', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Apply filters
        if (isset($filters['rating'])) {
            $query->byRating($filters['rating']);
        }

        if (isset($filters['min_rating'])) {
            $query->highRated($filters['min_rating']);
        }

        if (isset($filters['company'])) {
            $query->fromCompany($filters['company']);
        }

        if (isset($filters['featured'])) {
            $query->featured();
        }

        if (isset($filters['project_id'])) {
            $query->fromProject($filters['project_id']);
        }

        return $query->ordered()->paginate($filters['per_page'] ?? 12);
    }

    public static function getTestimonialStatistics()
    {
        return Cache::remember('testimonial_statistics', 3600, function() {
            return [
                'total_testimonials' => static::active()->count(),
                'verified_testimonials' => static::active()->verified()->count(),
                'featured_testimonials' => static::active()->featured()->count(),
                'high_rated_testimonials' => static::active()->highRated(4)->count(),
                'average_rating' => static::active()->avg('rating') ?? 5,
                'recent_testimonials' => static::active()->recent(30)->count(),
                'companies_count' => static::active()->distinct('company_name')->count(),
                'rating_distribution' => [
                    5 => static::active()->byRating(5)->count(),
                    4 => static::active()->byRating(4)->count(),
                    3 => static::active()->byRating(3)->count(),
                    2 => static::active()->byRating(2)->count(),
                    1 => static::active()->byRating(1)->count(),
                ],
            ];
        });
    }

    /**
     * Legacy support for old field names
     */
    public function getJudulTestimonialAttribute($value)
    {
        return $this->client_name ?? $value;
    }

    public function setJudulTestimonialAttribute($value)
    {
        $this->attributes['client_name'] = $value;
    }
}
