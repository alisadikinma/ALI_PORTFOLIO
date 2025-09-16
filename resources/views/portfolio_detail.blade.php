<?php
use Illuminate\Support\Facades\DB;

$konf = DB::table('setting')->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }} - Ali Sadikin</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/' . $konf->logo_setting) }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
        .backdrop-blur-lg {
            backdrop-filter: blur(8px);
        }
        .backdrop-blur-xl {
            backdrop-filter: blur(12px);
        }
        .scrollbar-thin {
            scrollbar-width: thin;
        }
        .scrollbar-thumb-slate-700 {
            scrollbar-color: #334155 transparent;
        }
        .scrollbar-track-slate-900 {
            /* No additional track styling needed */
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Mobile menu styles */
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
    </style>
</head>
<body class="bg-gradient-footer text-white font-['Inter']">
    <!-- Header -->
    <header class="w-full fixed top-0 left-0 z-50 bg-gradient-footer backdrop-blur-xl">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4" style="max-width: 1200px;">
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
                        class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
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
                        class="text-yellow-400 text-base font-semibold hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
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
                        class="text-gray-400 text-base font-normal hover:text-yellow-400 transition-colors py-2 w-full sm:w-auto">
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
        </div>
    </header>

    <!-- Breadcrumb -->
    <nav class="w-full bg-gradient-footer">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-left items-left gap-2 pt-24" style="max-width: 1200px;">
            <a href="{{ url('/') }}#portfolio" class="text-stone-300 text-base font-normal leading-normal tracking-tight hover:text-yellow-400">Portfolio</a>
            <span class="text-stone-300 text-base font-medium leading-normal tracking-tight">/</span>
            <span class="text-white text-base font-medium leading-normal tracking-tight">
                {{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }}
            </span>
        </div>
    </nav>

    <!-- Debug Section (Simple) -->
    @if(!isset($portfolio))
        <div class="w-full bg-gradient-footer">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" style="max-width: 1200px;">
                <div class="bg-red-800 p-4 rounded mb-4">
                    <h2 class="text-red-200 font-bold">❌ Error: Portfolio variable not found</h2>
                    <p class="text-red-100 mt-2">URL Slug: {{ request()->route('slug') ?? 'No slug parameter' }}</p>
                    <p class="text-red-100">Route Name: {{ request()->route()->getName() ?? 'No route name' }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Project Detail Section -->
    @if(isset($portfolio))
    <section id="project" class="w-full bg-gradient-footer">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col items-center gap-8 sm:gap-12" style="max-width: 1200px;">       
            <div class="w-full max-w-4xl flex flex-col gap-4">
                <div class="flex flex-col gap-3">
                    <h1 class="text-white text-3xl sm:text-5xl font-semibold leading-tight sm:leading-[48px]">{{ $portfolio->project_name ?? 'Portfolio Project' }}</h1>
                    <div class="flex gap-2">
                        @if (!empty($portfolio->project_category))
                            <span class="text-yellow-400 text-base font-normal leading-none">{{ $portfolio->project_category }}</span>
                        @endif
                        @if (!empty($portfolio->client_name))
                            @if (!empty($portfolio->project_category))
                                <span class="text-yellow-400 text-xs font-normal leading-none">-</span>
                            @endif
                            <span class="text-yellow-400 text-base font-normal leading-none">Client: {{ $portfolio->client_name }}</span>
                        @endif
                    </div>
                    @if (!empty($portfolio->location))
                        <div class="text-gray-400 text-xs font-normal leading-normal">{{ $portfolio->location }}</div>
                    @endif
                </div>
                <div class="w-full h-px bg-slate-800"></div>
                
                @if (!empty($portfolio->description))
                    <div class="text-zinc-400 text-base sm:text-lg font-normal leading-relaxed">{!! $portfolio->description !!}</div>
                @endif
                
                @if (!empty($portfolio->url_project))
                    <a href="{{ $portfolio->url_project }}" target="_blank"
                       class="px-6 sm:px-8 py-3 sm:py-4 bg-yellow-400 rounded-xl flex items-center gap-2.5 text-neutral-900 hover:bg-yellow-500 transition-colors w-fit">
                        <span class="text-base font-semibold capitalize leading-tight tracking-tight">View Live Project</span>
                        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @endif
                
                <!-- Other Related Projects Section - Fixed -->
                @php
                    // Get other projects (excluding current one)
                    try {
                        $otherProjects = \Illuminate\Support\Facades\DB::table('project')
                            ->where('status', 'Active')
                            ->where('id_project', '!=', $portfolio->id_project)
                            ->orderByRaw("CASE WHEN project_category = ? THEN 0 ELSE 1 END", [$portfolio->project_category])
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();
                    } catch (Exception $e) {
                        $otherProjects = collect();
                    }
                @endphp
                
                @if ($otherProjects->count() > 0)
                    <div class="w-full h-px bg-slate-800 mt-8"></div>
                    <div class="mt-6">
                        <h3 class="text-white text-xl sm:text-2xl font-semibold leading-tight mb-6">Other Related Projects</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($otherProjects as $project)
                                <div class="group cursor-pointer" onclick="window.location.href='{{ url('/project/' . $project->slug_project) }}'">
                                    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden hover:bg-slate-700 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl">                                    
                                        
                                        <!-- Project Image -->
                                        <div class="relative overflow-hidden h-48">
                                            @php
                                                $images = $project->images ? json_decode($project->images, true) : [];
                                                $featuredImage = $project->featured_image ?? ($images[0] ?? null);
                                            @endphp
                                            
                                            @if ($featuredImage)
                                                <img src="{{ asset('images/projects/' . $featuredImage) }}" 
                                                     alt="{{ $project->project_name }}"
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <!-- Overlay -->
                                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm px-4 py-2 bg-yellow-400 text-neutral-900 rounded-lg">View Details</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Project Info -->
                                        <div class="p-4">
                                            <h4 class="text-white text-lg font-semibold mb-2 line-clamp-2 group-hover:text-yellow-400 transition-colors">
                                                {{ $project->project_name }}
                                            </h4>
                                            
                                            @if (!empty($project->client_name))
                                                <p class="text-gray-400 text-sm mb-3">{{ $project->client_name }}</p>
                                            @endif
                                            
                                            <!-- Project Category -->
                                            @if (!empty($project->project_category))
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-block bg-yellow-400/20 text-yellow-400 text-xs px-3 py-1 rounded-full">
                                                        {{ $project->project_category }}
                                                    </span>
                                                    
                                                    <!-- View Arrow -->
                                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- View All Projects Button -->
                        <div class="text-center mt-8">
                            <a href="{{ url('/') }}#portfolio" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-slate-700 hover:bg-yellow-400 text-white hover:text-neutral-900 rounded-xl transition-all duration-300 border border-slate-600 hover:border-yellow-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span class="font-medium">View All Projects</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @else
        <section class="w-full bg-gradient-footer">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center" style="max-width: 1200px;">
                <h2 class="text-red-400 text-2xl font-bold">Portfolio Not Found</h2>
                <p class="text-red-300 mt-4">The requested portfolio could not be found. Please check the URL and try again.</p>
                <a href="{{ url('/') }}#portfolio" class="inline-block mt-6 px-6 py-3 bg-yellow-400 text-neutral-900 rounded-lg hover:bg-yellow-500 transition-colors">
                    Back to Portfolio
                </a>
            </div>
        </section>
    @endif

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
                if (!document.getElementById('nav-menu').classList.contains('sm:flex')) {
                    toggleMenu();
                }
            });
        });
    </script>
</body>
</html>