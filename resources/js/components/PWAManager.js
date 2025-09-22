/**
 * Professional PWA Manager
 * Progressive Web App features for professional offline access
 */

class PWAManager {
    constructor() {
        this.isInstalled = false;
        this.deferredPrompt = null;

        this.init();
    }

    init() {
        console.log('📱 PWA Manager initializing...');

        try {
            this.setupInstallPrompt();
            this.setupServiceWorker();

            console.log('✅ PWA Manager initialized');
        } catch (error) {
            console.warn('PWA Manager initialization failed:', error);
        }
    }

    setupInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        window.addEventListener('appinstalled', () => {
            this.isInstalled = true;
            this.hideInstallButton();
            this.trackInstallation();
        });
    }

    setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('✅ SW registered');
                })
                .catch((error) => {
                    console.warn('SW registration failed:', error);
                });
        }
    }

    showInstallButton() {
        // Could show an install button in the future
        console.log('PWA installation available');
    }

    hideInstallButton() {
        // Hide install button after installation
        console.log('PWA installed');
    }

    trackInstallation() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_installed', {
                timestamp: new Date().toISOString()
            });
        }
    }
}

export default PWAManager;