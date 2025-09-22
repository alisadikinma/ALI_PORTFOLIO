<?php

namespace App\Services;

use App\Models\Award;
use App\Models\Testimonial;
use App\Models\Galeri;
use App\Models\Berita;
use App\Models\Layanan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Content Service - Handles content-related business logic for testimonials, awards, gallery, articles
 */
class ContentService
{
    const CACHE_DURATION = [
        'testimonials' => 1800, // 30 minutes
        'awards' => 3600,       // 1 hour
        'gallery' => 1800,      // 30 minutes
        'articles' => 900,      // 15 minutes
        'services' => 3600,     // 1 hour
    ];

    /**
     * TESTIMONIALS
     */
    public static function getTestimonialsForHomepage($limit = 6)
    {
        return Cache::remember('homepage_testimonials', self::CACHE_DURATION['testimonials'], function() use ($limit) {
            return Testimonial::getTestimonialsForHomepage($limit);
        });
    }

    public static function getFeaturedTestimonials($limit = 6)
    {
        return Testimonial::getFeaturedTestimonials($limit);
    }

    public static function getHighRatedTestimonials($limit = 6, $minRating = 4)
    {
        return Testimonial::getHighRatedTestimonials($limit, $minRating);
    }

    public static function getTestimonialsByProject($projectId, $limit = null)
    {
        return Testimonial::getTestimonialsByProject($projectId, $limit);
    }

    public static function searchTestimonials($search = null, $filters = [])
    {
        return Testimonial::searchTestimonials($search, $filters);
    }

    public static function getTestimonialStatistics()
    {
        return Cache::remember('testimonial_statistics', self::CACHE_DURATION['testimonials'], function() {
            return Testimonial::getTestimonialStatistics();
        });
    }

    /**
     * AWARDS
     */
    public static function getAwardsForHomepage($limit = 6)
    {
        return Cache::remember('homepage_awards', self::CACHE_DURATION['awards'], function() use ($limit) {
            return Award::getAwardsForHomepage($limit);
        });
    }

    public static function getFeaturedAwards($limit = 6)
    {
        return Award::getFeaturedAwards($limit);
    }

    public static function getRecentAwards($limit = 6)
    {
        return Award::getRecentAwards($limit);
    }

    public static function getAwardsByLevel($level, $limit = null)
    {
        return Award::getAwardsByLevel($level, $limit);
    }

    public static function getAwardsByCategory($category, $limit = null)
    {
        return Award::getAwardsByCategory($category, $limit);
    }

    public static function searchAwards($search = null, $filters = [])
    {
        return Award::searchAwards($search, $filters);
    }

    public static function getAwardStatistics()
    {
        return Cache::remember('award_statistics', self::CACHE_DURATION['awards'], function() {
            return Award::getAwardStatistics();
        });
    }

    /**
     * GALLERY
     */
    public static function getGalleryForHomepage($limit = 12)
    {
        return Cache::remember('homepage_gallery', self::CACHE_DURATION['gallery'], function() use ($limit) {
            return Galeri::with(['items' => function($query) {
                $query->where('status', 'Active')->orderBy('sequence', 'asc');
            }])
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->limit($limit)
            ->get();
        });
    }

    public static function getAllGalleries()
    {
        return Galeri::with(['items' => function($query) {
                $query->where('status', 'Active')->orderBy('sequence', 'asc');
            }])
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
    }

    public static function getGalleryById($id)
    {
        return Galeri::with(['items' => function($query) {
                $query->where('status', 'Active')->orderBy('sequence', 'asc');
            }])
            ->where('id_galeri', $id)
            ->where('status', 'Active')
            ->first();
    }

    public static function getGalleryItems($galleryId)
    {
        $gallery = self::getGalleryById($galleryId);

        if (!$gallery) {
            return null;
        }

        return [
            'gallery' => [
                'id' => $gallery->id_galeri,
                'name' => $gallery->nama_galeri,
                'company' => $gallery->company,
                'period' => $gallery->period,
                'description' => $gallery->deskripsi_galeri,
                'thumbnail' => $gallery->thumbnail_url,
            ],
            'items' => $gallery->items->map(function($item) {
                return [
                    'id' => $item->id_gallery_item,
                    'type' => $item->type,
                    'file_url' => $item->type === 'image'
                        ? asset('file/galeri/' . $item->file_name)
                        : $item->youtube_url,
                    'thumbnail_url' => $item->type === 'image'
                        ? asset('file/galeri/' . $item->file_name)
                        : self::getYouTubeThumbnail($item->youtube_url),
                    'sequence' => $item->sequence,
                ];
            })
        ];
    }

    /**
     * ARTICLES/BLOG
     */
    public static function getArticlesForHomepage($limit = 4)
    {
        return Cache::remember('homepage_articles', self::CACHE_DURATION['articles'], function() use ($limit) {
            return Berita::select([
                'id_berita', 'judul_berita', 'slug_berita', 'gambar_berita',
                'isi_berita', 'tanggal_berita', 'kategori_berita',
                'meta_description', 'tags', 'reading_time', 'views', 'is_featured'
            ])
            ->orderBy('tanggal_berita', 'desc')
            ->limit($limit)
            ->get();
        });
    }

    public static function getFeaturedArticles($limit = 3)
    {
        return Cache::remember('featured_articles', self::CACHE_DURATION['articles'], function() use ($limit) {
            return Berita::select([
                'judul_berita', 'slug_berita', 'gambar_berita',
                'tanggal_berita', 'kategori_berita', 'meta_description'
            ])
            ->where('is_featured', true)
            ->orderBy('tanggal_berita', 'desc')
            ->limit($limit)
            ->get();
        });
    }

    public static function getArticleBySlug($slug, $incrementView = false)
    {
        $article = Berita::where('slug_berita', $slug)->first();

        if (!$article) {
            return null;
        }

        if ($incrementView) {
            // Use session to prevent multiple views from same user
            $sessionKey = 'viewed_article_' . $article->id_berita;
            if (!session()->has($sessionKey)) {
                $article->increment('views');
                session()->put($sessionKey, true);
            }
        }

        // Calculate reading time if not set
        if (!$article->reading_time) {
            $wordCount = str_word_count(strip_tags($article->isi_berita));
            $article->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
            $article->save();
        }

        return $article;
    }

    public static function getRelatedArticles($article, $limit = 3)
    {
        return Cache::remember("related_articles_{$article->id_berita}", self::CACHE_DURATION['articles'], function() use ($article, $limit) {
            return Berita::where('id_berita', '!=', $article->id_berita)
                ->where(function($query) use ($article) {
                    if (!empty($article->kategori_berita)) {
                        $query->where('kategori_berita', $article->kategori_berita);
                    }
                    if (!empty($article->tags)) {
                        $tags = explode(',', $article->tags);
                        foreach ($tags as $tag) {
                            $query->orWhere('tags', 'LIKE', '%' . trim($tag) . '%');
                        }
                    }
                })
                ->select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita'])
                ->orderBy('tanggal_berita', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    public static function getPopularTags($limit = 15)
    {
        return Cache::remember('popular_tags', self::CACHE_DURATION['articles'], function() use ($limit) {
            $allTags = Berita::whereNotNull('tags')->pluck('tags');
            $tagCounts = [];

            foreach ($allTags as $tagString) {
                $tags = array_map('trim', explode(',', $tagString));
                foreach ($tags as $tag) {
                    if (!empty($tag)) {
                        $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
                    }
                }
            }

            arsort($tagCounts);
            return array_slice($tagCounts, 0, $limit, true);
        });
    }

    public static function getArticleCategories()
    {
        return Cache::remember('article_categories', self::CACHE_DURATION['articles'], function() {
            return Berita::select('kategori_berita')
                ->distinct()
                ->whereNotNull('kategori_berita')
                ->where('kategori_berita', '!=', '')
                ->orderBy('kategori_berita')
                ->pluck('kategori_berita');
        });
    }

    public static function searchArticles($search = null, $filters = [])
    {
        $query = Berita::select([
            'id_berita', 'judul_berita', 'slug_berita', 'gambar_berita',
            'isi_berita', 'tanggal_berita', 'kategori_berita',
            'meta_title', 'meta_description', 'tags', 'reading_time',
            'views', 'is_featured', 'created_at', 'updated_at'
        ]);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_berita', 'LIKE', "%{$search}%")
                  ->orWhere('meta_title', 'LIKE', "%{$search}%")
                  ->orWhere('meta_description', 'LIKE', "%{$search}%")
                  ->orWhere('isi_berita', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }

        // Apply filters
        if (isset($filters['category'])) {
            $query->where('kategori_berita', $filters['category']);
        }

        if (isset($filters['tag'])) {
            $query->where('tags', 'LIKE', "%{$filters['tag']}%");
        }

        if (isset($filters['featured'])) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('tanggal_berita', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc')->orderBy('tanggal_berita', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('tanggal_berita', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('judul_berita', 'asc');
                break;
            case 'reading_time':
                $query->orderBy('reading_time', 'asc')->orderBy('tanggal_berita', 'desc');
                break;
            default:
                $query->orderBy('tanggal_berita', 'desc');
                break;
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * SERVICES
     */
    public static function getServicesForHomepage()
    {
        return Cache::remember('homepage_services', self::CACHE_DURATION['services'], function() {
            return \DB::table('layanan')
                ->select('id_layanan', 'nama_layanan', 'sub_nama_layanan', 'icon_layanan', 'gambar_layanan', 'keterangan_layanan', 'sequence', 'status')
                ->where('status', 'Active')
                ->orderBy('sequence', 'asc')
                ->get();
        });
    }

    /**
     * UTILITIES
     */
    private static function getYouTubeThumbnail($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $url, $matches);
        $videoId = $matches[1] ?? null;

        return $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
    }

    /**
     * Clear all content-related caches
     */
    public static function clearAllContentCaches()
    {
        $cacheKeys = [
            'homepage_testimonials',
            'featured_testimonials',
            'high_rated_testimonials_4',
            'testimonial_statistics',
            'homepage_awards',
            'featured_awards',
            'recent_awards',
            'award_statistics',
            'homepage_gallery',
            'homepage_articles',
            'featured_articles',
            'popular_tags',
            'article_categories',
            'homepage_services',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Log::info('All content caches cleared');
    }

    /**
     * Get comprehensive content statistics
     */
    public static function getContentStatistics()
    {
        return Cache::remember('content_statistics', 3600, function() {
            return [
                'testimonials' => [
                    'total' => Testimonial::active()->count(),
                    'verified' => Testimonial::active()->verified()->count(),
                    'featured' => Testimonial::active()->featured()->count(),
                    'high_rated' => Testimonial::active()->highRated(4)->count(),
                    'average_rating' => Testimonial::active()->avg('rating') ?? 5,
                ],
                'awards' => [
                    'total' => Award::active()->count(),
                    'international' => Award::active()->byLevel('international')->count(),
                    'national' => Award::active()->byLevel('national')->count(),
                    'local' => Award::active()->byLevel('local')->count(),
                    'featured' => Award::active()->featured()->count(),
                ],
                'gallery' => [
                    'total_galleries' => Galeri::where('status', 'Active')->count(),
                    'total_items' => \DB::table('gallery_items')->where('status', 'Active')->count(),
                    'image_items' => \DB::table('gallery_items')->where('type', 'image')->where('status', 'Active')->count(),
                    'video_items' => \DB::table('gallery_items')->where('type', 'youtube')->where('status', 'Active')->count(),
                ],
                'articles' => [
                    'total' => Berita::count(),
                    'featured' => Berita::where('is_featured', true)->count(),
                    'total_views' => Berita::sum('views'),
                    'categories' => Berita::distinct('kategori_berita')->whereNotNull('kategori_berita')->count(),
                    'avg_reading_time' => Berita::avg('reading_time') ?? 3,
                ],
                'services' => [
                    'total' => \DB::table('layanan')->where('status', 'Active')->count(),
                ]
            ];
        });
    }

    /**
     * Get dashboard metrics for admin
     */
    public static function getDashboardMetrics()
    {
        return Cache::remember('dashboard_content_metrics', 1800, function() {
            return [
                'recent_testimonials' => Testimonial::active()->recent(30)->count(),
                'recent_awards' => Award::active()->recent(30)->count(),
                'recent_articles' => Berita::whereDate('created_at', '>=', now()->subDays(30))->count(),
                'popular_articles' => Berita::orderBy('views', 'desc')->limit(5)->get(['judul_berita', 'views', 'slug_berita']),
                'top_rated_testimonials' => Testimonial::active()->highRated(5)->limit(5)->get(['client_name', 'rating', 'company_name']),
                'latest_awards' => Award::active()->orderBy('award_date', 'desc')->limit(5)->get(['nama_award', 'company', 'award_date']),
            ];
        });
    }
}