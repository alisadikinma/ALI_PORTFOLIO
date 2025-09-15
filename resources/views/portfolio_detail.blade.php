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
<body class="bg-gradient-footer text-white font-['Inter']">
    <!-- Navigation Header -->


    <!-- Breadcrumb -->
    <nav class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-4 flex justify-left items-left gap-2 pt-24">
        <a href="{{ url('/') }}#portfolio" class="text-stone-300 text-base font-normal leading-normal tracking-tight">Portfolio</a>
        <span class="text-stone-300 text-base font-medium leading-normal tracking-tight">/</span>
        <span class="text-white text-base font-medium leading-normal tracking-tight">
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
                    <div class="text-neutral-400 text-xs font-normal leading-normal">{{ $portfolio->location }}</div>
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
            
            @if (!empty($portfolio->other_projects))
                <div class="w-full h-px bg-slate-800 mt-8"></div>
                <div class="mt-6">
                    <h3 class="text-white text-xl sm:text-2xl font-semibold leading-tight mb-4">Other Related Projects</h3>
                    <div class="bg-slate-800/30 rounded-xl p-4 sm:p-6">
                        <p class="text-zinc-300 text-base leading-relaxed">{{ $portfolio->other_projects }}</p>
                        @php
                            // Try to find related projects based on the other_projects field
                            $relatedProjects = collect();
                            if (!empty($portfolio->other_projects)) {
                                try {
                                    $relatedProjects = \Illuminate\Support\Facades\DB::table('project')
                                        ->where('status', 'Active')
                                        ->where('id_project', '!=', $portfolio->id_project)
                                        ->where(function($query) use ($portfolio) {
                                            $query->where('project_name', 'LIKE', '%' . $portfolio->other_projects . '%')
                                                  ->orWhere('client_name', 'LIKE', '%' . $portfolio->other_projects . '%')
                                                  ->orWhere('project_category', 'LIKE', '%' . $portfolio->other_projects . '%');
                                        })
                                        ->limit(3)
                                        ->get();
                                } catch (Exception $e) {
                                    $relatedProjects = collect();
                                }
                            }
                        @endphp
                        
                        @if ($relatedProjects->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                                @foreach ($relatedProjects as $relatedProject)
                                    <a href="{{ url('/project/' . $relatedProject->slug_project) }}" 
                                       class="block bg-slate-700/50 rounded-lg p-4 hover:bg-slate-700/70 transition-colors">
                                        @php
                                            $images = $relatedProject->images ? json_decode($relatedProject->images, true) : [];
                                            $featuredImage = $relatedProject->featured_image ?? ($images[0] ?? null);
                                        @endphp
                                        
                                        @if ($featuredImage)
                                            <img src="{{ asset('images/projects/' . $featuredImage) }}" 
                                                 alt="{{ $relatedProject->project_name }}"
                                                 class="w-full h-32 object-cover rounded-lg mb-3">
                                        @endif
                                        
                                        <h4 class="text-white text-sm font-semibold mb-2 line-clamp-2">{{ $relatedProject->project_name }}</h4>
                                        <p class="text-zinc-400 text-xs mb-2">{{ $relatedProject->client_name }}</p>
                                        <span class="inline-block bg-yellow-400/20 text-yellow-400 text-xs px-2 py-1 rounded">
                                            {{ $relatedProject->project_category }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
    @else
        <section class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 text-center">
            <h2 class="text-red-400 text-2xl font-bold">Portfolio Not Found</h2>
            <p class="text-red-300 mt-4">The requested portfolio could not be found. Please check the URL and try again.</p>
            <a href="{{ url('/') }}#portfolio" class="inline-block mt-6 px-6 py-3 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-500 transition-colors">
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