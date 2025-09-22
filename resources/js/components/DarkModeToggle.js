/**
 * Dark Mode Toggle Component
 * Provides theme switching functionality with system preference detection
 */

class DarkModeToggle {
    constructor() {
        this.init();
    }

    init() {
        this.createToggleButton();
        this.bindEvents();
        this.loadTheme();
    }

    createToggleButton() {
        // Find the header container to insert the toggle button
        const headerContainer = document.querySelector('header .flex.justify-between.items-center');
        if (!headerContainer) {
            console.error('Header container not found for dark mode toggle');
            return;
        }

        // Create dark mode toggle button for header integration
        const toggleButton = document.createElement('button');
        toggleButton.id = 'dark-mode-toggle';
        toggleButton.className = 'dark-mode-toggle-header p-2 rounded-lg transition-all duration-300 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-yellow-400';
        toggleButton.setAttribute('aria-label', 'Toggle dark mode');
        toggleButton.setAttribute('type', 'button');

        toggleButton.innerHTML = `
            <svg class="sun-icon w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg class="moon-icon w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        `;

        // Insert the toggle button between the mobile menu toggle and navigation
        const mobileMenuToggle = headerContainer.querySelector('#menu-toggle');
        if (mobileMenuToggle) {
            headerContainer.insertBefore(toggleButton, mobileMenuToggle);
        } else {
            // Fallback: insert before the navigation
            const nav = headerContainer.querySelector('nav');
            if (nav) {
                headerContainer.insertBefore(toggleButton, nav);
            } else {
                headerContainer.appendChild(toggleButton);
            }
        }

        this.toggleButton = toggleButton;
    }

    bindEvents() {
        this.toggleButton.addEventListener('click', () => {
            this.toggleTheme();
        });

        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    this.setTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }

    loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        const theme = savedTheme || systemTheme;
        this.setTheme(theme);
    }

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
        localStorage.setItem('theme', newTheme);

        // Add toggle animation
        this.toggleButton.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.toggleButton.style.transform = 'scale(1)';
        }, 150);
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);

        const sunIcon = this.toggleButton.querySelector('.sun-icon');
        const moonIcon = this.toggleButton.querySelector('.moon-icon');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
            this.updateCSSVariables('dark');
        } else {
            document.documentElement.classList.remove('dark');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
            this.updateCSSVariables('light');
        }
    }

    updateCSSVariables(theme) {
        const root = document.documentElement;

        if (theme === 'dark') {
            root.style.setProperty('--bg-primary', '#0f0f23');
            root.style.setProperty('--bg-secondary', '#1a1a2e');
            root.style.setProperty('--text-primary', '#f8fafc');
            root.style.setProperty('--text-secondary', '#cbd5e1');
        } else {
            root.style.setProperty('--bg-primary', '#ffffff');
            root.style.setProperty('--bg-secondary', '#f8fafc');
            root.style.setProperty('--text-primary', '#1e293b');
            root.style.setProperty('--text-secondary', '#64748b');
        }
    }
}

// Initialize dark mode toggle when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new DarkModeToggle();
});

export default DarkModeToggle;