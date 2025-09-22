<?php
use Illuminate\Support\Facades\DB;

$konf = DB::table('setting')
    ->select([
        'id_setting',
        'instansi_setting',
        'pimpinan_setting',
        'logo_setting',
        'favicon_setting',
        'keyword_setting',
        'profile_content',
        'alamat_setting',
        'email_setting',
        'no_hp_setting',
        'instagram_setting',
        'youtube_setting',
        'linkedin_setting',
        'tiktok_setting',
        'facebook_setting'
    ])
    ->first();

// Get menu items from lookup_data table ordered by sort_order and only active items
$menuItems = DB::table('lookup_data')
    ->where('lookup_type', 'homepage_section')
    ->where('is_active', 1)
    ->orderBy('sort_order', 'asc')
    ->get();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Enhanced SEO Meta Tags for Digital Transformation Consulting --}}
    @php
        $seoService = app(\App\Services\SeoService::class);
        $currentPage = request()->segment(1) ?: 'homepage';
        $seoData = $seoService->generateConsultingMetadata($currentPage);
    @endphp

    <title>@yield('title', $seoData['title'])</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo/' . ($konf->logo_setting ?? 'default.ico')) }}">
    <meta name="description" content="@yield('meta_description', $seoData['description'])">
    <meta name="keywords" content="@yield('meta_keywords', $seoData['keywords'])">
    <meta name="author" content="{{ $konf->pimpinan_setting ?? 'Ali Sadikin' }}">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <meta name="theme-color" content="#1E2B44">

    {{-- Professional Consulting Meta Tags --}}
    <meta name="subject" content="Digital Transformation Consulting for Manufacturing Industry">
    <meta name="audience" content="Manufacturing Decision Makers, Gen Z Professionals, Technology Leaders">
    <meta name="category" content="Professional Services, Consulting, Manufacturing Technology">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="coverage" content="worldwide">
    <meta name="target" content="manufacturing companies, technology leaders, digital transformation professionals">

    {{-- Geo-targeting for Indonesia and SEA --}}
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Jakarta, Indonesia">
    <meta name="geo.position" content="-6.2088;106.8456">
    <meta name="ICBM" content="-6.2088, 106.8456">

    {{-- Business/Professional Meta --}}
    <meta name="classification" content="Digital Transformation Consulting">
    <meta name="directory" content="submission">
    <meta name="pagename" content="@yield('page_name', 'Ali Sadikin Digital Transformation Consultant')">
    <meta name="page-topic" content="Manufacturing AI Implementation, Digital Transformation Consulting">
    <meta name="page-type" content="Professional Portfolio">
    
    {{-- Enhanced Open Graph Tags for Professional Consulting --}}
    <meta property="og:title" content="@yield('og_title', $seoData['title'])">
    <meta property="og:description" content="@yield('og_description', $seoData['description'])">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', request()->url())">
    <meta property="og:image" content="@yield('og_image', asset('images/social-share-consulting.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Ali Sadikin - Digital Transformation Consultant for Manufacturing">
    <meta property="og:site_name" content="Ali Sadikin - Digital Transformation Consultant">
    <meta property="og:locale" content="id_ID">
    <meta property="og:locale:alternate" content="en_US">

    {{-- Professional/Business Open Graph --}}
    <meta property="business:contact_data:street_address" content="{{ $konf->alamat_setting }}">
    <meta property="business:contact_data:locality" content="Jakarta">
    <meta property="business:contact_data:region" content="DKI Jakarta">
    <meta property="business:contact_data:postal_code" content="10000">
    <meta property="business:contact_data:country_name" content="Indonesia">
    <meta property="business:contact_data:email" content="{{ $konf->email_setting }}">
    <meta property="business:contact_data:phone_number" content="{{ $konf->no_hp_setting }}">
    <meta property="business:contact_data:website" content="{{ url('/') }}">
    
    {{-- Enhanced Twitter Card for Professional Consulting --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', $seoData['title'])">
    <meta name="twitter:description" content="@yield('twitter_description', $seoData['description'])">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/social-share-consulting.jpg'))">
    <meta name="twitter:image:alt" content="Ali Sadikin Digital Transformation Consultant - Manufacturing AI Expert">
    <meta name="twitter:site" content="@yield('twitter_site', '@alisadikinma')">
    <meta name="twitter:creator" content="@yield('twitter_creator', '@alisadikinma')">
    <meta name="twitter:label1" content="Experience">
    <meta name="twitter:data1" content="16+ Years Manufacturing">
    <meta name="twitter:label2" content="Followers">
    <meta name="twitter:data2" content="54K+ Social Media">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', request()->url())">
    
    {{-- Article Specific Meta --}}
    @yield('article_meta')
    
    {{-- Schema.org Structured Data --}}
    @yield('structured_data')
    
    {{-- Default Organization Schema --}}
    @if(!View::hasSection('structured_data'))
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "name": "{{ $konf->instansi_setting }}",
                "url": "{{ url('/') }}",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ url('/') }}?s={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "Organization",
                "name": "{{ $konf->instansi_setting }}",
                "url": "{{ url('/') }}",
                "logo": "{{ asset('logo/' . $konf->logo_setting) }}",
                "sameAs": [
                    "https://twitter.com/alisadikin",
                    "https://linkedin.com/in/alisadikin",
                    "https://github.com/alisadikin"
                ]
            },
            {
                "@type": "Person",
                "name": "{{ $konf->pimpinan_setting }}",
                "url": "{{ url('/') }}",
                "jobTitle": "AI Generalist & Technopreneur",
                "worksFor": {
                    "@type": "Organization",
                    "name": "{{ $konf->instansi_setting }}"
                }
            }
        ]
    }
    </script>
    @endif
    
    <!-- Favicon and App Icons -->
    <link rel="icon" type="image/png" href="{{ asset('logo/' . $konf->logo_setting) }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-192x192.png') }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="Ali Sadikin Portfolio">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ali Portfolio">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#8b5cf6">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">

    <!-- Performance and DNS Prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Critical Resource Hints -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="{{ asset('js/app.js') }}" as="script">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Enhanced Theme Support -->
    <meta name="color-scheme" content="dark light">
    <meta name="supported-color-schemes" content="dark light">
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "name": "Ali Sadikin Portfolio",
                "url": "{{ url('/') }}",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ url('/') }}?s={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "Person",
                "name": "{{ $konf->pimpinan_setting }}",
                "jobTitle": "AI Generalist & Technopreneur",
                "url": "{{ url('/') }}",
                "image": "{{ url('/images/logo.png') }}",
                "sameAs": [
                    "https://www.linkedin.com/in/alisadikinma",
                    "https://twitter.com/alisadikinma",
                    "https://github.com/alisadikinma"
                ]
            }
        ]
    }
    </script>
    <style>
        :root {
            --gradient: linear-gradient(to left, #1E2B44, #121212);

            /* Enhanced color system for better contrast */
            --text-high-contrast: #ffffff;
            --text-medium-contrast: #e2e8f0;
            --text-low-contrast: #94a3b8;
            --bg-primary: #0f0f23;
            --bg-secondary: #1a1a2e;
            --electric-purple: #8b5cf6;
            --cyber-pink: #ec4899;
            --neon-green: #10b981;
            --aurora-blue: #06b6d4;
        }

        .bg-gradient-footer {
            background: var(--gradient);
        }

        .outline-offset--1 {
            outline-offset: -1px;
        }

        .backdrop-blur-xl {
            backdrop-filter: blur(12px);
        }

        /* Dark mode support */
        [data-theme="dark"] {
            --bg-primary: #0f0f23;
            --bg-secondary: #1a1a2e;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
        }

        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
        }

        /* Performance optimizations */
        .gpu-accelerated {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000;
            will-change: transform;
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* High contrast mode */
        @media (prefers-contrast: high) {
            :root {
                --text-primary: #000000;
                --text-secondary: #333333;
                --electric-purple: #6d28d9;
                --cyber-pink: #be185d;
            }
        }

        /* COMPLETELY FIXED Mobile Menu Styles - VERSION 3 */
        #nav-menu {
            transition: all 0.3s ease-in-out;
        }
        
        /* Mobile menu overlay */
        #nav-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #nav-menu-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile specific styles - Cleaned up with minimal !important usage */
        @media (max-width: 639px) {
            /* Hidden state */
            #nav-menu.hidden {
                transform: translateX(-100%);
                opacity: 0;
                visibility: hidden;
            }

            /* Visible state - Mobile Menu */
            #nav-menu:not(.hidden) {
                position: fixed;
                top: 0;
                left: 0;
                width: 320px;
                height: 100vh;
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.5);
                padding: 2rem 1.5rem;
                z-index: 999;
                flex-direction: column;
                gap: 0;
                transform: translateX(0);
                opacity: 1;
                visibility: visible;
                overflow-y: auto;
                align-items: flex-start;
                justify-content: flex-start;
                border-radius: 0;
            }
            
            /* Mobile menu header */
            #nav-menu:not(.hidden) .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            #nav-menu:not(.hidden) .mobile-menu-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #fbbf24;
            }

            #nav-menu:not(.hidden) .close-menu-btn {
                background: none;
                border: none;
                cursor: pointer;
                padding: 0.5rem;
                color: #fbbf24;
            }

            /* Menu items styling */
            #nav-menu:not(.hidden) a {
                display: block;
                width: 100%;
                color: white;
                text-decoration: none;
                padding: 1rem 0;
                font-size: 1rem;
                font-weight: 500;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 0.5rem;
                transition: all 0.3s ease;
            }

            #nav-menu:not(.hidden) a:hover {
                color: #fbbf24;
                background-color: rgba(251, 191, 36, 0.1);
                transform: translateX(8px);
                border-radius: 8px;
                padding-left: 1rem;
            }

            /* Special styling for Send Message button in mobile */
            #nav-menu:not(.hidden) a.bg-yellow-400 {
                background: #fbbf24;
                color: #000;
                text-align: center;
                font-weight: 600;
                margin-top: 1rem;
                border-radius: 8px;
                padding: 1rem;
                border-bottom: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            #nav-menu:not(.hidden) a.bg-yellow-400:hover {
                background: #f59e0b;
                color: #000;
                transform: translateY(-2px);
                padding-left: 1rem;
            }
        }

        /* Desktop styles - cleaned up */
        @media (min-width: 640px) {
            #nav-menu {
                transform: none;
                opacity: 1;
                background: none;
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                width: auto;
                height: auto;
                position: static;
                flex-direction: row;
                gap: 1.75rem;
                visibility: visible;
            }

            #nav-menu .mobile-menu-header {
                display: none;
            }

            #nav-menu-overlay {
                display: none;
            }
        }

        #nav-menu a {
            position: relative;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            overflow: hidden;
            text-decoration: none;
        }

        #nav-menu a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.2), transparent);
            transition: left 0.3s ease;
        }

        #nav-menu a:hover::before {
            left: 100%;
        }

        #nav-menu a:hover {
            background: rgba(255, 215, 0, 0.1);
            transform: translateX(8px);
        }

        #nav-menu .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #nav-menu .mobile-menu-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffd700;
        }

        #nav-menu .close-menu-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            color: #ffd700;
        }

        #nav-menu .close-menu-btn svg {
            transition: transform 0.3s ease;
        }

        #nav-menu .close-menu-btn:hover svg {
            transform: rotate(90deg);
        }

        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thumb-slate-700 {
            scrollbar-color: #334155 transparent;
        }

        .social-media-container {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .social-media-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
        }

        .social-media-text {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            letter-spacing: 0.02em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .social-icon-link {
            background: #334155;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .social-icon-link:hover {
            background: #475569;
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .social-icon-link svg {
            transition: transform 0.3s ease;
        }

        .social-icon-link:hover svg {
            transform: scale(1.2);
        }

        @media (min-width: 640px) {
            .social-media-container {
                padding: 2rem;
            }

            .social-icon-link {
                padding: 1.25rem;
            }
        }

        /* Testimonials Section */
        .testimonials-section {
            width: 100%;
            padding: 40px 0;
            text-align: center;
            background: #1e2b44;
            overflow: hidden;
        }

        .testimonials-title {
            font-size: 32px;
            font-weight: 700;
            color: #ffd300;
            margin-bottom: 16px;
        }

        .testimonials-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            padding: 20px 0;
        }

        .testimonials-grid {
            display: flex;
            gap: 20px;
            padding-bottom: 10px;
            scroll-snap-type: x mandatory;
        }

        .testimonial-item {
            background: #132138;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            min-width: 300px;
            flex-shrink: 0;
            scroll-snap-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .testimonial-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #ffd300;
            transition: transform 0.4s ease;
        }

        .testimonial-text {
            font-size: 16px;
            color: #ffffff;
            margin-bottom: 10px;
            line-height: 1.4;
            text-align: center;
        }

        .testimonial-author {
            font-size: 14px;
            color: #989898;
            font-style: italic;
            text-align: center;
        }

        .testimonial-item:hover {
            transform: translateY(-10px) scale(1.05) rotate(2deg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .testimonial-item:hover .testimonial-image {
            transform: scale(1.1);
        }

        .testimonials-wrapper::-webkit-scrollbar {
            display: none;
        }

        .testimonials-wrapper {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (min-width: 768px) {
            .testimonials-title {
                font-size: 48px;
            }

            .testimonial-item {
                min-width: 350px;
            }

            .testimonial-image {
                width: 120px;
                height: 120px;
            }
        }

        #contact {
            background: linear-gradient(135deg, #1e2b44 0%, #121212 100%);
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        #contact .flex-col {
            gap: 1.5rem;
        }

        #contact h2 {
            color: #ffd700;
            transition: color 0.3s ease;
        }

        #contact h2:hover {
            color: #ffeb3b;
        }

        #contact .bg-slate-900 {
            border-radius: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #contact .bg-slate-900:hover {
            background-color: #2d3748;
            transform: translateY(-2px);
        }

        #contact .text-gray-400 {
            color: #a0aec0;
        }

        #contact a {
            text-decoration: none;
        }

        #contact .social-icons a svg {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        #contact .social-icons a:hover svg {
            transform: scale(1.2);
            color: #000;
        }

        #contact input,
        #contact select,
        #contact textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        #contact input:focus,
        #contact select:focus,
        #contact textarea:focus {
            border-color: #ffd700;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }

        #contact button {
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        #contact button:hover {
            background-color: #ffca28;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 215, 0, 0.4);
        }

        @media (min-width: 640px) {
            #contact .sm:w-1/2 {
                width: 48%;
            }
        }

        @media (min-width: 1024px) {
            #contact {
                padding: 2rem 3rem;
            }

            #contact .lg:max-w-md {
                max-width: 40%;
            }

            #contact .flex-1 {
                flex: 1;
            }

            #contact .sm:w-80 {
                width: 100%;
                max-width: 20rem;
            }
        }
    </style>
</head>

<body class="bg-gradient-footer text-white font-['Inter']">
    <!-- Mobile Menu Overlay -->
    <div id="nav-menu-overlay" onclick="toggleMenu()"></div>

    <!-- Header -->
    <header class="w-full fixed top-0 left-0 z-50 bg-gradient-footer backdrop-blur-xl">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4" style="max-width: 1200px;">
            <div class="flex justify-between items-center">
                <!-- Logo and Name - Fixed to stay in one line -->
                <div class="text-neutral-50 text-xl sm:text-2xl font-bold leading-[48px] sm:leading-[72px] tracking-wide">
                    <a href="{{ url('/') }}" class="flex items-center gap-4 hover:text-yellow-400 transition-colors whitespace-nowrap">
                        <img src="{{ asset('logo/' . $konf->logo_setting) }}" alt="ASM Logo" class="w-12 sm:w-16 h-12 sm:h-16 object-contain">
                        <span class="whitespace-nowrap">{{ $konf->pimpinan_setting }}</span>
                    </a>
                </div>

                <!-- Enhanced Mobile Menu Toggle Button -->
                <button class="sm:hidden p-2 focus-visible rounded-lg"
                        onclick="toggleMenu()"
                        aria-label="Toggle navigation menu"
                        aria-expanded="false"
                        aria-controls="nav-menu"
                        aria-haspopup="true"
                        id="menu-toggle">
                    <svg class="w-6 h-6 transition-transform duration-300" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                            class="menu-icon" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"
                            class="close-icon hidden" />
                    </svg>
                    <span class="sr-only">Open main menu</span>
                </button>

                <!-- Navigation Menu -->
                <nav id="nav-menu"
                    class="hidden sm:flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-7 absolute sm:static top-0 left-0 w-full sm:w-auto sm:bg-transparent p-4 sm:p-0 shadow-lg sm:shadow-none">

                    <!-- Mobile Menu Header -->
                    <div class="mobile-menu-header sm:hidden">
                        <span class="mobile-menu-title">{{ $konf->pimpinan_setting }}</span>
                        <button class="close-menu-btn" onclick="toggleMenu()" aria-label="Close menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Home Link (Always first and active) --}}
                    <a href="{{ url('/') }}"
                        class="{{ request()->is('/') ? 'text-yellow-400 font-semibold' : 'text-white font-semibold' }} text-base hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                        Home
                    </a>

                    {{-- Dynamic Menu Items from Database --}}
                    @foreach($menuItems as $item)
                        @php
                            // Determine if this menu item is currently active page
                            $currentPath = request()->path();
                            $currentRoute = request()->route();
                            $routeName = $currentRoute ? $currentRoute->getName() : '';
                            
                            $isCurrentSection = false;
                            
                            // Check for portfolio/project pages
                            if ($item->lookup_code === 'portfolio') {
                                $isCurrentSection = 
                                    str_contains($currentPath, 'portfolio') ||
                                    str_contains($currentPath, 'project/') ||
                                    in_array($routeName, ['portfolio.detail', 'project.public.show', 'portfolio', 'portfolio.all']) ||
                                    preg_match('/\/(portfolio|project)\/[^\/]+$/', $currentPath);
                            }
                            
                            // Check for article pages
                            if ($item->lookup_code === 'articles') {
                                $isCurrentSection = request()->is('article/*');
                            }
                            
                            // Set link classes based on current section
                            $linkClasses = $isCurrentSection 
                                ? 'text-yellow-400 font-semibold' 
                                : 'text-gray-400 font-normal';
                            
                            // Create the link URL
                            $linkUrl = url('/#' . $item->lookup_code);
                            
                            // Special styling for contact (Send Message button)
                            $isContactButton = $item->lookup_code === 'contact';
                        @endphp

                        @if($isContactButton)
                            {{-- Contact Button (Special Styling) --}}
                            <a href="{{ $linkUrl }}"
                                class="px-4 sm:px-6 py-2 bg-yellow-400 rounded-lg flex items-center gap-3 text-neutral-900 hover:bg-yellow-500 transition-colors w-full sm:w-auto justify-center sm:justify-start">
                                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4m8-8v16" />
                                </svg>
                                <span class="text-sm font-semibold capitalize leading-[40px] sm:leading-[56px]">Send Message</span>
                            </a>
                        @else
                            {{-- Regular Menu Items --}}
                            <a href="{{ $linkUrl }}"
                                class="{{ $linkClasses }} text-base hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                                {{ $item->lookup_name }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24">
        @yield('isi')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-gradient-footer">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-28 pb-6 flex flex-col items-center gap-6 sm:gap-8" style="max-width: 1200px;">
            <div class="flex flex-col sm:flex-row justify-between items-center w-full gap-6 sm:gap-0">
            </div>
            <div class="w-full max-w-96 h-0.5 outline outline-1 outline-slate-800"></div>
            <div class="text-white text-sm font-normal leading-tight text-center">© Copyright 2025 | Portfolio by
                {{ $konf->pimpinan_setting }}</div>
        </div>
    </footer>

    <script>
        // ENHANCED ACCESSIBLE MOBILE MENU TOGGLE FUNCTION
        function toggleMenu() {
            const menu = document.getElementById('nav-menu');
            const overlay = document.getElementById('nav-menu-overlay');
            const toggleButton = document.getElementById('menu-toggle');
            const menuIcon = toggleButton.querySelector('.menu-icon');
            const closeIcon = toggleButton.querySelector('.close-icon');

            console.log('Toggle menu clicked', { menu, overlay, toggleButton });

            // Check if elements exist
            if (!menu || !overlay || !toggleButton || !menuIcon || !closeIcon) {
                console.error('Menu elements not found:', { menu, overlay, toggleButton, menuIcon, closeIcon });
                return;
            }

            const isOpen = !menu.classList.contains('hidden');
            console.log('Menu is currently open:', isOpen);

            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        // Announce to screen readers
        function announceToScreenReader(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = message;
            document.body.appendChild(announcement);

            setTimeout(() => {
                document.body.removeChild(announcement);
            }, 1000);
        }

        function openMenu() {
            const menu = document.getElementById('nav-menu');
            const overlay = document.getElementById('nav-menu-overlay');
            const toggleButton = document.getElementById('menu-toggle');
            const menuIcon = toggleButton.querySelector('.menu-icon');
            const closeIcon = toggleButton.querySelector('.close-icon');

            // Open menu
            menu.classList.remove('hidden');
            overlay.classList.add('show');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            toggleButton.setAttribute('aria-expanded', 'true');
            toggleButton.setAttribute('aria-label', 'Close navigation menu');

            // Announce to screen readers
            announceToScreenReader('Navigation menu opened');

            // Focus management - focus first menu item
            const firstMenuItem = menu.querySelector('a');
            if (firstMenuItem) {
                setTimeout(() => {
                    firstMenuItem.focus();
                    firstMenuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 150);
            }

            // Add class for styling
            document.body.classList.add('menu-open');

            console.log('Menu opened');
        }

        function closeMenu() {
            const menu = document.getElementById('nav-menu');
            const overlay = document.getElementById('nav-menu-overlay');
            const toggleButton = document.getElementById('menu-toggle');
            const menuIcon = toggleButton.querySelector('.menu-icon');
            const closeIcon = toggleButton.querySelector('.close-icon');

            // Close menu
            menu.classList.add('hidden');
            overlay.classList.remove('show');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.style.overflow = '';
            toggleButton.setAttribute('aria-expanded', 'false');
            toggleButton.setAttribute('aria-label', 'Open navigation menu');

            // Announce to screen readers
            announceToScreenReader('Navigation menu closed');

            // Remove class for styling
            document.body.classList.remove('menu-open');

            // Return focus to toggle button with smooth scroll
            setTimeout(() => {
                toggleButton.focus();
                toggleButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);

            console.log('Menu closed');
        }

        // Keyboard event handling
        document.addEventListener('keydown', function(e) {
            const menu = document.getElementById('nav-menu');
            const isMenuOpen = menu && !menu.classList.contains('hidden');

            if (isMenuOpen) {
                // Escape key closes menu
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeMenu();
                    return;
                }

                // Tab key focus trapping
                if (e.key === 'Tab') {
                    const focusableElements = menu.querySelectorAll('a, button');
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];

                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }

                // Arrow key navigation
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const menuItems = Array.from(menu.querySelectorAll('a'));
                    const currentIndex = menuItems.indexOf(document.activeElement);

                    let nextIndex;
                    if (e.key === 'ArrowDown') {
                        nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
                    } else {
                        nextIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
                    }

                    menuItems[nextIndex].focus();
                }
            }
        });

        // Enhanced mobile menu initialization with accessibility
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing enhanced mobile menu');

            const mobileMenuLinks = document.querySelectorAll('#nav-menu a');
            const menu = document.getElementById('nav-menu');
            const toggleButton = document.getElementById('menu-toggle');

            console.log('Found menu links:', mobileMenuLinks.length);

            // Add ARIA attributes
            if (menu) {
                menu.setAttribute('role', 'navigation');
                menu.setAttribute('aria-label', 'Main navigation');
            }

            if (toggleButton) {
                toggleButton.setAttribute('aria-controls', 'nav-menu');
                toggleButton.setAttribute('aria-haspopup', 'true');
            }

            // Enhanced menu link handling
            mobileMenuLinks.forEach((link, index) => {
                // Add keyboard support for menu items
                link.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });

                // Close menu on link click (mobile only)
                link.addEventListener('click', function() {
                    console.log('Menu link clicked:', this.textContent);
                    const isMenuVisible = !menu.classList.contains('hidden');
                    const isMobile = window.innerWidth < 640;

                    console.log('Link click - Menu visible:', isMenuVisible, 'Is mobile:', isMobile);

                    if (isMenuVisible && isMobile) {
                        console.log('Closing menu due to link click');
                        setTimeout(() => closeMenu(), 100); // Small delay for better UX
                    }
                });
            });

            // Initialize intersection observer for scroll animations
            initScrollAnimations();
        });

        // Intersection Observer for scroll-triggered animations
        function initScrollAnimations() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');

            if (animatedElements.length === 0) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                        // Add stagger effect for multiple elements
                        const delay = Array.from(animatedElements).indexOf(entry.target) * 100;
                        entry.target.style.animationDelay = delay + 'ms';
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            animatedElements.forEach(el => observer.observe(el));
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Slider functionality for Testimonials section
        const dots = document.querySelectorAll('#nav-dots > div');
        const slider = document.querySelector('#testimonials .overflow-x-auto');

        function updateSlider() {
            const cards = document.querySelectorAll('#testimonials .overflow-x-auto > div > div');
            if (cards.length === 0) return;

            const card = cards[0];
            const cardStyle = window.getComputedStyle(card);
            const cardWidth = card.offsetWidth + parseFloat(cardStyle.marginLeft) + parseFloat(cardStyle.marginRight);

            // Update active dot based on scroll position
            const updateActiveDot = () => {
                const index = Math.round(slider.scrollLeft / cardWidth);
                dots.forEach(d => d.classList.replace('bg-yellow-400', 'bg-neutral-600'));
                if (dots[index]) {
                    dots[index].classList.replace('bg-neutral-600', 'bg-yellow-400');
                }
            };

            // Handle dot clicks
            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const index = parseInt(dot.dataset.index);
                    slider.scrollTo({
                        left: index * cardWidth,
                        behavior: 'smooth'
                    });
                    updateActiveDot();
                });
            });

            // Handle scroll events
            if (slider) {
                slider.addEventListener('scroll', updateActiveDot);
            }

            // Update on window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (cards.length > 0) {
                        const newCardWidth = cards[0].offsetWidth + parseFloat(cardStyle.marginLeft) +
                            parseFloat(cardStyle.marginRight);
                        slider.scrollTo({
                            left: Math.round(slider.scrollLeft / cardWidth) * newCardWidth,
                            behavior: 'smooth'
                        });
                        updateActiveDot();
                    }
                }, 100);
            });

            // Initial update
            updateActiveDot();
        }

        // Initialize slider when DOM is loaded
        document.addEventListener('DOMContentLoaded', updateSlider);
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const sections = document.querySelectorAll("section[id]");
        const navLinks = document.querySelectorAll("#nav-menu a");

        console.log('🔍 Available sections:', Array.from(sections).map(s => s.id));
        console.log('🔗 Navigation links:', Array.from(navLinks).map(l => l.getAttribute('href')));

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        console.log('🎯 Section in view:', entry.target.id);
                        
                        navLinks.forEach((link) => {
                            link.classList.remove("text-yellow-400", "font-semibold");
                            link.classList.add("text-gray-400", "font-normal");

                            // ✅ Match with href that might be full URL
                            if (link.getAttribute("href").endsWith("#" + entry.target.id)) {
                                console.log('✨ Highlighting menu for section:', entry.target.id);
                                link.classList.add("text-yellow-400", "font-semibold");
                                link.classList.remove("text-gray-400", "font-normal");
                            }
                        });
                    }
                });
            }, {
                threshold: 0.5
            }
        );

        sections.forEach((section) => observer.observe(section));
    });
</script>

</body>

</html>