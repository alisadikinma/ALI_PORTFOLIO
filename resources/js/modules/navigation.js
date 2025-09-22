/**
 * Professional Navigation Manager
 * Enhanced for Cross-Device Professional Presentations
 */

export class NavigationManager {
    constructor() {
        this.isInitialized = false;
        this.activeSection = 'home';
        this.menuItems = [];

        this.init();
    }

    init() {
        console.log('📱 Navigation Manager initializing...');

        // Initialize navigation elements safely
        try {
            this.setupNavigationElements();
            this.setupSectionHighlighting();
            this.setupSmoothScrolling();
            this.setupNavigationAnalytics();

            this.isInitialized = true;
            console.log('✅ Navigation Manager initialized');
        } catch (error) {
            console.warn('Navigation Manager initialization failed:', error);
        }
    }

    setupNavigationElements() {
        // Get all navigation menu items
        this.menuItems = document.querySelectorAll('#nav-menu a[href^="#"]');

        // Add active state management
        this.menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                this.handleNavItemClick(e, item);
            });
        });
    }

    setupSectionHighlighting() {
        const sections = document.querySelectorAll('section[id]');
        if (sections.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                    this.setActiveSection(entry.target.id);
                }
            });
        }, {
            threshold: [0.3, 0.5, 0.7],
            rootMargin: '-10% 0px -10% 0px'
        });

        sections.forEach(section => {
            observer.observe(section);
        });
    }

    setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();

                const targetId = anchor.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 80;

                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });

                    this.trackNavigation(targetId);
                }
            });
        });
    }

    setupNavigationAnalytics() {
        this.menuItems.forEach(item => {
            item.addEventListener('click', () => {
                const section = item.getAttribute('href').substring(1);
                this.trackNavigation(section);
            });
        });
    }

    handleNavItemClick(event, item) {
        this.menuItems.forEach(navItem => {
            navItem.classList.remove('text-yellow-400', 'font-semibold');
            navItem.classList.add('text-gray-400', 'font-normal');
        });

        item.classList.remove('text-gray-400', 'font-normal');
        item.classList.add('text-yellow-400', 'font-semibold');
    }

    setActiveSection(sectionId) {
        if (this.activeSection === sectionId) return;

        this.activeSection = sectionId;

        this.menuItems.forEach(item => {
            const href = item.getAttribute('href');
            const isActive = href === `#${sectionId}`;

            if (isActive) {
                item.classList.remove('text-gray-400', 'font-normal');
                item.classList.add('text-yellow-400', 'font-semibold');
            } else {
                item.classList.remove('text-yellow-400', 'font-semibold');
                item.classList.add('text-gray-400', 'font-normal');
            }
        });
    }

    trackNavigation(section) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'navigation_click', {
                section: section,
                timestamp: new Date().toISOString()
            });
        }
    }
}

export default NavigationManager;