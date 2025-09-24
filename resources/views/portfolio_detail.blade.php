@extends('layouts.web')

@section('title', (isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail') . ' - Ali Sadikin')

@php
    // Explicitly set this as a portfolio page for navigation highlighting
    $isPortfolioPage = true;
@endphp

@section('isi')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* FORCE Portfolio menu highlighting on portfolio detail pages */
    /* Target the specific Portfolio menu item in header */
    header nav a[href="/#portfolio"] {
        color: #facc15 !important; /* Force yellow-400 */
        font-weight: 600 !important; /* Force semibold */
    }
    
    /* Additional backup selectors */
    header #nav-menu a[href="/#portfolio"] {
        color: #facc15 !important;
        font-weight: 600 !important;
    }
    
    /* Remove gray colors from Portfolio menu on this page */
    header nav a[href="/#portfolio"].text-gray-400 {
        color: #facc15 !important;
    }
    
    header nav a[href="/#portfolio"].font-normal {
        font-weight: 600 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Force portfolio menu highlighting in HEADER
    console.log('🔍 Portfolio Detail Page Loaded');
    console.log('📍 Current URL:', window.location.href);
    console.log('📍 Current Path:', window.location.pathname);
    
    // Wait a bit for the page to fully load
    setTimeout(function() {
        // Find the Portfolio menu item in the HEADER navigation specifically
        const headerNav = document.querySelector('header #nav-menu');
        
        if (headerNav) {
            console.log('📍 Found header navigation:', headerNav);
            
            const portfolioMenus = headerNav.querySelectorAll('a');
            portfolioMenus.forEach(link => {
                const linkText = link.textContent.trim();
                console.log('📍 Checking menu item:', linkText);
                
                if (linkText === 'Portfolio') {
                    console.log('📍 Found Portfolio menu in header:', link);
                    console.log('📍 Current classes before:', link.className);
                    
                    // Force yellow highlighting by removing gray classes and adding yellow
                    link.classList.remove('text-gray-400', 'font-normal');
                    link.classList.add('text-yellow-400', 'font-semibold');
                    
                    // Also force text color with inline style as backup
                    link.style.color = '#facc15'; // yellow-400
                    link.style.fontWeight = '600'; // semibold
                    
                    console.log('✅ Portfolio menu highlighted!');
                    console.log('📍 New classes after:', link.className);
                    console.log('📍 Style color:', link.style.color);
                }
            });
        } else {
            console.log('❌ Header navigation not found');
        }
        
        // Additional fallback: target all Portfolio links on page
        const allPortfolioLinks = document.querySelectorAll('a');
        allPortfolioLinks.forEach(link => {
            if (link.textContent.trim() === 'Portfolio' && link.closest('header')) {
                link.classList.remove('text-gray-400', 'font-normal');
                link.classList.add('text-yellow-400', 'font-semibold');
                link.style.color = '#facc15';
                link.style.fontWeight = '600';
                console.log('✅ Fallback highlighting applied to Portfolio menu');
            }
        });
        
    }, 100); // Small delay to ensure DOM is ready
});
</script>

<!-- Breadcrumb -->
<nav class="w-full bg-white pt-2">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-left items-left gap-2" style="max-width: 1200px;">
        <a href="{{ url('/') }}#portfolio" class="text-gray-600 text-base font-normal leading-normal tracking-tight hover:text-yellow-600">Portfolio</a>
        <span class="text-gray-600 text-base font-medium leading-normal tracking-tight">/</span>
        <span class="text-black text-base font-medium leading-normal tracking-tight">
            {{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }}
        </span>
    </div>
</nav>

<!-- Debug Section (Simple) -->
@if(!isset($portfolio))
    <div class="w-full bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" style="max-width: 1200px;">
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
                <h2 class="font-bold">❌ Error: Portfolio variable not found</h2>
                <p class="mt-2">URL Slug: {{ request()->route('slug') ?? 'No slug parameter' }}</p>
                <p>Route Name: {{ request()->route()->getName() ?? 'No route name' }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Project Detail Section -->
@if(isset($portfolio))
<section id="project" class="w-full bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col items-center gap-8 sm:gap-12" style="max-width: 1200px;">       
        <div class="w-full max-w-4xl flex flex-col gap-4">
            <div class="flex flex-col gap-3">
                <h1 class="text-black text-3xl sm:text-5xl font-semibold leading-tight sm:leading-[48px]">{{ $portfolio->project_name ?? 'Portfolio Project' }}</h1>
                <div class="flex gap-2">
                    @if (!empty($portfolio->project_category))
                        <span class="text-yellow-600 text-base font-normal leading-none">{{ $portfolio->project_category }}</span>
                    @endif
                    @if (!empty($portfolio->client_name))
                        @if (!empty($portfolio->project_category))
                            <span class="text-yellow-600 text-xs font-normal leading-none">-</span>
                        @endif
                        <span class="text-yellow-600 text-base font-normal leading-none">Client: {{ $portfolio->client_name }}</span>
                    @endif
                </div>
                @if (!empty($portfolio->location))
                    <div class="text-gray-600 text-xs font-normal leading-normal">{{ $portfolio->location }}</div>
                @endif
            </div>
            <div class="w-full h-px bg-gray-300"></div>
            
            @if (!empty($portfolio->description))
                <div class="text-gray-800 text-base sm:text-lg font-normal leading-relaxed">{!! $portfolio->description !!}</div>
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
            
            <!-- Other Projects Section with JSON handling -->
            @php
                // Handle the other_projects JSON field from the current portfolio
                $currentOtherProjects = [];
                if (!empty($portfolio->other_projects)) {
                    try {
                        $currentOtherProjects = json_decode($portfolio->other_projects, true) ?? [];
                    } catch (Exception $e) {
                        $currentOtherProjects = [];
                    }
                }
                
                // If we have other_projects in JSON, find those projects
                $otherProjects = collect();
                if (!empty($currentOtherProjects) && is_array($currentOtherProjects)) {
                    try {
                        $otherProjects = \Illuminate\Support\Facades\DB::table('project')
                            ->whereIn('project_name', $currentOtherProjects)
                            ->where('status', 'Active')
                            ->where('id_project', '!=', $portfolio->id_project)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    } catch (Exception $e) {
                        $otherProjects = collect();
                    }
                } else {
                    // Fallback: Get related projects by category if no JSON data
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
                }
            @endphp
            
            @if ($otherProjects->count() > 0)
                <div class="w-full h-px bg-gray-300 mt-8"></div>
                <div class="mt-6">
                    <h3 class="text-black text-xl sm:text-2xl font-semibold leading-tight mb-6">Other Projects</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($otherProjects as $project)
                            @php
                                $publicPath = env('PUBLIC_PATH', '/');
                                $projectUrl = $publicPath . 'portfolio/' . $project->slug_project;
                            @endphp
                            <div class="group cursor-pointer" onclick="window.location.href='{{ $projectUrl }}'">
                                <div class="bg-white border border-gray-300 rounded-xl overflow-hidden hover:bg-gray-50 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl">                                    
                                    
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
                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <h4 class="text-black text-lg font-semibold mb-2 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                                            {{ $project->project_name }}
                                        </h4>
                                        
                                        @if (!empty($project->client_name))
                                            <p class="text-gray-600 text-sm mb-3">{{ $project->client_name }}</p>
                                        @endif
                                        
                                        <!-- Project Category -->
                                        @if (!empty($project->project_category))
                                            <div class="flex items-center justify-between">
                                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full border border-yellow-200">
                                                    {{ $project->project_category }}
                                                </span>
                                                
                                                <!-- View Arrow -->
                                                <svg class="w-5 h-5 text-gray-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-yellow-400 text-gray-800 hover:text-neutral-900 rounded-xl transition-all duration-300 border border-gray-300 hover:border-yellow-400">
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
    <section class="w-full bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center" style="max-width: 1200px;">
            <h2 class="text-red-600 text-2xl font-bold">Portfolio Not Found</h2>
            <p class="text-red-500 mt-4">The requested portfolio could not be found. Please check the URL and try again.</p>
            <a href="{{ url('/') }}#portfolio" class="inline-block mt-6 px-6 py-3 bg-yellow-400 text-neutral-900 rounded-lg hover:bg-yellow-500 transition-colors">
                Back to Portfolio
            </a>
        </div>
    </section>
@endif
@endsection
