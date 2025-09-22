@extends('layouts.web')

@section('title', 'Portfolio - Ali Sadikin')

@section('isi')
<!-- Gen Z Enhanced Portfolio Page -->
<section class="min-h-screen portfolio-showcase-container" style="padding-top: 120px;">
    <div class="container mx-auto" style="max-width: 1400px;">

        <!-- Animated Page Header -->
        <div class="text-center mb-16 relative">
            <div class="hero-badge">
                <i class="fas fa-rocket mr-2"></i>
                <span>Creative Portfolio</span>
            </div>
            <h1 class="portfolio-main-title">
                <span class="title-line">My</span>
                <span class="title-line gradient-text">Portfolio</span>
            </h1>
            <p class="portfolio-subtitle">Discover innovative projects crafted with passion and precision</p>
            <div class="floating-elements">
                <div class="floating-icon floating-icon-1"><i class="fas fa-code"></i></div>
                <div class="floating-icon floating-icon-2"><i class="fas fa-palette"></i></div>
                <div class="floating-icon floating-icon-3"><i class="fas fa-lightbulb"></i></div>
            </div>
        </div>

        <!-- Enhanced Controls Section -->
        <div class="controls-section mb-16">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-8">
                <!-- Category Filter Buttons - Enhanced Design -->
                <div class="filter-container">
                    <div class="filter-label">
                        <i class="fas fa-filter mr-2"></i>
                        <span>Filter by Category</span>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <button class="filter-btn-modern active" data-filter="all">
                            <i class="fas fa-th-large mr-2"></i>
                            <span>All Projects</span>
                        </button>
                        @if(isset($projectCategories) && count($projectCategories) > 0)
                            @foreach ($projectCategories as $category)
                            <button class="filter-btn-modern"
                                    data-filter="{{ $category->lookup_code }}"
                                    data-category-id="{{ $category->id }}"
                                    title="{{ $category->lookup_description }}">
                                <span class="category-icon">{{ $category->lookup_icon }}</span>
                                <span>{{ $category->lookup_name }}</span>
                            </button>
                            @endforeach
                        @else
                            <!-- Enhanced Fallback buttons -->
                            <button class="filter-btn-modern" data-filter="mobile-app">
                                <i class="fas fa-mobile-alt mr-2"></i>
                                <span>Mobile Apps</span>
                            </button>
                            <button class="filter-btn-modern" data-filter="web-app">
                                <i class="fas fa-laptop-code mr-2"></i>
                                <span>Web Apps</span>
                            </button>
                            <button class="filter-btn-modern" data-filter="ai-ml">
                                <i class="fas fa-robot mr-2"></i>
                                <span>AI/ML</span>
                            </button>
                            <button class="filter-btn-modern" data-filter="iot">
                                <i class="fas fa-wifi mr-2"></i>
                                <span>IoT</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Enhanced Sort Dropdown -->
                <div class="sort-container">
                    <div class="sort-label">
                        <i class="fas fa-sort mr-2"></i>
                        <span>Sort Projects</span>
                    </div>
                    <div class="custom-select-wrapper">
                        <select id="sortSelect" class="custom-select">
                            <option value="newest">🆕 Newest First</option>
                            <option value="oldest">📅 Oldest First</option>
                            <option value="name-asc">🔤 Name A-Z</option>
                            <option value="name-desc">🔤 Name Z-A</option>
                            <option value="sequence">📊 Display Order</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Projects Grid -->
        <div class="projects-grid-wrapper">
            <div id="portfolioGrid" class="projects-grid">
                <!-- Projects will be loaded here via JavaScript -->
            </div>
        </div>

        <!-- Enhanced Loading State -->
        <div id="loadingState" class="loading-state hidden">
            <div class="loading-animation">
                <div class="loading-spinner"></div>
                <div class="loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <p class="loading-text">Discovering amazing projects...</p>
        </div>

        <!-- Enhanced No Results State -->
        <div id="noResults" class="no-results-state hidden">
            <div class="no-results-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3 class="no-results-title">No Projects Found</h3>
            <p class="no-results-subtitle">Try adjusting your filters or explore different categories.</p>
            <button class="btn-reset-filters" onclick="portfolioManager.resetFilters()">
                <i class="fas fa-refresh mr-2"></i>
                Reset Filters
            </button>
        </div>

        <!-- Enhanced Pagination -->
        <div id="paginationContainer" class="pagination-wrapper">
            <!-- Pagination will be generated here -->
        </div>
    </div>
</section>

<style>
/* Gen Z Enhanced Portfolio Styles */
.portfolio-showcase-container {
    background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.portfolio-showcase-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 60%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

/* Hero Section */
.hero-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 2rem;
    padding: 0.75rem 1.5rem;
    color: #a78bfa;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    animation: float-gentle 6s ease-in-out infinite;
}

.portfolio-main-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 900;
    font-size: clamp(3rem, 8vw, 6rem);
    line-height: 1.1;
    margin-bottom: 1.5rem;
    position: relative;
}

.title-line {
    display: block;
    color: white;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.gradient-text {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 50%, #06b6d4 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradient-shift 4s ease infinite;
}

.portfolio-subtitle {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.25rem;
    color: #94a3b8;
    font-weight: 500;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.floating-icon {
    position: absolute;
    width: 3rem;
    height: 3rem;
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a78bfa;
    font-size: 1.25rem;
    backdrop-filter: blur(10px);
}

.floating-icon-1 {
    top: 20%;
    left: 10%;
    animation: float-1 8s ease-in-out infinite;
}

.floating-icon-2 {
    top: 30%;
    right: 15%;
    animation: float-2 10s ease-in-out infinite;
}

.floating-icon-3 {
    bottom: 20%;
    left: 20%;
    animation: float-3 12s ease-in-out infinite;
}

/* Controls Section */
.controls-section {
    background: rgba(255, 255, 255, 0.02);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.5rem;
    padding: 2rem;
    margin-bottom: 3rem;
}

.filter-container, .sort-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.filter-label, .sort-label {
    color: #e2e8f0;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.filter-btn-modern {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    padding: 0.875rem 1.5rem;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.filter-btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.2), transparent);
    transition: left 0.5s ease;
}

.filter-btn-modern:hover::before {
    left: 100%;
}

.filter-btn-modern.active,
.filter-btn-modern:hover {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    border-color: rgba(139, 92, 246, 0.5);
    color: white;
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.4), 0 0 40px rgba(236, 72, 153, 0.2);
}

.category-icon {
    font-size: 1rem;
}

/* Custom Select */
.custom-select-wrapper {
    position: relative;
    min-width: 200px;
}

.custom-select {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    color: white;
    padding: 0.875rem 3rem 0.875rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    width: 100%;
    appearance: none;
    cursor: pointer;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.custom-select:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}

.select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    transition: transform 0.3s ease;
}

.custom-select:focus + .select-arrow {
    transform: translateY(-50%) rotate(180deg);
    color: #8b5cf6;
}

/* Dynamic category colors from lookup data */
@if(isset($projectCategories) && count($projectCategories) > 0)
    @foreach ($projectCategories as $category)
    .category-{{ $category->lookup_code }} {
        background: {{ $category->lookup_color ?? 'linear-gradient(135deg, #8b5cf6, #ec4899)' }};
    }
    @endforeach
@endif

/* Projects Grid */
.projects-grid-wrapper {
    position: relative;
    margin-bottom: 4rem;
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    position: relative;
}

/* Enhanced Project Cards */
.project-card {
    background: linear-gradient(135deg, rgba(26, 26, 46, 0.8) 0%, rgba(15, 15, 35, 0.9) 100%);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.5rem;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    cursor: pointer;
    group: hover;
}

.project-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.project-card:hover::before {
    opacity: 1;
}

.project-card:hover {
    transform: translateY(-12px) scale(1.02);
    border-color: rgba(139, 92, 246, 0.3);
    box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.5),
        0 0 30px rgba(139, 92, 246, 0.2),
        0 0 60px rgba(236, 72, 153, 0.1);
}

.card-image-section {
    height: 260px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.project-card:hover .card-image {
    transform: scale(1.15) rotate(2deg);
}

.card-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.8) 0%, rgba(236, 72, 153, 0.8) 100%);
    opacity: 0;
    transition: all 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
}

.project-card:hover .card-image-overlay {
    opacity: 1;
}

.overlay-badge {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 2rem;
    padding: 1rem 2rem;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    transform: translateY(20px);
    transition: transform 0.4s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.project-card:hover .overlay-badge {
    transform: translateY(0);
}

.card-content {
    padding: 2rem;
    position: relative;
}

.project-title {
    color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.3;
    transition: all 0.3s ease;
}

.project-card:hover .project-title {
    background: linear-gradient(135deg, #ffffff 0%, #8b5cf6 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.project-category {
    background: rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.3);
    color: #67e8f9;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.375rem 1rem;
    border-radius: 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.project-card:hover .project-category {
    background: rgba(6, 182, 212, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
}

.project-description {
    color: #cbd5e1;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.project-client {
    color: #94a3b8;
    font-size: 0.8rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.view-project-btn {
    background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
    background-size: 200% 200%;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    justify-content: center;
    border: none;
    cursor: pointer;
    width: 100%;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: gradient-shift 4s ease infinite;
}

.view-project-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.view-project-btn:hover::before {
    left: 100%;
}

.view-project-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 0 20px rgba(236, 72, 153, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
    background-position: 100% 0;
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 4rem 2rem;
}

.loading-animation {
    position: relative;
    margin-bottom: 2rem;
}

.loading-spinner {
    width: 4rem;
    height: 4rem;
    border: 3px solid rgba(139, 92, 246, 0.1);
    border-top: 3px solid #8b5cf6;
    border-radius: 50%;
    margin: 0 auto 1rem;
    animation: spin 1s linear infinite;
}

.loading-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.loading-dots span {
    width: 0.75rem;
    height: 0.75rem;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    border-radius: 50%;
    animation: loading-bounce 1.4s ease-in-out infinite both;
}

.loading-dots span:nth-child(1) { animation-delay: -0.32s; }
.loading-dots span:nth-child(2) { animation-delay: -0.16s; }

.loading-text {
    color: #e2e8f0;
    font-size: 1.125rem;
    font-weight: 500;
    font-family: 'Space Grotesk', sans-serif;
}

/* No Results State */
.no-results-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.5rem;
    backdrop-filter: blur(20px);
    margin: 2rem 0;
}

.no-results-icon {
    width: 5rem;
    height: 5rem;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: #8b5cf6;
    font-size: 2rem;
    animation: float-gentle 4s ease-in-out infinite;
}

.no-results-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.no-results-subtitle {
    color: #94a3b8;
    font-size: 1.125rem;
    margin-bottom: 2rem;
    font-family: 'Space Grotesk', sans-serif;
}

.btn-reset-filters {
    background: rgba(139, 92, 246, 0.1);
    border: 2px solid rgba(139, 92, 246, 0.3);
    color: #8b5cf6;
    padding: 0.875rem 2rem;
    border-radius: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.btn-reset-filters:hover {
    background: rgba(139, 92, 246, 0.2);
    border-color: rgba(139, 92, 246, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
}

/* Enhanced Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem;
    margin-top: 3rem;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.02);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.5rem;
}

.pagination-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.pagination-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.2), transparent);
    transition: left 0.5s ease;
}

.pagination-btn:hover::before {
    left: 100%;
}

.pagination-btn:hover:not(:disabled) {
    background: rgba(139, 92, 246, 0.1);
    border-color: rgba(139, 92, 246, 0.3);
    color: #a78bfa;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(139, 92, 246, 0.2);
}

.pagination-btn.active {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    border-color: rgba(139, 92, 246, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
}

.pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
}

.pagination-btn:disabled:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    transform: none;
    box-shadow: none;
}

/* Enhanced Animations */
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float-gentle {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-8px) rotate(1deg); }
    66% { transform: translateY(4px) rotate(-1deg); }
}

@keyframes float-1 {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    25% { transform: translateY(-10px) translateX(5px); }
    50% { transform: translateY(5px) translateX(-3px); }
    75% { transform: translateY(-5px) translateX(8px); }
}

@keyframes float-2 {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    20% { transform: translateY(8px) translateX(-6px); }
    40% { transform: translateY(-5px) translateX(4px); }
    60% { transform: translateY(10px) translateX(-2px); }
    80% { transform: translateY(-8px) translateX(7px); }
}

@keyframes float-3 {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    30% { transform: translateY(-12px) translateX(8px); }
    60% { transform: translateY(6px) translateX(-5px); }
    90% { transform: translateY(-4px) translateX(3px); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes loading-bounce {
    0%, 80%, 100% {
        transform: scale(0);
    }
    40% {
        transform: scale(1);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.fade-in-up:nth-child(1) { animation-delay: 0.1s; }
.fade-in-up:nth-child(2) { animation-delay: 0.2s; }
.fade-in-up:nth-child(3) { animation-delay: 0.3s; }
.fade-in-up:nth-child(4) { animation-delay: 0.4s; }
.fade-in-up:nth-child(5) { animation-delay: 0.5s; }
.fade-in-up:nth-child(6) { animation-delay: 0.6s; }

/* Responsive Design */
@media (max-width: 1024px) {
    .projects-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .controls-section {
        padding: 1.5rem;
    }

    .filter-container, .sort-container {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .portfolio-main-title {
        font-size: clamp(2.5rem, 10vw, 4rem);
    }

    .portfolio-subtitle {
        font-size: 1rem;
        padding: 0 1rem;
    }

    .projects-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .card-image-section {
        height: 220px;
    }

    .card-content {
        padding: 1.5rem;
    }

    .project-title {
        font-size: 1.25rem;
    }

    .filter-btn-modern {
        padding: 0.75rem 1.25rem;
        font-size: 0.8rem;
    }

    .controls-section {
        flex-direction: column;
        gap: 2rem;
        padding: 1.5rem 1rem;
    }

    .floating-icon {
        display: none;
    }

    .pagination-wrapper {
        padding: 1rem;
        gap: 0.5rem;
    }

    .pagination-btn {
        padding: 0.5rem 0.75rem;
        min-width: 2.5rem;
        height: 2.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .hero-badge {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    .portfolio-main-title {
        font-size: clamp(2rem, 12vw, 3rem);
        margin-bottom: 1rem;
    }

    .portfolio-subtitle {
        font-size: 0.9rem;
    }

    .controls-section {
        margin-bottom: 2rem;
    }

    .filter-btn-modern {
        padding: 0.625rem 1rem;
        font-size: 0.75rem;
        flex: 1;
        min-width: 0;
    }

    .projects-grid {
        gap: 1rem;
    }

    .card-content {
        padding: 1.25rem;
    }
}
</style>

<script>
// Gen Z Enhanced Portfolio Management System
class PortfolioManager {
    constructor() {
        this.projects = [];
        this.filteredProjects = [];
        this.currentPage = 1;
        this.projectsPerPage = 9;
        this.currentFilter = 'all';
        this.currentSort = 'newest';
        this.categoryLookup = {};
        
        this.init();
    }
    
    async init() {
        await this.loadProjectCategories();
        await this.loadProjects();
        this.bindEvents();
        this.renderProjects();
    }
    
    async loadProjectCategories() {
        // Load category lookup data from server
        this.categoryLookup = @json($projectCategories ?? []).reduce((acc, cat) => {
            acc[cat.id] = {
                name: cat.lookup_name,
                code: cat.lookup_code,
                icon: cat.lookup_icon,
                color: cat.lookup_color,
                description: cat.lookup_description
            };
            return acc;
        }, {});
    }
    
    async loadProjects() {
        // Use projects data from controller instead of AJAX
        this.projects = @json($projects ?? []);
        this.filteredProjects = [...this.projects];
        
        console.log('Projects loaded from controller:', this.projects.length);
    }
    
    async loadSampleData() {
        // Fallback: Use real sample data from database structure
        // Simulate API delay
        await this.simulateDelay(500);
        
        // No sample data - let API handle this
        this.projects = [];
        this.filteredProjects = [];
    }
    
    simulateDelay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    bindEvents() {
        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                
                // Apply filter
                this.currentFilter = e.target.dataset.filter;
                this.currentPage = 1;
                this.applyFiltersAndSort();
            });
        });
        
        // Sort dropdown
        document.getElementById('sortSelect').addEventListener('change', (e) => {
            this.currentSort = e.target.value;
            this.currentPage = 1;
            this.applyFiltersAndSort();
        });
    }
    
    applyFiltersAndSort() {
        // Apply filters
        if (this.currentFilter === 'all') {
            this.filteredProjects = [...this.projects];
        } else {
            // Filter by project_category (since we don't have lookup table integration)
            this.filteredProjects = this.projects.filter(project => {
                return project.project_category && 
                       project.project_category.toLowerCase().includes(this.currentFilter.toLowerCase());
            });
        }
        
        // Apply sorting
        this.filteredProjects.sort((a, b) => {
            switch (this.currentSort) {
                case 'newest':
                    return new Date(b.created_at) - new Date(a.created_at);
                case 'oldest':
                    return new Date(a.created_at) - new Date(b.created_at);
                case 'name-asc':
                    return a.project_name.localeCompare(b.project_name);
                case 'name-desc':
                    return b.project_name.localeCompare(a.project_name);
                case 'sequence':
                    return (a.sequence || 999) - (b.sequence || 999);
                default:
                    return 0;
            }
        });
        
        this.renderProjects();
    }
    
    renderProjects() {
        const grid = document.getElementById('portfolioGrid');
        const noResults = document.getElementById('noResults');
        
        if (this.filteredProjects.length === 0) {
            grid.innerHTML = '';
            noResults.classList.remove('hidden');
            document.getElementById('paginationContainer').innerHTML = '';
            return;
        }
        
        noResults.classList.add('hidden');
        
        // Calculate pagination
        const totalPages = Math.ceil(this.filteredProjects.length / this.projectsPerPage);
        const startIndex = (this.currentPage - 1) * this.projectsPerPage;
        const endIndex = startIndex + this.projectsPerPage;
        const projectsToShow = this.filteredProjects.slice(startIndex, endIndex);
        
        // Render projects
        grid.innerHTML = projectsToShow.map((project, index) => this.createProjectCard(project, index)).join('');
        
        // Render pagination
        this.renderPagination(totalPages);
        
        // Add animation classes
        setTimeout(() => {
            grid.querySelectorAll('.project-card').forEach((card, index) => {
                card.classList.add('fade-in-up');
                card.style.animationDelay = `${index * 0.1}s`;
            });
        }, 50);
    }
    
    createProjectCard(project, index) {
        const categoryName = project.project_category || project.category_name || 'General';
        const categoryIcon = this.getCategoryIcon(categoryName);
        const categoryColor = this.getCategoryColor(categoryName);

        // Enhanced image section with modern overlay
        let imageSection = '';
        if (project.featured_image) {
            const imageUrl = `{{ asset('images/projects') }}/${project.featured_image}`;
            imageSection = `
                <img src="${imageUrl}"
                     alt="${project.project_name}"
                     class="card-image"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="this.nextElementSibling.style.display='none';">
                <div class="card-image-overlay">
                    <div class="overlay-badge">
                        <i class="fas fa-eye"></i>
                        <span>View Project</span>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-purple-600 to-pink-600" style="display: none;">
                    <div class="text-center text-white p-6">
                        <i class="fas fa-project-diagram text-4xl mb-4"></i>
                        <h3 class="text-lg font-bold">${project.project_name}</h3>
                    </div>
                </div>
            `;
        } else {
            imageSection = `
                <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-purple-600 to-pink-600">
                    <div class="text-center text-white p-6">
                        <i class="fas fa-project-diagram text-4xl mb-4 opacity-80"></i>
                        <h3 class="text-lg font-bold opacity-90">${project.project_name}</h3>
                        <p class="text-sm opacity-70 mt-2">${categoryName}</p>
                    </div>
                </div>
                <div class="card-image-overlay">
                    <div class="overlay-badge">
                        <i class="fas fa-eye"></i>
                        <span>View Project</span>
                    </div>
                </div>
            `;
        }

        return `
            <div class="project-card fade-in-up" style="animation-delay: ${index * 0.1}s">
                <div class="card-image-section">
                    ${imageSection}
                </div>
                <div class="card-content">
                    <div class="project-category">
                        ${categoryIcon} ${categoryName}
                    </div>
                    <h3 class="project-title">${project.project_name}</h3>
                    ${project.client_name ? `<div class="project-client"><i class="fas fa-user-tie"></i> ${project.client_name}${project.location ? ' • ' + project.location : ''}</div>` : ''}
                    <p class="project-description">${project.summary_description || project.description || 'Innovative project crafted with modern technologies and creative solutions.'}</p>
                    <button class="view-project-btn ripple-effect" onclick="portfolioManager.viewProject('${project.slug_project}')">
                        <i class="fas fa-rocket"></i>
                        <span>Explore Project</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        `;
    }
    
    getCategoryIcon(categoryName) {
        const categoryLower = categoryName.toLowerCase();
        if (categoryLower.includes('mobile') || categoryLower.includes('app')) return '📱';
        if (categoryLower.includes('web')) return '💻';
        if (categoryLower.includes('ai') || categoryLower.includes('ml')) return '🤖';
        if (categoryLower.includes('iot')) return '🌐';
        if (categoryLower.includes('automation')) return '⚙️';
        return '🚀'; // default
    }
    
    getCategoryColor(categoryName) {
        const categoryLower = categoryName.toLowerCase();
        if (categoryLower.includes('mobile') || categoryLower.includes('app')) return '#3b82f6';
        if (categoryLower.includes('web')) return '#10b981';
        if (categoryLower.includes('ai') || categoryLower.includes('ml')) return '#8b5cf6';
        if (categoryLower.includes('iot')) return '#f59e0b';
        if (categoryLower.includes('automation')) return '#ef4444';
        return '#6b7280'; // default gray
    }
    
    darkenColor(color, percent) {
        // Simple color darkening function
        if (color.startsWith('#')) {
            const num = parseInt(color.slice(1), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return `#${(0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1)}`;
        }
        return color;
    }
    
    renderPagination(totalPages) {
        const container = document.getElementById('paginationContainer');

        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Previous button with enhanced styling
        paginationHTML += `
            <button class="pagination-btn ${this.currentPage === 1 ? 'opacity-40 cursor-not-allowed' : ''}"
                    onclick="portfolioManager.goToPage(${this.currentPage - 1})"
                    ${this.currentPage === 1 ? 'disabled' : ''}
                    title="Previous page">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;

        // Page numbers with smart ellipsis
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(totalPages, this.currentPage + 2);

        if (startPage > 1) {
            paginationHTML += `<button class="pagination-btn" onclick="portfolioManager.goToPage(1)" title="Go to page 1">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<span class="text-gray-400 px-2 flex items-center"><i class="fas fa-ellipsis-h text-xs"></i></span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="pagination-btn ${i === this.currentPage ? 'active' : ''}"
                        onclick="portfolioManager.goToPage(${i})"
                        title="Go to page ${i}">
                    ${i}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<span class="text-gray-400 px-2 flex items-center"><i class="fas fa-ellipsis-h text-xs"></i></span>`;
            }
            paginationHTML += `<button class="pagination-btn" onclick="portfolioManager.goToPage(${totalPages})" title="Go to page ${totalPages}">${totalPages}</button>`;
        }

        // Next button with enhanced styling
        paginationHTML += `
            <button class="pagination-btn ${this.currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : ''}"
                    onclick="portfolioManager.goToPage(${this.currentPage + 1})"
                    ${this.currentPage === totalPages ? 'disabled' : ''}
                    title="Next page">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;

        container.innerHTML = paginationHTML;
    }
    
    goToPage(page) {
        const totalPages = Math.ceil(this.filteredProjects.length / this.projectsPerPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.renderProjects();
            
            // Smooth scroll to top of grid
            document.getElementById('portfolioGrid').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }
    
    viewProject(slug) {
        // Enhanced redirect with animation
        const publicPath = '{{ env("PUBLIC_PATH", "/") }}';

        // Add loading state to clicked card
        event.target.closest('.project-card').style.transform = 'scale(0.95)';
        event.target.closest('.project-card').style.opacity = '0.7';

        // Navigate after brief animation
        setTimeout(() => {
            window.location.href = `${publicPath}portfolio/${slug}`;
        }, 150);
    }

    resetFilters() {
        // Reset all filters to default
        this.currentFilter = 'all';
        this.currentSort = 'newest';
        this.currentPage = 1;

        // Update UI
        document.querySelectorAll('.filter-btn-modern').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector('[data-filter="all"]').classList.add('active');
        document.getElementById('sortSelect').value = 'newest';

        // Re-render
        this.applyFiltersAndSort();
    }
}

// Enhanced Portfolio Manager Initialization
let portfolioManager;
document.addEventListener('DOMContentLoaded', () => {
    portfolioManager = new PortfolioManager();

    // Add smooth scroll to top functionality
    window.addEventListener('scroll', () => {
        const scrollBtn = document.getElementById('scrollToTop');
        if (window.pageYOffset > 300) {
            if (!scrollBtn) {
                createScrollToTopButton();
            }
        } else if (scrollBtn) {
            scrollBtn.remove();
        }
    });

    // Add ripple effect to buttons
    addRippleEffect();
});

function createScrollToTopButton() {
    const btn = document.createElement('button');
    btn.id = 'scrollToTop';
    btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    btn.className = 'fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 z-50 flex items-center justify-center';
    btn.style.animation = 'fadeInUp 0.3s ease';
    btn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
    document.body.appendChild(btn);
}

function addRippleEffect() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ripple-effect')) {
            const button = e.target.closest('.ripple-effect');
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                width: ${size}px;
                height: ${size}px;
                pointer-events: none;
            `;

            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        }
    });
}

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize Portfolio Manager
portfolioManager = null;
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        portfolioManager = new PortfolioManager();
    });
} else {
    portfolioManager = new PortfolioManager();
}
</script>
@endsection