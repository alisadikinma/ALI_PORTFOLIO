@extends('layouts.web')

@section('title', 'All Projects - Portfolio')

@section('isi')
<div class="portfolio-listing-page">
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">All Projects</h1>
                <p class="hero-subtitle">Explore my complete portfolio of AI, web development, and digital innovation projects</p>
            </div>
        </div>
    </div>

    <div class="projects-section">
        <div class="container">
            <div class="projects-filters">
                <button class="filter-btn active" data-filter="all">All Projects</button>
                @php
                    $categories = $projects->pluck('project_category')->unique()->values();
                @endphp
                @foreach($categories as $category)
                    <button class="filter-btn" data-filter="{{ Str::slug($category) }}">{{ $category }}</button>
                @endforeach
            </div>

            <div class="projects-grid">
                @forelse($projects as $project)
                    <div class="project-card" data-category="{{ Str::slug($project->project_category) }}">
                        <div class="project-image">
                            @php
                                $images = $project->images ? json_decode($project->images, true) : [];
                                $featuredImage = $project->featured_image ?? ($images[0] ?? null);
                            @endphp
                            @if($featuredImage)
                                <img src="{{ asset('images/projects/' . $featuredImage) }}" alt="{{ $project->project_name }}" loading="lazy">
                            @else
                                <img src="{{ asset('images/placeholder/project-placeholder.jpg') }}" alt="{{ $project->project_name }}" loading="lazy">
                            @endif
                            <div class="project-overlay">
                                <a href="{{ route('project.public.show', $project->slug_project) }}" class="view-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View Project
                                </a>
                            </div>
                        </div>
                        <div class="project-content">
                            <div class="project-category">{{ $project->project_category }}</div>
                            <h3 class="project-title">{{ $project->project_name }}</h3>
                            <p class="project-summary">{{ $project->summary_description ?? 'No summary available' }}</p>
                            <div class="project-meta">
                                <span class="project-client">{{ $project->client_name }}</span>
                                <span class="project-location">{{ $project->location }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-projects">
                        <div class="no-projects-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3>No Projects Found</h3>
                        <p>There are no projects to display at the moment.</p>
                    </div>
                @endforelse
            </div>

            @if($projects->hasPages())
                <div class="pagination-wrapper">
                    {{ $projects->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.portfolio-listing-page {
    background: #0f172a;
    min-height: 100vh;
    color: white;
}

.hero-section {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #f59e0b;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: #94a3b8;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.projects-section {
    padding: 80px 0;
}

.projects-filters {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 60px;
}

.filter-btn {
    background: rgba(15, 23, 42, 0.8);
    border: 2px solid rgba(245, 158, 11, 0.3);
    color: #94a3b8;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.filter-btn:hover,
.filter-btn.active {
    background: #f59e0b;
    border-color: #f59e0b;
    color: #0f172a;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.project-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    border: 2px solid rgba(245, 158, 11, 0.2);
    overflow: hidden;
    transition: all 0.4s ease;
    cursor: pointer;
}

.project-card:hover {
    transform: translateY(-10px);
    border-color: #f59e0b;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.project-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.1);
}

.project-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-card:hover .project-overlay {
    opacity: 1;
}

.view-btn {
    background: linear-gradient(45deg, #f59e0b, #eab308);
    color: #0f172a;
    padding: 15px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.view-btn:hover {
    transform: scale(1.05);
    color: #0f172a;
    text-decoration: none;
}

.view-btn svg {
    width: 20px;
    height: 20px;
}

.project-content {
    padding: 25px;
}

.project-category {
    color: #f59e0b;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    font-weight: 500;
}

.project-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: white;
    margin-bottom: 12px;
    line-height: 1.3;
}

.project-summary {
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.project-meta {
    display: flex;
    justify-content: space-between;
    padding-top: 15px;
    border-top: 1px solid rgba(245, 158, 11, 0.2);
    font-size: 0.9rem;
}

.project-client {
    color: #e2e8f0;
    font-weight: 500;
}

.project-location {
    color: #94a3b8;
}

.no-projects {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.no-projects-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    color: #f59e0b;
}

.no-projects h3 {
    font-size: 1.8rem;
    color: white;
    margin-bottom: 10px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.pagination-wrapper .pagination {
    background: rgba(15, 23, 42, 0.8);
    border-radius: 15px;
    padding: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.pagination-wrapper .page-link {
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 10px 15px;
    margin: 0 5px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.pagination-wrapper .page-link:hover,
.pagination-wrapper .page-item.active .page-link {
    background: #f59e0b;
    color: #0f172a;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .projects-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .projects-filters {
        gap: 10px;
    }
    
    .filter-btn {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
    
    .project-meta {
        flex-direction: column;
        gap: 5px;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 100px 0 60px;
    }
    
    .projects-section {
        padding: 60px 0;
    }
    
    .container {
        padding: 0 15px;
    }
    
    .project-content {
        padding: 20px;
    }
}

/* Animation for filtering */
.project-card.filtering {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.project-card.show {
    opacity: 1;
    transform: scale(1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter projects
            projectCards.forEach(card => {
                card.classList.add('filtering');
                
                setTimeout(() => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.classList.remove('filtering');
                            card.classList.add('show');
                        }, 50);
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('show');
                    }
                }, 150);
            });
        });
    });
    
    // Initialize all cards as shown
    setTimeout(() => {
        projectCards.forEach(card => {
            card.classList.add('show');
        });
    }, 100);
});
</script>
@endsection
