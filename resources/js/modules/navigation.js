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
        const navLinks = document.querySelectorAll('#nav-menu a[href^="#"]');
        
        if (!sections.length || !navLinks.length) return;
        
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        // Remove active class from all links
                        navLinks.forEach((link) => {
                            link.classList.remove('text-yellow-400', 'font-semibold');
                            link.classList.add('text-gray-400', 'font-normal');
                        });
                        
                        // Add active class to current section link
                        const activeLink = document.querySelector(`#nav-menu a[href="#${entry.target.id}"]`);
                        if (activeLink) {
                            activeLink.classList.add('text-yellow-400', 'font-semibold');
                            activeLink.classList.remove('text-gray-400', 'font-normal');
                        }
                    }
                });
            },
            { threshold: 0.6, rootMargin: '-20% 0px -20% 0px' }
        );
        
        sections.forEach((section) => observer.observe(section));
    }
}