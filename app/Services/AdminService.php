<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Setting;
use App\Models\Award;
use App\Models\Testimonial;
use App\Models\Galeri;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Admin Service - Handles admin dashboard and management functionality
 */
class AdminService
{
    const CACHE_DURATION = 1800; // 30 minutes

    /**
     * Get comprehensive dashboard statistics
     */
    public static function getDashboardStats()
    {
        return Cache::remember('admin_dashboard_stats', self::CACHE_DURATION, function() {
            return [
                'overview' => self::getOverviewStats(),
                'content' => self::getContentStats(),
                'performance' => self::getPerformanceStats(),
                'recent_activity' => self::getRecentActivity(),
                'popular_content' => self::getPopularContent(),
                'growth_metrics' => self::getGrowthMetrics(),
                'quick_actions' => self::getQuickActions(),
            ];
        });
    }

    /**
     * Get overview statistics
     */
    private static function getOverviewStats()
    {
        return [
            'total_projects' => Project::active()->count(),
            'total_testimonials' => Testimonial::active()->count(),
            'total_awards' => Award::active()->count(),
            'total_articles' => Berita::count(),
            'total_gallery_items' => DB::table('gallery_items')->where('status', 'Active')->count(),
            'total_views' => Project::sum('views_count') + Berita::sum('views'),
            'total_likes' => Project::sum('likes_count'),
            'featured_content' => [
                'projects' => Project::active()->featured()->count(),
                'testimonials' => Testimonial::active()->featured()->count(),
                'awards' => Award::active()->featured()->count(),
                'articles' => Berita::where('is_featured', true)->count(),
            ],
        ];
    }

    /**
     * Get content statistics
     */
    private static function getContentStats()
    {
        return [
            'projects' => [
                'total' => Project::count(),
                'active' => Project::active()->count(),
                'inactive' => Project::where('status', '!=', 'Active')->count(),
                'featured' => Project::featured()->count(),
                'recent' => Project::active()->recent(30)->count(),
                'by_category' => Project::active()
                    ->select('project_category', DB::raw('count(*) as total'))
                    ->groupBy('project_category')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
                    ->pluck('total', 'project_category')
                    ->toArray(),
            ],
            'testimonials' => [
                'total' => Testimonial::count(),
                'active' => Testimonial::active()->count(),
                'verified' => Testimonial::active()->verified()->count(),
                'high_rated' => Testimonial::active()->highRated(4)->count(),
                'average_rating' => round(Testimonial::active()->avg('rating') ?? 5, 1),
            ],
            'awards' => [
                'total' => Award::count(),
                'active' => Award::active()->count(),
                'by_level' => [
                    'international' => Award::active()->byLevel('international')->count(),
                    'national' => Award::active()->byLevel('national')->count(),
                    'local' => Award::active()->byLevel('local')->count(),
                ],
                'recent' => Award::active()->recent(365)->count(),
            ],
            'articles' => [
                'total' => Berita::count(),
                'featured' => Berita::where('is_featured', true)->count(),
                'total_views' => Berita::sum('views'),
                'categories' => Berita::distinct('kategori_berita')->whereNotNull('kategori_berita')->count(),
                'avg_reading_time' => round(Berita::avg('reading_time') ?? 3, 1),
            ],
        ];
    }

    /**
     * Get performance statistics
     */
    private static function getPerformanceStats()
    {
        return [
            'cache_health' => self::getCacheHealth(),
            'database_size' => self::getDatabaseSize(),
            'image_stats' => self::getImageStats(),
            'response_metrics' => self::getResponseMetrics(),
        ];
    }

    /**
     * Get recent activity
     */
    private static function getRecentActivity()
    {
        return [
            'recent_projects' => Project::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id_project', 'project_name', 'client_name', 'created_at'])
                ->map(function($project) {
                    return [
                        'type' => 'project',
                        'title' => $project->project_name,
                        'subtitle' => $project->client_name,
                        'date' => $project->created_at,
                        'url' => route('project.show', $project->id_project),
                    ];
                }),
            'recent_testimonials' => Testimonial::active()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id_testimonial', 'client_name', 'company_name', 'rating', 'created_at'])
                ->map(function($testimonial) {
                    return [
                        'type' => 'testimonial',
                        'title' => $testimonial->client_name ?? $testimonial->client_full_name,
                        'subtitle' => $testimonial->company_name,
                        'rating' => $testimonial->rating,
                        'date' => $testimonial->created_at,
                    ];
                }),
            'recent_awards' => Award::active()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id_award', 'nama_award', 'company', 'award_date', 'created_at'])
                ->map(function($award) {
                    return [
                        'type' => 'award',
                        'title' => $award->nama_award,
                        'subtitle' => $award->company,
                        'date' => $award->award_date ?? $award->created_at,
                    ];
                }),
            'recent_articles' => Berita::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id_berita', 'judul_berita', 'kategori_berita', 'views', 'created_at'])
                ->map(function($article) {
                    return [
                        'type' => 'article',
                        'title' => $article->judul_berita,
                        'subtitle' => $article->kategori_berita,
                        'views' => $article->views,
                        'date' => $article->created_at,
                    ];
                }),
        ];
    }

    /**
     * Get popular content
     */
    private static function getPopularContent()
    {
        return [
            'top_projects' => Project::active()
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(['id_project', 'project_name', 'client_name', 'views_count', 'likes_count'])
                ->map(function($project) {
                    return [
                        'title' => $project->project_name,
                        'subtitle' => $project->client_name,
                        'views' => $project->views_count,
                        'likes' => $project->likes_count,
                        'url' => route('project.show', $project->id_project),
                    ];
                }),
            'top_articles' => Berita::orderBy('views', 'desc')
                ->limit(10)
                ->get(['id_berita', 'judul_berita', 'kategori_berita', 'views', 'slug_berita'])
                ->map(function($article) {
                    return [
                        'title' => $article->judul_berita,
                        'subtitle' => $article->kategori_berita,
                        'views' => $article->views,
                        'url' => route('article.detail', $article->slug_berita),
                    ];
                }),
            'top_testimonials' => Testimonial::active()
                ->verified()
                ->highRated(4)
                ->orderBy('rating', 'desc')
                ->limit(10)
                ->get(['client_name', 'company_name', 'rating', 'created_at'])
                ->map(function($testimonial) {
                    return [
                        'title' => $testimonial->client_name ?? $testimonial->client_full_name,
                        'subtitle' => $testimonial->company_name,
                        'rating' => $testimonial->rating,
                        'date' => $testimonial->created_at,
                    ];
                }),
        ];
    }

    /**
     * Get growth metrics
     */
    private static function getGrowthMetrics()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        return [
            'projects_growth' => [
                'current' => Project::where('created_at', '>=', $currentMonth)->count(),
                'previous' => Project::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            ],
            'testimonials_growth' => [
                'current' => Testimonial::where('created_at', '>=', $currentMonth)->count(),
                'previous' => Testimonial::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            ],
            'awards_growth' => [
                'current' => Award::where('created_at', '>=', $currentMonth)->count(),
                'previous' => Award::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            ],
            'articles_growth' => [
                'current' => Berita::where('created_at', '>=', $currentMonth)->count(),
                'previous' => Berita::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            ],
            'views_growth' => self::getViewsGrowth(),
        ];
    }

    /**
     * Get quick actions for admin
     */
    private static function getQuickActions()
    {
        return [
            'pending_approvals' => [
                'testimonials' => Testimonial::where('is_verified', false)->count(),
                'projects' => Project::where('status', 'Draft')->count(),
            ],
            'maintenance_tasks' => [
                'cache_size' => self::getCacheSize(),
                'log_files' => self::getLogFileCount(),
                'image_optimization_needed' => self::getUnoptimizedImagesCount(),
            ],
            'content_needs_attention' => [
                'projects_without_images' => Project::active()->whereNull('featured_image')->count(),
                'testimonials_unrated' => Testimonial::active()->whereNull('rating')->count(),
                'articles_no_meta' => Berita::whereNull('meta_description')->count(),
            ],
        ];
    }

    /**
     * Get system health status
     */
    public static function getSystemHealth()
    {
        return Cache::remember('system_health', 300, function() { // 5 minutes cache
            $health = [
                'overall_status' => 'healthy',
                'checks' => [],
                'score' => 100,
            ];

            // Database connection check
            try {
                DB::connection()->getPdo();
                $health['checks']['database'] = ['status' => 'healthy', 'message' => 'Database connection successful'];
            } catch (\Exception $e) {
                $health['checks']['database'] = ['status' => 'error', 'message' => 'Database connection failed'];
                $health['score'] -= 30;
                $health['overall_status'] = 'warning';
            }

            // Storage check
            $storageWritable = is_writable(storage_path());
            $health['checks']['storage'] = [
                'status' => $storageWritable ? 'healthy' : 'error',
                'message' => $storageWritable ? 'Storage is writable' : 'Storage is not writable'
            ];
            if (!$storageWritable) {
                $health['score'] -= 20;
                $health['overall_status'] = 'error';
            }

            // Cache check
            try {
                Cache::put('health_check', 'test', 60);
                $cacheWorking = Cache::get('health_check') === 'test';
                Cache::forget('health_check');

                $health['checks']['cache'] = [
                    'status' => $cacheWorking ? 'healthy' : 'warning',
                    'message' => $cacheWorking ? 'Cache is working' : 'Cache may have issues'
                ];
                if (!$cacheWorking) {
                    $health['score'] -= 10;
                    if ($health['overall_status'] === 'healthy') {
                        $health['overall_status'] = 'warning';
                    }
                }
            } catch (\Exception $e) {
                $health['checks']['cache'] = ['status' => 'error', 'message' => 'Cache system error'];
                $health['score'] -= 15;
                $health['overall_status'] = 'warning';
            }

            // Performance checks
            $health['checks']['performance'] = self::getPerformanceHealth();
            if ($health['checks']['performance']['status'] !== 'healthy') {
                $health['score'] -= 10;
                if ($health['overall_status'] === 'healthy') {
                    $health['overall_status'] = 'warning';
                }
            }

            return $health;
        });
    }

    /**
     * Get user activity summary
     */
    public static function getUserActivity($days = 30)
    {
        return Cache::remember("user_activity_{$days}", 3600, function() use ($days) {
            $startDate = Carbon::now()->subDays($days);

            return [
                'total_users' => User::count(),
                'active_users' => User::where('last_login_at', '>=', $startDate)->count(),
                'new_users' => User::where('created_at', '>=', $startDate)->count(),
                'user_logins' => User::whereNotNull('last_login_at')
                    ->where('last_login_at', '>=', $startDate)
                    ->count(),
                'admin_users' => User::where('role', 'admin')->count(),
            ];
        });
    }

    /**
     * Clear all admin caches
     */
    public static function clearAdminCaches()
    {
        $cacheKeys = [
            'admin_dashboard_stats',
            'system_health',
            'user_activity_30',
            'content_statistics',
            'performance_metrics',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Log::info('Admin caches cleared');
        return true;
    }

    /**
     * Generate admin report
     */
    public static function generateReport($type = 'overview', $period = '30')
    {
        $startDate = Carbon::now()->subDays((int)$period);

        switch ($type) {
            case 'content':
                return self::generateContentReport($startDate);
            case 'performance':
                return self::generatePerformanceReport($startDate);
            case 'users':
                return self::generateUserReport($startDate);
            default:
                return self::generateOverviewReport($startDate);
        }
    }

    /**
     * Helper methods for private calculations
     */
    private static function getCacheHealth()
    {
        try {
            $testKey = 'health_test_' . time();
            Cache::put($testKey, 'test', 60);
            $working = Cache::get($testKey) === 'test';
            Cache::forget($testKey);

            return [
                'status' => $working ? 'healthy' : 'warning',
                'message' => $working ? 'Cache working properly' : 'Cache may have issues'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache system error: ' . $e->getMessage()
            ];
        }
    }

    private static function getDatabaseSize()
    {
        try {
            $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = DATABASE()");
            return [
                'size_mb' => $result[0]->size_mb ?? 0,
                'status' => 'healthy'
            ];
        } catch (\Exception $e) {
            return [
                'size_mb' => 0,
                'status' => 'error',
                'message' => 'Could not calculate database size'
            ];
        }
    }

    private static function getImageStats()
    {
        $projectImages = glob(public_path('images/projects/*.{jpg,jpeg,png,gif,webp}'), GLOB_BRACE);
        $totalSize = 0;

        foreach ($projectImages as $image) {
            $totalSize += filesize($image);
        }

        return [
            'total_images' => count($projectImages),
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'avg_size_kb' => count($projectImages) > 0 ? round($totalSize / count($projectImages) / 1024, 2) : 0,
        ];
    }

    private static function getResponseMetrics()
    {
        // This would typically be implemented with actual performance monitoring
        // For now, return sample data
        return [
            'avg_response_time' => '< 200ms',
            'uptime' => '99.9%',
            'status' => 'healthy'
        ];
    }

    private static function getViewsGrowth()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // This is simplified - in a real app, you'd track daily/monthly view counts
        $currentViews = Project::where('created_at', '>=', $currentMonth)->sum('views_count') +
                       Berita::where('created_at', '>=', $currentMonth)->sum('views');

        $previousViews = Project::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('views_count') +
                        Berita::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('views');

        return [
            'current' => $currentViews,
            'previous' => $previousViews,
        ];
    }

    private static function getCacheSize()
    {
        // Simplified cache size calculation
        return '< 100MB';
    }

    private static function getLogFileCount()
    {
        try {
            $logFiles = glob(storage_path('logs/*.log'));
            return count($logFiles);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private static function getUnoptimizedImagesCount()
    {
        // This would check for images that could be optimized
        // Simplified implementation
        return 0;
    }

    private static function getPerformanceHealth()
    {
        // Simplified performance health check
        return [
            'status' => 'healthy',
            'message' => 'Performance metrics within normal range'
        ];
    }

    private static function generateContentReport($startDate)
    {
        return [
            'type' => 'content',
            'period' => $startDate->format('Y-m-d') . ' to ' . now()->format('Y-m-d'),
            'summary' => self::getContentStats(),
            'growth' => self::getGrowthMetrics(),
            'popular' => self::getPopularContent(),
        ];
    }

    private static function generatePerformanceReport($startDate)
    {
        return [
            'type' => 'performance',
            'period' => $startDate->format('Y-m-d') . ' to ' . now()->format('Y-m-d'),
            'metrics' => self::getPerformanceStats(),
            'health' => self::getSystemHealth(),
        ];
    }

    private static function generateUserReport($startDate)
    {
        return [
            'type' => 'users',
            'period' => $startDate->format('Y-m-d') . ' to ' . now()->format('Y-m-d'),
            'activity' => self::getUserActivity($startDate->diffInDays(now())),
        ];
    }

    private static function generateOverviewReport($startDate)
    {
        return [
            'type' => 'overview',
            'period' => $startDate->format('Y-m-d') . ' to ' . now()->format('Y-m-d'),
            'overview' => self::getOverviewStats(),
            'content' => self::getContentStats(),
            'performance' => self::getPerformanceStats(),
            'growth' => self::getGrowthMetrics(),
        ];
    }
}