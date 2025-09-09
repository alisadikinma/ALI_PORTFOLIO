<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Award;
use Carbon\Carbon;

class HomeWebController extends Controller
{
    public function index()
    {
        // Clear cache first to ensure we get fresh data
        Cache::forget('homepage_data');
        Cache::forget('site_config');
        
        // Cache site configuration for 5 minutes (300 seconds)
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        // Cache homepage data for 30 minutes
        $data = Cache::remember('homepage_data', 1800, function() {
            return [
                'layanan' => DB::table('layanan')
                    ->select('id_layanan', 'nama_layanan', 'sub_nama_layanan', 'icon_layanan', 'gambar_layanan', 'keterangan_layanan', 'sequence', 'status')
                    ->where('status', 'Active')
                    ->orderBy('sequence', 'asc')
                    ->get(),
                'testimonial' => DB::table('testimonial')->select('judul_testimonial', 'gambar_testimonial', 'deskripsi_testimonial', 'jabatan')->get(),
                'galeri' => Galeri::with(['activeGalleryItems' => function($query) {
                        $query->orderBy('sequence', 'asc');
                    }])
                    ->where('status', 'Active')
                    ->orderBy('sequence', 'asc')
                    ->limit(12)
                    ->get(),
                'article' => DB::table('berita')->select('judul_berita', 'slug_berita', 'gambar_berita', 'isi_berita', 'tanggal_berita', 'kategori_berita', 'meta_description', 'tags')->orderBy('tanggal_berita', 'desc')->limit(4)->get(),
                'award' => Award::where('status', 'Active')
                    ->orderBy('sequence', 'asc')
                    ->get(),
                'projects' => DB::table('project')->select('nama_project', 'slug_project', 'gambar_project', 'keterangan_project', 'jenis_project')->orderBy('created_at', 'desc')->limit(9)->get(),
            ];
        });
        
        // Cache project types
        $jenis_projects = Cache::remember('project_types', 3600, function() {
            return DB::table('project')->distinct()->pluck('jenis_project')->filter()->values()->toArray();
        });
        
        // Extract cached data
        $layanan = $data['layanan'];
        $testimonial = $data['testimonial'];
        $galeri = $data['galeri'];
        $article = $data['article'];
        $award = $data['award'];
        $projects = $data['projects'];
        
        return view('welcome', compact('konf', 'layanan', 'testimonial', 'galeri', 'article', 'award', 'projects', 'jenis_projects'));
    }

    public function portfolio()
    {
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        $projects = Cache::remember('all_projects', 1800, function() {
            return DB::table('project')->select('nama_project', 'slug_project', 'gambar_project', 'keterangan_project', 'jenis_project')->get();
        });
        
        $jenis_projects = Cache::remember('project_types', 3600, function() {
            return DB::table('project')->distinct()->pluck('jenis_project')->filter()->values()->toArray();
        });
        
        return view('portfolio', compact('konf', 'projects','jenis_projects'));
    }

    public function gallery()
    {
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        // Get all active galleries with their active items
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
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        $portfolio = Cache::remember("portfolio_{$slug}", 1800, function() use ($slug) {
            return DB::table('project')->where('slug_project', $slug)->first();
        });
        
        if (!$portfolio) {
            abort(404, 'Portfolio not found');
        }
        
        return view('portfolio_detail', compact('konf', 'portfolio'));
    }

    public function articleDetail($slug)
    {
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        // Get article with ALL fields including SEO data
        $article = DB::table('berita')
            ->select([
                'id_berita',
                'judul_berita',
                'kategori_berita', 
                'isi_berita',
                'gambar_berita',
                'tanggal_berita',
                'slug_berita',
                'created_at',
                'updated_at',
                // SEO Fields
                'meta_title',
                'meta_description',
                'tags',
                'focus_keyword',
                // Content Enhancement
                'featured_snippet',
                'conclusion', 
                'faq_data',
                // Metadata
                'reading_time',
                'is_featured',
                'views',
                'related_ids'
            ])
            ->where('slug_berita', $slug)
            ->first();
        
        if (!$article) {
            abort(404, 'Article not found');
        }
        
        // Convert article to model instance if needed for relationships
        $articleModel = Berita::find($article->id_berita);
        
        // Increment view count (only once per session per article)
        $sessionKey = 'viewed_article_' . $article->id_berita;
        if (!session()->has($sessionKey)) {
            DB::table('berita')
                ->where('id_berita', $article->id_berita)
                ->increment('views');
            session()->put($sessionKey, true);
        }
        
        // Track visitor geo location (optional)
        $this->trackVisitor($article->id_berita);
        
        // Calculate reading time if not set
        if (!$article->reading_time) {
            $wordCount = str_word_count(strip_tags($article->isi_berita));
            $article->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
        }
        
        // Parse FAQ data
        if ($article->faq_data) {
            try {
                $article->faq_data = is_string($article->faq_data) ? json_decode($article->faq_data, true) : $article->faq_data;
            } catch (Exception $e) {
                $article->faq_data = null;
            }
        }
        
        // Get related articles
        $related_articles = collect();
        if ($articleModel && $articleModel->related_ids && count($articleModel->related_ids) > 0) {
            $related_articles = DB::table('berita')
                ->whereIn('id_berita', $articleModel->related_ids)
                ->select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita', 'isi_berita'])
                ->get();
        }
        
        // If no manual related articles, get similar ones
        if ($related_articles->count() < 3) {
            $autoRelated = DB::table('berita')
                ->where('id_berita', '!=', $article->id_berita)
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
        
        // Get recent articles from same category
        $recent_articles = Cache::remember('recent_articles_' . $article->kategori_berita . '_' . $article->id_berita, 900, function() use ($article) {
            return DB::table('berita')
                ->select([
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
        $konf = Cache::remember('site_config', 300, function() {
            return DB::table('setting')->first();
        });
        
        $query = DB::table('berita')
            ->select([
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
        
        // Get categories for filter dropdown
        $categories = Cache::remember('article_categories', 1800, function() {
            return DB::table('berita')
                ->select('kategori_berita')
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
        
        // Get featured articles for sidebar/highlights
        $featured_articles = Cache::remember('featured_articles', 1800, function() {
            return DB::table('berita')
                ->select(['judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita'])
                ->where('is_featured', true)
                ->orderBy('tanggal_berita', 'desc')
                ->limit(3)
                ->get();
        });
        
        // Get article statistics
        $stats = Cache::remember('article_stats', 3600, function() {
            return [
                'total_articles' => DB::table('berita')->count(),
                'total_views' => DB::table('berita')->sum('views'),
                'total_categories' => DB::table('berita')->distinct()->whereNotNull('kategori_berita')->count('kategori_berita'),
                'featured_count' => DB::table('berita')->where('is_featured', true)->count(),
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
     * Get popular tags from articles
     */
    private function getPopularTags($limit = 10)
    {
        $allTags = DB::table('berita')
            ->whereNotNull('tags')
            ->pluck('tags');
        
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
            ['url' => url('/portfolio'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['url' => url('/gallery'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => url('/articles'), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ]);
        
        // Add articles
        $articles = DB::table('berita')
            ->select('slug_berita', 'updated_at')
            ->get();
            
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