import './bootstrap';

/**
 * Professional Digital Transformation Consultant Portfolio
 * Enhanced for Manufacturing Industry Credibility & Gen Z Appeal
 * Optimized for Cross-Device Professional Presentations
 */

// Core Modules
import { NavigationManager } from './modules/navigation';
import { PortfolioManager } from './modules/portfolio';
import { GalleryManager } from './modules/gallery';
import { TestimonialManager } from './modules/testimonials';
import DarkModeToggle from './components/DarkModeToggle';
import PWAManager from './components/PWAManager';

// Professional Portfolio App Class
class ProfessionalPortfolioApp {
    constructor() {
        this.isInitialized = false;
        this.modules = {};
        this.viewport = {
            width: window.innerWidth,
            height: window.innerHeight,
            isMobile: window.innerWidth < 768,
            isTablet: window.innerWidth >= 768 && window.innerWidth < 1024,
            isDesktop: window.innerWidth >= 1024
        };

        this.init();
    }

    async init() {
        try {
            console.log('🚀 Professional Portfolio Initializing...');
            console.log('📱 Viewport:', this.viewport);

            // Initialize core modules
            await this.initializeCoreModules();

            // Initialize responsive enhancements
            this.initializeResponsiveFeatures();

            // Initialize professional features
            this.initializeProfessionalFeatures();

            // Initialize performance optimizations
            this.initializePerformanceOptimizations();

            // Setup viewport monitoring
            this.setupViewportMonitoring();

            // Setup consultation CTAs
            this.setupConsultationCTAs();

            this.isInitialized = true;
            console.log('✅ Professional Portfolio Initialized Successfully!');

            // Analytics
            this.trackInitialization();

        } catch (error) {
            console.error('❌ Error initializing Portfolio App:', error);
            this.handleInitializationError(error);
        }
    }

    async initializeCoreModules() {
        const modulePromises = [
            this.safeInitialize('navigation', () => new NavigationManager()),
            this.safeInitialize('portfolio', () => new PortfolioManager()),
            this.safeInitialize('gallery', () => new GalleryManager()),
            this.safeInitialize('testimonials', () => new TestimonialManager()),
            this.safeInitialize('darkMode', () => new DarkModeToggle()),
            this.safeInitialize('pwa', () => new PWAManager())
        ];

        await Promise.allSettled(modulePromises);
    }

    async safeInitialize(name, initFunction) {
        try {
            this.modules[name] = initFunction();
            console.log(`✅ ${name} module initialized`);
        } catch (error) {
            console.warn(`⚠️ ${name} module failed to initialize:`, error);
        }
    }

    initializeResponsiveFeatures() {
        // Critical tablet responsive fixes
        this.fixTabletLayouts();

        // Enhanced mobile navigation
        this.enhanceMobileNavigation();

        // Responsive image optimization
        this.optimizeImagesForDevice();

        // Touch interaction enhancements
        this.enhanceTouchInteractions();
    }

    initializeProfessionalFeatures() {
        // Consultation booking enhancements
        this.enhanceConsultationBooking();

        // Manufacturing credibility indicators
        this.setupCredibilityIndicators();

        // Authority building elements
        this.setupAuthorityElements();

        // Professional micro-interactions
        this.setupProfessionalMicroInteractions();
    }

    initializePerformanceOptimizations() {
        // Initialize scroll animations
        this.initScrollAnimations();

        // Initialize performance optimizations
        this.initPerformanceOptimizations();

        // Preload critical resources
        this.preloadCriticalResources();

        // Setup intersection observers
        this.setupIntersectionObservers();
    }

    // Critical tablet layout fixes (768px breakpoint priority)
    fixTabletLayouts() {
        if (this.viewport.isTablet) {
            console.log('🔧 Applying tablet layout fixes');

            // Fix hero section layout
            const heroSection = document.getElementById('home');
            if (heroSection) {
                const heroContent = heroSection.querySelector('.flex');
                if (heroContent) {
                    heroContent.style.flexDirection = 'column';
                    heroContent.style.alignItems = 'center';
                    heroContent.style.textAlign = 'center';
                    heroContent.style.gap = '2rem';
                }
            }

            // Fix navigation spacing
            const navMenu = document.getElementById('nav-menu');
            if (navMenu && window.innerWidth >= 768) {
                navMenu.style.gap = '1.25rem';
            }

            // Fix stats grid
            const statsGrid = document.querySelector('.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-5');
            if (statsGrid) {
                statsGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                statsGrid.style.gap = '1.5rem';
            }

            // Fix contact section
            const contactSection = document.getElementById('contact');
            if (contactSection) {
                contactSection.style.flexDirection = 'column';
                contactSection.style.gap = '2rem';
            }
        }
    }

    // Enhanced mobile navigation with accessibility
    enhanceMobileNavigation() {
        const menuToggle = document.getElementById('menu-toggle');
        const navMenu = document.getElementById('nav-menu');
        const overlay = document.getElementById('nav-menu-overlay');

        if (!menuToggle || !navMenu || !overlay) {
            console.warn('Mobile navigation elements not found');
            return;
        }

        // Enhanced toggle with haptic feedback
        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();

            // Haptic feedback for mobile devices
            if ('vibrate' in navigator) {
                navigator.vibrate(50);
            }

            this.toggleMobileMenu();
        });

        // Enhanced keyboard navigation
        menuToggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.toggleMobileMenu();
            }
        });

        // Close menu on overlay click
        overlay.addEventListener('click', () => {
            this.closeMobileMenu();
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !navMenu.classList.contains('hidden')) {
                this.closeMobileMenu();
            }
        });

        // Auto-close on link click (mobile only)
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (this.viewport.isMobile) {
                    setTimeout(() => this.closeMobileMenu(), 150);
                }
            });
        });
    }

    toggleMobileMenu() {
        const navMenu = document.getElementById('nav-menu');
        const overlay = document.getElementById('nav-menu-overlay');
        const isOpen = !navMenu.classList.contains('hidden');

        if (isOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        const navMenu = document.getElementById('nav-menu');
        const overlay = document.getElementById('nav-menu-overlay');
        const toggleButton = document.getElementById('menu-toggle');

        navMenu.classList.remove('hidden');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Update aria attributes
        toggleButton.setAttribute('aria-expanded', 'true');
        toggleButton.setAttribute('aria-label', 'Close navigation menu');

        // Focus management
        const firstLink = navMenu.querySelector('a');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 150);
        }

        // Announce to screen readers
        this.announceToScreenReader('Navigation menu opened');
    }

    closeMobileMenu() {
        const navMenu = document.getElementById('nav-menu');
        const overlay = document.getElementById('nav-menu-overlay');
        const toggleButton = document.getElementById('menu-toggle');

        navMenu.classList.add('hidden');
        overlay.classList.remove('show');
        document.body.style.overflow = '';

        // Update aria attributes
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.setAttribute('aria-label', 'Open navigation menu');

        // Return focus to toggle button
        setTimeout(() => toggleButton.focus(), 100);

        // Announce to screen readers
        this.announceToScreenReader('Navigation menu closed');
    }

    announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        document.body.appendChild(announcement);

        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    // Optimize images for current device
    optimizeImagesForDevice() {
        const images = document.querySelectorAll('img');

        images.forEach(img => {
            // Add loading="lazy" for performance
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }

            // Add proper alt text if missing
            if (!img.hasAttribute('alt')) {
                img.setAttribute('alt', 'Professional portfolio image');
            }

            // Optimize image sizes based on viewport
            if (this.viewport.isMobile && img.naturalWidth > 800) {
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
            }
        });
    }

    // Enhanced touch interactions for mobile/tablet
    enhanceTouchInteractions() {
        if ('ontouchstart' in window) {
            // Add touch-friendly classes
            document.body.classList.add('touch-device');

            // Enhance button touch targets
            const buttons = document.querySelectorAll('button, .btn-cyber, .btn-glass');
            buttons.forEach(button => {
                button.style.minHeight = '48px';
                button.style.minWidth = '48px';
                button.style.touchAction = 'manipulation';
            });

            // Add touch feedback to interactive elements
            const interactiveElements = document.querySelectorAll('a, button, [role="button"]');
            interactiveElements.forEach(element => {
                element.addEventListener('touchstart', () => {
                    element.style.transform = 'scale(0.98)';
                });

                element.addEventListener('touchend', () => {
                    setTimeout(() => {
                        element.style.transform = '';
                    }, 150);
                });
            });
        }
    }

    // Setup consultation booking enhancements
    enhanceConsultationBooking() {
        const ctaButtons = document.querySelectorAll('[data-consultation-cta]');

        ctaButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Track consultation interest
                this.trackConsultationInterest(button.textContent);

                // Add professional hover effects
                button.classList.add('consultation-active');

                setTimeout(() => {
                    button.classList.remove('consultation-active');
                }, 300);
            });
        });
    }

    // Setup consultation CTAs
    setupConsultationCTAs() {
        // Add data attributes to consultation-related buttons
        const consultationButtons = document.querySelectorAll('a[href*="contact"], button:contains("consultation"), .btn-cyber');

        consultationButtons.forEach(button => {
            button.setAttribute('data-consultation-cta', 'true');
        });
    }

    // Setup credibility indicators
    setupCredibilityIndicators() {
        // Animate statistics on scroll
        const stats = document.querySelectorAll('[data-stat-value]');

        stats.forEach(stat => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateStatistic(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(stat);
        });
    }

    // Setup authority building elements
    setupAuthorityElements() {
        // Add follower count animation
        const followerElement = document.querySelector('[data-followers]');
        if (followerElement) {
            this.animateFollowerCount(followerElement);
        }

        // Add experience years animation
        const experienceElement = document.querySelector('[data-experience]');
        if (experienceElement) {
            this.animateExperience(experienceElement);
        }
    }

    // Setup professional micro-interactions
    setupProfessionalMicroInteractions() {
        // Add magnetic cursor effect for buttons
        const magneticElements = document.querySelectorAll('.btn-cyber, .btn-glass, .card-professional');

        magneticElements.forEach(element => {
            element.addEventListener('mousemove', (e) => {
                if (this.viewport.isDesktop) {
                    const rect = element.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;

                    element.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
                }
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'translate(0px, 0px)';
            });
        });
    }

    // Setup viewport monitoring for responsive adjustments
    setupViewportMonitoring() {
        let resizeTimeout;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);

            resizeTimeout = setTimeout(() => {
                // Update viewport info
                this.viewport = {
                    width: window.innerWidth,
                    height: window.innerHeight,
                    isMobile: window.innerWidth < 768,
                    isTablet: window.innerWidth >= 768 && window.innerWidth < 1024,
                    isDesktop: window.innerWidth >= 1024
                };

                console.log('📱 Viewport updated:', this.viewport);

                // Reapply responsive fixes
                this.fixTabletLayouts();
                this.optimizeImagesForDevice();

            }, 250);
        });
    }

    // Initialize scroll-triggered animations
    initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');

                    // Add professional stagger effect
                    const delay = Array.from(entry.target.parentElement.children).indexOf(entry.target) * 100;
                    entry.target.style.animationDelay = delay + 'ms';

                    // Add fade-in animation
                    entry.target.style.animation = 'fade-in-professional 0.6s ease forwards';
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.animate-on-scroll, .reveal-up, .reveal-left, .reveal-right, .card-professional').forEach(el => {
            observer.observe(el);
        });
    }

    // Initialize performance optimizations
    initPerformanceOptimizations() {
        console.log('⚡ Initializing performance optimizations');

        // Enhanced lazy loading with progressive enhancement
        this.setupLazyLoading();

        // Critical resource preloading
        this.preloadCriticalResources();

        // Setup intersection observers
        this.setupIntersectionObservers();

        // Performance monitoring
        this.setupPerformanceMonitoring();
    }

    setupLazyLoading() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;

                    if (img.dataset.src) {
                        // Progressive image loading
                        const tempImg = new Image();
                        tempImg.onload = () => {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy-load');
                            img.classList.add('loaded');
                        };
                        tempImg.src = img.dataset.src;

                        imageObserver.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px'
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            img.classList.add('lazy-load');
            imageObserver.observe(img);
        });
    }

    preloadCriticalResources() {
        const criticalResources = [
            { href: '/build/assets/app.css', as: 'style' },
            { href: '/build/assets/app.js', as: 'script' },
            { href: '/favicon/favicon.png', as: 'image' }
        ];

        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource.href;
            link.as = resource.as;
            if (resource.as === 'style') {
                link.onload = () => {
                    link.rel = 'stylesheet';
                };
            }
            document.head.appendChild(link);
        });
    }

    setupIntersectionObservers() {
        // Content visibility optimization
        const contentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.contentVisibility = 'visible';
                } else {
                    entry.target.style.contentVisibility = 'auto';
                }
            });
        });

        document.querySelectorAll('section').forEach(section => {
            contentObserver.observe(section);
        });
    }

    setupPerformanceMonitoring() {
        // Web Vitals monitoring
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.entryType === 'largest-contentful-paint') {
                        console.log('LCP:', entry.startTime);
                    }
                    if (entry.entryType === 'first-input') {
                        console.log('FID:', entry.processingStart - entry.startTime);
                    }
                    if (entry.entryType === 'layout-shift') {
                        console.log('CLS:', entry.value);
                    }
                });
            });

            observer.observe({entryTypes: ['largest-contentful-paint', 'first-input', 'layout-shift']});
        }
    }

    // Animation helpers
    animateStatistic(element) {
        const finalValue = parseInt(element.textContent);
        const duration = 2000;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = Math.floor(finalValue * easeOutQuart);

            element.textContent = currentValue + (element.dataset.suffix || '');

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    animateFollowerCount(element) {
        element.style.animation = 'pulse-glow 2s ease-in-out infinite';
    }

    animateExperience(element) {
        element.style.animation = 'bounce-gentle 3s ease-in-out infinite';
    }

    // Analytics and tracking
    trackInitialization() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'portfolio_initialized', {
                viewport_type: this.viewport.isMobile ? 'mobile' : this.viewport.isTablet ? 'tablet' : 'desktop',
                screen_width: this.viewport.width,
                screen_height: this.viewport.height
            });
        }
    }

    trackConsultationInterest(buttonText) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'consultation_interest', {
                button_text: buttonText,
                viewport_type: this.viewport.isMobile ? 'mobile' : this.viewport.isTablet ? 'tablet' : 'desktop'
            });
        }
    }

    // Error handling
    handleInitializationError(error) {
        // Create fallback experience
        const fallbackMessage = document.createElement('div');
        fallbackMessage.className = 'fixed top-4 right-4 bg-amber-100 border border-amber-400 text-amber-800 px-4 py-2 rounded-lg z-50';
        fallbackMessage.innerHTML = `
            <p class="font-semibold">⚠️ Some features may be limited</p>
            <p class="text-sm">Refresh the page to try again</p>
        `;
        document.body.appendChild(fallbackMessage);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (fallbackMessage.parentNode) {
                fallbackMessage.parentNode.removeChild(fallbackMessage);
            }
        }, 5000);

        // Track error
        if (typeof gtag !== 'undefined') {
            gtag('event', 'portfolio_error', {
                error_message: error.message,
                error_stack: error.stack
            });
        }
    }
}

// Initialize the professional portfolio app
document.addEventListener('DOMContentLoaded', () => {
    window.portfolioApp = new ProfessionalPortfolioApp();
});

// Enhanced performance monitoring
window.addEventListener('load', () => {
    try {
        const perfData = performance.getEntriesByType("navigation")[0];
        const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
        const domContentLoadedTime = perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart;

        console.log('🔍 Performance Metrics:');
        console.log(`⏱️ Total load time: ${loadTime}ms`);
        console.log(`⚡ DOM ready time: ${domContentLoadedTime}ms`);

        if (loadTime > 3000) {
            console.warn(`⚠️ Page load time: ${loadTime}ms - Consider optimization`);
        } else {
            console.log('✅ Good performance - Load time under 3 seconds');
        }

        // Track performance in analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_performance', {
                load_time: loadTime,
                dom_ready_time: domContentLoadedTime,
                performance_rating: loadTime < 2000 ? 'excellent' : loadTime < 3000 ? 'good' : 'needs_improvement'
            });
        }

    } catch (error) {
        console.warn('Performance monitoring error:', error);
    }
});

// Service Worker registration for PWA features
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('✅ SW registered: ', registration);
            })
            .catch((registrationError) => {
                console.warn('SW registration failed: ', registrationError);
            });
    });
}

// Export for global access
window.ProfessionalPortfolioApp = ProfessionalPortfolioApp;