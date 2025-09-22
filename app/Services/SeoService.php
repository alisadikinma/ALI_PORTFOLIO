<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    /**
     * Generate Digital Transformation Consultant specific SEO metadata
     */
    public function generateConsultingMetadata($page = 'homepage')
    {
        $baseKeywords = [
            'Digital Transformation Consultant Manufacturing',
            'AI Implementation Manufacturing Industry',
            'Manufacturing Digital Strategy',
            'AI Specialist Indonesia',
            'Manufacturing Consultant Jakarta',
            'Content Creator AI Expert',
            'Manufacturing AI Implementation',
            'Digital Transformation Indonesia'
        ];

        switch ($page) {
            case 'homepage':
                return [
                    'title' => 'Ali Sadikin - Digital Transformation Consultant Manufacturing | AI Generalist Indonesia',
                    'description' => 'Digital Transformation Consultant specializing in manufacturing AI implementation. 16+ years experience, 54K+ followers. Transform your manufacturing with proven AI solutions.',
                    'keywords' => implode(', ', $baseKeywords),
                    'focus_keyword' => 'Digital Transformation Consultant Manufacturing'
                ];

            case 'portfolio':
                return [
                    'title' => 'Manufacturing AI Projects Portfolio | Digital Transformation Case Studies',
                    'description' => 'Explore proven manufacturing AI transformation projects. Real case studies from production optimization to smart factory implementations.',
                    'keywords' => implode(', ', array_merge($baseKeywords, ['Manufacturing AI Case Studies', 'Smart Factory Projects', 'Production Optimization'])),
                    'focus_keyword' => 'Manufacturing AI Projects'
                ];

            case 'services':
                return [
                    'title' => 'Digital Transformation Services Manufacturing | AI Implementation Consulting',
                    'description' => 'Professional AI implementation services for manufacturing companies. Custom GPT solutions, automation, and digital strategy consulting.',
                    'keywords' => implode(', ', array_merge($baseKeywords, ['AI Implementation Services', 'Manufacturing Automation', 'Custom GPT Manufacturing'])),
                    'focus_keyword' => 'AI Implementation Services Manufacturing'
                ];

            case 'about':
                return [
                    'title' => 'About Ali Sadikin | Manufacturing AI Expert & Digital Transformation Leader',
                    'description' => '16+ years manufacturing experience, 54K+ followers. From production engineer to AI innovator helping manufacturers embrace digital transformation.',
                    'keywords' => implode(', ', array_merge($baseKeywords, ['Manufacturing Expert Biography', 'AI Innovation Leader', 'Production Engineer AI'])),
                    'focus_keyword' => 'Manufacturing AI Expert'
                ];

            default:
                return [
                    'title' => 'Ali Sadikin - Digital Transformation Consultant | Manufacturing AI Specialist',
                    'description' => 'Leading digital transformation consultant for manufacturing industry. Specializing in AI implementation and smart factory solutions.',
                    'keywords' => implode(', ', $baseKeywords),
                    'focus_keyword' => 'Digital Transformation Consultant'
                ];
        }
    }

    /**
     * Generate structured data for Professional Consulting Services
     */
    public function generateConsultingSchema($settings = null)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => 'Ali Sadikin - Digital Transformation Consultant',
            'alternateName' => 'Manufacturing AI Specialist',
            'description' => 'Digital Transformation Consultant specializing in manufacturing AI implementation with 16+ years of experience helping companies modernize their operations.',
            'url' => url('/'),
            'logo' => asset('logo/logo.png'),
            'image' => asset('images/ali-sadikin-consultant.jpg'),
            'telephone' => $settings->no_hp_setting ?? '+62-XXX-XXXX-XXXX',
            'email' => $settings->email_setting ?? 'contact@alisadikin.com',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Jakarta',
                'addressCountry' => 'Indonesia',
                'streetAddress' => $settings->alamat_setting ?? 'Jakarta, Indonesia'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '-6.2088',
                'longitude' => '106.8456'
            ],
            'areaServed' => [
                [
                    '@type' => 'Country',
                    'name' => 'Indonesia'
                ],
                [
                    '@type' => 'AdministrativeArea',
                    'name' => 'Southeast Asia'
                ]
            ],
            'serviceType' => [
                'Digital Transformation Consulting',
                'AI Implementation for Manufacturing',
                'Smart Factory Solutions',
                'Manufacturing Process Optimization',
                'Custom AI Development'
            ],
            'priceRange' => '$$-$$$',
            'founder' => [
                '@type' => 'Person',
                'name' => $settings->pimpinan_setting ?? 'Ali Sadikin',
                'jobTitle' => 'Digital Transformation Consultant & AI Generalist',
                'description' => '16+ years manufacturing experience, 54K+ social media followers, specializing in AI implementation for manufacturing industry.',
                'image' => asset('favicon/' . ($settings->favicon_setting ?? 'profile.jpg')),
                'sameAs' => [
                    'https://linkedin.com/in/' . ($settings->linkedin_setting ?? 'alisadikin'),
                    'https://instagram.com/' . ($settings->instagram_setting ?? 'alisadikin'),
                    'https://youtube.com/' . ($settings->youtube_setting ?? 'alisadikin'),
                    'https://tiktok.com/@' . ($settings->tiktok_setting ?? 'alisadikin')
                ]
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '23',
                'bestRating' => '5'
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Digital Transformation Services',
                'itemListElement' => [
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Manufacturing Digital Assessment',
                            'description' => 'Comprehensive evaluation of current manufacturing processes and digital readiness'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'AI Implementation Strategy',
                            'description' => 'Custom AI solution design and implementation planning for manufacturing operations'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Smart Factory Development',
                            'description' => 'End-to-end smart factory transformation with IoT and AI integration'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Generate Project Portfolio Schema for SEO
     */
    public function generateProjectSchema($project)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project->project_name,
            'description' => $project->summary_description ?? $project->description,
            'url' => url('/portfolio/' . $project->slug_project),
            'image' => asset('file/project/' . $project->featured_image),
            'creator' => [
                '@type' => 'Person',
                'name' => 'Ali Sadikin',
                'jobTitle' => 'Digital Transformation Consultant'
            ],
            'about' => [
                '@type' => 'Thing',
                'name' => $project->project_category ?? 'Digital Transformation',
                'description' => 'Manufacturing AI implementation and digital transformation project'
            ],
            'keywords' => $this->generateProjectKeywords($project),
            'dateCreated' => Carbon::parse($project->created_at)->toISOString(),
            'inLanguage' => 'id-ID',
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'Ali Sadikin Portfolio',
                'url' => url('/')
            ]
        ];
    }

    /**
     * Generate project-specific keywords for SEO
     */
    private function generateProjectKeywords($project)
    {
        $baseKeywords = [
            'Manufacturing Digital Transformation',
            'AI Implementation Case Study',
            'Smart Factory Project'
        ];

        $categoryKeywords = [
            'Mobile Application' => ['Manufacturing Mobile App', 'Industrial IoT Mobile', 'Factory Management App'],
            'Web Application' => ['Manufacturing Web Platform', 'Industrial Dashboard', 'Production Management System'],
            'AI Solution' => ['Manufacturing AI', 'Industrial Artificial Intelligence', 'Smart Manufacturing AI'],
            'IoT Solution' => ['Industrial IoT', 'Manufacturing IoT', 'Smart Factory IoT'],
            'Custom Software' => ['Manufacturing Software', 'Industrial Custom Solution', 'Production Software']
        ];

        $keywords = $baseKeywords;

        if (isset($categoryKeywords[$project->project_category])) {
            $keywords = array_merge($keywords, $categoryKeywords[$project->project_category]);
        }

        // Add client-specific keywords if available
        if (!empty($project->client_name)) {
            $keywords[] = $project->client_name . ' Digital Transformation';
        }

        return implode(', ', $keywords);
    }

    /**
     * Generate Local SEO Schema for Indonesian market
     */
    public function generateLocalBusinessSchema($settings)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Ali Sadikin Digital Transformation Consulting',
            'description' => 'Leading digital transformation consultant in Indonesia specializing in manufacturing AI implementation and smart factory solutions.',
            'url' => url('/'),
            'telephone' => $settings->no_hp_setting,
            'email' => $settings->email_setting,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->alamat_setting,
                'addressLocality' => 'Jakarta',
                'addressRegion' => 'DKI Jakarta',
                'postalCode' => '10000',
                'addressCountry' => 'ID'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => -6.2088,
                'longitude' => 106.8456
            ],
            'openingHours' => 'Mo-Fr 09:00-17:00',
            'priceRange' => '$$-$$$',
            'paymentAccepted' => 'Cash, Credit Card, Bank Transfer',
            'currenciesAccepted' => 'IDR, USD',
            'areaServed' => [
                'Jakarta',
                'Surabaya',
                'Bandung',
                'Medan',
                'Semarang',
                'Indonesia',
                'Southeast Asia'
            ],
            'serviceArea' => [
                '@type' => 'GeoCircle',
                'geoMidpoint' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => -6.2088,
                    'longitude' => 106.8456
                ],
                'geoRadius' => '50000'
            ]
        ];
    }

    /**
     * Generate FAQ Schema for Digital Transformation Consulting
     */
    public function generateConsultingFaqSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'What is digital transformation in manufacturing?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Digital transformation in manufacturing involves integrating AI, IoT, and automation technologies to optimize production processes, improve efficiency, and enable data-driven decision making. This includes implementing smart factory solutions, predictive maintenance, and real-time monitoring systems.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How can AI implementation benefit manufacturing companies?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'AI implementation in manufacturing can reduce operational costs by 20-30%, improve production efficiency by 25%, enable predictive maintenance to prevent downtime, optimize quality control, and provide real-time insights for better decision making. Our clients typically see ROI within 6-12 months.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What makes Ali Sadikin different from other consultants?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'With 16+ years of hands-on manufacturing experience and 54K+ followers, Ali combines deep industry knowledge with cutting-edge AI expertise. He has successfully delivered 18+ digital transformation projects with 99% success rate, focusing on practical solutions that deliver measurable business impact.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How long does a typical digital transformation project take?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Project timelines vary based on scope and complexity. A basic AI implementation typically takes 3-6 months, while comprehensive smart factory transformation can take 6-18 months. We use agile methodology to deliver value incrementally throughout the project.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Do you provide training for manufacturing teams?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes, we provide comprehensive training programs for manufacturing teams including AI literacy, system operation, data analysis, and change management. Our training ensures successful adoption and maximizes the ROI of your digital transformation investment.'
                    ]
                ]
            ]
        ];
    }

    /**
     * Generate sitemap data for SEO
     */
    public function generateSitemapData()
    {
        $urls = collect();

        // Add main pages
        $mainPages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => url('/portfolio'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => url('/articles'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/gallery'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($mainPages as $page) {
            $urls->push(array_merge($page, ['lastmod' => now()->toISOString()]));
        }

        // Add project pages
        $projects = DB::table('project')
            ->where('status', 'Active')
            ->select('slug_project', 'updated_at')
            ->get();

        foreach ($projects as $project) {
            $urls->push([
                'url' => url('/portfolio/' . $project->slug_project),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::parse($project->updated_at)->toISOString()
            ]);
        }

        // Add article pages
        $articles = DB::table('berita')
            ->select('slug_berita', 'updated_at')
            ->get();

        foreach ($articles as $article) {
            $urls->push([
                'url' => url('/article/' . $article->slug_berita),
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::parse($article->updated_at)->toISOString()
            ]);
        }

        return $urls;
    }

    /**
     * Generate meta tags for social media sharing
     */
    public function generateSocialMetaTags($page, $data = null)
    {
        $baseData = $this->generateConsultingMetadata($page);

        return [
            // Open Graph
            'og:title' => $baseData['title'],
            'og:description' => $baseData['description'],
            'og:type' => 'website',
            'og:url' => url()->current(),
            'og:image' => asset('images/social-share-default.jpg'),
            'og:site_name' => 'Ali Sadikin - Digital Transformation Consultant',
            'og:locale' => 'id_ID',

            // Twitter Card
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $baseData['title'],
            'twitter:description' => $baseData['description'],
            'twitter:image' => asset('images/social-share-default.jpg'),
            'twitter:site' => '@alisadikin',
            'twitter:creator' => '@alisadikin',

            // LinkedIn
            'linkedin:owner' => 'alisadikin'
        ];
    }
}
