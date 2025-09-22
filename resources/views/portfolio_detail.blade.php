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

    /* Gen Z Enhanced Portfolio Detail Styles */
    .portfolio-detail-container {
        background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .portfolio-detail-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 25% 25%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .project-header {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        padding: 2.5rem;
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .project-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transition: left 0.8s ease;
    }

    .project-header:hover::before {
        left: 100%;
    }

    .project-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #8b5cf6 50%, #ec4899 100%);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 4s ease infinite;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .project-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .project-badge {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: #a78bfa;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .project-badge:hover {
        background: rgba(139, 92, 246, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .project-description {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 2rem;
        margin: 2rem 0;
        backdrop-filter: blur(10px);
        font-family: 'Space Grotesk', sans-serif;
        line-height: 1.7;
        color: #e2e8f0;
    }

    .btn-cyber-modern {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
        background-size: 200% 200%;
        border: none;
        border-radius: 1rem;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        padding: 1rem 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        animation: gradient-shift 4s ease infinite;
    }

    .btn-cyber-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-cyber-modern:hover::before {
        left: 100%;
    }

    .btn-cyber-modern:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 0 20px rgba(236, 72, 153, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
        text-decoration: none;
        color: white;
    }

    .other-projects-section {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        padding: 2.5rem;
        margin: 3rem 0;
        backdrop-filter: blur(20px);
    }

    .other-projects-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #06b6d4 100%);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 3s ease infinite;
        margin-bottom: 2rem;
        text-align: center;
    }

    .project-card-modern {
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.8) 0%, rgba(15, 15, 35, 0.8) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.25rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        group: hover;
    }

    .project-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(139, 92, 246, 0.3);
        box-shadow:
            0 25px 50px rgba(0, 0, 0, 0.5),
            0 0 20px rgba(139, 92, 246, 0.2);
    }

    .project-card-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .project-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .project-card-modern:hover .project-card-image img {
        transform: scale(1.1);
    }

    .project-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.8) 0%, rgba(236, 72, 153, 0.8) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .project-card-modern:hover .project-card-overlay {
        opacity: 1;
    }

    .overlay-content {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        transform: translateY(10px);
        transition: transform 0.3s ease;
    }

    .project-card-modern:hover .overlay-content {
        transform: translateY(0);
    }

    .project-card-content {
        padding: 1.5rem;
    }

    .project-card-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 0.5rem;
        transition: color 0.3s ease;
    }

    .project-card-modern:hover .project-card-title {
        color: #a78bfa;
    }

    .project-card-client {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .project-card-category {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: #67e8f9;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }

    .view-all-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(139, 92, 246, 0.3);
        color: #8b5cf6;
        padding: 0.875rem 2rem;
        border-radius: 1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .view-all-btn:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: rgba(139, 92, 246, 0.6);
        color: #a78bfa;
        transform: translateY(-2px);
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .project-header {
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .project-title {
            font-size: 2rem;
        }

        .project-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .other-projects-section {
            padding: 1.5rem;
        }

        .other-projects-title {
            font-size: 1.5rem;
        }
    }

    /* Enhanced breadcrumb */
    .breadcrumb-modern {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin: 1rem 0;
    }

    .breadcrumb-modern a {
        color: #8b5cf6;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .breadcrumb-modern a:hover {
        color: #a78bfa;
    }

    .breadcrumb-separator {
        color: #64748b;
        margin: 0 0.75rem;
    }

    .breadcrumb-current {
        color: #e2e8f0;
        font-weight: 600;
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

<!-- Enhanced Breadcrumb -->
<div class="portfolio-detail-container">
    <nav class="w-full pt-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 1200px;">
            <div class="breadcrumb-modern">
                <a href="{{ url('/') }}#portfolio" class="hover:text-yellow-400 transition-colors">
                    <i class="fas fa-folder-open mr-2"></i>Portfolio
                </a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">
                    {{ isset($portfolio) ? $portfolio->project_name : 'Portfolio Detail' }}
                </span>
            </div>
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
    <section id="project" class="w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" style="max-width: 1200px;">
            <div class="w-full max-w-5xl mx-auto">
                <!-- Project Header -->
                <div class="project-header">
                    <h1 class="project-title text-3xl sm:text-5xl">{{ $portfolio->project_name ?? 'Portfolio Project' }}</h1>

                    <div class="project-meta">
                        @if (!empty($portfolio->project_category))
                            <span class="project-badge">
                                <i class="fas fa-tag mr-2"></i>{{ $portfolio->project_category }}
                            </span>
                        @endif
                        @if (!empty($portfolio->client_name))
                            <span class="project-badge">
                                <i class="fas fa-user-tie mr-2"></i>{{ $portfolio->client_name }}
                            </span>
                        @endif
                        @if (!empty($portfolio->location))
                            <span class="project-badge">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $portfolio->location }}
                            </span>
                        @endif
                    </div>
                </div>
            
                @if (!empty($portfolio->description))
                    <div class="project-description">
                        <h3 class="text-xl font-semibold mb-4 text-white">
                            <i class="fas fa-info-circle mr-2 text-cyan-400"></i>Project Overview
                        </h3>
                        <div class="prose prose-invert max-w-none">{!! $portfolio->description !!}</div>
                    </div>
                @endif
            
                @if (!empty($portfolio->url_project))
                    <div class="text-center mt-8">
                        <a href="{{ $portfolio->url_project }}" target="_blank" class="btn-cyber-modern">
                            <i class="fas fa-rocket"></i>
                            <span>Launch Live Project</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
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
                <div class="other-projects-section">
                    <h3 class="other-projects-title">Explore More Projects</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($otherProjects as $project)
                            @php
                                $publicPath = env('PUBLIC_PATH', '/');
                                $projectUrl = $publicPath . 'portfolio/' . $project->slug_project;
                            @endphp
                            <div class="project-card-modern" onclick="window.location.href='{{ $projectUrl }}'">
                                <!-- Project Image -->
                                <div class="project-card-image">
                                    @php
                                        $images = $project->images ? json_decode($project->images, true) : [];
                                        $featuredImage = $project->featured_image ?? ($images[0] ?? null);
                                    @endphp

                                    @if ($featuredImage)
                                        <img src="{{ asset('images/projects/' . $featuredImage) }}"
                                             alt="{{ $project->project_name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-900 to-pink-900 flex items-center justify-center">
                                            <i class="fas fa-project-diagram text-4xl text-purple-300"></i>
                                        </div>
                                    @endif

                                    <!-- Modern Overlay -->
                                    <div class="project-card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Project
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Content -->
                                <div class="project-card-content">
                                    <h4 class="project-card-title">
                                        {{ $project->project_name }}
                                    </h4>

                                    @if (!empty($project->client_name))
                                        <p class="project-card-client">
                                            <i class="fas fa-user-tie mr-2"></i>{{ $project->client_name }}
                                        </p>
                                    @endif

                                    @if (!empty($project->project_category))
                                        <div class="flex items-center justify-between">
                                            <span class="project-card-category">
                                                {{ $project->project_category }}
                                            </span>
                                            <i class="fas fa-arrow-right text-purple-400 transition-transform duration-300 transform group-hover:translate-x-2"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- View All Projects Button -->
                    <div class="text-center mt-12">
                        <a href="{{ url('/') }}#portfolio" class="view-all-btn">
                            <i class="fas fa-th-large"></i>
                            <span>Explore All Projects</span>
                            <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-2"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @else
        <section class="w-full py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center" style="max-width: 1200px;">
                <div class="project-header max-w-2xl mx-auto">
                    <div class="text-6xl mb-6">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-4">Portfolio Not Found</h2>
                    <p class="text-gray-300 mb-8 text-lg">The requested portfolio could not be found. Please check the URL and try again.</p>
                    <a href="{{ url('/') }}#portfolio" class="btn-cyber-modern">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Portfolio</span>
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll animation for project cards
    const projectCards = document.querySelectorAll('.project-card-modern');

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);

    projectCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Enhanced click handling with animation
    projectCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('div');
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(139, 92, 246, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${x - 25}px;
                top: ${y - 25}px;
                width: 50px;
                height: 50px;
                pointer-events: none;
            `;

            this.style.position = 'relative';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<style>
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.prose-invert {
    color: #e2e8f0;
}

.prose-invert h1, .prose-invert h2, .prose-invert h3, .prose-invert h4 {
    color: #f8fafc;
}

.prose-invert a {
    color: #8b5cf6;
    text-decoration: none;
}

.prose-invert a:hover {
    color: #a78bfa;
    text-decoration: underline;
}

.prose-invert strong {
    color: #f8fafc;
}

.prose-invert ul, .prose-invert ol {
    color: #e2e8f0;
}
</style>
