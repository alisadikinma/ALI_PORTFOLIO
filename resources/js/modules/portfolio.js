/**
 * Professional Portfolio Manager
 * Manufacturing Project Showcase Enhancement
 */

export class PortfolioManager {
    constructor() {
        this.isInitialized = false;
        this.projects = [];
        this.currentFilter = 'all';

        this.init();
    }

    init() {
        console.log('💼 Portfolio Manager initializing...');

        try {
            this.setupPortfolioElements();
            this.setupFilterSystem();
            this.setupProjectInteractions();
            this.setupProjectAnalytics();

            this.isInitialized = true;
            console.log('✅ Portfolio Manager initialized');
        } catch (error) {
            console.warn('Portfolio Manager initialization failed:', error);
        }
    }

    setupPortfolioElements() {
        // Get all portfolio items
        this.projects = document.querySelectorAll('.portfolio-item, .project-card');

        // Setup hover effects for professional presentation
        this.projects.forEach(project => {
            this.setupProjectHoverEffects(project);
        });
    }

    setupFilterSystem() {
        const filterButtons = document.querySelectorAll('.portfolio-filter-btn');

        filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const filter = button.getAttribute('data-filter');
                this.filterProjects(filter);
                this.updateFilterButtons(button);
            });
        });
    }

    setupProjectInteractions() {
        this.projects.forEach(project => {
            project.addEventListener('click', (e) => {
                this.handleProjectClick(e, project);
            });
        });
    }

    setupProjectHoverEffects(project) {
        project.addEventListener('mouseenter', () => {
            project.style.transform = 'translateY(-8px) scale(1.02)';
            project.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
        });

        project.addEventListener('mouseleave', () => {
            project.style.transform = 'translateY(0) scale(1)';
            project.style.boxShadow = '';
        });
    }

    filterProjects(filter) {
        this.currentFilter = filter;

        this.projects.forEach(project => {
            const projectCategory = project.getAttribute('data-category');

            if (filter === 'all' || projectCategory === filter) {
                project.style.display = 'block';
                project.style.animation = 'scale-in-professional 0.4s ease forwards';
            } else {
                project.style.display = 'none';
            }
        });

        this.trackFilter(filter);
    }

    updateFilterButtons(activeButton) {
        const allButtons = document.querySelectorAll('.portfolio-filter-btn');

        allButtons.forEach(button => {
            button.classList.remove('active', 'bg-yellow-400', 'text-black');
            button.classList.add('bg-gray-700', 'text-white');
        });

        activeButton.classList.add('active', 'bg-yellow-400', 'text-black');
        activeButton.classList.remove('bg-gray-700', 'text-white');
    }

    handleProjectClick(event, project) {
        const projectId = project.getAttribute('data-project-id');
        const projectTitle = project.querySelector('.project-title')?.textContent || 'Unknown Project';

        // Track project interaction
        this.trackProjectInteraction(projectId, projectTitle);

        // Add professional click feedback
        project.style.transform = 'scale(0.98)';
        setTimeout(() => {
            project.style.transform = '';
        }, 150);
    }

    setupProjectAnalytics() {
        // Setup intersection observer for project views
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const project = entry.target;
                    const projectId = project.getAttribute('data-project-id');
                    this.trackProjectView(projectId);
                }
            });
        }, {
            threshold: 0.5
        });

        this.projects.forEach(project => {
            observer.observe(project);
        });
    }

    trackFilter(filter) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'portfolio_filter', {
                filter: filter,
                timestamp: new Date().toISOString()
            });
        }
    }

    trackProjectView(projectId) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'project_view', {
                project_id: projectId,
                timestamp: new Date().toISOString()
            });
        }
    }

    trackProjectInteraction(projectId, projectTitle) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'project_interaction', {
                project_id: projectId,
                project_title: projectTitle,
                timestamp: new Date().toISOString()
            });
        }
    }
}

export default PortfolioManager;