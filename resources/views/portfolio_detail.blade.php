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
    </style>
</head>
<body class="bg-gradient-footer text-white font-['Inter']">
    <!-- Navigation Header -->


    <!-- Breadcrumb -->
    <nav class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-4 flex justify-center items-center gap-2 pt-24">
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
        @php
            $images = [];
            // Parse images field if it contains multiple images (JSON or comma-separated)
            if (!empty($portfolio->images)) {
                if (is_string($portfolio->images)) {
                    // Try to decode as JSON first
                    $decoded = json_decode($portfolio->images, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        foreach ($decoded as $image) {
                            if (!empty($image)) {
                                $images[] = asset('images/projects/' . $image);
                            }
                        }
                    } else {
                        // If not JSON, try comma-separated
                        $imageArray = explode(',', $portfolio->images);
                        foreach ($imageArray as $image) {
                            $image = trim($image);
                            if (!empty($image)) {
                                $images[] = asset('images/projects/' . $image);
                            }
                        }
                    }
                }
            }
            
            // If images field is empty or has no valid images, use featured_image
            if (empty($images) && !empty($portfolio->featured_image)) {
                $images[] = asset('images/projects/' . $portfolio->featured_image);
            }
        @endphp
        
        @if (!empty($images))
            <div class="w-full max-w-4xl overflow-x-auto snap-x snap-mandatory scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-900">
                <div class="flex flex-row gap-4 min-w-max">
                    @foreach ($images as $index => $image)
                        <img src="{{ $image }}"
                             alt="{{ $portfolio->project_name }} - Image {{ $index + 1 }}"
                             class="w-full max-w-4xl h-auto rounded-3xl snap-center shrink-0 object-cover" 
                             onerror="this.src='{{ asset('images/placeholder/project-placeholder.jpg') }}'" />
                    @endforeach
                </div>
            </div>
        @else
            <img src="{{ asset('images/placeholder/project-placeholder.jpg') }}"
                 alt="{{ $portfolio->project_name ?? 'Portfolio' }} - Placeholder"
                 class="w-full max-w-4xl h-auto rounded-3xl" />
        @endif
        
        <div class="w-full max-w-4xl flex flex-col gap-8">
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
            
            @if (!empty($portfolio->summary_description))
                <div class="flex flex-col gap-3">
                    <h2 class="text-white text-xl sm:text-2xl font-semibold leading-loose">Summary</h2>
                    <p class="text-white text-base sm:text-lg font-normal leading-relaxed">{!! $portfolio->summary_description !!}</p>
                </div>
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