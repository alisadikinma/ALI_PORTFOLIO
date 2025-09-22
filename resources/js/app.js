import './bootstrap';
import { NavigationManager } from './modules/navigation';
import { PortfolioManager } from './modules/portfolio';
import { GalleryManager } from './modules/gallery';
import { TestimonialManager } from './modules/testimonials';
import DarkModeToggle from './components/DarkModeToggle';
import PWAManager from './components/PWAManager';

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Portfolio App Initializing...');

    try {
        // Core functionality
        new NavigationManager();
        new PortfolioManager();
        new GalleryManager();
        new TestimonialManager();

        // Enhanced Gen Z features
        new DarkModeToggle();
        new PWAManager();

        // Initialize scroll animations
        initScrollAnimations();

        // Initialize performance optimizations
        initPerformanceOptimizations();

        console.log('Portfolio App Initialized Successfully!');
    } catch (error) {
        console.error('Error initializing Portfolio App:', error);
    }
});

// Initialize scroll-triggered animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');

                // Add stagger animation for elements
                const delay = Math.random() * 200;
                entry.target.style.animationDelay = delay + 'ms';
            }
        });
    }, observerOptions);

    // Observe elements with animation classes
    document.querySelectorAll('.animate-on-scroll, .reveal-up, .reveal-left, .reveal-right').forEach(el => {
        observer.observe(el);
    });
}

// Initialize performance optimizations
function initPerformanceOptimizations() {
    // Lazy load images
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-load');
                    imageObserver.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });

    // Preload critical resources
    const criticalResources = [
        '/css/app.css',
        '/js/app.js'
    ];

    criticalResources.forEach(resource => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.href = resource;
        link.as = resource.endsWith('.css') ? 'style' : 'script';
        document.head.appendChild(link);
    });

    // Add magnetic cursor effect for interactive elements
    const magneticElements = document.querySelectorAll('.magnetic');

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

// Performance monitoring
window.addEventListener('load', () => {
    const perfData = performance.getEntriesByType("navigation")[0];
    const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
    
    if (loadTime > 3000) {
        console.warn(`Page load time: ${loadTime}ms - Consider optimization`);
    }
});
