/**
 * Dark Mode Handler for Portfolio Admin
 * Handles dark mode toggle and persistence across all pages
 */

class DarkModeManager {
    constructor() {
        this.toggleElement = document.getElementById('darkModeToggle');
        this.bodyElement = document.body;
        this.containerElement = document.querySelector('.container-xxl');
        this.darkModeKey = 'darkMode';
        
        this.init();
    }
    
    init() {
        // Apply stored dark mode preference on page load
        this.applyStoredPreference();
        
        // Set up toggle event listener
        if (this.toggleElement) {
            this.toggleElement.addEventListener('change', (e) => {
                this.handleToggle(e.target.checked);
            });
        }
        
        // Listen for system theme changes
        this.listenForSystemThemeChanges();
        
        // Initialize CKEditor dark mode if present
        this.initializeCKEditorDarkMode();
        
        // Set up mutation observer for dynamic content
        this.setupMutationObserver();
    }
    
    applyStoredPreference() {
        const storedPreference = localStorage.getItem(this.darkModeKey);
        const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Use stored preference if available, otherwise use system preference
        const shouldUseDarkMode = storedPreference === 'enabled' || 
                                 (storedPreference === null && systemPrefersDark);
        
        if (shouldUseDarkMode) {
            this.enableDarkMode();
        } else {
            this.disableDarkMode();
        }
        
        // Update toggle state
        if (this.toggleElement) {
            this.toggleElement.checked = shouldUseDarkMode;
        }
    }
    
    handleToggle(isEnabled) {
        if (isEnabled) {
            this.enableDarkMode();
            localStorage.setItem(this.darkModeKey, 'enabled');
        } else {
            this.disableDarkMode();
            localStorage.setItem(this.darkModeKey, 'disabled');
        }
        
        // Trigger custom event for other components
        this.dispatchDarkModeEvent(isEnabled);
    }
    
    enableDarkMode() {
        this.bodyElement.classList.add('dark-mode');
        if (this.containerElement) {
            this.containerElement.classList.add('dark-mode');
        }
        
        // Apply to any existing CKEditor instances
        this.applyCKEditorDarkMode(true);
        
        // Add meta tag for better mobile experience
        this.updateThemeColorMeta('#1e1e2f');
        
        // Force remove white backgrounds
        setTimeout(() => this.forceRemoveWhiteBackgrounds(), 100);
    }
    
    disableDarkMode() {
        this.bodyElement.classList.remove('dark-mode');
        if (this.containerElement) {
            this.containerElement.classList.remove('dark-mode');
        }
        
        // Remove from any existing CKEditor instances
        this.applyCKEditorDarkMode(false);
        
        // Update meta tag for light theme
        this.updateThemeColorMeta('#ffffff');
    }
    
    applyCKEditorDarkMode(isDark) {
        // Apply to all CKEditor instances
        const editorElements = document.querySelectorAll('.ck.ck-editor');
        editorElements.forEach(element => {
            if (isDark) {
                element.classList.add('dark-mode');
            } else {
                element.classList.remove('dark-mode');
            }
        });
    }
    
    setupMutationObserver() {
        // Watch for new elements being added to the DOM
        const observer = new MutationObserver((mutations) => {
            if (this.isDarkModeEnabled()) {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === Node.ELEMENT_NODE) {
                                // Apply dark mode to new elements
                                setTimeout(() => {
                                    this.applyDarkModeToElement(node);
                                }, 50);
                            }
                        });
                    }
                });
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    applyDarkModeToElement(element) {
        // Check if this element or its children have white backgrounds
        const elementsToCheck = [element, ...element.querySelectorAll('*')];
        elementsToCheck.forEach(el => {
            const style = window.getComputedStyle(el);
            
            if (style.backgroundColor === 'rgb(255, 255, 255)' || 
                style.backgroundColor === 'white' ||
                style.backgroundColor === '#ffffff' ||
                style.backgroundColor === '#FFFFFF') {
                
                if (!el.classList.contains('bg-primary') && 
                    !el.classList.contains('bg-success') && 
                    !el.classList.contains('bg-warning') && 
                    !el.classList.contains('bg-danger') &&
                    !el.classList.contains('bg-info')) {
                    
                    el.style.setProperty('background-color', '#2a2a3d', 'important');
                }
            }
        });
    }
    
    initializeCKEditorDarkMode() {
        // Wait for CKEditor to be available
        const checkCKEditor = () => {
            if (typeof ClassicEditor !== 'undefined') {
                const isDarkMode = localStorage.getItem(this.darkModeKey) === 'enabled';
                if (isDarkMode) {
                    setTimeout(() => this.applyCKEditorDarkMode(true), 100);
                }
            } else {
                setTimeout(checkCKEditor, 100);
            }
        };
        
        checkCKEditor();
    }
    
    listenForSystemThemeChanges() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', (e) => {
                // Only apply system preference if user hasn't set a preference
                if (localStorage.getItem(this.darkModeKey) === null) {
                    if (e.matches) {
                        this.enableDarkMode();
                        if (this.toggleElement) this.toggleElement.checked = true;
                    } else {
                        this.disableDarkMode();
                        if (this.toggleElement) this.toggleElement.checked = false;
                    }
                }
            });
        }
    }
    
    updateThemeColorMeta(color) {
        let themeColorMeta = document.querySelector('meta[name=\"theme-color\"]');
        if (!themeColorMeta) {
            themeColorMeta = document.createElement('meta');
            themeColorMeta.name = 'theme-color';
            document.head.appendChild(themeColorMeta);
        }
        themeColorMeta.content = color;
    }
    
    dispatchDarkModeEvent(isEnabled) {
        const event = new CustomEvent('darkModeChanged', {
            detail: { enabled: isEnabled }
        });
        document.dispatchEvent(event);
    }
    
    // Public methods
    isDarkModeEnabled() {
        return this.bodyElement.classList.contains('dark-mode');
    }
    
    toggle() {
        const currentState = this.isDarkModeEnabled();
        this.handleToggle(!currentState);
        if (this.toggleElement) {
            this.toggleElement.checked = !currentState;
        }
    }
    
    // Force remove white backgrounds for aggressive override
    forceRemoveWhiteBackgrounds() {
        // Find all elements with white backgrounds and override them
        const allElements = document.querySelectorAll('*');
        allElements.forEach(element => {
            const style = window.getComputedStyle(element);
            const inlineStyle = element.style.backgroundColor;
            
            // Check if element has white background
            if (style.backgroundColor === 'rgb(255, 255, 255)' || 
                style.backgroundColor === 'white' ||
                style.backgroundColor === '#ffffff' ||
                style.backgroundColor === '#FFFFFF' ||
                inlineStyle === 'white' ||
                inlineStyle === '#ffffff' ||
                inlineStyle === '#FFFFFF' ||
                inlineStyle === 'rgb(255, 255, 255)') {
                
                // Don't override metric/status cards (they have specific colors)
                if (!element.classList.contains('bg-primary') && 
                    !element.classList.contains('bg-success') && 
                    !element.classList.contains('bg-warning') && 
                    !element.classList.contains('bg-danger') &&
                    !element.classList.contains('bg-info') &&
                    !element.closest('.card.bg-primary') &&
                    !element.closest('.card.bg-success') &&
                    !element.closest('.card.bg-warning') &&
                    !element.closest('.card.bg-danger') &&
                    !element.closest('.card.bg-info')) {
                    
                    element.style.backgroundColor = '#2a2a3d';
                    element.style.setProperty('background-color', '#2a2a3d', 'important');
                }
            }
            
            // Also check for background property
            if (style.background.includes('rgb(255, 255, 255)') ||
                style.background.includes('#ffffff') ||
                style.background.includes('#FFFFFF') ||
                style.background.includes('white')) {
                
                if (!element.classList.contains('bg-primary') && 
                    !element.classList.contains('bg-success') && 
                    !element.classList.contains('bg-warning') && 
                    !element.classList.contains('bg-danger') &&
                    !element.classList.contains('bg-info') &&
                    !element.closest('.card.bg-primary') &&
                    !element.closest('.card.bg-success') &&
                    !element.closest('.card.bg-warning') &&
                    !element.closest('.card.bg-danger') &&
                    !element.closest('.card.bg-info')) {
                    
                    element.style.background = '#2a2a3d';
                    element.style.setProperty('background', '#2a2a3d', 'important');
                }
            }
        });
    }
}

// Initialize Dark Mode Manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.darkModeManager = new DarkModeManager();
});

// Export for potential use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DarkModeManager;
}