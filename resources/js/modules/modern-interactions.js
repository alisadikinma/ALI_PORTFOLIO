/**
 * Modern Interactions & Animations
 * Gen-Z focused micro-interactions and scroll effects
 */

class ModernInteractions {
    constructor() {
        this.init();
    }

    init() {
        this.initScrollReveal();
        this.initMagneticButtons();
        this.initParticleBackground();
        this.initHeroAnimations();
        this.initMobileNavigation();
        this.initGlassmorphismEffects();
        this.initStaggerAnimations();
    }

    /**
     * Scroll Reveal Animations
     */
    initScrollReveal() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    // Unobserve after revealing for better performance
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all scroll reveal elements
        const scrollElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right');
        scrollElements.forEach(el => observer.observe(el));
    }

    /**
     * Magnetic Button Effects
     */
    initMagneticButtons() {
        const magneticElements = document.querySelectorAll('.btn-magnetic');

        magneticElements.forEach(element => {
            element.addEventListener('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                element.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'translate(0px, 0px)';
            });
        });
    }

    /**
     * Floating Particle Background
     */
    initParticleBackground() {
        const heroSection = document.querySelector('#hero, .hero-section');
        if (!heroSection) return;

        const particleCount = window.innerWidth > 768 ? 20 : 10;

        for (let i = 0; i < particleCount; i++) {
            this.createParticle(heroSection, i);
        }
    }

    createParticle(container, index) {
        const particle = document.createElement('div');
        particle.className = 'hero-particle';

        // Random starting position
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.animationDelay = `${Math.random() * 8}s`;
        particle.style.animationDuration = `${8 + Math.random() * 4}s`;

        container.appendChild(particle);
    }

    /**
     * Hero Section Animations
     */
    initHeroAnimations() {
        const heroTitle = document.querySelector('.hero-title');
        const heroSubtitle = document.querySelector('.hero-subtitle');
        const heroButtons = document.querySelectorAll('.hero-button');

        if (heroTitle) {
            heroTitle.classList.add('stagger-item');
        }

        if (heroSubtitle) {
            heroSubtitle.classList.add('stagger-item');
        }

        heroButtons.forEach((button, index) => {
            button.classList.add('stagger-item');
            button.style.animationDelay = `${0.8 + (index * 0.2)}s`;
        });
    }

    /**
     * Modern Mobile Navigation
     */
    initMobileNavigation() {
        // Create mobile bottom navigation if it doesn't exist
        this.createMobileBottomNav();

        // Add scroll behavior for mobile nav
        let lastScrollY = window.scrollY;
        const mobileNav = document.querySelector('.mobile-bottom-nav');

        if (mobileNav) {
            window.addEventListener('scroll', () => {
                const currentScrollY = window.scrollY;

                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    // Scrolling down
                    mobileNav.style.transform = 'translateY(100%)';
                } else {
                    // Scrolling up
                    mobileNav.style.transform = 'translateY(0)';
                }

                lastScrollY = currentScrollY;
            });
        }
    }

    createMobileBottomNav() {
        // Only create if it doesn't exist
        if (document.querySelector('.mobile-bottom-nav')) return;

        const nav = document.createElement('nav');
        nav.className = 'mobile-bottom-nav';
        nav.innerHTML = `
            <a href="#hero" class="mobile-nav-item" data-section="hero">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="mobile-nav-text">Home</span>
            </a>
            <a href="#services" class="mobile-nav-item" data-section="services">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                <span class="mobile-nav-text">Services</span>
            </a>
            <a href="#gallery" class="mobile-nav-item" data-section="gallery">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="mobile-nav-text">Gallery</span>
            </a>
            <a href="#contact" class="mobile-nav-item" data-section="contact">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="mobile-nav-text">Contact</span>
            </a>
        `;

        document.body.appendChild(nav);

        // Add smooth scrolling and active state management
        nav.addEventListener('click', (e) => {
            e.preventDefault();
            const link = e.target.closest('.mobile-nav-item');
            if (!link) return;

            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });

                // Update active state
                nav.querySelectorAll('.mobile-nav-item').forEach(item => item.classList.remove('active'));
                link.classList.add('active');
            }
        });
    }

    /**
     * Enhanced Glassmorphism Effects
     */
    initGlassmorphismEffects() {
        // Add glass effects to existing elements
        const cards = document.querySelectorAll('.card, .project-card, .service-card');
        cards.forEach(card => {
            if (!card.classList.contains('glass-card') && !card.classList.contains('project-card-glass')) {
                card.classList.add('glass-card');
            }
        });

        // Add glass effects to forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.classList.add('glass-form');
        });

        const inputs = document.querySelectorAll('input:not([type="submit"]):not([type="button"]), select, textarea');
        inputs.forEach(input => {
            input.classList.add('glass-input');
        });
    }

    /**
     * Stagger Animations for Lists
     */
    initStaggerAnimations() {
        const staggerContainers = document.querySelectorAll('.gallery-grid, .services-grid, .awards-grid');

        staggerContainers.forEach(container => {
            const items = container.children;
            Array.from(items).forEach((item, index) => {
                item.classList.add('stagger-item');
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    }

    /**
     * Text Gradient Animations
     */
    initTextGradients() {
        const headings = document.querySelectorAll('h1, h2.text-yellow-400, h3.text-yellow-400');
        headings.forEach(heading => {
            if (!heading.classList.contains('text-gradient')) {
                heading.classList.add('text-gradient');
            }
        });
    }

    /**
     * Performance Optimization
     */
    optimizeForPerformance() {
        // Reduce animations for users who prefer reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01ms');
            return;
        }

        // Pause animations when tab is not visible
        document.addEventListener('visibilitychange', () => {
            const animations = document.querySelectorAll('.float-element, .pulse-glow, .hero-particle');
            if (document.hidden) {
                animations.forEach(el => el.style.animationPlayState = 'paused');
            } else {
                animations.forEach(el => el.style.animationPlayState = 'running');
            }
        });
    }
}

// Auto-initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ModernInteractions();
    });
} else {
    new ModernInteractions();
}

// Export for use in other modules
export default ModernInteractions;