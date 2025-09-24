@extends('layouts.web')

@section('title', 'Portfolio - Ali Sadikin')

@section('isi')
<!-- Enhanced Portfolio Page with Cards Design using Lookup Data -->
<section class="min-h-screen bg-gradient-footer py-20 px-4" style="padding-top: 120px;">
    <div class="container mx-auto" style="max-width: 1200px;">
        
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-6xl font-bold text-white mb-4">My Portfolio</h1>
            <p class="text-gray-400 text-lg">Explore my latest projects and innovations</p>
        </div>

        <!-- Controls Section -->
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6 mb-12">
            <!-- Category Filter Buttons - Using Lookup Data -->
            <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                <button class="filter-btn active px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="all">
                    All Projects
                </button>
                @if(isset($projectCategories) && count($projectCategories) > 0)
                    @foreach ($projectCategories as $category)
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" 
                            data-filter="{{ $category->lookup_code }}"
                            data-category-id="{{ $category->id }}"
                            title="{{ $category->lookup_description }}">
                        {{ $category->lookup_icon }} {{ $category->lookup_name }}
                    </button>
                    @endforeach
                @else
                    <!-- Fallback buttons if lookup data is not available -->
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="mobile-app">
                        📱 Mobile Apps
                    </button>
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="web-app">
                        💻 Web Apps
                    </button>
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="ai-ml">
                        🤖 AI/ML
                    </button>
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="iot">
                        🌐 IoT
                    </button>
                @endif
            </div>
            
            <!-- Sort Dropdown -->
            <div class="flex items-center gap-4">
                <label class="text-white font-medium">Sort By:</label>
                <select id="sortSelect" class="px-4 py-2 rounded-lg bg-slate-800 text-white border border-slate-600 focus:border-yellow-400 focus:outline-none">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name-asc">Name A-Z</option>
                    <option value="name-desc">Name Z-A</option>
                    <option value="sequence">Display Order</option>
                </select>
            </div>
        </div>

        <!-- Projects Grid -->
        <div id="portfolioGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Projects will be loaded here via JavaScript -->
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
            <p class="text-white">Loading projects...</p>
        </div>

        <!-- No Results State -->
        <div id="noResults" class="hidden text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-bold text-white mb-2">No Projects Found</h3>
            <p class="text-gray-400">Try adjusting your filters or search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="flex justify-center items-center gap-4 mt-12">
            <!-- Pagination will be generated here -->
        </div>
    </div>
</section>

<style>
/* Enhanced Portfolio Styles */
.filter-btn {
    background: rgba(71, 85, 105, 0.5);
    color: #cbd5e1;
    border: 1px solid rgba(71, 85, 105, 0.3);
    backdrop-filter: blur(10px);
}

.filter-btn.active,
.filter-btn:hover {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    border-color: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
}

/* Dynamic category colors from lookup data */
@if(isset($projectCategories) && count($projectCategories) > 0)
    @foreach ($projectCategories as $category)
    .category-{{ $category->lookup_code }} {
        background: {{ $category->lookup_color ?? '#6b7280' }};
    }
    @endforeach
@endif

/* Card Styles - Enhanced with Lookup Integration */
.project-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.project-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    border-color: rgba(251, 191, 36, 0.5);
}

.card-image-section {
    height: 240px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.project-card:hover .card-image {
    transform: scale(1.05);
}

.card-content {
    padding: 24px;
}

.project-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.3;
}

.project-category {
    color: #fbbf24;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.project-description {
    color: #cbd5e1;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 24px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.view-project-btn {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    justify-content: center;
}

.view-project-btn:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
}

/* Client info styles */
.project-client {
    color: #94a3b8;
    font-size: 0.75rem;
    font-style: italic;
    margin-bottom: 8px;
}

/* Pagination Styles */
.pagination-btn {
    background: rgba(71, 85, 105, 0.5);
    color: #cbd5e1;
    border: 1px solid rgba(71, 85, 105, 0.3);
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(10px);
}

.pagination-btn:hover:not(:disabled) {
    background: rgba(251, 191, 36, 0.2);
    border-color: #fbbf24;
    color: #fbbf24;
}

.pagination-btn.active {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    border-color: #fbbf24;
}

.pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* Animation Classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.fade-in-up:nth-child(2) { animation-delay: 0.1s; }
.fade-in-up:nth-child(3) { animation-delay: 0.2s; }
.fade-in-up:nth-child(4) { animation-delay: 0.3s; }
.fade-in-up:nth-child(5) { animation-delay: 0.4s; }
.fade-in-up:nth-child(6) { animation-delay: 0.5s; }

/* Responsive Adjustments */
@media (max-width: 768px) {
    .project-card {
        margin-bottom: 20px;
    }
    
    .card-image-section {
        height: 200px;
    }
    
    .card-content {
        padding: 20px;
    }
    
    .project-title {
        font-size: 1.25rem;
    }
    
    .filter-btn {
        padding: 8px 16px;
        font-size: 0.875rem;
    }
}
</style>

<script>
// Enhanced Portfolio Management System with Lookup Data Integration
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
        // Use project_category directly (not lookup table)
        const categoryName = project.project_category || project.category_name || 'General';
        const categoryIcon = this.getCategoryIcon(categoryName);
        const categoryColor = this.getCategoryColor(categoryName);
        
        // Create gradient background using category color
        const bgGradient = `linear-gradient(135deg, ${categoryColor}, ${this.darkenColor(categoryColor, 20)})`;
        
        // Perbaikan logika gambar - gunakan asset() helper seperti portfolio.blade.php
        let imageSection = '';
        if (project.featured_image) {
            const imageUrl = `{{ asset('images/projects') }}/${project.featured_image}`;
            imageSection = `
                <img src="${imageUrl}" 
                     alt="${project.project_name}" 
                     class="card-image" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="this.nextElementSibling.style.display='none';">
                <div class="flex items-center justify-center w-full h-full" style="display: flex;">
                    <div class="text-center text-white p-6">
                        <div class="text-4xl mb-4">${categoryIcon}</div>
                        <h3 class="text-xl font-bold mb-2">${project.project_name}</h3>
                        <p class="text-sm opacity-90">${categoryName}</p>
                    </div>
                </div>
            `;
        } else {
            imageSection = `
                <div class="flex items-center justify-center w-full h-full">
                    <div class="text-center text-white p-6">
                        <div class="text-4xl mb-4">${categoryIcon}</div>
                        <h3 class="text-xl font-bold mb-2">${project.project_name}</h3>
                        <p class="text-sm opacity-90">${categoryName}</p>
                    </div>
                </div>
            `;
        }
        
        return `
            <div class="project-card">
                <div class="card-image-section" style="background: ${bgGradient}">
                    ${imageSection}
                </div>
                <div class="card-content">
                    <div class="project-category">
                        ${categoryIcon} ${categoryName}
                    </div>
                    <h3 class="project-title">${project.project_name}</h3>
                    <div class="project-client">${project.client_name} • ${project.location}</div>
                    <p class="project-description">${project.summary_description || project.description || 'No description available.'}</p>
                    <button class="view-project-btn" onclick="portfolioManager.viewProject('${project.slug_project}')">
                        View Project
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
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
        
        // Previous button
        paginationHTML += `
            <button class="pagination-btn ${this.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                    onclick="portfolioManager.goToPage(${this.currentPage - 1})" 
                    ${this.currentPage === 1 ? 'disabled' : ''}>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        `;
        
        // Page numbers
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(totalPages, this.currentPage + 2);
        
        if (startPage > 1) {
            paginationHTML += `<button class="pagination-btn" onclick="portfolioManager.goToPage(1)">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<span class="text-gray-400 px-2">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="pagination-btn ${i === this.currentPage ? 'active' : ''}" 
                        onclick="portfolioManager.goToPage(${i})">
                    ${i}
                </button>
            `;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<span class="text-gray-400 px-2">...</span>`;
            }
            paginationHTML += `<button class="pagination-btn" onclick="portfolioManager.goToPage(${totalPages})">${totalPages}</button>`;
        }
        
        // Next button
        paginationHTML += `
            <button class="pagination-btn ${this.currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                    onclick="portfolioManager.goToPage(${this.currentPage + 1})" 
                    ${this.currentPage === totalPages ? 'disabled' : ''}>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
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
        // Redirect to project detail page
        const publicPath = '{{ env("PUBLIC_PATH", "/") }}';
        window.location.href = `${publicPath}portfolio/${slug}`;
    }
}

// Initialize Portfolio Manager
let portfolioManager;
document.addEventListener('DOMContentLoaded', () => {
    portfolioManager = new PortfolioManager();
});
</script>
@endsection