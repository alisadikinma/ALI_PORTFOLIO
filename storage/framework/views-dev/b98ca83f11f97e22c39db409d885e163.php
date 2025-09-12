<!-- Global Gallery Loader Component -->
<!-- Include this file in your main layout after global-image-modal.blade.php -->

<script>
// Global Gallery Loader Object
window.GlobalGalleryLoader = {
    
    // Pagination settings
    itemsPerPage: 6, // 2x3 grid
    currentPage: 1,
    totalPages: 1,
    allItems: [],
    currentType: '',
    currentId: '',
    currentItemName: '',
    currentContainerId: '',
    
    // Load gallery items for any type (award/gallery)
    async loadGalleryItems(type, id, itemName = '') {
        console.log(`GlobalGalleryLoader: Loading ${type} gallery for ID: ${id}`);
        
        // Get base URL and path
        const baseUrl = window.location.origin;
        const currentPath = window.location.pathname;
        
        // Determine base path
        let basePath = '';
        if (currentPath.includes('/ALI_PORTFOLIO/')) {
            basePath = '/ALI_PORTFOLIO';
        } else if (currentPath.startsWith('/ALI_PORTFOLIO')) {
            basePath = '/ALI_PORTFOLIO';
        }
        
        // API endpoints to try (in order of preference)
        const apiUrls = [
            `${baseUrl}${basePath}/public/global_gallery_api.php?type=${type}&id=${id}`,
            `${baseUrl}${basePath}/global_gallery_api.php?type=${type}&id=${id}`,
            `${baseUrl}/ALI_PORTFOLIO/public/global_gallery_api.php?type=${type}&id=${id}`,
            `${baseUrl}/ALI_PORTFOLIO/global_gallery_api.php?type=${type}&id=${id}`,
            // Fallback to old endpoints
            `${baseUrl}${basePath}/gallery_api.php?${type}_id=${id}`,
            `${baseUrl}${basePath}/test_gallery_api.php?${type}_id=${id}`
        ];
        
        console.log('Trying URLs:', apiUrls);
        
        // Try each URL until one works
        let lastError = null;
        
        for (const url of apiUrls) {
            try {
                console.log(`Trying URL: ${url}`);
                
                // Reduced timeout to 3 seconds for faster no-data detection
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 3000); // Reduced from 10s to 3s
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                console.log(`Response from ${url}:`, response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Gallery data received:', data);
                    
                    return {
                        success: true,
                        data: data,
                        url: url
                    };
                } else {
                    lastError = `HTTP ${response.status}`;
                }
                
            } catch (error) {
                console.log(`Error with ${url}:`, error.message);
                lastError = error.message;
                
                // If it's a timeout or abort error, treat as no data available
                if (error.name === 'AbortError') {
                    lastError = 'Request timeout - no data available';
                }
            }
        }
        
        // If all URLs failed, assume no data rather than error
        console.log('All API attempts failed, treating as no data available');
        return {
            success: true, // Change to true to trigger no-data state instead of error
            data: { success: false, items: [] },
            error: lastError,
            attemptedUrls: apiUrls
        };
    },
    
    // Initialize gallery with pagination
    initializeGallery(items, containerElementId, galleryName, type = 'general') {
        // Store current gallery data
        this.allItems = items || [];
        this.currentType = type;
        this.currentItemName = galleryName;
        this.currentContainerId = containerElementId;
        this.currentPage = 1;
        this.totalPages = Math.ceil(this.allItems.length / this.itemsPerPage);
        
        // Display first page
        this.displayCurrentPage();
    },
    
    // Display current page items
    displayCurrentPage() {
        const container = document.getElementById(this.currentContainerId);
        if (!container) {
            console.error('Container element not found:', this.currentContainerId);
            return;
        }
        
        // Check if we have any items
        if (!this.allItems || this.allItems.length === 0) {
            this.showEmptyGallery(container, this.currentItemName, this.currentType);
            return;
        }
        
        // Filter valid items
        const validItems = this.allItems.filter(item => item);
        
        // Calculate pagination
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const currentPageItems = validItems.slice(startIndex, endIndex);
        
        // Prepare images for global gallery modal (all images, not just current page)
        const galleryImages = validItems
            .filter(item => item.type === 'image' && item.file_url)
            .map((item, index) => ({
                url: item.file_url,
                title: `${this.currentItemName} - Image ${index + 1}`,
                alt: `${this.currentType} image ${index + 1}`
            }));
        
        // Build content with standardized 2x3 grid layout
        let content = `
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        `;
        
        currentPageItems.forEach((item, index) => {
            const globalIndex = startIndex + index; // Global index for gallery modal navigation
            const imageUrl = item.file_url || '';
            const title = item.gallery_name || this.currentItemName || 'Gallery Item';
            
            content += `
                <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group cursor-pointer" style="height: 280px;">
                    <div class="relative" style="height: 220px; overflow: hidden;">
            `;
            
            if (item.type === 'image' && imageUrl) {
                // Find the index of this image in the gallery images array for navigation
                const galleryIndex = galleryImages.findIndex(img => img.url === imageUrl);
                
                content += `
                        <img src="${imageUrl}" 
                             alt="${title}" 
                             class="w-full h-full object-cover group-hover:opacity-90 transition-opacity" 
                             onclick="GlobalImageModal.openGallery(${JSON.stringify(galleryImages).replace(/"/g, '&quot;')}, ${galleryIndex}, '${this.currentItemName}')"
                             style="object-fit: cover; width: 100%; height: 100%;"
                             onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm\\' style=\\'height: 220px;\\'>Image Error</div>';">
                `;
            } else if (item.type === 'youtube' && item.youtube_url) {
                const videoId = this.extractYouTubeId(item.youtube_url);
                const thumbnailUrl = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
                
                content += `
                        ${thumbnailUrl ? 
                            `<img src="${thumbnailUrl}" alt="${title}" class="w-full h-full object-cover" style="object-fit: cover; width: 100%; height: 100%;">` :
                            `<div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm" style="height: 220px;">YouTube Video</div>`
                        }
                        <div class="absolute inset-0 flex items-center justify-center" onclick="GlobalGalleryLoader.openYouTubeModal('${item.youtube_url}', '${title}')">
                            <div class="bg-red-600 rounded-full p-3 group-hover:bg-red-700 transition-colors">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                `;
            } else {
                content += `
                        <div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm" style="height: 220px;">
                            <div class="text-center">
                                <i class="fas fa-file text-xl mb-2"></i>
                                <p>Unknown type: ${item.type}</p>
                            </div>
                        </div>
                `;
            }
            
            content += `
                        <!-- Type Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">${item.type}</span>
                        </div>
                        ${item.sequence ? `<div class="absolute top-2 left-2">
                            <span class="bg-blue-500 bg-opacity-80 text-white text-xs px-2 py-1 rounded">#${item.sequence}</span>
                        </div>` : ''}
                    </div>
                    
                    <!-- Content with fixed height -->
                    <div class="p-3" style="height: 60px; display: flex; flex-direction: column; justify-content: center;">
                        <h4 class="text-white font-semibold text-sm mb-1 truncate">${title}</h4>
                        <p class="text-gray-400 text-xs">Item ${globalIndex + 1} of ${validItems.length}</p>
                    </div>
                </div>
            `;
        });
        
        content += '</div>';
        
        // Add pagination controls if needed
        if (this.totalPages > 1) {
            content += this.buildPaginationControls();
        }
        
        container.innerHTML = content;
    },
    
    // Build pagination controls
    buildPaginationControls() {
        const totalItems = this.allItems.length;
        const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.itemsPerPage, totalItems);
        
        let paginationHtml = `
            <div class="flex flex-col sm:flex-row items-center justify-between bg-slate-800 rounded-lg p-4 gap-4">
                <!-- Info Text -->
                <div class="text-gray-300 text-sm">
                    Showing <span class="font-semibold text-white">${startItem}</span> to 
                    <span class="font-semibold text-white">${endItem}</span> of 
                    <span class="font-semibold text-white">${totalItems}</span> items
                </div>
                
                <!-- Pagination Buttons -->
                <div class="flex items-center space-x-2">
        `;
        
        // Previous button
        paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage - 1})" 
                            class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-colors ${this.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                            ${this.currentPage === 1 ? 'disabled' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
        `;
        
        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(this.totalPages, startPage + maxVisiblePages - 1);
        
        // Adjust start page if we're near the end
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        // Add first page and ellipsis if needed
        if (startPage > 1) {
            paginationHtml += `
                        <button onclick="GlobalGalleryLoader.goToPage(1)" 
                                class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-colors">
                            1
                        </button>
            `;
            if (startPage > 2) {
                paginationHtml += `<span class="text-gray-500">...</span>`;
            }
        }
        
        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === this.currentPage;
            paginationHtml += `
                        <button onclick="GlobalGalleryLoader.goToPage(${i})" 
                                class="px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
                                    isActive 
                                        ? 'bg-yellow-500 text-black font-semibold' 
                                        : 'text-gray-400 bg-slate-700 hover:bg-slate-600 hover:text-white'
                                }">
                            ${i}
                        </button>
            `;
        }
        
        // Add last page and ellipsis if needed
        if (endPage < this.totalPages) {
            if (endPage < this.totalPages - 1) {
                paginationHtml += `<span class="text-gray-500">...</span>`;
            }
            paginationHtml += `
                        <button onclick="GlobalGalleryLoader.goToPage(${this.totalPages})" 
                                class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-colors">
                            ${this.totalPages}
                        </button>
            `;
        }
        
        // Next button
        paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage + 1})" 
                            class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-colors ${this.currentPage === this.totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
                            ${this.currentPage === this.totalPages ? 'disabled' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return paginationHtml;
    },
    
    // Navigate to specific page
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
            this.currentPage = page;
            this.displayCurrentPage();
        }
    },
    
    // Display gallery items in a container (main entry point)
    displayGalleryItems(items, containerElementId, galleryName, type = 'general') {
        const container = document.getElementById(containerElementId);
        if (!container) {
            console.error('Container element not found:', containerElementId);
            return;
        }
        
        if (!items || items.length === 0) {
            this.showEmptyGallery(container, galleryName, type);
            return;
        }
        
        // Initialize gallery with pagination
        this.initializeGallery(items, containerElementId, galleryName, type);
    },
    
    // Show empty gallery state
    showEmptyGallery(container, itemName, type) {
        const emoji = type === 'award' ? '🏆' : '🖼️';
        const title = type === 'award' ? 'No Award Gallery' : 'No Gallery Items';
        
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-yellow-400 text-6xl mb-4">${emoji}</div>
                <h3 class="text-white text-xl font-semibold mb-2">${title}</h3>
                <p class="text-gray-400 mb-4">"${itemName}" doesn't have any gallery items yet.</p>
                <div class="text-gray-500 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    No data available to display
                </div>
            </div>
        `;
    },
    
    // Show loading state with faster timeout
    showLoading(container, itemName, type, id) {
        container.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                    <p class="text-gray-400 text-lg">Loading gallery...</p>
                    <p class="text-gray-500 text-sm mt-2">${type.charAt(0).toUpperCase() + type.slice(1)} ID: ${id}</p>
                    <p class="text-gray-500 text-xs mt-1">Will show "No Data" if not found within 3 seconds</p>
                </div>
            </div>
        `;
    },
    
    // Show error state
    showError(container, itemName, type, id, error, attemptedUrls) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-red-400 text-6xl mb-4">⚠️</div>
                <h3 class="text-white text-xl font-semibold mb-2">Error Loading Gallery</h3>
                <p class="text-gray-400 mb-4">Failed to load gallery items for "${itemName}".</p>
                <div class="text-left bg-slate-700 p-4 rounded-lg text-sm max-w-2xl mx-auto">
                    <p class="text-gray-300 mb-2"><strong>Debug Info:</strong></p>
                    <p class="text-gray-400">${type.charAt(0).toUpperCase() + type.slice(1)} ID: ${id}</p>
                    <p class="text-gray-400">Last Error: ${error}</p>
                    <p class="text-gray-400">Attempted URLs:</p>
                    <ul class="text-gray-400 text-xs mt-1 ml-4">
                        ${attemptedUrls.map(url => `<li>• ${url}</li>`).join('')}
                    </ul>
                </div>
                <button onclick="this.retryLoad('${type}', '${id}', '${itemName}')" 
                        class="mt-4 px-6 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-400 transition-colors">
                    Retry
                </button>
            </div>
        `;
    },
    
    // Helper function to extract YouTube ID
    extractYouTubeId(url) {
        if (!url) return null;
        const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
        const match = url.match(regex);
        return match ? match[1] : null;
    },
    
    // Open YouTube modal with responsive container sizing
    openYouTubeModal(youtubeUrl, title) {
        const videoId = this.extractYouTubeId(youtubeUrl);
        const embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1` : youtubeUrl;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-60 flex items-center justify-center p-4';
        modal.style.maxHeight = '100vh';
        modal.style.overflow = 'auto';
        
        modal.innerHTML = `
            <div class="relative w-full max-w-4xl max-h-full">
                <div class="relative w-full bg-black rounded-lg overflow-hidden" style="max-height: 80vh; padding-bottom: min(56.25%, 80vh - 120px);">
                    <iframe 
                        class="absolute top-0 left-0 w-full h-full" 
                        src="${embedUrl}" 
                        title="${title}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" 
                        class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors z-70">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="text-center mt-4">
                    <h3 class="text-white text-lg font-semibold">${title}</h3>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Close on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
                document.body.style.overflow = '';
            }
        });
    }
};

// Enhanced GlobalImageModal for responsive image sizing
if (typeof window.GlobalImageModal === 'undefined') {
    window.GlobalImageModal = {
        currentImages: [],
        currentIndex: 0,
        currentGalleryName: '',
        
        // Open gallery with proper image sizing
        openGallery(images, startIndex = 0, galleryName = 'Gallery') {
            this.currentImages = images || [];
            this.currentIndex = startIndex || 0;
            this.currentGalleryName = galleryName;
            
            if (this.currentImages.length === 0) {
                console.warn('No images provided to gallery modal');
                return;
            }
            
            this.showModal();
        },
        
        // Show modal with responsive image container
        showModal() {
            if (this.currentIndex < 0 || this.currentIndex >= this.currentImages.length) {
                console.warn('Invalid image index:', this.currentIndex);
                return;
            }
            
            const currentImage = this.currentImages[this.currentIndex];
            
            // Remove existing modal
            const existingModal = document.getElementById('global-image-modal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create modal with responsive container
            const modal = document.createElement('div');
            modal.id = 'global-image-modal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center';
            modal.style.padding = '20px';
            modal.style.boxSizing = 'border-box';
            
            modal.innerHTML = `
                <div class="relative w-full h-full max-w-6xl max-h-full flex flex-col">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4 flex-shrink-0">
                        <div class="text-white">
                            <h3 class="text-xl font-semibold">${this.currentGalleryName}</h3>
                            <p class="text-gray-300 text-sm">Image ${this.currentIndex + 1} of ${this.currentImages.length}</p>
                        </div>
                        <button onclick="GlobalImageModal.closeModal()" 
                                class="text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Image Container with controlled sizing -->
                    <div class="flex-1 flex items-center justify-center relative overflow-hidden" style="min-height: 0;">
                        <img id="modal-image" 
                             src="${currentImage.url}" 
                             alt="${currentImage.alt || currentImage.title}" 
                             class="max-w-full max-h-full object-contain"
                             style="max-width: 100%; max-height: 100%; width: auto; height: auto; display: block;"
                             onload="this.style.opacity = '1';"
                             style="opacity: 0; transition: opacity 0.3s ease;">
                        
                        <!-- Loading indicator -->
                        <div id="modal-loading" class="absolute inset-0 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                        </div>
                        
                        <!-- Navigation arrows -->
                        ${this.currentImages.length > 1 ? `
                            <button onclick="GlobalImageModal.previousImage()" 
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-colors ${this.currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${this.currentIndex === 0 ? 'disabled' : ''}>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button onclick="GlobalImageModal.nextImage()" 
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-colors ${this.currentIndex === this.currentImages.length - 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${this.currentIndex === this.currentImages.length - 1 ? 'disabled' : ''}>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-4 text-center flex-shrink-0">
                        <p class="text-white font-medium">${currentImage.title}</p>
                        ${this.currentImages.length > 1 ? `
                            <div class="flex justify-center mt-2 space-x-1">
                                ${this.currentImages.map((_, index) => `
                                    <button onclick="GlobalImageModal.goToImage(${index})"
                                            class="w-2 h-2 rounded-full transition-colors ${
                                                index === this.currentIndex 
                                                    ? 'bg-white' 
                                                    : 'bg-gray-500 hover:bg-gray-300'
                                            }"></button>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            // Handle image load events
            const img = document.getElementById('modal-image');
            const loading = document.getElementById('modal-loading');
            
            img.onload = function() {
                loading.style.display = 'none';
                this.style.opacity = '1';
            };
            
            img.onerror = function() {
                loading.innerHTML = `
                    <div class="text-center text-white">
                        <div class="text-red-400 text-4xl mb-2">⚠️</div>
                        <p>Failed to load image</p>
                    </div>
                `;
            };
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    GlobalImageModal.closeModal();
                }
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', this.handleKeyPress);
        },
        
        // Navigation methods
        previousImage() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.showModal();
            }
        },
        
        nextImage() {
            if (this.currentIndex < this.currentImages.length - 1) {
                this.currentIndex++;
                this.showModal();
            }
        },
        
        goToImage(index) {
            if (index >= 0 && index < this.currentImages.length) {
                this.currentIndex = index;
                this.showModal();
            }
        },
        
        // Close modal
        closeModal() {
            const modal = document.getElementById('global-image-modal');
            if (modal) {
                modal.remove();
            }
            document.body.style.overflow = '';
            document.removeEventListener('keydown', this.handleKeyPress);
        },
        
        // Keyboard handler
        handleKeyPress(e) {
            switch(e.key) {
                case 'Escape':
                    GlobalImageModal.closeModal();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    GlobalImageModal.previousImage();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    GlobalImageModal.nextImage();
                    break;
            }
        }
    };
}

// Backward compatibility functions with faster no-data handling
window.loadAwardGallery = async function(awardId, awardName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, awardName, 'award', awardId);
    
    console.log(`🔍 Starting loadAwardGallery for ID: ${awardId}, Name: ${awardName}`);
    
    // Reduced loading timeout to 3 seconds for faster no-data detection
    const loadingTimeout = setTimeout(() => {
        console.log('⏰ Loading timeout reached - showing no data state');
        GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
    }, 3000); // Reduced from 8s to 3s
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('award', awardId, awardName);
        
        clearTimeout(loadingTimeout); // Clear timeout if we get a response
        
        console.log('🎯 Award Gallery API Full Result:', result);
        console.log('📊 Result.success:', result.success);
        console.log('📊 Result.data:', result.data);
        
        // Immediate check for obvious no-data scenarios
        if (!result || !result.success) {
            console.log('❌ API failed or no success flag - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        // Check multiple possible data structures with detailed logging
        let items = null;
        let hasValidData = false;
        
        console.log('🔍 Analyzing data structure...');
        
        if (!result.data) {
            console.log('❌ No result.data found - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        // Log the exact structure we received
        console.log('📋 Data structure type:', typeof result.data);
        console.log('📋 Data is array:', Array.isArray(result.data));
        console.log('📋 Data keys:', Object.keys(result.data));
        
        // Try different possible API response structures
        if (result.data.success === true && result.data.items) {
            console.log('✅ Found data.success=true with items');
            items = result.data.items;
        } else if (result.data.success === false) {
            console.log('❌ API returned success=false - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        } else if (Array.isArray(result.data)) {
            console.log('✅ Data is direct array');
            items = result.data;
        } else if (result.data.data) {
            console.log('✅ Found nested data.data');
            items = result.data.data;
        } else if (result.data.items) {
            console.log('✅ Found data.items (without success flag)');
            items = result.data.items;
        } else {
            console.log('❌ Unknown data structure - showing empty state');
            console.log('🔍 Full data object:', result.data);
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        console.log('📦 Extracted items:', items);
        console.log('📦 Items type:', typeof items);
        console.log('📦 Items is array:', Array.isArray(items));
        console.log('📦 Items length:', items ? items.length : 'null');
        
        // Check if we have valid items
        if (!items) {
            console.log('❌ Items is null/undefined - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        if (!Array.isArray(items)) {
            console.log('❌ Items is not an array - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        if (items.length === 0) {
            console.log('❌ Items array is empty - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        // Filter out null/undefined items
        const validItems = items.filter(item => item !== null && item !== undefined);
        console.log('✅ Valid items after filtering:', validItems.length);
        
        if (validItems.length === 0) {
            console.log('❌ No valid items after filtering - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        console.log('🎉 Award gallery data found - displaying items');
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, awardName, 'award');
        
    } catch (error) {
        clearTimeout(loadingTimeout);
        console.error('🚨 Error in loadAwardGallery:', error);
        GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
    }
};

window.loadGalleryItems = async function(galleryId, galleryName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, galleryName, 'gallery', galleryId);
    
    console.log(`🔍 Starting loadGalleryItems for ID: ${galleryId}, Name: ${galleryName}`);
    
    // Reduced loading timeout to 3 seconds for faster no-data detection
    const loadingTimeout = setTimeout(() => {
        console.log('⏰ Loading timeout reached - showing no data state');
        GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
    }, 3000); // Reduced from 8s to 3s
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('gallery', galleryId, galleryName);
        
        clearTimeout(loadingTimeout); // Clear timeout if we get a response
        
        console.log('🎯 Gallery API Full Result:', result);
        console.log('📊 Result.success:', result.success);
        console.log('📊 Result.data:', result.data);
        
        // Immediate check for obvious no-data scenarios
        if (!result || !result.success) {
            console.log('❌ API failed or no success flag - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        // Check multiple possible data structures with detailed logging
        let items = null;
        let hasValidData = false;
        
        console.log('🔍 Analyzing data structure...');
        
        if (!result.data) {
            console.log('❌ No result.data found - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        // Log the exact structure we received
        console.log('📋 Data structure type:', typeof result.data);
        console.log('📋 Data is array:', Array.isArray(result.data));
        console.log('📋 Data keys:', Object.keys(result.data));
        
        // Try different possible API response structures
        if (result.data.success === true && result.data.items) {
            console.log('✅ Found data.success=true with items');
            items = result.data.items;
        } else if (result.data.success === false) {
            console.log('❌ API returned success=false - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        } else if (Array.isArray(result.data)) {
            console.log('✅ Data is direct array');
            items = result.data;
        } else if (result.data.data) {
            console.log('✅ Found nested data.data');
            items = result.data.data;
        } else if (result.data.items) {
            console.log('✅ Found data.items (without success flag)');
            items = result.data.items;
        } else {
            console.log('❌ Unknown data structure - showing empty state');
            console.log('🔍 Full data object:', result.data);
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        console.log('📦 Extracted items:', items);
        console.log('📦 Items type:', typeof items);
        console.log('📦 Items is array:', Array.isArray(items));
        console.log('📦 Items length:', items ? items.length : 'null');
        
        // Check if we have valid items
        if (!items) {
            console.log('❌ Items is null/undefined - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        if (!Array.isArray(items)) {
            console.log('❌ Items is not an array - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        if (items.length === 0) {
            console.log('❌ Items array is empty - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        // Filter out null/undefined items
        const validItems = items.filter(item => item !== null && item !== undefined);
        console.log('✅ Valid items after filtering:', validItems.length);
        
        if (validItems.length === 0) {
            console.log('❌ No valid items after filtering - showing empty state');
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        console.log('🎉 Gallery data found - displaying items');
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, galleryName, 'gallery');
        
    } catch (error) {
        clearTimeout(loadingTimeout);
        console.error('🚨 Error in loadGalleryItems:', error);
        GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
    }
};

console.log('Enhanced Global Gallery Loader loaded successfully with faster no-data detection and responsive image modal');
</script><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/global-gallery-loader.blade.php ENDPATH**/ ?>