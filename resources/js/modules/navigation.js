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
        // Active section highlighting is now handled by the main script in web.blade.php
        // to avoid duplicate IntersectionObserver conflicts
        console.log('Active section highlighting delegated to main script');
    }
}