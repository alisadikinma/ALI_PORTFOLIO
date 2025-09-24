<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    /**
     * Generate meta title for article
     */
    public function generateMetaTitle($article, $siteName = null)
    {
        if (!empty($article->meta_title)) {
            return $article->meta_title;
        }
        
        $title = $article->judul_berita;
        $siteName = $siteName ?? 'Ali Sadikin Portfolio';
        
        // Limit title to 60 characters for SEO
        if (strlen($title) > 50) {
            $title = Str::limit($title, 50, '');
        }
        
        return $title . ' | ' . $siteName;
    }
    
    /**
     * Generate meta description for article
     */
    public function generateMetaDescription($article)
    {
        if (!empty($article->meta_description)) {
            return $article->meta_description;
        }
        
        if (!empty($article->featured_snippet)) {
            return Str::limit($article->featured_snippet, 160);
        }
        
        $content = strip_tags($article->isi_berita);
        return Str::limit($content, 160);
    }
    
    /**
     * Calculate reading time based on content
     */
    public function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $averageWordsPerMinute = 200;
        $readingTime = ceil($wordCount / $averageWordsPerMinute);
        
        return max(1, $readingTime); // Minimum 1 minute
    }
    
    /**
     * Generate tags from content
     */
    public function generateTags($content, $existingTags = null)
    {
        if (!empty($existingTags)) {
            return $existingTags;
        }
        
        // Basic keyword extraction (you can enhance this with NLP)
        $commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'can', 'may', 'might', 'must'];
        
        $words = str_word_count(strtolower(strip_tags($content)), 1);
        $words = array_diff($words, $commonWords);
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        
        return implode(', ', array_slice(array_keys($wordFreq), 0, 10));
    }
    
    /**
     * Generate structured data for article
     */
    public function generateArticleSchema($article, $author, $publisher)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->judul_berita,
            'description' => $this->generateMetaDescription($article),
            'image' => [
                '@type' => 'ImageObject',
                'url' => asset('file/berita/' . $article->gambar_berita),
                'width' => 1200,
                'height' => 630
            ],
            'datePublished' => $article->created_at->toISOString(),
            'dateModified' => $article->updated_at->toISOString(),
            'author' => [
                '@type' => 'Person',
                'name' => $author
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $publisher,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo/logo.png')
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current()
            ],
            'wordCount' => str_word_count(strip_tags($article->isi_berita)),
            'timeRequired' => 'PT' . $this->calculateReadingTime($article->isi_berita) . 'M',
            'articleSection' => $article->kategori_berita ?? 'Technology',
            'keywords' => $this->generateTags($article->isi_berita, $article->tags ?? null)
        ];
    }
    
    /**
     * Generate FAQ schema
     */
    public function generateFaqSchema($faqData)
    {
        if (empty($faqData)) {
            return null;
        }
        
        $faqItems = [];
        foreach ($faqData as $faq) {
            $faqItems[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems
        ];
    }
    
    /**
     * Generate breadcrumb schema
     */
    public function generateBreadcrumbSchema($breadcrumbs)
    {
        $items = [];
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }
    
    /**
     * Get related articles based on tags/category
     */
    public function getRelatedArticles($currentArticle, $limit = 3)
    {
        return Cache::remember("related_articles_{$currentArticle->id}", 1800, function() use ($currentArticle, $limit) {
            return \DB::table('berita')
                ->where('id', '!=', $currentArticle->id)
                ->where(function($query) use ($currentArticle) {
                    if (!empty($currentArticle->kategori_berita)) {
                        $query->where('kategori_berita', $currentArticle->kategori_berita);
                    }
                    if (!empty($currentArticle->tags)) {
                        $tags = explode(',', $currentArticle->tags);
                        foreach ($tags as $tag) {
                            $query->orWhere('tags', 'LIKE', '%' . trim($tag) . '%');
                        }
                    }
                })
                ->select('judul_berita', 'slug_berita', 'gambar_berita', 'tanggal_berita', 'kategori_berita')
                ->orderBy('tanggal_berita', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Update article view count
     */
    public function incrementViews($articleId)
    {
        \DB::table('berita')
            ->where('id', $articleId)
            ->increment('views');
    }
    
    /**
     * Generate canonical URL
     */
    public function generateCanonicalUrl($article)
    {
        $date = \Carbon\Carbon::parse($article->tanggal_berita);
        return url("/articles/{$date->year}/{$date->format('m')}/{$article->slug_berita}");
    }
}
