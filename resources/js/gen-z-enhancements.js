/**
 * Gen Z Appeal & Accessibility Enhancements
 * Modern interactions and accessibility features
 */

class GenZEnhancements {
    constructor() {
        this.init();
    }

    init() {
        this.setupScrollAnimations();
        this.setupCounterAnimations();
        this.setupFloatingActionButton();
        this.setupEnhancedHoverEffects();
        this.setupAccessibilityFeatures();
        this.setupParticleBackground();
        this.setupSmoothScrolling();
        this.setupPreloadedAnimations();
    }

    /**
     * Intersection Observer for scroll animations
     */
    setupScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -50px 0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');

                    // Stagger children animations
                    const children = entry.target.querySelectorAll('.stagger-child');
                    children.forEach((child, index) => {
                        child.style.setProperty('--stagger-index', index);
                        setTimeout(() => {
                            child.classList.add('in-view');
                        }, index * 100);
                    });
                }
            });
        }, observerOptions);

        // Add animation classes to elements
        const animatedElements = document.querySelectorAll(`
            .card-modern,
            .project-card,
            .service-card,
            section > *,
            .stats-item,
            .testimonial-item,
            .skill-item
        `);

        animatedElements.forEach((el, index) => {
            // Add random animation class
            const animations = ['scroll-animate', 'scroll-animate-left', 'scroll-animate-right', 'scroll-animate-scale'];
            const randomAnimation = animations[index % animations.length];
            el.classList.add(randomAnimation);
            observer.observe(el);
        });
    }

    /**
     * Counter animations for statistics
     */
    setupCounterAnimations() {
        const counters = document.querySelectorAll('.animate-counter');

        const animateValue = (element, start, end, duration, suffix = '') => {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const current = Math.floor(progress * (end - start) + start);
                element.textContent = current.toLocaleString() + suffix;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    const element = entry.target;
                    const endValue = parseInt(element.textContent.replace(/[^\d]/g, ''));
                    const suffix = element.textContent.replace(/[\d,]/g, '');

                    element.dataset.animated = 'true';
                    animateValue(element, 0, endValue, 2000, suffix);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    /**
     * Floating Action Button for quick contact
     */
    setupFloatingActionButton() {
        const fabHTML = `
            <div class="floating-actions" id="floating-actions">
                <button class="fab-main" onclick="this.parentElement.classList.toggle('expanded')"
                        aria-label="Contact options" aria-expanded="false">
                    💬
                </button>
                <button class="fab-option" onclick="window.location.href='mailto:ali@aliportfolio.com'"
                        aria-label="Send email" style="transform: scale(0); opacity: 0;">
                    📧
                </button>
                <button class="fab-option" onclick="window.open('https://wa.me/6281234567890', '_blank')"
                        aria-label="WhatsApp chat" style="transform: scale(0); opacity: 0;">
                    📱
                </button>
                <button class="fab-option" onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'})"
                        aria-label="Contact form" style="transform: scale(0); opacity: 0;">
                    📝
                </button>
            </div>
        `;

        // Add FAB to body
        document.body.insertAdjacentHTML('beforeend', fabHTML);

        // Enhanced FAB functionality
        const fab = document.getElementById('floating-actions');
        const mainButton = fab.querySelector('.fab-main');
        const options = fab.querySelectorAll('.fab-option');

        mainButton.addEventListener('click', () => {
            const isExpanded = fab.classList.contains('expanded');
            fab.classList.toggle('expanded');
            mainButton.setAttribute('aria-expanded', !isExpanded);

            options.forEach((option, index) => {
                if (fab.classList.contains('expanded')) {
                    setTimeout(() => {
                        option.style.transform = 'scale(1)';
                        option.style.opacity = '1';
                    }, index * 100);
                } else {
                    option.style.transform = 'scale(0)';
                    option.style.opacity = '0';
                }
            });
        });

        // Close FAB when clicking outside
        document.addEventListener('click', (e) => {
            if (!fab.contains(e.target)) {
                fab.classList.remove('expanded');
                mainButton.setAttribute('aria-expanded', 'false');
                options.forEach(option => {
                    option.style.transform = 'scale(0)';
                    option.style.opacity = '0';
                });
            }
        });
    }

    /**
     * Enhanced hover effects for portfolio items
     */
    setupEnhancedHoverEffects() {
        // Project cards enhanced interactions
        const projectCards = document.querySelectorAll('.project-card, .card-modern');

        projectCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                // Add dynamic glow effect
                card.style.boxShadow = '0 25px 50px rgba(251, 191, 36, 0.4)';

                // Tilt effect on mouse move
                card.addEventListener('mousemove', handleCardTilt);
            });

            card.addEventListener('mouseleave', () => {
                card.style.boxShadow = '';
                card.style.transform = '';
                card.removeEventListener('mousemove', handleCardTilt);
            });
        });

        function handleCardTilt(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / centerY * -5;
            const rotateY = (x - centerX) / centerX * 5;

            e.currentTarget.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px) scale(1.02)`;
        }
    }

    /**
     * Enhanced accessibility features
     */
    setupAccessibilityFeatures() {
        // Enhanced keyboard navigation
        document.addEventListener('keydown', (e) => {
            // Enhanced focus management
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });

        // Screen reader announcements
        this.announcePageChanges();

        // Enhanced focus indicators
        const focusableElements = document.querySelectorAll(`
            a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])
        `);

        focusableElements.forEach(element => {
            element.classList.add('focus-ring');
        });
    }

    /**
     * Particle background effect for hero section
     */
    setupParticleBackground() {
        const heroSection = document.querySelector('.hero-section, #hero, .hero');
        if (!heroSection) return;

        const canvas = document.createElement('canvas');
        canvas.id = 'particle-canvas';
        canvas.style.position = 'absolute';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '1';

        heroSection.style.position = 'relative';
        heroSection.appendChild(canvas);

        const ctx = canvas.getContext('2d');

        // Responsive canvas sizing
        const resizeCanvas = () => {
            canvas.width = heroSection.offsetWidth;
            canvas.height = heroSection.offsetHeight;
        };

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Particle system
        const particles = [];
        const particleCount = window.innerWidth < 768 ? 30 : 50;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 2;
                this.vy = (Math.random() - 0.5) * 2;
                this.life = Math.random();
                this.decay = 0.01;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.life -= this.decay;

                if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                if (this.life <= 0) this.life = 1;
            }

            draw() {
                ctx.save();
                ctx.globalAlpha = this.life * 0.3;
                ctx.fillStyle = '#fbbf24';
                ctx.beginPath();
                ctx.arc(this.x, this.y, 2, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }
        }

        // Initialize particles
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        // Animation loop
        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });

            requestAnimationFrame(animate);
        };

        animate();
    }

    /**
     * Smooth scrolling for navigation links
     */
    setupSmoothScrolling() {
        const navLinks = document.querySelectorAll('a[href^="#"]');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').slice(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update URL without jumping
                    if (history.pushState) {
                        history.pushState(null, null, `#${targetId}`);
                    }

                    // Announce navigation to screen readers
                    this.announceNavigation(targetElement);
                }
            });
        });
    }

    /**
     * Preloaded animations for immediate response
     */
    setupPreloadedAnimations() {
        // Add loading states
        const loadingElements = document.querySelectorAll('.lazy-load');

        loadingElements.forEach(element => {
            element.classList.add('loading-skeleton');

            // Simulate loading complete
            setTimeout(() => {
                element.classList.remove('loading-skeleton');
                element.classList.add('scroll-animate');
            }, Math.random() * 1000 + 500);
        });
    }

    /**
     * Screen reader announcements
     */
    announcePageChanges() {
        const announcer = document.createElement('div');
        announcer.id = 'sr-announcer';
        announcer.setAttribute('aria-live', 'polite');
        announcer.setAttribute('aria-atomic', 'true');
        announcer.style.position = 'absolute';
        announcer.style.left = '-10000px';
        announcer.style.width = '1px';
        announcer.style.height = '1px';
        announcer.style.overflow = 'hidden';
        document.body.appendChild(announcer);

        window.announceToScreenReader = (message) => {
            announcer.textContent = message;
        };
    }

    announceNavigation(element) {
        const heading = element.querySelector('h1, h2, h3, h4, h5, h6');
        const message = heading ?
            `Navigated to ${heading.textContent}` :
            `Navigated to ${element.id || 'section'}`;

        if (window.announceToScreenReader) {
            window.announceToScreenReader(message);
        }
    }
}

// Initialize enhancements when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new GenZEnhancements();
});

// Initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new GenZEnhancements());
} else {
    new GenZEnhancements();
}