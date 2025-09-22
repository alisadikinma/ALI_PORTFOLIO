<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Award;
use App\Models\LookupData;
use App\Models\Setting;
use Carbon\Carbon;
use Exception;

class HomeWebController extends Controller
{
    public function index()
    {
        try {
            // Clear cache and get data directly from correct tables
            Cache::forget('homepage_complete_data');
            
            // Get configuration
            $konf = Setting::first();

            // 1. Services from 'layanan' table
            $layanan = DB::table('layanan')
                ->select('id_layanan', 'nama_layanan', 'sub_nama_layanan', 'icon_layanan', 'gambar_layanan', 'keterangan_layanan', 'sequence', 'status')
                ->where('status', 'Active')
                ->orderBy('sequence', 'asc')
                ->get();

            // 2. Projects from 'project' table  
            $projects = DB::table('project')
                ->select('id_project', 'project_name', 'slug_project', 'featured_image', 'summary_description', 'client_name', 'location', 'project_category', 'created_at', 'sequence')
                ->where('status', 'Active')
                ->orderBy('sequence', 'asc')
                ->orderBy('created_at', 'desc')
                ->limit(9)
                ->get();

            // 3. Gallery (Achievements) from 'galeri' table
            $galeri = DB::table('galeri')
                ->select('id_galeri', 'nama_galeri', 'company', 'period', 'deskripsi_galeri', 'thumbnail', 'sequence', 'status')
                ->where('status', 'Active')
                ->orderBy('sequence', 'asc')
                ->limit(12)
                ->get();

            // Optional data (with fallbacks)
            try {
                $testimonial = DB::table('testimonial')
                    ->select('id_testimonial', 'client_name', 'judul_testimonial', 'gambar_testimonial', 'deskripsi_testimonial', 'jabatan', 'company_name', 'rating')
                    ->where('status', 'Active')
                    ->orderBy('display_order', 'asc')
                    ->limit(6)
                    ->get();
            } catch (Exception $e) {
                $testimonial = collect();
            }

            try {
                $award = DB::table('award')
                    ->select('id_award', 'nama_award', 'company', 'period', 'gambar_award', 'keterangan_award', 'award_level', 'sequence')
                    ->where('status', 'Active')
                    ->orderBy('sequence', 'asc')
                    ->limit(6)
                    ->get();
            } catch (Exception $e) {
                $award = collect();
            }

            try {
                $article = DB::table('berita')
                    ->select('id_berita', 'judul_berita', 'slug_berita', 'gambar_berita', 'isi_berita', 'tanggal_berita', 'kategori_berita')
                    ->orderBy('tanggal_berita', 'desc')
                    ->limit(4)
                    ->get();
            } catch (Exception $e) {
                $article = collect();
            }

            // Enhanced data using LookupData configurations
            $projectCategories = LookupData::getProjectCategories();
            
            // Get homepage sections configuration from lookup_data table
            $homepageSectionsData = LookupData::getHomepageSections();
            $homepageSections = $homepageSectionsData->pluck('lookup_code')->toArray();
            
            // Create section configurations array from database
            $sectionConfigs = [];
            foreach ($homepageSectionsData as $section) {
                $sectionConfigs[$section->lookup_code] = [
                    'title' => $section->lookup_name,
                    'description' => $section->lookup_description,
                    'icon' => $section->lookup_icon,
                    'color' => $section->lookup_color,
                    'metadata' => $section->lookup_metadata,
                    'is_active' => $section->is_active,
                    'sort_order' => $section->sort_order
                ];
            }
            
            // Fallback if no sections in database
            if (empty($sectionConfigs)) {
                $homepageSections = ['about', 'services', 'portfolio', 'awards', 'testimonials', 'gallery', 'articles', 'contact'];
                $sectionConfigs = [
                    'about' => ['title' => 'About', 'description' => 'About me, mission, vision content', 'is_active' => true, 'sort_order' => 1],
                    'services' => ['title' => 'Services', 'description' => 'Services & Offering', 'is_active' => true, 'sort_order' => 2], 
                    'portfolio' => ['title' => 'Portfolio', 'description' => 'Project showcase', 'is_active' => true, 'sort_order' => 3],
                    'awards' => ['title' => 'Awards', 'description' => 'Achievements and Recognitions', 'is_active' => true, 'sort_order' => 4],
                    'testimonials' => ['title' => 'Testimonials', 'description' => 'Client reviews', 'is_active' => true, 'sort_order' => 5],
                    'gallery' => ['title' => 'Gallery', 'description' => 'Achievements & Recognitions', 'is_active' => true, 'sort_order' => 6],
                    'articles' => ['title' => 'Articles', 'description' => 'Blog posts', 'is_active' => true, 'sort_order' => 7],
                    'contact' => ['title' => 'Contact', 'description' => 'Contact form', 'is_active' => true, 'sort_order' => 8]
                ];
            }

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

        } catch (Exception $e) {
            Log::error('Homepage loading error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->renderFallbackHomepage();
        }
    }

    /**
     * Render fallback homepage when main loading fails
     */
    private function renderFallbackHomepage()
    {
        $konf = Setting::first() ?: (object)[
            'instansi_setting' => 'ALI PORTFOLIO',
            'pimpinan_setting' => 'Ali Sadikin'
        ];
        
        $homepageSections = ['about', 'contact'];
        $sectionConfigs = [
            'about' => ['title' => 'About', 'is_active' => true],
            'contact' => ['title' => 'Contact', 'is_active' => true]
        ];

        // Empty collections for fallback
        $projects = collect();
        $testimonial = collect();
        $galeri = collect();
        $article = collect();
        $award = collect();
        $layanan = collect();
        $projectCategories = collect();

        return view('welcome', compact(
            'konf', 'layanan', 'testimonial', 'galeri',
            'article', 'award', 'projects', 'projectCategories',
            'homepageSections', 'sectionConfigs'
        ))->with('fallback_mode', true);
    }

    public function portfolio()
    {
        $konf = Setting::first();
        
        $projects = DB::table('project')
            ->select('id_project', 'project_name', 'slug_project', 'featured_image', 'summary_description', 
                   'client_name', 'location', 'project_category', 'created_at', 'sequence', 'description')
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $projectCategories = collect(['Web Development', 'Digital Transformation', 'Manufacturing AI', 'Technology Consulting']);
        
        return view('portfolio_all', compact('konf', 'projectCategories', 'projects'));
    }

    public function gallery()
    {
        $konf = Setting::first();
        
        $galeri = DB::table('galeri')
            ->select('id_galeri', 'nama_galeri', 'company', 'period', 'deskripsi_galeri', 'thumbnail', 'sequence')
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
        
        return view('gallery', compact('konf', 'galeri'));
    }

    public function portfolioDetail($slug)
    {
        try {
            // Get project by slug
            $portfolio = DB::table('project')
                ->select('*')
                ->where('slug_project', $slug)
                ->where('status', 'Active')
                ->first();

            if (!$portfolio) {
                abort(404, 'Project not found or inactive');
            }

            $konf = Setting::first();

            // Get related projects from same category
            $related_projects = DB::table('project')
                ->select('id_project', 'project_name', 'slug_project', 'featured_image', 'summary_description', 'client_name')
                ->where('project_category', $portfolio->project_category)
                ->where('id_project', '!=', $portfolio->id_project)
                ->where('status', 'Active')
                ->limit(3)
                ->get();

            return view('portfolio_detail', compact('konf', 'portfolio', 'related_projects'));

        } catch (Exception $e) {
            Log::error('Portfolio detail error', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'Project not found');
        }
    }

    public function articleDetail($slug)
    {
        try {
            $konf = Setting::first();
            
            // Get article by slug
            $article = DB::table('berita')
                ->select('*')
                ->where('slug_berita', $slug)
                ->first();
            
            if (!$article) {
                abort(404, 'Article not found');
            }
            
            // Get recent articles from same category
            $recent_articles = DB::table('berita')
                ->select('judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita')
                ->where('kategori_berita', $article->kategori_berita)
                ->where('id_berita', '!=', $article->id_berita)
                ->orderBy('tanggal_berita', 'desc')
                ->limit(4)
                ->get();
            
            return view('article_detail', compact('konf', 'article', 'recent_articles'));
            
        } catch (Exception $e) {
            Log::error('Article detail error: ' . $e->getMessage());
            abort(404, 'Article not found');
        }
    }

    public function articles(Request $request)
    {
        $konf = Setting::first();
        
        // Get all articles with search and filter
        $query = DB::table('berita')->select('*');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_berita', 'LIKE', "%{$search}%")
                  ->orWhere('isi_berita', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('kategori_berita', $request->category);
        }
        
        // Sort
        $query->orderBy('tanggal_berita', 'desc');
        
        // Paginate
        $articles = $query->paginate(12)->withQueryString();
        
        // Get categories for filter
        $categories = DB::table('berita')
            ->select('kategori_berita')
            ->distinct()
            ->whereNotNull('kategori_berita')
            ->where('kategori_berita', '!=', '')
            ->orderBy('kategori_berita')
            ->pluck('kategori_berita');
        
        return view('articles', compact('konf', 'articles', 'categories'));
    }
}
