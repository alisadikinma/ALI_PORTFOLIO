/**
 * PHASE 3: PERFORMANCE OPTIMIZATION MODULE
 * Modern Web Performance & Core Web Vitals
 */

export class PerformanceManager {
    constructor() {
        this.metrics = {
            lcp: 0,
            fid: 0,
            cls: 0,
            ttfb: 0,
            fcp: 0
        };
        this.observers = new Map();

        this.init();
    }

    init() {
        this.observeWebVitals();
        this.optimizeImages();
        this.setupIntersectionObservers();
        this.preloadCriticalResources();
        this.setupResourceHints();
    }

    // Observe Core Web Vitals
    observeWebVitals() {
        // Largest Contentful Paint
        if ('PerformanceObserver' in window) {
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.metrics.lcp = lastEntry.startTime;
                this.reportMetric('LCP', lastEntry.startTime);
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });

            // First Input Delay
            const fidObserver = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    this.metrics.fid = entry.processingStart - entry.startTime;
                    this.reportMetric('FID', this.metrics.fid);
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });

            // Cumulative Layout Shift
            const clsObserver = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (!entry.hadRecentInput) {
                        this.metrics.cls += entry.value;
                        this.reportMetric('CLS', this.metrics.cls);
                    }
                });
            });
            clsObserver.observe({ entryTypes: ['layout-shift'] });

            // Time to First Byte
            this.measureTTFB();
        }
    }

    measureTTFB() {
        const navigationEntries = performance.getEntriesByType('navigation');
        if (navigationEntries.length > 0) {
            const navigationEntry = navigationEntries[0];
            this.metrics.ttfb = navigationEntry.responseStart - navigationEntry.requestStart;
            this.reportMetric('TTFB', this.metrics.ttfb);
        }
    }

    reportMetric(name, value) {
        console.log(`📊 ${name}: ${Math.round(value)}ms`);

        // Rating system
        const ratings = {
            LCP: { good: 2500, needs: 4000 },
            FID: { good: 100, needs: 300 },
            CLS: { good: 0.1, needs: 0.25 },
            TTFB: { good: 600, needs: 1000 }
        };

        if (ratings[name]) {
            const rating = ratings[name];
            let status = 'poor';

            if (value <= rating.good) status = 'good';
            else if (value <= rating.needs) status = 'needs-improvement';

            console.log(`📈 ${name} Rating: ${status}`);

            // Send to analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'web_vitals', {
                    name,
                    value: Math.round(value),
                    rating: status
                });
            }
        }
    }

    // Progressive Image Loading
    optimizeImages() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    this.loadImageProgressively(img);
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px'
        });

        // Observe all images with data-src
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });

        // WebP support detection
        this.supportsWebP().then(supported => {
            if (supported) {
                document.documentElement.classList.add('webp-supported');
            }
        });
    }

    async loadImageProgressively(img) {
        try {
            // Create temporary image to preload
            const tempImg = new Image();

            return new Promise((resolve, reject) => {
                tempImg.onload = () => {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-loading');
                    img.classList.add('loaded');
                    resolve();
                };
                tempImg.onerror = reject;
                tempImg.src = img.dataset.src;
            });
        } catch (error) {
            console.warn('Image loading error:', error);
        }
    }

    async supportsWebP() {
        return new Promise(resolve => {
            const webP = new Image();
            webP.onload = webP.onerror = () => {
                resolve(webP.height === 2);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        });
    }

    // Content visibility optimization
    setupIntersectionObservers() {
        // Viewport-based content loading
        const contentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.contentVisibility = 'visible';
                    this.loadSectionContent(entry.target);
                } else {
                    entry.target.style.contentVisibility = 'auto';
                }
            });
        });

        document.querySelectorAll('section').forEach(section => {
            contentObserver.observe(section);
        });

        this.observers.set('content', contentObserver);
    }

    loadSectionContent(section) {
        // Load section-specific content
        const lazyElements = section.querySelectorAll('[data-lazy-load]');
        lazyElements.forEach(element => {
            if (element.dataset.lazyLoad) {
                this.loadLazyContent(element);
            }
        });
    }

    async loadLazyContent(element) {
        try {
            const content = element.dataset.lazyLoad;
            // Implement lazy content loading logic
            element.innerHTML = content;
            element.removeAttribute('data-lazy-load');
        } catch (error) {
            console.warn('Lazy content loading error:', error);
        }
    }

    // Preload critical resources
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

    // DNS prefetch for external resources
    setupResourceHints() {
        const prefetchDomains = [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'cdnjs.cloudflare.com'
        ];

        prefetchDomains.forEach(domain => {
            const link = document.createElement('link');
            link.rel = 'dns-prefetch';
            link.href = `//${domain}`;
            document.head.appendChild(link);
        });
    }

    // Resource monitoring
    monitorResources() {
        const resources = performance.getEntriesByType('resource');
        const slowResources = resources.filter(resource => resource.duration > 1000);

        if (slowResources.length > 0) {
            console.warn('🐌 Slow resources detected:', slowResources);
        }

        // Monitor bundle sizes
        const jsResources = resources.filter(r => r.name.includes('.js'));
        const cssResources = resources.filter(r => r.name.includes('.css'));

        console.log('📦 Bundle Analysis:');
        console.log(`JS Size: ${this.formatBytes(jsResources.reduce((total, r) => total + r.transferSize, 0))}`);
        console.log(`CSS Size: ${this.formatBytes(cssResources.reduce((total, r) => total + r.transferSize, 0))}`);
    }

    formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Performance budget check
    checkPerformanceBudget() {
        const budget = {
            lcp: 2500,
            fid: 100,
            cls: 0.1,
            totalSize: 500 * 1024 // 500KB
        };

        const issues = [];

        if (this.metrics.lcp > budget.lcp) {
            issues.push(`LCP too slow: ${this.metrics.lcp}ms (budget: ${budget.lcp}ms)`);
        }

        if (this.metrics.fid > budget.fid) {
            issues.push(`FID too high: ${this.metrics.fid}ms (budget: ${budget.fid}ms)`);
        }

        if (this.metrics.cls > budget.cls) {
            issues.push(`CLS too high: ${this.metrics.cls} (budget: ${budget.cls})`);
        }

        if (issues.length > 0) {
            console.warn('⚠️ Performance Budget Issues:', issues);
        } else {
            console.log('✅ Performance budget met!');
        }
    }

    // Cleanup observers
    destroy() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
    }
}

export default PerformanceManager;