/**
 * Modern Theme Manager with Dark Mode Support
 * Handles theme switching, persistence, and system preference detection
 */

export class ThemeManager {
    constructor() {
        this.themes = ['light', 'dark', 'auto'];
        this.currentTheme = this.getStoredTheme() || 'auto';
        this.systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

        this.init();
    }

    init() {
        console.log('ThemeManager initializing...');

        // Set initial theme
        this.applyTheme(this.currentTheme);

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', (e) => {
                this.systemTheme = e.matches ? 'dark' : 'light';
                if (this.currentTheme === 'auto') {
                    this.applyTheme('auto');
                }
            });

        // Create theme toggle if it doesn't exist
        this.createThemeToggle();

        console.log(`ThemeManager initialized with theme: ${this.currentTheme}`);
    }

    getStoredTheme() {
        try {
            return localStorage.getItem('portfolio-theme');
        } catch (error) {
            console.warn('LocalStorage not available, using default theme');
            return null;
        }
    }

    setStoredTheme(theme) {
        try {
            localStorage.setItem('portfolio-theme', theme);
        } catch (error) {
            console.warn('Unable to store theme preference');
        }
    }

    applyTheme(theme) {
        const effectiveTheme = theme === 'auto' ? this.systemTheme : theme;

        // Apply theme to document
        if (effectiveTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
        }

        // Update theme toggle button if it exists
        this.updateThemeToggle(theme);

        console.log(`Theme applied: ${theme} (effective: ${effectiveTheme})`);
    }

    toggleTheme() {
        const themeOrder = ['light', 'dark', 'auto'];
        const currentIndex = themeOrder.indexOf(this.currentTheme);
        const nextIndex = (currentIndex + 1) % themeOrder.length;

        this.currentTheme = themeOrder[nextIndex];
        this.setStoredTheme(this.currentTheme);
        this.applyTheme(this.currentTheme);
    }

    createThemeToggle() {
        // Check if theme toggle already exists
        if (document.getElementById('theme-toggle')) {
            return;
        }

        // Create theme toggle button
        const themeToggle = document.createElement('button');
        themeToggle.id = 'theme-toggle';
        themeToggle.className = 'fixed top-4 right-4 z-50 p-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 transition-all duration-300 hover:bg-white/20';
        themeToggle.setAttribute('aria-label', 'Toggle theme');
        themeToggle.innerHTML = this.getThemeIcon(this.currentTheme);

        // Add click handler
        themeToggle.addEventListener('click', () => {
            this.toggleTheme();
        });

        // Append to body
        document.body.appendChild(themeToggle);
    }

    updateThemeToggle(theme) {
        const toggle = document.getElementById('theme-toggle');
        if (toggle) {
            toggle.innerHTML = this.getThemeIcon(theme);
            toggle.setAttribute('title', `Current theme: ${theme}`);
        }
    }

    getThemeIcon(theme) {
        const icons = {
            light: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>`,
            dark: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>`,
            auto: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
            </svg>`
        };

        return icons[theme] || icons.auto;
    }

    // Public API
    setTheme(theme) {
        if (this.themes.includes(theme)) {
            this.currentTheme = theme;
            this.setStoredTheme(theme);
            this.applyTheme(theme);
        }
    }

    getTheme() {
        return this.currentTheme;
    }

    getEffectiveTheme() {
        return this.currentTheme === 'auto' ? this.systemTheme : this.currentTheme;
    }
}