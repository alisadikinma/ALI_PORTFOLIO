/**
 * Modern Theme Manager with Dark Mode Support
 * Handles theme switching, persistence, and system preference detection
 */

export class ThemeManager {
    constructor() {
        this.themes = ['light', 'dark', 'auto'];
        this.currentTheme = this.getStoredTheme() || 'dark'; // Default to dark theme
        this.systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

        this.init();
    }

    init() {
        console.log('🎨 ThemeManager initializing...');

        // Set initial theme
        this.applyTheme(this.currentTheme);

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', (e) => {
                this.systemTheme = e.matches ? 'dark' : 'light';
                console.log(`🌓 System theme changed to: ${this.systemTheme}`);
                if (this.currentTheme === 'auto') {
                    this.applyTheme('auto');
                }
            });

        // Create theme toggle if it doesn't exist
        this.createThemeToggle();

        // Add global debugging access
        window.debugTheme = () => {
            console.log('🔍 Theme Debug Info:');
            console.log('- Current theme:', this.currentTheme);
            console.log('- System theme:', this.systemTheme);
            console.log('- Effective theme:', this.getEffectiveTheme());
            console.log('- Data theme attribute:', document.documentElement.getAttribute('data-theme'));
            console.log('- Stored theme:', this.getStoredTheme());
            console.log('- Toggle button exists:', !!document.getElementById('theme-toggle'));
        };

        console.log(`✅ ThemeManager initialized with theme: ${this.currentTheme}`);
        console.log('💡 Use window.debugTheme() to debug theme issues');
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

        console.log(`🎨 Applying theme: ${theme} (effective: ${effectiveTheme})`);

        // Apply theme to document
        if (effectiveTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark-mode');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark-mode');
        }

        // Update theme toggle button if it exists
        this.updateThemeToggle(theme);

        // Force a repaint to ensure CSS changes are applied
        document.body.offsetHeight;

        console.log(`✅ Theme applied successfully: ${theme} (effective: ${effectiveTheme})`);
        console.log(`📊 Document data-theme: ${document.documentElement.getAttribute('data-theme') || 'none'}`);
        console.log(`📊 Document has dark class: ${document.documentElement.classList.contains('dark')}`);
    }

    toggleTheme() {
        const themeOrder = ['light', 'dark', 'auto'];
        const currentIndex = themeOrder.indexOf(this.currentTheme);
        const nextIndex = (currentIndex + 1) % themeOrder.length;

        const previousTheme = this.currentTheme;
        this.currentTheme = themeOrder[nextIndex];

        console.log(`🔄 Toggling theme: ${previousTheme} → ${this.currentTheme}`);

        this.setStoredTheme(this.currentTheme);
        this.applyTheme(this.currentTheme);

        // Add visual feedback
        this.showThemeChangeNotification();
        console.log(`✅ Theme switched successfully: ${previousTheme} → ${this.currentTheme}`);
    }

    showThemeChangeNotification() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 bg-slate-800/90 backdrop-blur-md rounded-lg border border-yellow-400/30 text-yellow-400 text-sm font-medium transition-all duration-300';
        notification.textContent = `Theme: ${this.currentTheme}`;

        document.body.appendChild(notification);

        // Remove notification after 2 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 2000);
    }

    createThemeToggle() {
        // Check if theme toggle already exists
        if (document.getElementById('theme-toggle')) {
            this.setupExistingToggle();
            return;
        }

        // Create theme toggle button
        const themeToggle = document.createElement('button');
        themeToggle.id = 'theme-toggle';
        themeToggle.setAttribute('data-theme-toggle', 'true');
        themeToggle.className = 'fixed top-20 right-4 z-50 p-3 bg-slate-800/90 backdrop-blur-md rounded-full border-2 border-yellow-400/50 transition-all duration-300 hover:bg-yellow-400/20 hover:border-yellow-400/80 hover:scale-110 text-yellow-400 shadow-lg hover:shadow-xl';
        themeToggle.setAttribute('aria-label', 'Toggle theme');
        themeToggle.setAttribute('title', `Current theme: ${this.currentTheme}`);
        themeToggle.style.cssText = `
            position: fixed !important;
            top: 5rem !important;
            right: 1rem !important;
            z-index: 9999 !important;
            padding: 0.75rem !important;
            background: rgba(30, 41, 59, 0.9) !important;
            backdrop-filter: blur(12px) !important;
            border-radius: 50% !important;
            border: 2px solid rgba(255, 212, 0, 0.5) !important;
            color: #ffd400 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        `;
        themeToggle.innerHTML = this.getThemeIcon(this.currentTheme);

        // Add click handler
        themeToggle.addEventListener('click', () => {
            this.toggleTheme();
        });

        // Add hover effects
        themeToggle.addEventListener('mouseenter', () => {
            themeToggle.style.transform = 'scale(1.1)';
            themeToggle.style.backgroundColor = 'rgba(255, 212, 0, 0.2)';
            themeToggle.style.borderColor = 'rgba(255, 212, 0, 0.8)';
        });

        themeToggle.addEventListener('mouseleave', () => {
            themeToggle.style.transform = 'scale(1)';
            themeToggle.style.backgroundColor = 'rgba(30, 41, 59, 0.9)';
            themeToggle.style.borderColor = 'rgba(255, 212, 0, 0.5)';
        });

        // Append to body
        document.body.appendChild(themeToggle);
        console.log('✅ Theme toggle created and added to page');
    }

    setupExistingToggle() {
        const toggle = document.getElementById('theme-toggle');
        if (toggle) {
            toggle.setAttribute('data-theme-toggle', 'true');
            toggle.addEventListener('click', () => {
                this.toggleTheme();
            });
            this.updateThemeToggle(this.currentTheme);
            console.log('Existing theme toggle setup complete');
        }
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