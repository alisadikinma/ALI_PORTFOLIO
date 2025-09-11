<!-- Global Image Modal Component -->
<!-- Include this file in your main layout -->

<!-- Global Image Modal HTML -->
<div id="globalImageModal" class="fixed inset-0 bg-black bg-opacity-95 z-[80] hidden flex items-center justify-center p-4">
    <div class="relative max-w-full max-h-full">
        <!-- Loading Spinner -->
        <div id="globalImageLoading" class="absolute inset-0 flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400"></div>
        </div>
        
        <!-- Main Image -->
        <img id="globalImageModalImg" 
             src="" 
             alt="Image Preview" 
             class="max-w-full max-h-full object-contain rounded-lg shadow-2xl opacity-0 transition-opacity duration-300"
             onload="GlobalImageModal.handleImageLoad(this);"
             onerror="GlobalImageModal.handleImageError(this);">
        
        <!-- Close Button -->
        <button onclick="GlobalImageModal.close()" 
                class="absolute top-4 right-4 text-white bg-black bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all duration-300 shadow-lg z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Navigation Buttons (for gallery mode) -->
        <button id="globalImagePrev" 
                onclick="GlobalImageModal.previous()" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all duration-300 shadow-lg hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button id="globalImageNext" 
                onclick="GlobalImageModal.next()" 
                class="absolute right-16 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all duration-300 shadow-lg hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Gallery Navigation Dots (for paginated galleries) -->
        <div id="globalImageDots" class="absolute bottom-20 left-1/2 transform -translate-x-1/2 flex space-x-2 hidden">
            <!-- Dots will be generated dynamically -->
        </div>
        
        <!-- Image Info -->
        <div id="globalImageInfo" class="absolute bottom-4 left-4 right-4 text-center">
            <div class="bg-black bg-opacity-60 backdrop-blur-sm px-4 py-2 rounded-lg">
                <p id="globalImageTitle" class="text-white text-sm font-medium mb-1"></p>
                <p id="globalImageCounter" class="text-gray-300 text-xs"></p>
                <p id="globalImageGalleryInfo" class="text-gray-400 text-xs mt-1 hidden"></p>
            </div>
        </div>
    </div>
</div>

<!-- Global Image Modal Styles -->
<style>
#globalImageModal {
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease-out;
}

#globalImageModal .relative {
    animation: scaleIn 0.3s ease-out;
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

/* Hide scrollbar when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Gallery dots styling */
#globalImageDots .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
}

#globalImageDots .dot.active {
    background-color: #fbbf24;
    transform: scale(1.2);
}

#globalImageDots .dot:hover {
    background-color: rgba(255, 255, 255, 0.7);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #globalImageModal .absolute.top-4.right-4 {
        top: 1rem;
        right: 1rem;
        padding: 0.5rem;
    }
    
    #globalImageModal .absolute.left-4,
    #globalImageModal .absolute.right-16 {
        padding: 0.5rem;
    }
    
    #globalImageInfo {
        bottom: 1rem;
        left: 1rem;
        right: 1rem;
    }
    
    #globalImageDots {
        bottom: 5rem;
    }
    
    #globalImageDots .dot {
        width: 10px;
        height: 10px;
    }
}

/* Keyboard navigation hint */
.keyboard-hint {
    position: absolute;
    bottom: 4px;
    right: 4px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    opacity: 0.7;
}
</style>

<!-- Global Image Modal JavaScript -->
<script>
// Global Image Modal Object
window.GlobalImageModal = {
    isOpen: false,
    currentIndex: 0,
    images: [],
    currentTitle: '',
    galleryMode: false,
    showDots: false,
    
    // Open modal with single image
    open: function(imageUrl, title = '', altText = '') {
        console.log('GlobalImageModal: Opening single image', imageUrl);
        
        this.images = [{
            url: imageUrl,
            title: title,
            alt: altText || title || 'Image Preview'
        }];
        this.currentIndex = 0;
        this.currentTitle = title;
        this.galleryMode = false;
        this.showDots = false;
        
        this.showModal();
    },
    
    // Open modal with image gallery (array of images)
    openGallery: function(images, startIndex = 0, galleryTitle = '', showDots = true) {
        console.log('GlobalImageModal: Opening gallery', images.length, 'images, starting at', startIndex);
        
        // Normalize image data structure
        this.images = images.map((img, index) => ({
            url: img.url || img.src || img.file_url || img,
            title: img.title || img.alt || img.name || `Image ${index + 1}`,
            alt: img.alt || img.title || img.name || `Gallery Image ${index + 1}`
        }));
        
        this.currentIndex = Math.max(0, Math.min(startIndex, this.images.length - 1));
        this.currentTitle = galleryTitle;
        this.galleryMode = true;
        this.showDots = showDots && this.images.length > 1 && this.images.length <= 20; // Show dots only for reasonable number of images
        
        this.showModal();
        this.updateNavigation();
        this.updateCounter();
        this.updateDots();
    },
    
    // Show the modal
    showModal: function() {
        const modal = document.getElementById('globalImageModal');
        const img = document.getElementById('globalImageModalImg');
        const loading = document.getElementById('globalImageLoading');
        const title = document.getElementById('globalImageTitle');
        const galleryInfo = document.getElementById('globalImageGalleryInfo');
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        
        // Reset states
        img.style.opacity = '0';
        loading.style.display = 'flex';
        
        // Set image and title
        const currentImage = this.images[this.currentIndex];
        img.src = currentImage.url;
        img.alt = currentImage.alt;
        title.textContent = currentImage.title;
        
        // Show gallery info if in gallery mode
        if (this.galleryMode && this.currentTitle) {
            galleryInfo.textContent = `Gallery: ${this.currentTitle}`;
            galleryInfo.classList.remove('hidden');
        } else {
            galleryInfo.classList.add('hidden');
        }
        
        this.isOpen = true;
    },
    
    // Handle successful image load
    handleImageLoad: function(imgElement) {
        const loading = document.getElementById('globalImageLoading');
        loading.style.display = 'none';
        imgElement.style.opacity = '1';
    },
    
    // Handle image load error
    handleImageError: function(imgElement) {
        const loading = document.getElementById('globalImageLoading');
        const title = document.getElementById('globalImageTitle');
        
        loading.style.display = 'none';
        imgElement.style.opacity = '1';
        imgElement.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjNDA0MDQwIi8+Cjx0ZXh0IHg9IjEwMCIgeT0iMTAwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiBmaWxsPSIjODA4MDgwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiPkltYWdlIE5vdCBGb3VuZDwvdGV4dD4KPHN2Zz4K';
        title.textContent = 'Image could not be loaded';
    },
    
    // Navigate to previous image
    previous: function() {
        if (this.images.length <= 1) return;
        
        this.currentIndex = this.currentIndex > 0 ? this.currentIndex - 1 : this.images.length - 1;
        this.updateImage();
        this.updateCounter();
        this.updateDots();
    },
    
    // Navigate to next image
    next: function() {
        if (this.images.length <= 1) return;
        
        this.currentIndex = this.currentIndex < this.images.length - 1 ? this.currentIndex + 1 : 0;
        this.updateImage();
        this.updateCounter();
        this.updateDots();
    },
    
    // Update current image
    updateImage: function() {
        const img = document.getElementById('globalImageModalImg');
        const loading = document.getElementById('globalImageLoading');
        const title = document.getElementById('globalImageTitle');
        
        // Show loading and hide image
        img.style.opacity = '0';
        loading.style.display = 'flex';
        
        // Update image and title
        const currentImage = this.images[this.currentIndex];
        img.src = currentImage.url;
        img.alt = currentImage.alt;
        title.textContent = currentImage.title;
    },
    
    // Update navigation buttons visibility
    updateNavigation: function() {
        const prevBtn = document.getElementById('globalImagePrev');
        const nextBtn = document.getElementById('globalImageNext');
        
        if (this.galleryMode && this.images.length > 1) {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
        } else {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
        }
    },
    
    // Update counter display
    updateCounter: function() {
        const counter = document.getElementById('globalImageCounter');
        
        if (this.galleryMode && this.images.length > 1) {
            counter.textContent = `${this.currentIndex + 1} of ${this.images.length}`;
            counter.style.display = 'block';
        } else {
            counter.style.display = 'none';
        }
    },
    
    // Update navigation dots
    updateDots: function() {
        const dotsContainer = document.getElementById('globalImageDots');
        
        if (!this.showDots || this.images.length <= 1) {
            dotsContainer.classList.add('hidden');
            return;
        }
        
        // Generate dots
        let dotsHtml = '';
        for (let i = 0; i < this.images.length; i++) {
            const activeClass = i === this.currentIndex ? 'active' : '';
            dotsHtml += `<div class="dot ${activeClass}" onclick="GlobalImageModal.goToImage(${i})"></div>`;
        }
        
        dotsContainer.innerHTML = dotsHtml;
        dotsContainer.classList.remove('hidden');
    },
    
    // Close modal
    close: function() {
        const modal = document.getElementById('globalImageModal');
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
        
        // Reset states
        this.isOpen = false;
        this.images = [];
        this.currentIndex = 0;
        this.currentTitle = '';
        this.galleryMode = false;
        this.showDots = false;
        
        // Clear image source to prevent memory leaks
        const img = document.getElementById('globalImageModalImg');
        img.src = '';
        
        // Hide dots
        const dotsContainer = document.getElementById('globalImageDots');
        dotsContainer.classList.add('hidden');
    },
    
    // Go to specific image index
    goToImage: function(index) {
        if (index >= 0 && index < this.images.length && index !== this.currentIndex) {
            this.currentIndex = index;
            this.updateImage();
            this.updateCounter();
            this.updateDots();
        }
    },
    
    // Toggle gallery view mode (for future enhancements)
    toggleViewMode: function() {
        // Future enhancement: Toggle between single view and grid view
        console.log('Toggle view mode - Feature coming soon');
    },
    
    // Preload adjacent images for better performance
    preloadAdjacentImages: function() {
        const preloadIndices = [];
        
        // Add previous image
        if (this.currentIndex > 0) {
            preloadIndices.push(this.currentIndex - 1);
        }
        
        // Add next image
        if (this.currentIndex < this.images.length - 1) {
            preloadIndices.push(this.currentIndex + 1);
        }
        
        preloadIndices.forEach(index => {
            if (this.images[index]) {
                const img = new Image();
                img.src = this.images[index].url;
            }
        });
    }
};

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside the image
    document.getElementById('globalImageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            GlobalImageModal.close();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!GlobalImageModal.isOpen) return;
        
        switch(e.key) {
            case 'Escape':
                GlobalImageModal.close();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                GlobalImageModal.previous();
                break;
            case 'ArrowRight':
                e.preventDefault();
                GlobalImageModal.next();
                break;
            case 'Home':
                e.preventDefault();
                GlobalImageModal.goToImage(0);
                break;
            case 'End':
                e.preventDefault();
                GlobalImageModal.goToImage(GlobalImageModal.images.length - 1);
                break;
            case ' ':
                e.preventDefault();
                GlobalImageModal.next();
                break;
        }
    });
    
    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.getElementById('globalImageModal').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    document.getElementById('globalImageModal').addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        
        const swipeThreshold = 50;
        const swipeDistance = touchStartX - touchEndX;
        
        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                // Swipe left - next image
                GlobalImageModal.next();
            } else {
                // Swipe right - previous image
                GlobalImageModal.previous();
            }
        }
    });
});

// Global functions for backward compatibility and ease of use
window.openImageModal = function(imageUrl, title, altText) {
    GlobalImageModal.open(imageUrl, title, altText);
};

window.openImageGallery = function(images, startIndex, galleryTitle) {
    GlobalImageModal.openGallery(images, startIndex, galleryTitle);
};

window.closeImageModal = function() {
    GlobalImageModal.close();
};

console.log('Global Image Modal loaded successfully');
</script>