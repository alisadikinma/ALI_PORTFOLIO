/**
 * Professional Testimonials Manager
 * Client testimonial showcase for manufacturing credibility
 */

export class TestimonialManager {
    constructor() {
        this.isInitialized = false;
        this.currentSlide = 0;
        this.testimonials = [];
        this.autoPlayInterval = null;

        this.init();
    }

    init() {
        console.log('💬 Testimonials Manager initializing...');

        try {
            this.setupTestimonialElements();
            this.setupSlider();
            this.setupAutoPlay();
            this.setupTouchSupport();

            this.isInitialized = true;
            console.log('✅ Testimonials Manager initialized');
        } catch (error) {
            console.warn('Testimonials Manager initialization failed:', error);
        }
    }

    setupTestimonialElements() {
        this.testimonials = document.querySelectorAll('.testimonial-item');

        if (this.testimonials.length === 0) {
            console.log('No testimonials found');
            return;
        }

        // Setup hover effects
        this.testimonials.forEach((testimonial, index) => {
            testimonial.addEventListener('mouseenter', () => {
                this.pauseAutoPlay();
            });

            testimonial.addEventListener('mouseleave', () => {
                this.resumeAutoPlay();
            });
        });
    }

    setupSlider() {
        const dotsContainer = document.getElementById('nav-dots');
        const slider = document.querySelector('#testimonials .overflow-x-auto');

        if (!dotsContainer || !slider) return;

        const dots = dotsContainer.querySelectorAll('div');

        // Handle dot clicks
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.updateDots(index);
            });
        });

        // Handle scroll events
        slider.addEventListener('scroll', () => {
            this.handleScrollUpdate();
        });
    }

    setupAutoPlay() {
        if (this.testimonials.length <= 1) return;

        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 5000); // Auto-advance every 5 seconds
    }

    setupTouchSupport() {
        const slider = document.querySelector('#testimonials .overflow-x-auto');
        if (!slider) return;

        let startX = 0;
        let scrollStart = 0;

        slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            scrollStart = slider.scrollLeft;
            this.pauseAutoPlay();
        });

        slider.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const currentX = e.touches[0].clientX;
            const diff = startX - currentX;
            slider.scrollLeft = scrollStart + diff;
        });

        slider.addEventListener('touchend', () => {
            this.resumeAutoPlay();
            this.handleScrollUpdate();
        });
    }

    goToSlide(index) {
        const slider = document.querySelector('#testimonials .overflow-x-auto');
        if (!slider || !this.testimonials[index]) return;

        const testimonial = this.testimonials[index];
        const cardWidth = testimonial.offsetWidth + 20; // Include gap

        slider.scrollTo({
            left: index * cardWidth,
            behavior: 'smooth'
        });

        this.currentSlide = index;
        this.trackTestimonialView(index);
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.testimonials.length;
        this.goToSlide(nextIndex);
    }

    previousSlide() {
        const prevIndex = this.currentSlide === 0 ? this.testimonials.length - 1 : this.currentSlide - 1;
        this.goToSlide(prevIndex);
    }

    updateDots(activeIndex) {
        const dots = document.querySelectorAll('#nav-dots > div');

        dots.forEach((dot, index) => {
            if (index === activeIndex) {
                dot.classList.remove('bg-neutral-600');
                dot.classList.add('bg-yellow-400');
            } else {
                dot.classList.remove('bg-yellow-400');
                dot.classList.add('bg-neutral-600');
            }
        });
    }

    handleScrollUpdate() {
        const slider = document.querySelector('#testimonials .overflow-x-auto');
        if (!slider || this.testimonials.length === 0) return;

        const cardWidth = this.testimonials[0].offsetWidth + 20;
        const newIndex = Math.round(slider.scrollLeft / cardWidth);

        if (newIndex !== this.currentSlide) {
            this.currentSlide = newIndex;
            this.updateDots(newIndex);
        }
    }

    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }

    resumeAutoPlay() {
        if (!this.autoPlayInterval && this.testimonials.length > 1) {
            this.autoPlayInterval = setInterval(() => {
                this.nextSlide();
            }, 5000);
        }
    }

    trackTestimonialView(index) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'testimonial_view', {
                testimonial_index: index,
                timestamp: new Date().toISOString()
            });
        }
    }

    // Public API
    destroy() {
        this.pauseAutoPlay();
        this.isInitialized = false;
    }

    getCurrentSlide() {
        return this.currentSlide;
    }

    getTotalSlides() {
        return this.testimonials.length;
    }
}

export default TestimonialManager;