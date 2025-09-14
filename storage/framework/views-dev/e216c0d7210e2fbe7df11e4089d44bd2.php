<!-- Enhanced Global Image Modal Component - Awards Style Design -->
<!-- Include this file in your main layout -->

<!-- Global Image Modal HTML -->
<div id="globalImageModal" class="fixed inset-0 bg-black bg-opacity-98 z-[100] hidden flex items-center justify-center p-4" style="backdrop-filter: blur(15px);">
    <div class="relative max-w-6xl max-h-full bg-slate-900/30 rounded-2xl overflow-hidden">
        <!-- Loading Spinner -->
        <div id="globalImageLoading" class="absolute inset-0 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm rounded-2xl">
            <div class="text-center">
                <div class="relative mb-6">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-slate-600 border-t-yellow-400 mx-auto"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-2xl">🖼️</div>
                    </div>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">Loading Image...</h3>
                <p class="text-gray-400 text-sm">Enhanced Awards-style Gallery</p>
            </div>
        </div>
        
        <!-- Main Image -->
        <img id="globalImageModalImg" 
             src="" 
             alt="Image Preview" 
             class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl opacity-0 transition-opacity duration-300"
             onload="GlobalImageModal.handleImageLoad(this);"
             onerror="GlobalImageModal.handleImageError(this);">
        
        <!-- Enhanced Close Button -->
        <button onclick="GlobalImageModal.close()" 
                class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-500 rounded-full p-3 transition-all duration-200 shadow-lg hover:scale-110 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Enhanced Navigation Buttons (for gallery mode) -->
        <button id="globalImagePrev" 
                onclick="GlobalImageModal.previous()" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-slate-800 bg-opacity-80 rounded-full p-4 hover:bg-opacity-100 transition-all duration-200 shadow-xl hover:scale-110 hidden">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button id="globalImageNext" 
                onclick="GlobalImageModal.next()" 
                class="absolute right-16 top-1/2 transform -translate-y-1/2 text-white bg-slate-800 bg-opacity-80 rounded-full p-4 hover:bg-opacity-100 transition-all duration-200 shadow-xl hover:scale-110 hidden">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Enhanced Gallery Navigation Dots -->
        <div id="globalImageDots" class="absolute bottom-20 left-1/2 transform -translate-x-1/2 flex space-x-2 hidden">
            <!-- Dots will be generated dynamically -->
        </div>
        
        <!-- Enhanced Image Info -->
        <div id="globalImageInfo" class="absolute bottom-4 left-4 right-4 text-center bg-black/60 rounded-lg p-3 backdrop-blur-sm">
            <h3 id="globalImageTitle" class="text-white text-lg font-semibold"></h3>
            <p id="globalImageCounter" class="text-gray-300 text-sm mt-1"></p>
            <p class="text-gray-400 text-xs mt-1">Click outside or press ESC to close</p>
        </div>
    </div>
</div>

<!-- Enhanced Global Image Modal Styles -->
<style>
#globalImageModal {
    backdrop-filter: blur(15px);
    animation: fadeIn 0.3s ease-out;
    z-index: 9999 !important;
}

#globalImageModal .relative {
    animation: scaleIn 0.3s ease-out;
    z-index: 10000 !important;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from { 
        opacity: 0; 
        transform: scale(0.9); 
    }
    to { 
        opacity: 1; 
        transform: scale(1); 
    }
}

body.modal-open {
    overflow: hidden;
}

#globalImageDots .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

#globalImageDots .dot.active {
    background-color: #fbbf24;
    transform: scale(1.3);
    border-color: rgba(251, 191, 36, 0.5);
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.4);
}

#globalImageDots .dot:hover {
    background-color: rgba(255, 255, 255, 0.7);
    transform: scale(1.1);
}

#globalImageModal button:not(.dot) {
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

#globalImageModal button:not(.dot):hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
}

@media (max-width: 768px) {
    #globalImageModal .absolute.top-4.right-4 {
        top: 1rem;
        right: 1rem;
        padding: 0.75rem;
    }
    
    #globalImageModal .absolute.left-4,
    #globalImageModal .absolute.right-16 {
        padding: 0.75rem;
    }
    
    #globalImageModal .absolute.left-4 {
        left: 1rem;
    }
    
    #globalImageModal .absolute.right-16 {
        right: 4rem;
    }
    
    #globalImageInfo {
        bottom: 1rem;
        left: 1rem;
        right: 1rem;
        padding: 0.75rem;
    }
}
</style>

<!-- Enhanced Global Image Modal JavaScript -->
<script>
window.GlobalImageModal = {
    currentGallery: [],
    currentIndex: 0,
    isGalleryMode: false,
    
    // Open single image
    open(imageUrl, title = '', alt = '') {
        this.currentGallery = [{
            url: imageUrl,
            title: title,
            alt: alt
        }];
        this.currentIndex = 0;
        this.isGalleryMode = false;
        this.showModal();
    },
    
    // Open gallery with multiple images
    openGallery(images, startIndex = 0, galleryTitle = '') {
        this.currentGallery = images || [];
        this.currentIndex = Math.max(0, Math.min(startIndex, this.currentGallery.length - 1));
        this.isGalleryMode = this.currentGallery.length > 1;
        this.showModal();
    },
    
    // Show the modal
    showModal() {
        const modal = document.getElementById('globalImageModal');
        const img = document.getElementById('globalImageModalImg');
        const loading = document.getElementById('globalImageLoading');
        
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        
        loading.classList.remove('hidden');
        img.style.opacity = '0';
        
        this.updateNavigation();
        this.loadCurrentImage();
        this.updateGalleryInfo();
    },
    
    // Close the modal
    close() {
        const modal = document.getElementById('globalImageModal');
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
        
        this.currentGallery = [];
        this.currentIndex = 0;
        this.isGalleryMode = false;
    },
    
    // Load current image
    loadCurrentImage() {
        if (!this.currentGallery[this.currentIndex]) return;
        
        const img = document.getElementById('globalImageModalImg');
        const currentImage = this.currentGallery[this.currentIndex];
        
        img.src = currentImage.url;
        img.alt = currentImage.alt || currentImage.title || 'Image';
    },
    
    // Handle image load success
    handleImageLoad(img) {
        const loading = document.getElementById('globalImageLoading');
        loading.classList.add('hidden');
        img.style.opacity = '1';
    },
    
    // Handle image load error
    handleImageError(img) {
        const loading = document.getElementById('globalImageLoading');
        loading.classList.add('hidden');
        img.style.opacity = '1';
        img.src = 'data:image/svg+xml;base64,' + btoa(`
            <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
                <rect width="400" height="300" fill="#1e293b"/>
                <text x="200" y="140" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="16">Image not found</text>
                <text x="200" y="170" text-anchor="middle" fill="#475569" font-family="Arial" font-size="14">Failed to load image</text>
            </svg>
        `);
    },
    
    // Navigate to previous image
    previous() {
        if (!this.isGalleryMode || this.currentGallery.length <= 1) return;
        
        this.currentIndex = (this.currentIndex - 1 + this.currentGallery.length) % this.currentGallery.length;
        this.showLoading();
        this.loadCurrentImage();
        this.updateGalleryInfo();
        this.updateDots();
    },
    
    // Navigate to next image
    next() {
        if (!this.isGalleryMode || this.currentGallery.length <= 1) return;
        
        this.currentIndex = (this.currentIndex + 1) % this.currentGallery.length;
        this.showLoading();
        this.loadCurrentImage();
        this.updateGalleryInfo();
        this.updateDots();
    },
    
    // Go to specific image
    goTo(index) {
        if (index < 0 || index >= this.currentGallery.length) return;
        
        this.currentIndex = index;
        this.showLoading();
        this.loadCurrentImage();
        this.updateGalleryInfo();
        this.updateDots();
    },
    
    // Show loading state
    showLoading() {
        const loading = document.getElementById('globalImageLoading');
        const img = document.getElementById('globalImageModalImg');
        
        loading.classList.remove('hidden');
        img.style.opacity = '0';
    },
    
    // Update navigation buttons visibility
    updateNavigation() {
        const prevBtn = document.getElementById('globalImagePrev');
        const nextBtn = document.getElementById('globalImageNext');
        const dotsContainer = document.getElementById('globalImageDots');
        
        if (this.isGalleryMode) {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
            dotsContainer.classList.remove('hidden');
            this.createDots();
        } else {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            dotsContainer.classList.add('hidden');
        }
    },
    
    // Create navigation dots
    createDots() {
        const dotsContainer = document.getElementById('globalImageDots');
        dotsContainer.innerHTML = '';
        
        if (this.currentGallery.length <= 1 || this.currentGallery.length > 10) {
            return;
        }
        
        this.currentGallery.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `dot ${index === this.currentIndex ? 'active' : ''}`;
            dot.onclick = () => this.goTo(index);
            dotsContainer.appendChild(dot);
        });
    },
    
    // Update dots active state
    updateDots() {
        const dots = document.querySelectorAll('#globalImageDots .dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    },
    
    // Update gallery info
    updateGalleryInfo() {
        const titleElement = document.getElementById('globalImageTitle');
        const counterElement = document.getElementById('globalImageCounter');
        
        if (!this.currentGallery[this.currentIndex]) return;
        
        const currentImage = this.currentGallery[this.currentIndex];
        titleElement.textContent = currentImage.title || `Image ${this.currentIndex + 1}`;
        
        if (this.isGalleryMode) {
            counterElement.textContent = `${this.currentIndex + 1} of ${this.currentGallery.length}`;
        } else {
            counterElement.textContent = '';
        }
    }
};

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Click outside to close
    document.getElementById('globalImageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            GlobalImageModal.close();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('globalImageModal');
        if (!modal.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    GlobalImageModal.close();
                    break;
                case 'ArrowLeft':
                    GlobalImageModal.previous();
                    break;
                case 'ArrowRight':
                    GlobalImageModal.next();
                    break;
            }
        }
    });
});

console.log('🖼️ Enhanced Global Image Modal with Awards-style design loaded successfully!');
</script>
<?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/global-image-modal.blade.php ENDPATH**/ ?>