/**
 * PHASE 3: MODERN ANIMATION SYSTEM
 * Advanced scroll-triggered animations and micro-interactions
 */

export class AnimationManager {
    constructor() {
        this.observers = new Map();
        this.animatedElements = new Set();
        this.isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        this.init();
    }

    init() {
        if (this.isReducedMotion) {
            console.log('🔕 Reduced motion preferred - minimal animations enabled');
            this.setupReducedMotionAnimations();
        } else {
            console.log('🎬 Full animation suite enabled');
            this.setupScrollAnimations();
            this.setupParallaxEffects();
            this.setupMicroInteractions();
            this.setupCounterAnimations();
        }

        this.setupLoadingAnimations();
    }

    setupScrollAnimations() {
        const observerOptions = {
            threshold: [0, 0.25, 0.5, 0.75, 1],
            rootMargin: '0px 0px -50px 0px'
        };

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => this.handleScrollAnimation(entry));
        }, observerOptions);

        // Observe elements with animation classes
        const animatableElements = document.querySelectorAll(`
            .animate-on-scroll,
            .fade-in-up,
            .fade-in-left,
            .fade-in-right,
            .slide-in-up,
            .slide-in-left,
            .slide-in-right,
            .scale-in,
            .rotate-in,
            .bounce-in,
            .card-modern,
            .stagger-children > *
        `);

        animatableElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = this.getInitialTransform(element);
            element.dataset.animationIndex = index;
            scrollObserver.observe(element);
        });

        this.observers.set('scroll', scrollObserver);
    }

    handleScrollAnimation(entry) {
        if (entry.isIntersecting) {
            const element = entry.target;
            const animationClass = this.detectAnimationClass(element);
            const delay = this.calculateStaggerDelay(element);

            setTimeout(() => {
                this.animateElement(element, animationClass);
                this.animatedElements.add(element);
            }, delay);

            // Unobserve after animation to improve performance
            this.observers.get('scroll').unobserve(element);
        }
    }

    detectAnimationClass(element) {
        const classes = element.classList;

        if (classes.contains('fade-in-up')) return 'fade-in-up';
        if (classes.contains('fade-in-left')) return 'fade-in-left';
        if (classes.contains('fade-in-right')) return 'fade-in-right';
        if (classes.contains('slide-in-up')) return 'slide-in-up';
        if (classes.contains('slide-in-left')) return 'slide-in-left';
        if (classes.contains('slide-in-right')) return 'slide-in-right';
        if (classes.contains('scale-in')) return 'scale-in';
        if (classes.contains('rotate-in')) return 'rotate-in';
        if (classes.contains('bounce-in')) return 'bounce-in';

        return 'fade-in'; // Default animation
    }

    getInitialTransform(element) {
        const animationClass = this.detectAnimationClass(element);

        switch (animationClass) {
            case 'fade-in-up':
            case 'slide-in-up':
                return 'translateY(30px)';
            case 'fade-in-left':
            case 'slide-in-left':
                return 'translateX(-30px)';
            case 'fade-in-right':
            case 'slide-in-right':
                return 'translateX(30px)';
            case 'scale-in':
                return 'scale(0.8)';
            case 'rotate-in':
                return 'rotate(-10deg) scale(0.8)';
            case 'bounce-in':
                return 'scale(0.3)';
            default:
                return 'translateY(20px)';
        }
    }

    animateElement(element, animationClass) {
        const duration = this.getAnimationDuration(animationClass);
        const easing = this.getAnimationEasing(animationClass);

        element.style.transition = `all ${duration}ms ${easing}`;
        element.style.opacity = '1';
        element.style.transform = 'none';

        // Add special effects for specific animations
        switch (animationClass) {
            case 'bounce-in':
                element.style.transition = `all ${duration}ms cubic-bezier(0.68, -0.55, 0.265, 1.55)`;
                break;
            case 'rotate-in':
                element.style.transformOrigin = 'center center';
                break;
        }

        // Cleanup after animation
        setTimeout(() => {
            element.style.transition = '';
        }, duration);
    }

    getAnimationDuration(animationClass) {
        const durations = {
            'fade-in': 600,
            'fade-in-up': 600,
            'fade-in-left': 700,
            'fade-in-right': 700,
            'slide-in-up': 500,
            'slide-in-left': 600,
            'slide-in-right': 600,
            'scale-in': 400,
            'rotate-in': 800,
            'bounce-in': 1000
        };

        return durations[animationClass] || 600;
    }

    getAnimationEasing(animationClass) {
        const easings = {
            'fade-in': 'cubic-bezier(0.4, 0, 0.2, 1)',
            'slide-in-up': 'cubic-bezier(0.16, 1, 0.3, 1)',
            'scale-in': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)'
        };

        return easings[animationClass] || 'cubic-bezier(0.4, 0, 0.2, 1)';
    }

    calculateStaggerDelay(element) {
        if (!element.parentElement.classList.contains('stagger-children')) {
            return 0;
        }

        const index = parseInt(element.dataset.animationIndex) || 0;
        const staggerDelay = parseInt(element.parentElement.dataset.staggerDelay) || 100;

        return index * staggerDelay;
    }

    setupParallaxEffects() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        if (parallaxElements.length === 0) return;

        const parallaxObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateParallax(entry.target);
                }
            });
        });

        parallaxElements.forEach(element => {
            parallaxObserver.observe(element);
        });

        this.observers.set('parallax', parallaxObserver);
    }

    animateParallax(element) {
        const speed = parseFloat(element.dataset.parallax) || 0.5;

        const updateParallax = () => {
            if (!this.isElementInViewport(element)) return;

            const rect = element.getBoundingClientRect();
            const scrolled = window.pageYOffset;
            const offset = rect.top + scrolled;
            const diff = scrolled - offset;
            const transform = diff * speed;

            element.style.transform = `translateY(${transform}px)`;
        };

        // Throttled scroll listener
        let ticking = false;
        const handleScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateParallax();
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
    }

    setupMicroInteractions() {
        // Hover animations for buttons and cards
        const interactiveElements = document.querySelectorAll(`
            .btn-modern,
            .btn-cyber,
            .btn-glass,
            .card-modern,
            .hover-lift,
            .hover-glow,
            .hover-scale
        `);

        interactiveElements.forEach(element => {
            this.setupHoverAnimation(element);
        });

        // Magnetic cursor effect for important elements
        this.setupMagneticCursor();
    }

    setupHoverAnimation(element) {
        let hoverTimeout;

        element.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
            this.triggerHoverIn(element);
        });

        element.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => {
                this.triggerHoverOut(element);
            }, 50);
        });

        // Touch support for mobile
        element.addEventListener('touchstart', () => {
            this.triggerHoverIn(element);
        });

        element.addEventListener('touchend', () => {
            setTimeout(() => this.triggerHoverOut(element), 150);
        });
    }

    triggerHoverIn(element) {
        element.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

        if (element.classList.contains('hover-lift')) {
            element.style.transform = 'translateY(-8px) scale(1.02)';
        } else if (element.classList.contains('hover-scale')) {
            element.style.transform = 'scale(1.05)';
        } else if (element.classList.contains('btn-modern') || element.classList.contains('btn-cyber')) {
            element.style.transform = 'translateY(-3px) scale(1.05)';
        }
    }

    triggerHoverOut(element) {
        element.style.transform = '';
    }

    setupMagneticCursor() {
        const magneticElements = document.querySelectorAll('.magnetic');

        magneticElements.forEach(element => {
            element.addEventListener('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                const strength = 0.3;
                element.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'translate(0px, 0px)';
            });
        });
    }

    setupCounterAnimations() {
        const counters = document.querySelectorAll('[data-counter]');

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });

        this.observers.set('counter', counterObserver);
    }

    animateCounter(element) {
        const target = parseInt(element.dataset.counter);
        const duration = parseInt(element.dataset.counterDuration) || 2000;
        const suffix = element.dataset.counterSuffix || '';

        let current = 0;
        const increment = target / (duration / 16); // 60fps

        const updateCounter = () => {
            current += increment;

            if (current < target) {
                element.textContent = Math.floor(current) + suffix;
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target + suffix;
            }
        };

        updateCounter();
    }

    setupLoadingAnimations() {
        // Skeleton loading animations
        const skeletons = document.querySelectorAll('.skeleton');
        skeletons.forEach(skeleton => {
            skeleton.classList.add('animate-shimmer');
        });

        // Page transition animations
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('page-loaded');
        });
    }

    setupReducedMotionAnimations() {
        // Simplified animations for users who prefer reduced motion
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -20px 0px'
        };

        const simpleObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    simpleObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(element => {
            element.style.opacity = '0';
            simpleObserver.observe(element);
        });

        this.observers.set('simple', simpleObserver);
    }

    isElementInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Public methods for external use
    animateElement(selector, animation = 'fade-in') {
        const element = document.querySelector(selector);
        if (element) {
            this.handleScrollAnimation({
                target: element,
                isIntersecting: true
            });
        }
    }

    addStaggerChildren(parentSelector, delay = 100) {
        const parent = document.querySelector(parentSelector);
        if (parent) {
            parent.classList.add('stagger-children');
            parent.dataset.staggerDelay = delay;
        }
    }

    destroy() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
        this.animatedElements.clear();
    }
}

export default AnimationManager;