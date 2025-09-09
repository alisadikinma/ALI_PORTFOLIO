@props([
    'type' => 'website',
    'data' => []
])

@php
    $konf = $konf ?? DB::table('setting')->first();
    
    // Base organization schema
    $organizationSchema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => $konf->instansi_setting ?? "Ali Sadikin Portfolio",
        "url" => url('/'),
        "logo" => [
            "@type" => "ImageObject",
            "url" => asset('logo/' . $konf->logo_setting)
        ],
        "contactPoint" => [
            "@type" => "ContactPoint",
            "telephone" => $konf->telp_setting ?? "",
            "contactType" => "customer service",
            "availableLanguage" => ["English", "Indonesian"]
        ],
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Batam",
            "addressRegion" => "Riau",
            "addressCountry" => "ID"
        ]
    ];
    
    // Base person schema
    $personSchema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "name" => $konf->pimpinan_setting ?? "Ali Sadikin",
        "jobTitle" => $konf->profile_title ?? "AI Generalist & Technopreneur",
        "description" => strip_tags($konf->tentang_setting ?? ""),
        "url" => url('/'),
        "image" => asset('favicon/' . $konf->favicon_setting),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Batam",
            "addressRegion" => "Riau",
            "addressCountry" => "ID"
        ],
        "sameAs" => [
            "https://www.linkedin.com/in/ali-sadikin",
            "https://github.com/ali-sadikin",
            "https://twitter.com/ali_sadikin"
        ],
        "knowsAbout" => [
            "Artificial Intelligence",
            "Machine Learning",
            "Web Development",
            "Technology Consulting",
            "Digital Innovation"
        ],
        "worksFor" => [
            "@type" => "Organization",
            "name" => $konf->instansi_setting ?? "Ali Sadikin Portfolio"
        ]
    ];
    
    // Website schema
    $websiteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => $konf->instansi_setting ?? "Ali Sadikin Portfolio",
        "url" => url('/'),
        "description" => strip_tags($konf->tentang_setting ?? ""),
        "inLanguage" => "en-US",
        "copyrightYear" => date('Y'),
        "creator" => [
            "@type" => "Person",
            "name" => $konf->pimpinan_setting ?? "Ali Sadikin"
        ],
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => [
                "@type" => "EntryPoint",
                "urlTemplate" => url('/') . "?s={search_term_string}"
            ],
            "query-input" => "required name=search_term_string"
        ]
    ];
@endphp

@if($type === 'website')
<script type="application/ld+json">
{!! json_encode([$organizationSchema, $personSchema, $websiteSchema], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'article' && !empty($data))
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'faq' && !empty($data))
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'breadcrumb' && !empty($data))
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($type === 'portfolio')
@php
    $portfolioSchema = [
        "@context" => "https://schema.org",
        "@type" => "CreativeWork",
        "name" => $data['name'] ?? "",
        "description" => $data['description'] ?? "",
        "image" => $data['image'] ?? "",
        "url" => $data['url'] ?? "",
        "creator" => [
            "@type" => "Person",
            "name" => $konf->pimpinan_setting ?? "Ali Sadikin"
        ],
        "dateCreated" => $data['dateCreated'] ?? "",
        "genre" => $data['category'] ?? "Technology",
        "keywords" => $data['keywords'] ?? ""
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($portfolioSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
