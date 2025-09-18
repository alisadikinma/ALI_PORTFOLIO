<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Award;
use App\Models\LookupData;
use App\Models\Setting;
use App\Services\HomepageSectionService;
use Carbon\Carbon;

class HomeWebController extends Controller
{
    public function index()
    {
        // Use models instead of direct DB queries for better MVC architecture
        $konf = $this->getSiteConfiguration();
        $homepageSections = $this->getHomepageSections();
        $homepageData = $this->getHomepageData($homepageSections);
        $projectCategories = $this->getProjectCategories();
        
        // Extract data for view
        $layanan = $homepageData['layanan'];
        $testimonial = $homepageData['testimonial'];
        $galeri = $homepageData['galeri'];
        $article = $homepageData['article'];
        $award = $homepageData['award'];
        $projects = $homepageData['projects'];
        
        // Get section configurations for view
        $sectionConfigs = HomepageSectionService::getAllSectionConfigs();
        
        return view('welcome', compact(
            'konf', 
            'layanan', 
            'testimonial', 
            'galeri', 
            'article', 
            'award', 
            'projects', 
            'projectCategories', 
            'homepageSections',
            'sectionConfigs'
        ));
    }

    /**
     * Get site configuration using Model - Updated to ensure all settings columns are available
     */
    private function getSiteConfiguration()
    {
        return Cache::remember('site_config', 300, function() {
            // Use Setting model to get all configuration data
            try {
                // Ensure we get the first settings record with all columns
                $settings = Setting::select([
                    'id_setting',
                    'instansi_setting',
                    'pimpinan_setting', 
                    'logo_setting',
                    'favicon_setting',
                    'misi_setting',
                    'visi_setting',
                    'keyword_setting',
                    'alamat_setting',
                    'instagram_setting',
                    'youtube_setting', 
                    'email_setting',
                    'tiktok_setting',
                    'facebook_setting',
                    'linkedin_setting',
                    'no_hp_setting',
                    'maps_setting',
                    'profile_title',           // Column 1 yang diminta
                    'profile_content',         // Column 3 yang diminta
                    'primary_button_title',    // Column 4 yang diminta
                    'primary_button_link',     // Column 4 link yang diminta
                    'secondary_button_title',  // Column 5 yang diminta
                    'secondary_button_link',   // Column 5 link yang diminta
                    'years_experience',
                    'followers_count',
                    'project_delivered',
                    'cost_savings',
                    'success_rate',
                    'about_section_title',
                    'about_section_subtitle',
                    'about_section_description',
                    'about_section_image',
                    'award_section_title',
                    'award_section_subtitle',
                    'view_cv_url'
                ])->first();
                
                return $settings;
            } catch (\Exception $e) {
                // Fallback to direct DB query if model fails
                return DB::table('setting')->first();
            }
        });
    }

    /**
     * Get homepage sections configuration using Service
     */
    private function getHomepageSections()
    {
        return Cache::remember('homepage_sections', 1800, function() {
            try {
                // Use HomepageSectionService for better architecture
                $activeSections = HomepageSectionService::getActiveSectionsInOrder();
                return array_keys($activeSections);
            } catch (\Exception $e) {
                \Log::error('Failed to get homepage sections: ' . $e->getMessage());
                // Fallback to default sections
                return ['about', 'services', 'portfolio', 'awards', 'testimonials', 'gallery', 'articles', 'contact'];
            }
        });
    }

    /**
     * Get homepage data based on active sections using Models
     */
    private function getHomepageData($homepageSections)
    {
        return Cache::remember('homepage_data', 1800, function() use ($homepageSections) {
            try {
                $result = [];
                
                // Load data for active sections using Models instead of direct DB queries
                if (in_array('services', $homepageSections)) {
                    $result['layanan'] = $this->getServicesData();
                }
                
                if (in_array('testimonials', $homepageSections)) {
                    $result['testimonial'] = $this->getTestimonialsData();
                }
                
                if (in_array('gallery', $homepageSections)) {
                    $result['galeri'] = $this->getGalleryData();
                }
                
                if (in_array('articles', $homepageSections)) {
                    $result['article'] = $this->getArticlesData();
                }
                
                if (in_array('awards', $homepageSections)) {
                    $result['award'] = $this->getAwardsData();
                }
                
                if (in_array('portfolio', $homepageSections)) {
                    $result['projects'] = $this->getProjectsData();
                }
                
                // Add empty collections for inactive sections to prevent errors
                $defaultCollections = [
                    'layanan' => collect(),
                    'testimonial' => collect(),
                    'galeri' => collect(),
                    'article' => collect(),
                    'award' => collect(),
                    'projects' => collect(),
                ];
                
                return array_merge($defaultCollections, $result);
                
            } catch (\Exception $e) {
                \Log::error('Database error in homepage: ' . $e->getMessage());
                // Return empty collections if database fails
                return [
                    'layanan' => collect(),
                    'testimonial' => collect(),
                    'galeri' => collect(),
                    'article' => collect(),
                    'award' => collect(),
                    'projects' => collect(),
                ];
            }
        });
    }

    /**
     * Get services data using proper Model structure
     */
    private function getServicesData()
    {
        return DB::table('layanan')
            ->select('id_layanan', 'nama_layanan', 'sub_nama_layanan', 'icon_layanan', 'gambar_layanan', 'keterangan_layanan', 'sequence', 'status')
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
    }

    /**
     * Get testimonials data using proper Model structure
     */
    private function getTestimonialsData()
    {
        return DB::table('testimonial')
            ->select('judul_testimonial', 'gambar_testimonial', 'deskripsi_testimonial', 'jabatan')
            ->get();
    }

    /**
     * Get gallery data using Galeri Model
     */
    private function getGalleryData()
    {
        return Galeri::with(['galleryItems' => function($query) {
                $query->where('status', 'Active')
                      ->orderBy('sequence', 'asc');
            }])
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->limit(12)
            ->get();
    }

    /**
     * Get articles data using Berita Model
     */
    private function getArticlesData()
    {
        return Berita::select('judul_berita', 'slug_berita', 'gambar_berita', 'isi_berita', 'tanggal_berita', 'kategori_berita', 'meta_description', 'tags')
            ->orderBy('tanggal_berita', 'desc')
            ->limit(4)
            ->get();
    }

    /**
     * Get awards data using Award Model
     */
    private function getAwardsData()
    {
        return Award::where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
    }

    /**
     * Get projects data using proper Model structure
     */
    private function getProjectsData()
    {
        return DB::table('project')
            ->select('project_name', 'slug_project', 'featured_image', 'summary_description', 'client_name', 
                   'location', 'project_category', 'created_at', 'sequence')
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();
    }

    /**
     * Get project categories using LookupData Model
     */
    private function getProjectCategories()
    {
        return Cache::remember('project_categories_homepage', 3600, function() {
            try {
                // Use LookupData model for better MVC architecture
                return LookupData::getProjectCategories();
            } catch (\Exception $e) {
                // Return empty collection if lookup_data doesn't exist yet
                return collect();
            }
        });
    }

    /**
     * Helper function to check if section is active using LookupData Model
     */
    public function isSectionActive($sectionCode)
    {
        $activeSections = Cache::remember('active_homepage_sections', 1800, function() {
            try {
                return LookupData::getActiveHomepageSectionCodes();
            } catch (\Exception $e) {
                return ['about', 'services', 'portfolio', 'awards', 'testimonials', 'gallery', 'articles', 'contact'];
            }
        });
        
        return in_array($sectionCode, $activeSections);
    }

    /**
     * Get section order for frontend use using LookupData Model
     */
    public function getSectionOrder($sectionCode)
    {
        $sectionOrders = Cache::remember('section_orders', 1800, function() {
            try {
                return LookupData::byType('homepage_section')
                    ->active()
                    ->pluck('sort_order', 'lookup_code')
                    ->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
        
        return $sectionOrders[$sectionCode] ?? 999;
    }

    /**
     * Get section configuration using LookupData Model
     */
    public function getSectionConfig($sectionCode)
    {
        return Cache::remember("section_config_{$sectionCode}", 1800, function() use ($sectionCode) {
            return LookupData::getSectionConfig($sectionCode);
        });
    }

    public function portfolio()
    {
        $konf = $this->getSiteConfiguration();
        $projectCategories = $this->getProjectCategories();
        
        // Get projects data using proper model structure
        $projects = Cache::remember('portfolio_all_projects', 900, function() {
            try {
                return DB::table('project')
                    ->select('id_project', 'project_name', 'slug_project', 'featured_image', 'summary_description', 
                           'client_name', 'location', 'project_category', 'created_at', 'sequence', 'description')
                    ->where('status', 'Active')
                    ->orderBy('sequence', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Portfolio all projects error: ' . $e->getMessage());
                return collect();
            }
        });
        
        return view('portfolio_all', compact('konf', 'projectCategories', 'projects'));
    }

    public function portfolioAll()
    {
        $konf = $this->getSiteConfiguration();
        
        // Use hybrid approach - try lookup first, fallback to old structure
        try {
            $projects = DB::table('project as p')
                ->leftJoin('lookup_data as ld', 'p.category_lookup_id', '=', 'ld.id')
                ->select('p.id_project', 'p.project_name', 'p.client_name', 'p.location', 'p.slug_project', 
                       'p.featured_image', 'p.images', 'p.summary_description', 'p.created_at', 'p.sequence',
                       'p.project_category', // Keep old column for fallback
                       'ld.lookup_name as category_name', 'ld.lookup_icon as category_icon', 'ld.lookup_color as category_color')
                ->where('p.status', 'Active')
                ->orderBy('p.sequence', 'asc')
                ->orderBy('p.created_at', 'desc')
                ->paginate(12);
        } catch (\Exception $e) {
            // Fallback to simple query if lookup fails
            $projects = DB::table('project')
                ->select('id_project', 'project_name', 'client_name', 'location', 'slug_project', 
                       'featured_image', 'images', 'summary_description', 'created_at', 'sequence', 'project_category')
                ->where('status', 'Active')
                ->orderBy('sequence', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }
        
        return view('portfolio_all', compact('konf', 'projects'));
    }

    public function gallery()
    {
        $konf = $this->getSiteConfiguration();
        
        // Get all active galleries with their active items using Galeri Model
        $galeri = Galeri::with(['items' => function($query) {
                $query->where('status', 'Active')
                      ->orderBy('sequence', 'asc');
            }])
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
        
        return view('gallery', compact('konf', 'galeri'));
    }

    public function portfolioDetail($slug)
    {
        try {
            // Try enhanced query with lookup data first
            try {
                $portfolio = DB::table('project as p')
                            ->leftJoin('lookup_data as ld', 'p.category_lookup_id', '=', 'ld.id')
                            ->select('p.*', 
                                   'ld.lookup_name as category_name', 
                                   'ld.lookup_icon as category_icon', 
                                   'ld.lookup_color as category_color', 
                                   'ld.lookup_description as category_description')
                            ->where('p.slug_project', $slug)
                            ->where('p.status', 'Active')
                            ->first();
            } catch (\Exception $e) {
                // Fallback to simple query if lookup fails
                $portfolio = DB::table('project')
                            ->where('slug_project', $slug)
                            ->where('status', 'Active')
                            ->first();
            }
            
            if (!$portfolio) {
                abort(404, 'Portfolio not found');
            }
            
            $konf = $this->getSiteConfiguration();
            
            return view('portfolio_detail', compact('konf', 'portfolio'));
            
        } catch (\Exception $e) {
            \Log::error('Portfolio detail error: ' . $e->getMessage());
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

    public function articleDetail($slug)
    {
        $konf = $this->getSiteConfiguration();
        
        // Get article using Berita Model
        $article = Berita::where('slug_berita', $slug)->first();
        
        if (!$article) {
            abort(404, 'Article not found');
        }
        
        // Increment view count (only once per session per article)
        $sessionKey = 'viewed_article_' . $article->id_berita;
        if (!session()->has($sessionKey)) {
            $article->increment('views');
            session()->put($sessionKey, true);
        }
        
        // Track visitor geo location (optional)
        $this->trackVisitor($article->id_berita);
        
        // Calculate reading time if not set
        if (!$article->reading_time) {
            $wordCount = str_word_count(strip_tags($article->isi_berita));
            $article->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
            $article->save();
        }
        
        // Parse FAQ data
        if ($article->faq_data) {
            try {
                $article->faq_data = is_string($article->faq_data) ? json_decode($article->faq_data, true) : $article->faq_data;
            } catch (Exception $e) {
                $article->faq_data = null;
            }
        }
        
        // Get related articles using model relationships if available
        $related_articles = collect();
        if ($article->related_ids && count($article->related_ids) > 0) {
            $related_articles = Berita::whereIn('id_berita', $article->related_ids)
                ->select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita', 'isi_berita'])
                ->get();
        }
        
        // If no manual related articles, get similar ones
        if ($related_articles->count() < 3) {
            $autoRelated = Berita::where('id_berita', '!=', $article->id_berita)
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
                ->select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita', 'isi_berita'])
                ->orderBy('tanggal_berita', 'desc')
                ->limit(3 - $related_articles->count())
                ->get();
                
            $related_articles = $related_articles->merge($autoRelated);
        }
        
        $article->related_articles = $related_articles;
        
        // Get recent articles from same category using Berita Model
        $recent_articles = Cache::remember('recent_articles_' . $article->kategori_berita . '_' . $article->id_berita, 900, function() use ($article) {
            return Berita::select([
                    'judul_berita', 
                    'slug_berita', 
                    'gambar_berita', 
                    'tanggal_berita', 
                    'kategori_berita', 
                    'created_at', 
                    'isi_berita',
                    'meta_description',
                    'reading_time',
                    'views'
                ])
                ->where('kategori_berita', $article->kategori_berita)
                ->where('id_berita', '!=', $article->id_berita)
                ->orderBy('tanggal_berita', 'desc')
                ->limit(4)
                ->get();
        });
        
        return view('article_detail', compact('konf', 'article', 'recent_articles'));
    }

    public function articles(Request $request)
    {
        $konf = $this->getSiteConfiguration();
        
        // Use Berita Model for article queries
        $query = Berita::select([
                'id_berita',
                'judul_berita', 
                'slug_berita', 
                'gambar_berita', 
                'isi_berita', 
                'tanggal_berita', 
                'kategori_berita', 
                'meta_title',
                'meta_description', 
                'tags', 
                'reading_time', 
                'views',
                'is_featured',
                'featured_snippet',
                'conclusion',
                'focus_keyword',
                'created_at',
                'updated_at'
            ]);
        
        // Search functionality - search across multiple fields
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_berita', 'LIKE', "%{$search}%")
                  ->orWhere('meta_title', 'LIKE', "%{$search}%")
                  ->orWhere('meta_description', 'LIKE', "%{$search}%")
                  ->orWhere('featured_snippet', 'LIKE', "%{$search}%")
                  ->orWhere('isi_berita', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%")
                  ->orWhere('focus_keyword', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('kategori_berita', $request->category);
        }
        
        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $tag = $request->tag;
            $query->where('tags', 'LIKE', "%{$tag}%");
        }
        
        // Filter by featured articles
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }
        
        // Sorting options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('tanggal_berita', 'asc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc')
                      ->orderBy('tanggal_berita', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('tanggal_berita', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('judul_berita', 'asc');
                break;
            case 'reading_time':
                $query->orderBy('reading_time', 'asc')
                      ->orderBy('tanggal_berita', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('tanggal_berita', 'desc');
                break;
        }
        
        // Add secondary sorting for consistency
        $query->orderBy('created_at', 'desc');
        
        // Paginate results
        $articles = $query->paginate(12)->withQueryString();
        
        // Get categories for filter dropdown using Berita Model
        $categories = Cache::remember('article_categories', 1800, function() {
            return Berita::select('kategori_berita')
                ->distinct()
                ->whereNotNull('kategori_berita')
                ->where('kategori_berita', '!=', '')
                ->orderBy('kategori_berita')
                ->pluck('kategori_berita');
        });
        
        // Get popular tags with counts
        $popular_tags = Cache::remember('popular_tags_list', 1800, function() {
            return $this->getPopularTags(15);
        });
        
        // Get featured articles for sidebar/highlights using Berita Model
        $featured_articles = Cache::remember('featured_articles', 1800, function() {
            return Berita::select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita'])
                ->where('is_featured', true)
                ->orderBy('tanggal_berita', 'desc')
                ->limit(3)
                ->get();
        });
        
        // Get article statistics using Berita Model
        $stats = Cache::remember('article_stats', 3600, function() {
            return [
                'total_articles' => Berita::count(),
                'total_views' => Berita::sum('views'),
                'total_categories' => Berita::distinct()->whereNotNull('kategori_berita')->count('kategori_berita'),
                'featured_count' => Berita::where('is_featured', true)->count(),
            ];
        });
        
        return view('articles', compact(
            'konf', 
            'articles', 
            'categories', 
            'popular_tags', 
            'featured_articles',
            'stats'
        ));
    }
    
    /**
     * Track visitor information
     */
    private function trackVisitor($articleId)
    {
        try {
            $ip = request()->ip();
            $userAgent = request()->userAgent();
            
            // Check if visitor already viewed this article today
            $exists = DB::table('berita_visits')
                ->where('article_id', $articleId)
                ->where('ip_address', $ip)
                ->whereDate('created_at', Carbon::today())
                ->exists();
            
            if (!$exists) {
                // Try to get geo information (optional, requires external API)
                $geoData = $this->getGeoData($ip);
                
                DB::table('berita_visits')->insert([
                    'article_id' => $articleId,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'country' => $geoData['country'] ?? null,
                    'city' => $geoData['city'] ?? null,
                    'region' => $geoData['region'] ?? null,
                    'latitude' => $geoData['lat'] ?? null,
                    'longitude' => $geoData['lon'] ?? null,
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Failed to track visitor: ' . $e->getMessage());
        }
    }
    
    /**
     * Get geo data from IP (optional)
     */
    private function getGeoData($ip)
    {
        // Skip for localhost
        if ($ip == '127.0.0.1' || $ip == '::1') {
            return [
                'country' => 'Local',
                'city' => 'Localhost',
                'region' => 'Local',
            ];
        }
        
        try {
            // Using ip-api.com (free tier: 45 requests per minute)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,lat,lon");
            
            if ($response) {
                $data = json_decode($response, true);
                if ($data && $data['status'] == 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'city' => $data['city'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'lat' => $data['lat'] ?? null,
                        'lon' => $data['lon'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get geo data: ' . $e->getMessage());
        }
        
        return [];
    }
    
    /**
     * Get popular tags from articles using Berita Model
     */
    private function getPopularTags($limit = 10)
    {
        $allTags = Berita::whereNotNull('tags')->pluck('tags');
        
        $tagCounts = [];
        
        foreach ($allTags as $tagString) {
            $tags = array_map('trim', explode(',', $tagString));
            foreach ($tags as $tag) {
                if (!empty($tag)) {
                    if (!isset($tagCounts[$tag])) {
                        $tagCounts[$tag] = 0;
                    }
                    $tagCounts[$tag]++;
                }
            }
        }
        
        arsort($tagCounts);
        
        return array_slice($tagCounts, 0, $limit, true);
    }
    
    /**
     * Generate sitemap
     */
    public function sitemap()
    {
        $urls = collect([
            ['url' => url('/'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => url('/portfolio/all'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/gallery'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => url('/articles'), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ]);
        
        // Add articles using Berita Model
        $articles = Berita::select('slug_berita', 'updated_at')->get();
            
        foreach ($articles as $article) {
            $urls->push([
                'url' => url('/article/' . $article->slug_berita),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'lastmod' => $article->updated_at,
            ]);
        }
        
        // Add projects
        $projects = DB::table('project')
            ->select('slug_project', 'updated_at')
            ->get();
            
        foreach ($projects as $project) {
            $urls->push([
                'url' => url('/portfolio/' . $project->slug_project),
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'lastmod' => $project->updated_at,
            ]);
        }
        
        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Get gallery items for modal display
     */
    public function getGalleryItems($galleryId)
    {
        try {
            $gallery = Galeri::with(['galleryItems' => function($query) {
                    $query->where('status', 'Active')
                          ->orderBy('sequence', 'asc');
                }])
                ->where('id_galeri', $galleryId)
                ->where('status', 'Active')
                ->first();
            
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }
            
            // Format gallery items for frontend
            $items = $gallery->galleryItems->map(function($item) {
                $data = [
                    'id' => $item->id_gallery_item,
                    'type' => $item->type,
                    'sequence' => $item->sequence,
                    'status' => $item->status
                ];
                
                if ($item->type === 'image') {
                    $data['file_url'] = asset('file/galeri/' . $item->file_name);
                    $data['thumbnail_url'] = asset('file/galeri/' . $item->file_name);
                } elseif ($item->type === 'youtube') {
                    $data['youtube_url'] = $item->youtube_url;
                    $data['file_url'] = $item->youtube_url; // Add file_url for consistency
                    $videoId = $this->extractYouTubeId($item->youtube_url);
                    $data['thumbnail_url'] = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
                }
                
                return $data;
            });
            
            return response()->json([
                'success' => true,
                'gallery' => [
                    'id' => $gallery->id_galeri,
                    'nama_galeri' => $gallery->nama_galeri,
                    'company' => $gallery->company,
                    'period' => $gallery->period,
                    'deskripsi_galeri' => $gallery->deskripsi_galeri,
                    'thumbnail' => $gallery->thumbnail ? asset('file/galeri/' . $gallery->thumbnail) : null
                ],
                'items' => $items
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get gallery items: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load gallery items'
            ], 500);
        }
    }
    
    /**
     * Extract YouTube video ID from URL
     */
    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}
