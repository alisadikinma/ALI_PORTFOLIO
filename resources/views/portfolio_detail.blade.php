<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }} - Ali Sadikin</title>
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
    </style>
</head>
<body class="bg-white text-gray-900 font-['Inter']">
    <!-- Navigation Header -->


    <!-- Breadcrumb -->
    <nav class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-4 flex justify-left items-left gap-2 pt-24">
        <a href="{{ url('/') }}#portfolio" class="text-gray-600 text-base font-normal leading-normal tracking-tight hover:text-gray-900">Portfolio</a>
        <span class="text-gray-600 text-base font-medium leading-normal tracking-tight">/</span>
        <span class="text-gray-900 text-base font-medium leading-normal tracking-tight">
            {{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }}
        </span>
    </nav>

    <!-- Debug Section (Simple) -->
    @if(!isset($portfolio))
        <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8">
            <div class="bg-red-800 p-4 rounded mb-4">
                <h2 class="text-red-200 font-bold">❌ Error: Portfolio variable not found</h2>
                <p class="text-red-100 mt-2">URL Slug: {{ request()->route('slug') ?? 'No slug parameter' }}</p>
                <p class="text-red-100">Route Name: {{ request()->route()->getName() ?? 'No route name' }}</p>
            </div>
        </div>
    @endif

    <!-- Project Detail Section -->
    @if(isset($portfolio))
    <section id="project" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-12">       
        <div class="w-full max-w-4xl flex flex-col gap-4">
            <div class="flex flex-col gap-3">
                <h1 class="text-gray-900 text-3xl sm:text-5xl font-semibold leading-tight sm:leading-[48px]">{{ $portfolio->project_name ?? 'Portfolio Project' }}</h1>
                <div class="flex gap-2">
                    @if (!empty($portfolio->project_category))
                        <span class="text-blue-600 text-base font-normal leading-none">{{ $portfolio->project_category }}</span>
                    @endif
                    @if (!empty($portfolio->client_name))
                        @if (!empty($portfolio->project_category))
                            <span class="text-blue-600 text-xs font-normal leading-none">-</span>
                        @endif
                        <span class="text-blue-600 text-base font-normal leading-none">Client: {{ $portfolio->client_name }}</span>
                    @endif
                </div>
                @if (!empty($portfolio->location))
                    <div class="text-gray-600 text-xs font-normal leading-normal">{{ $portfolio->location }}</div>
                @endif
            </div>
            <div class="w-full h-px bg-gray-200"></div>
            
            @if (!empty($portfolio->description))
                <div class="text-gray-700 text-base sm:text-lg font-normal leading-relaxed">{!! $portfolio->description !!}</div>
            @endif
            
            @if (!empty($portfolio->url_project))
                <a href="{{ $portfolio->url_project }}" target="_blank"
                   class="px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 rounded-xl flex items-center gap-2.5 text-white hover:bg-blue-700 transition-colors w-fit">
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
                <div class="w-full h-px bg-gray-200 mt-8"></div>
                <div class="mt-6">
                    <h3 class="text-gray-900 text-xl sm:text-2xl font-semibold leading-tight mb-6">Other Related Projects</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($otherProjects as $project)
                            <div class="group cursor-pointer" onclick="window.location.href='{{ url('/project/' . $project->slug_project) }}'">
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:bg-gray-50 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl">                                    
                                    
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
                                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm px-4 py-2 bg-blue-600 rounded-lg">View Details</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Project Info -->
                                    <div class="p-4">
                                        <h4 class="text-gray-900 text-lg font-semibold mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ $project->project_name }}
                                        </h4>
                                        
                                        @if (!empty($project->client_name))
                                            <p class="text-gray-600 text-sm mb-3">{{ $project->client_name }}</p>
                                        @endif
                                        
                                        <!-- Project Category -->
                                        @if (!empty($project->project_category))
                                            <div class="flex items-center justify-between">
                                                <span class="inline-block bg-blue-100 text-blue-600 text-xs px-3 py-1 rounded-full">
                                                    {{ $project->project_category }}
                                                </span>
                                                
                                                <!-- View Arrow -->
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-blue-600 text-gray-700 hover:text-white rounded-xl transition-all duration-300 border border-gray-300 hover:border-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="font-medium">View All Projects</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
    @else
        <section class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 text-center">
            <h2 class="text-red-600 text-2xl font-bold">Portfolio Not Found</h2>
            <p class="text-red-500 mt-4">The requested portfolio could not be found. Please check the URL and try again.</p>
            <a href="{{ url('/') }}#portfolio" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Back to Portfolio
            </a>
        </section>
    @endif



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