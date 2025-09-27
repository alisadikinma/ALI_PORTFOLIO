export class NavigationManager {
    constructor() {
        this.menuToggle = document.getElementById('menu-toggle');
        this.navMenu = document.getElementById('nav-menu');
        this.init();
    }
    
    init() {
        this.initMobileMenu();
        this.initSmoothScrolling();
        this.initActiveSection();
    }
    
    initMobileMenu() {
        if (!this.menuToggle || !this.navMenu) return;
        
        this.menuToggle.addEventListener('click', () => {
            this.toggleMenu();
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.navMenu.contains(e.target) && !this.menuToggle.contains(e.target)) {
                this.closeMenu();
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeMenu();
            }
        });
    }
    
    toggleMenu() {
        const isOpen = !this.navMenu.classList.contains('hidden');
        
        this.navMenu.classList.toggle('hidden');
        this.menuToggle.setAttribute('aria-expanded', !isOpen);
        document.body.style.overflow = isOpen ? '' : 'hidden';
        
        // Toggle icons
        const menuIcon = this.menuToggle.querySelector('.menu-icon');
        const closeIcon = this.menuToggle.querySelector('.close-icon');
        
        menuIcon?.classList.toggle('hidden', !isOpen);
        closeIcon?.classList.toggle('hidden', isOpen);
    }
    
    closeMenu() {
        if (this.navMenu.classList.contains('hidden')) return;
        
        this.navMenu.classList.add('hidden');
        this.menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        
        const menuIcon = this.menuToggle.querySelector('.menu-icon');
        const closeIcon = this.menuToggle.querySelector('.close-icon');
        
        menuIcon?.classList.remove('hidden');
        closeIcon?.classList.add('hidden');
    }
    
    initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = anchor.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    this.closeMenu(); // Close mobile menu after clicking
                }
            });
        });
    }
    
    initActiveSection() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('#nav-menu a');

        if (!sections.length || !navLinks.length) {
            console.warn('Navigation: No sections or nav links found');
            return;
        }

        console.log('🔧 Navigation initialized with', sections.length, 'sections and', navLinks.length, 'links');

        // Build a mapping of section IDs to navigation links
        const sectionLinkMap = new Map();

        // Find home link - look for various patterns
        const homeLink = Array.from(navLinks).find(link => {
            const href = link.getAttribute('href') || '';
            const text = link.textContent.trim().toLowerCase();
            return href === '/' || href.endsWith('/') || text === 'home';
        }) || navLinks[0];

        console.log('🏠 Home link found:', homeLink ? homeLink.textContent.trim() : 'none');

        // Map each section to its corresponding nav link
        sections.forEach(section => {
            const sectionId = section.id;
            let matchedLink = null;

            // Try multiple matching strategies
            // 1. Direct href matching with hash
            matchedLink = Array.from(navLinks).find(link => {
                const href = link.getAttribute('href') || '';
                return href.includes(`#${sectionId}`) || href.includes(`/${sectionId}`);
            });

            // 2. Text content matching
            if (!matchedLink) {
                matchedLink = Array.from(navLinks).find(link => {
                    const text = link.textContent.trim().toLowerCase();
                    return text === sectionId.toLowerCase();
                });
            }

            // 3. Partial text matching for common cases
            if (!matchedLink) {
                const textMappings = {
                    'awards': ['award', 'awards'],
                    'services': ['service', 'services'],
                    'about': ['about'],
                    'home': ['home'],
                    'contact': ['contact', 'send message', 'send', 'message'],
                    'portfolio': ['portfolio'],
                    'testimonials': ['testimonials', 'testimonial'],
                    'gallery': ['gallery'],
                    'articles': ['articles', 'article']
                };

                const possibleTexts = textMappings[sectionId] || [sectionId];
                matchedLink = Array.from(navLinks).find(link => {
                    const text = link.textContent.trim().toLowerCase();
                    return possibleTexts.includes(text);
                });
            }

            if (matchedLink) {
                sectionLinkMap.set(sectionId, matchedLink);
                console.log(`📌 Mapped section "${sectionId}" to link "${matchedLink.textContent.trim()}"`);
            } else {
                console.warn(`❌ No link found for section: ${sectionId}`);
            }
        });

        // Set initial state - determine which section is currently in view
        this.clearAllActiveLinks(navLinks);

        // Find the section currently in view on page load
        const initialSection = this.findCurrentSection(sections);
        if (initialSection) {
            const initialLink = sectionLinkMap.get(initialSection.id) || homeLink;
            this.setActiveLink(initialLink);
            console.log(`🚀 Initial section detected: ${initialSection.id}`);
        } else {
            // Fallback to home link
            this.setActiveLink(homeLink);
            console.log('🚀 No initial section detected - defaulting to home');
        }

        // Create intersection observer with optimal settings
        const observer = new IntersectionObserver(
            (entries) => {
                // Find all intersecting sections and their visibility
                const visibleSections = [];

                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        visibleSections.push({
                            section: entry.target,
                            ratio: entry.intersectionRatio,
                            id: entry.target.id
                        });
                    }
                });

                if (visibleSections.length === 0) return;

                // Sort by intersection ratio and prefer sections higher in the viewport
                visibleSections.sort((a, b) => {
                    const aRect = a.section.getBoundingClientRect();
                    const bRect = b.section.getBoundingClientRect();

                    // Prefer sections closer to the top of the viewport
                    const aDistance = Math.abs(aRect.top);
                    const bDistance = Math.abs(bRect.top);

                    // Combine ratio and distance for better selection
                    const aScore = a.ratio - (aDistance / window.innerHeight);
                    const bScore = b.ratio - (bDistance / window.innerHeight);

                    return bScore - aScore;
                });

                const bestSection = visibleSections[0].section;
                const sectionId = bestSection.id;

                console.log(`📍 Best section in view: ${sectionId} (ratio: ${visibleSections[0].ratio.toFixed(2)})`);
                console.log(`📊 All visible sections:`, visibleSections.map(s => `${s.id}(${s.ratio.toFixed(2)})`).join(', '));

                // Clear all active states
                this.clearAllActiveLinks(navLinks);

                // Special handling for home section
                if (sectionId === 'home') {
                    this.setActiveLink(homeLink);
                    console.log('✨ Home section active');
                    return;
                }

                // Find and activate the corresponding link
                const correspondingLink = sectionLinkMap.get(sectionId);
                if (correspondingLink) {
                    this.setActiveLink(correspondingLink);
                    console.log(`✨ Section "${sectionId}" active - highlighting "${correspondingLink.textContent.trim()}"`);
                } else {
                    // Fallback to home if no link found
                    this.setActiveLink(homeLink);
                    console.log(`⚠️  No link for section "${sectionId}" - fallback to home`);
                }
            },
            {
                threshold: [0, 0.1, 0.25, 0.5, 0.75, 1], // More granular thresholds
                rootMargin: '-20% 0px -20% 0px' // Adjust margin for better center detection
            }
        );

        // Observe all sections
        sections.forEach(section => {
            observer.observe(section);
            console.log(`👀 Observing section: ${section.id}`);
        });

        console.log('✅ Navigation system initialized successfully');
    }

    clearAllActiveLinks(navLinks) {
        navLinks.forEach((link) => {
            link.classList.remove('text-yellow-400', 'font-semibold');
            link.classList.add('text-gray-400', 'font-normal');
        });
    }

    setActiveLink(link) {
        if (link) {
            link.classList.add('text-yellow-400', 'font-semibold');
            link.classList.remove('text-gray-400', 'font-normal', 'text-white');
            console.log('🎯 Active link set:', link.textContent.trim());
        }
    }

    findCurrentSection(sections) {
        // Find the section currently in the viewport on page load
        const viewportHeight = window.innerHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        let bestSection = null;
        let bestVisibilityScore = 0;

        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            const sectionTop = rect.top + scrollTop;
            const sectionBottom = sectionTop + rect.height;

            // Calculate how much of the section is visible
            const visibleTop = Math.max(sectionTop, scrollTop);
            const visibleBottom = Math.min(sectionBottom, scrollTop + viewportHeight);
            const visibleHeight = Math.max(0, visibleBottom - visibleTop);
            const visibilityRatio = visibleHeight / rect.height;

            // Prefer sections that are more visible and higher on the page
            const score = visibilityRatio + (rect.top <= 100 ? 0.5 : 0);

            if (score > bestVisibilityScore && visibilityRatio > 0.1) {
                bestVisibilityScore = score;
                bestSection = section;
            }

            console.log(`📏 Section ${section.id}: visibility ${(visibilityRatio * 100).toFixed(1)}%, score ${score.toFixed(2)}`);
        });

        return bestSection;
    }
}