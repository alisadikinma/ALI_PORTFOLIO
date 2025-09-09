<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class BeritaController extends Controller
{
    protected $seoService;

    public function __construct()
    {
        // SeoService akan di-inject secara otomatis atau manual
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Data Berita";
        $berita = Berita::orderBy('created_at', 'desc')->get();

        return view('berita.index', compact('berita', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Article';
        $allBerita = Berita::select('id_berita', 'judul_berita')->orderBy('judul_berita')->get();

        return view('berita.create', compact('title', 'allBerita'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_berita'   => 'required|max:100',
            'isi_berita'     => 'required',
            'kategori_berita' => 'required',
            'gambar_berita'  => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'meta_title'     => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'featured_snippet' => 'nullable|max:300',
            'conclusion'     => 'nullable|max:500',
            'focus_keyword'  => 'nullable|max:100',
            'tags'           => 'nullable|max:255',
            'faq_data'       => 'nullable',
        ]);

        // Handle image upload
        $gambar = $request->file('gambar_berita');
        $namaGambar = 'Berita' . date('Ymdhis') . '.' . $gambar->getClientOriginalExtension();
        $gambar->move('file/berita/', $namaGambar);

        // Auto-generate reading time
        $readingTime = $this->calculateReadingTime($request->isi_berita);

        // Auto-generate meta fields if not provided
        $metaTitle = $request->meta_title ?: $request->judul_berita;
        $metaDescription = $request->meta_description ?: 
            ($request->featured_snippet ?: Str::limit(strip_tags($request->isi_berita), 160));

        // Auto-generate tags if not provided
        $tags = $request->tags ?: $this->generateTags($request->isi_berita);

        $berita = new Berita();
        $berita->judul_berita     = $request->judul_berita;
        $berita->meta_title       = $metaTitle;
        $berita->meta_description = $metaDescription;
        $berita->tags             = $tags;
        $berita->reading_time     = $readingTime;
        $berita->featured_snippet = $request->featured_snippet;
        $berita->conclusion       = $request->conclusion;
        $berita->focus_keyword    = $request->focus_keyword;
        $berita->faq_data         = $request->faq_data ? json_decode($request->faq_data, true) : null;
        $berita->is_featured      = $request->has('is_featured');
        $berita->isi_berita       = $request->isi_berita;
        $berita->kategori_berita  = $request->kategori_berita;
        $berita->gambar_berita    = $namaGambar;
        $berita->slug_berita      = Str::slug($request->judul_berita);
        $berita->tanggal_berita   = $request->tanggal_berita ? Carbon::parse($request->tanggal_berita) : Carbon::now();
        $berita->related_ids      = $request->input('related_ids') ? explode(',', $request->input('related_ids')) : [];
        $berita->views            = 0;
        
        $berita->save();

        return redirect()->route('berita.index')->with('success', 'Article berhasil dipublish!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Increment view count
        $berita->increment('views');

        // Get related articles
        $related = Berita::where('kategori_berita', $berita->kategori_berita)
            ->where('id_berita', '!=', $berita->id_berita)
            ->latest()
            ->take(3)
            ->get();

        return view('berita.show', compact('berita', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $title = 'Edit Article';
        $allBerita = Berita::select('id_berita', 'judul_berita')->where('id_berita', '!=', $id)->orderBy('judul_berita')->get();

        return view('berita.edit', compact('berita', 'title', 'allBerita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul_berita'   => 'required|max:100',
            'isi_berita'     => 'required',
            'kategori_berita' => 'required',
            'gambar_berita'  => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
            'meta_title'     => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'featured_snippet' => 'nullable|max:300',
            'conclusion'     => 'nullable|max:500',
            'focus_keyword'  => 'nullable|max:100',
            'tags'           => 'nullable|max:255',
            'faq_data'       => 'nullable',
        ]);

        // Handle image upload if new image is provided
        $namaGambar = $berita->gambar_berita;
        if ($request->hasFile('gambar_berita')) {
            $gambar = $request->file('gambar_berita');
            $namaGambar = 'Berita' . date('Ymdhis') . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('file/berita/', $namaGambar);
            
            // Delete old image if exists
            $oldImagePath = public_path('file/berita/' . $berita->gambar_berita);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }

        // Auto-generate reading time if content changed
        $readingTime = $berita->reading_time;
        if ($request->isi_berita !== $berita->isi_berita) {
            $readingTime = $this->calculateReadingTime($request->isi_berita);
        }

        // Auto-generate meta fields if not provided
        $metaTitle = $request->meta_title ?: $request->judul_berita;
        $metaDescription = $request->meta_description ?: 
            ($request->featured_snippet ?: Str::limit(strip_tags($request->isi_berita), 160));

        // Auto-generate tags if not provided
        $tags = $request->tags ?: $this->generateTags($request->isi_berita);

        $berita->update([
            'judul_berita'     => $request->judul_berita,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDescription,
            'tags'             => $tags,
            'reading_time'     => $readingTime,
            'featured_snippet' => $request->featured_snippet,
            'conclusion'       => $request->conclusion,
            'focus_keyword'    => $request->focus_keyword,
            'faq_data'         => $request->faq_data ? json_decode($request->faq_data, true) : null,
            'is_featured'      => $request->has('is_featured'),
            'isi_berita'       => $request->isi_berita,
            'kategori_berita'  => $request->kategori_berita,
            'gambar_berita'    => $namaGambar,
            'slug_berita'      => Str::slug($request->judul_berita),
            'tanggal_berita'   => $request->tanggal_berita ? Carbon::parse($request->tanggal_berita) : $berita->tanggal_berita,
            'related_ids'      => $request->input('related_ids') ? explode(',', $request->input('related_ids')) : [],
        ]);

        return redirect()->route('berita.index')->with('success', 'Article berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $namafileberita = $berita->gambar_berita;
        $file_berita = public_path('file/berita/') . $namafileberita;
        
        if (file_exists($file_berita)) {
            @unlink($file_berita);
        }
        
        $berita->delete();
        
        return redirect()->back()->with('success', 'Article berhasil dihapus!');
    }

    /**
     * Calculate reading time based on content
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $averageWordsPerMinute = 200;
        $readingTime = ceil($wordCount / $averageWordsPerMinute);
        
        return max(1, $readingTime); // Minimum 1 minute
    }

    /**
     * Generate tags from content
     */
    private function generateTags($content)
    {
        // Basic keyword extraction
        $commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'can', 'may', 'might', 'must'];
        
        $words = str_word_count(strtolower(strip_tags($content)), 1);
        $words = array_diff($words, $commonWords);
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        
        return implode(', ', array_slice(array_keys($wordFreq), 0, 5));
    }

    /**
     * Get articles for AJAX search (for related articles)
     */
    public function searchArticles(Request $request)
    {
        $query = $request->get('q', '');
        $exclude = $request->get('exclude', []);
        
        $articles = Berita::select('id_berita as id', 'judul_berita as title', 'kategori_berita as category')
            ->where('judul_berita', 'LIKE', "%{$query}%")
            ->whereNotIn('id_berita', $exclude)
            ->orderBy('judul_berita')
            ->limit(10)
            ->get();
            
        return response()->json($articles);
    }
}
