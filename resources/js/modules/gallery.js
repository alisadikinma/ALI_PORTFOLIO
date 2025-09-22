/**
 * Professional Gallery Manager
 * Manufacturing Project Gallery Enhancement
 */

export class GalleryManager {
    constructor() {
        this.isInitialized = false;
        this.currentIndex = 0;
        this.galleryItems = [];

        this.init();
    }

    init() {
        console.log('🖼️ Gallery Manager initializing...');

        try {
            this.setupGalleryElements();
            this.setupLightbox();
            this.setupImageOptimization();

            this.isInitialized = true;
            console.log('✅ Gallery Manager initialized');
        } catch (error) {
            console.warn('Gallery Manager initialization failed:', error);
        }
    }

    setupGalleryElements() {
        this.galleryItems = document.querySelectorAll('.gallery-item, .gallery-image');

        this.galleryItems.forEach((item, index) => {
            item.addEventListener('click', (e) => {
                this.openLightbox(index);
            });
        });
    }

    setupLightbox() {
        // Create lightbox if it doesn't exist
        if (!document.getElementById('gallery-lightbox')) {
            this.createLightbox();
        }
    }

    createLightbox() {
        const lightbox = document.createElement('div');
        lightbox.id = 'gallery-lightbox';
        lightbox.className = 'fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center';
        lightbox.innerHTML = `
            <div class="relative max-w-4xl max-h-full p-4">
                <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
                <button id="lightbox-close" class="absolute top-4 right-4 text-white text-2xl hover:text-yellow-400">
                    ✕
                </button>
                <button id="lightbox-prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-yellow-400">
                    ‹
                </button>
                <button id="lightbox-next" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-yellow-400">
                    ›
                </button>
            </div>
        `;

        document.body.appendChild(lightbox);

        // Setup lightbox controls
        document.getElementById('lightbox-close').addEventListener('click', () => this.closeLightbox());
        document.getElementById('lightbox-prev').addEventListener('click', () => this.previousImage());
        document.getElementById('lightbox-next').addEventListener('click', () => this.nextImage());

        // Close on overlay click
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                this.closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') this.closeLightbox();
                if (e.key === 'ArrowLeft') this.previousImage();
                if (e.key === 'ArrowRight') this.nextImage();
            }
        });
    }

    setupImageOptimization() {
        // Add loading="lazy" to gallery images
        this.galleryItems.forEach(item => {
            const img = item.querySelector('img') || item;
            if (img.tagName === 'IMG') {
                img.loading = 'lazy';
                img.style.transition = 'all 0.3s ease';
            }
        });
    }

    openLightbox(index) {
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxImage = document.getElementById('lightbox-image');

        this.currentIndex = index;
        const currentItem = this.galleryItems[index];
        const img = currentItem.querySelector('img') || currentItem;

        lightboxImage.src = img.src;
        lightboxImage.alt = img.alt;

        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        this.trackGalleryView(index);
    }

    closeLightbox() {
        const lightbox = document.getElementById('gallery-lightbox');
        lightbox.classList.add('hidden');
        document.body.style.overflow = '';
    }

    previousImage() {
        this.currentIndex = this.currentIndex > 0 ? this.currentIndex - 1 : this.galleryItems.length - 1;
        this.updateLightboxImage();
    }

    nextImage() {
        this.currentIndex = this.currentIndex < this.galleryItems.length - 1 ? this.currentIndex + 1 : 0;
        this.updateLightboxImage();
    }

    updateLightboxImage() {
        const lightboxImage = document.getElementById('lightbox-image');
        const currentItem = this.galleryItems[this.currentIndex];
        const img = currentItem.querySelector('img') || currentItem;

        lightboxImage.src = img.src;
        lightboxImage.alt = img.alt;

        this.trackGalleryView(this.currentIndex);
    }

    trackGalleryView(index) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'gallery_view', {
                image_index: index,
                timestamp: new Date().toISOString()
            });
        }
    }
}

export default GalleryManager;