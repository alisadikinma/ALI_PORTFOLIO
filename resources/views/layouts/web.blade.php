<?php
use Illuminate\Support\Facades\DB;

$konf = DB::table('setting')->first();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Dynamic SEO Meta Tags --}}
    <title>@yield('title', $konf->instansi_setting . ' - AI Generalist & Technopreneur')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo/' . ($konf->logo_setting ?? 'default.ico')) }}">
    <meta name="description" content="@yield('meta_description', $konf->tentang_setting)">
    <meta name="keywords" content="@yield('meta_keywords', $konf->keyword_setting)">
    <meta name="author" content="{{ $konf->pimpinan_setting }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="theme-color" content="#1E2B44">
    
    {{-- Open Graph Tags --}}
    <meta property="og:title" content="@yield('og_title', $konf->instansi_setting)">
    <meta property="og:description" content="@yield('og_description', $konf->tentang_setting)">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', request()->url())">
    <meta property="og:image" content="@yield('og_image', asset('logo/' . $konf->logo_setting))">
    <meta property="og:site_name" content="{{ $konf->instansi_setting }}">
    <meta property="og:locale" content="id_ID">
    
    {{-- Twitter Card Tags --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', $konf->instansi_setting)">
    <meta name="twitter:description" content="@yield('twitter_description', $konf->tentang_setting)">
    <meta name="twitter:image" content="@yield('twitter_image', asset('logo/' . $konf->logo_setting))">
    <meta name="twitter:site" content="@yield('twitter_site', '@alisadikin')">
    <meta name="twitter:creator" content="@yield('twitter_creator', '@alisadikin')">
    
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/' . $konf->logo_setting) }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/' . $konf->logo_setting) }}">
    
    <!-- DNS Prefetch for Performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    "https://www.linkedin.com/in/ali-sadikin",
                    "https://twitter.com/ali_sadikin",
                    "https://github.com/ali-sadikin"
                ]
            }
        ]
    }
    </script>
    <style>
        :root {
            --gradient: linear-gradient(to left, #1E2B44, #121212);
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

        #nav-menu {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            transform: translateX(-100%);
            opacity: 0;
        }

        #nav-menu.hidden {
            transform: translateX(-100%);
            opacity: 0;
        }

        #nav-menu:not(.hidden) {
            transform: translateX(0);
            opacity: 1;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-radius: 0 8px 8px 0;
            padding: 2rem 1.5rem;
            width: 80%;
            max-width: 320px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        #nav-menu a {
            position: relative;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            overflow: hidden;
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
        }

        #nav-menu .close-menu-btn svg {
            transition: transform 0.3s ease;
        }

        #nav-menu .close-menu-btn:hover svg {
            transform: rotate(90deg);
        }

        @media (min-width: 640px) {
            #nav-menu {
                transform: none !important;
                opacity: 1 !important;
                background: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                width: auto !important;
                height: auto !important;
                position: static !important;
                flex-direction: row !important;
                gap: 1.75rem !important;
            }

            #nav-menu .mobile-menu-header {
                display: none !important;
            }

            #nav-menu a::before {
                display: none !important;
            }

            #nav-menu a:hover {
                background: none !important;
                transform: none !important;
            }
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
    <!-- Header -->
    <header
        class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-4 fixed top-0 left-0 z-50 bg-gradient-footer backdrop-blur-xl">
        <div class="flex justify-between items-center">
            <div class="text-neutral-50 text-xl sm:text-2xl font-bold leading-[48px] sm:leading-[72px] tracking-wide">
                <a href="{{ url('/') }}" class="flex items-center gap-4 hover:text-yellow-400 transition-colors">
                    <img src="{{ asset('logo/' . $konf->logo_setting) }}" alt="ASM Logo" class="w-12 sm:w-16 h-12 sm:h-16 object-contain">
                    {{ $konf->pimpinan_setting }}
                </a>
            </div>
            <button class="sm:hidden p-2" onclick="toggleMenu()" aria-label="Toggle navigation menu"
                aria-expanded="false" id="menu-toggle">
                <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                        class="menu-icon" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"
                        class="close-icon hidden" />
                </svg>
            </button>
            <nav id="nav-menu"
                class="hidden sm:flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-7 absolute sm:static top-0 left-0 w-full sm:w-auto sm:bg-transparent p-4 sm:p-0 shadow-lg sm:shadow-none">

                <div class="mobile-menu-header sm:hidden">
                    <span class="mobile-menu-title">{{ $konf->pimpinan_setting }}</span>
                </div>

                {{-- Home --}}
                <a href="{{ url('/') }}"
                    class="{{ request()->is('/') ? 'text-yellow-400 font-semibold' : 'text-white font-semibold' }} text-base hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Home
                </a>

                {{-- About --}}
                @if($konf->about_section_active ?? true)
                <a href="{{ url('/#about') }}"
                    class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    About
                </a>
                @endif

                {{-- Services --}}
                @if($konf->services_section_active ?? true)
                <a href="{{ url('/#services') }}"
                    class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Services
                </a>
                @endif

                {{-- Portfolio --}}
                @if($konf->portfolio_section_active ?? true)
                <a href="{{ url('/#portfolio') }}"
                    class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Portfolio
                </a>
                @endif

                {{-- Testimonials --}}
                @if($konf->testimonials_section_active ?? true)
                <a href="{{ url('/#testimonials') }}"
                    class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Testimonials
                </a>
                @endif

                {{-- Gallery --}}
                @if($konf->gallery_section_active ?? true)
                <a href="{{ url('/#gallery') }}"
                    class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Gallery
                </a>
                @endif

                {{-- Articles --}}
                @if($konf->articles_section_active ?? true)
                <a href="{{ url('/#articles') }}"
                    class="{{ request()->is('article/*') ? 'text-yellow-400 font-semibold' : 'text-gray-400 font-normal' }} text-base hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
                    Articles
                </a>
                @endif

                {{-- Contact (tombol khusus) --}}
                @if($konf->contact_section_active ?? true)
                <a href="{{ url('/#contact') }}"
                    class="px-4 sm:px-6 py-2 bg-yellow-400 rounded-lg flex items-center gap-3 text-neutral-900 hover:bg-yellow-500 transition-colors w-full sm:w-auto justify-center sm:justify-start">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4m8-8v16" />
                    </svg>
                    <span class="text-sm font-semibold capitalize leading-[40px] sm:leading-[56px]">Send Message</span>
                </a>
                @endif
            </nav>

        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24">
        @yield('isi')
    </main>

    <!-- Footer -->
    <footer
        class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 pt-12 sm:pt-28 pb-6 bg-gradient-footer flex flex-col items-center gap-6 sm:gap-8">
        <div class="flex flex-col sm:flex-row justify-between items-center w-full gap-6 sm:gap-0">
        </div>
        <div class="w-full max-w-96 h-0.5 outline outline-1 outline-slate-800"></div>
        <div class="text-white text-sm font-normal leading-tight text-center">© Copyright 2025 | Portfolio by
            {{ $konf->pimpinan_setting }}</div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('nav-menu');
            const toggleButton = document.getElementById('menu-toggle');
            const menuIcon = toggleButton.querySelector('.menu-icon');
            const closeIcon = toggleButton.querySelector('.close-icon');
            const isOpen = !menu.classList.contains('hidden');

            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden', !isOpen);
            closeIcon.classList.toggle('hidden', isOpen);
            toggleButton.setAttribute('aria-expanded', !isOpen);
            document.body.style.overflow = isOpen ? '' : 'hidden';
        }

        function toggleFooterMenu() {
            const menu = document.getElementById('footer-nav-menu');
            const toggleButton = document.getElementById('footer-menu-toggle');
            const menuIcon = toggleButton.querySelector('.menu-icon');
            const closeIcon = toggleButton.querySelector('.close-icon');
            const isOpen = !menu.classList.contains('hidden');

            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden', !isOpen);
            closeIcon.classList.toggle('hidden', isOpen);
            toggleButton.setAttribute('aria-expanded', !isOpen);
            document.body.style.overflow = isOpen ? '' : 'hidden';
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
                // Close mobile menu after clicking a link
                if (!document.getElementById('nav-menu').classList.contains('sm:flex')) {
                    toggleMenu();
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
            slider.addEventListener('scroll', updateActiveDot);

            // Update on window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const newCardWidth = cards[0].offsetWidth + parseFloat(cardStyle.marginLeft) +
                        parseFloat(cardStyle.marginRight);
                    slider.scrollTo({
                        left: Math.round(slider.scrollLeft / cardWidth) * newCardWidth,
                        behavior: 'smooth'
                    });
                    updateActiveDot();
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

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        navLinks.forEach((link) => {
                            link.classList.remove("text-yellow-400", "font-semibold");
                            link.classList.add("text-gray-400", "font-normal");

                            // ✅ cocokkan dengan href yang mungkin berupa URL penuh
                            if (link.getAttribute("href").endsWith("#" + entry.target.id)) {
                                link.classList.add("text-yellow-400", "font-semibold");
                                link.classList.remove("text-gray-400", "font-normal");
                            }
                        });
                    }
                });
            }, {
                threshold: 0.6
            }
        );

        sections.forEach((section) => observer.observe(section));
    });
</script>



</body>

</html>
