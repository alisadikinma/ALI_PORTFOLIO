@props([
    'article',
    'relatedArticles' => [],
    'seoService'
])

@php
    $konf = $konf ?? DB::table('setting')->first();
    $readingTime = $seoService->calculateReadingTime($article->isi_berita);
    $metaTitle = $seoService->generateMetaTitle($article, $konf->instansi_setting);
    $metaDescription = $seoService->generateMetaDescription($article);
    
    // Generate article schema
    $articleSchema = $seoService->generateArticleSchema(
        $article, 
        $konf->pimpinan_setting, 
        $konf->instansi_setting
    );
    
    // Generate breadcrumb schema
    $breadcrumbs = [
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Articles', 'url' => route('articles')],
        ['name' => $article->judul_berita, 'url' => url()->current()]
    ];
    $breadcrumbSchema = $seoService->generateBreadcrumbSchema($breadcrumbs);
    
    // Generate FAQ schema if available
    $faqSchema = null;
    if (!empty($article->faq_data)) {
        $faqData = json_decode($article->faq_data, true);
        $faqSchema = $seoService->generateFaqSchema($faqData);
    }
    
    // Generate canonical URL
    $canonicalUrl = $seoService->generateCanonicalUrl($article);
@endphp

<!-- SEO Meta Tags -->
<x-seo-meta 
    :title="$metaTitle"
    :description="$metaDescription"
    :keywords="$article->tags ?? $konf->keyword_setting"
    :image="asset('file/berita/' . $article->gambar_berita)"
    :canonical="$canonicalUrl"
    type="article"
    :author="$konf->pimpinan_setting"
    :published="$article->created_at->toISOString()"
    :modified="$article->updated_at->toISOString()"
    :section="$article->kategori_berita ?? 'Technology'"
/>

<!-- Structured Data -->
<x-structured-data type="article" :data="$articleSchema" />
<x-structured-data type="breadcrumb" :data="$breadcrumbSchema" />

@if($faqSchema)
    <x-structured-data type="faq" :data="$faqSchema" />
@endif

<!-- Critical CSS for above-the-fold content -->
<style>
    /* Critical CSS for article page */
    .article-container {
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    .article-header {
        margin-bottom: 2rem;
    }
    
    .article-meta {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .article-content {
        font-size: 1.125rem;
        line-height: 1.7;
    }
    
    .article-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 2rem 0 1rem 0;
        color: #fff;
    }
    
    .article-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 1.5rem 0 0.75rem 0;
        color: #fff;
    }
    
    .article-content p {
        margin-bottom: 1rem;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }
    
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
        z-index: 1000;
        transition: width 0.3s ease;
    }
    
    .table-of-contents {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(71, 85, 105, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
        margin: 2rem 0;
    }
    
    .article-summary {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(245, 158, 11, 0.1));
        border-left: 4px solid #fbbf24;
        padding: 1.5rem;
        margin: 2rem 0;
        border-radius: 0 8px 8px 0;
    }
    
    @media (max-width: 768px) {
        .article-content {
            font-size: 1rem;
        }
        
        .article-content h2 {
            font-size: 1.375rem;
        }
        
        .article-content h3 {
            font-size: 1.125rem;
        }
    }
</style>

<!-- Preload important resources -->
<link rel="preload" href="{{ asset('file/berita/' . $article->gambar_berita) }}" as="image">

@if(!empty($relatedArticles))
    @foreach($relatedArticles->take(2) as $related)
        <link rel="prefetch" href="{{ route('article.detail', $related->slug_berita) }}">
    @endforeach
@endif
