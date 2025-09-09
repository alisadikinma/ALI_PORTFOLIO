@props([
    'title' => 'Ali Sadikin - AI Generalist & Technopreneur',
    'description' => 'Discover Ali Sadikin\'s expertise in AI and technology. Explore innovative solutions, portfolio projects, and cutting-edge services in artificial intelligence.',
    'keywords' => 'AI, artificial intelligence, technology, web development, machine learning, tech consultant, portfolio',
    'image' => null,
    'url' => null,
    'type' => 'website',
    'author' => 'Ali Sadikin',
    'published' => null,
    'modified' => null,
    'section' => null,
    'canonical' => null,
    'noindex' => false
])

@php
    $konf = $konf ?? DB::table('setting')->first();
    $currentUrl = $url ?? request()->fullUrl();
    $ogImage = $image ?? asset('logo/' . $konf->logo_setting);
    $canonicalUrl = $canonical ?? $currentUrl;
@endphp

<!-- Primary Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">

@if($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
@endif

<meta name="theme-color" content="#1E2B44">
<meta name="msapplication-TileColor" content="#1E2B44">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ $konf->instansi_setting ?? 'Ali Sadikin Portfolio' }}">
<meta property="og:locale" content="en_US">

@if($type === 'article')
    @if($published)
        <meta property="article:published_time" content="{{ $published }}">
    @endif
    @if($modified)
        <meta property="article:modified_time" content="{{ $modified }}">
    @endif
    @if($section)
        <meta property="article:section" content="{{ $section }}">
    @endif
    <meta property="article:author" content="{{ $author }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $currentUrl }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:creator" content="@ali_sadikin">
<meta name="twitter:site" content="@ali_sadikin">

<!-- Additional SEO Tags -->
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<meta name="format-detection" content="email=no">

<!-- Preconnect for performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/' . $konf->favicon_setting) }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/' . $konf->favicon_setting) }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/' . $konf->favicon_setting) }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- DNS Prefetch for external resources -->
<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//www.googletagmanager.com">

<!-- Language and Region -->
<meta name="language" content="English">
<meta name="geo.region" content="ID-KR">
<meta name="geo.placename" content="Batam">
<meta name="geo.position" content="1.1456;103.7781">
<meta name="ICBM" content="1.1456, 103.7781">
