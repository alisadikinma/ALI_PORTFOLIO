<!-- Enhanced Global Gallery Loader Component with Improved 2x3 Grid and Pagination -->
<!-- Include this file in your main layout after global-image-modal.blade.php -->

<script>
// Enhanced Global Gallery Loader Object
window.GlobalGalleryLoader = {
    
    // Pagination settings - Enhanced 2x3 grid
    itemsPerPage: 6, // 2x3 grid layout (2 columns x 3 rows)
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
                
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 8000);
                
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
                
                if (error.name === 'AbortError') {
                    lastError = 'Request timeout - no data available';
                }
            }
        }
        
        console.log('All API attempts failed, treating as no data available');
        return {
            success: true,
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
    
    // Display current page items with enhanced 2x3 grid
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
        
        // Prepare images for global gallery modal
        const galleryImages = validItems
            .filter(item => item.type === 'image' && item.file_url)
            .map((item, index) => ({
                url: item.file_url,
                title: `${this.currentItemName} - Image ${index + 1}`,
                alt: `${this.currentType} image ${index + 1}`
            }));
        
        // Build content with enhanced 2x3 grid layout
        let content = `
            <!-- Enhanced 2x3 Grid Gallery -->
            <div class="enhanced-gallery-grid grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6" 
                 style="min-height: ${this.calculateGridHeight()}px;">
        `;
        
        // Fill grid items
        for (let i = 0; i < this.itemsPerPage; i++) {
            const item = currentPageItems[i];
            const globalIndex = startIndex + i;
            
            if (item) {
                // Actual item
                content += this.buildGalleryItem(item, globalIndex, galleryImages);
            } else {
                // Empty placeholder for consistent grid
                content += this.buildEmptyPlaceholder();
            }
        }
        
        content += '</div>';
        
        // Add enhanced pagination controls
        if (this.totalPages > 1) {
            content += this.buildEnhancedPaginationControls();
        }
        
        // Add gallery statistics
        content += this.buildGalleryStats(validItems.length, currentPageItems.length);
        
        container.innerHTML = content;
        
        // Apply enhanced grid styling
        this.applyEnhancedGridStyling();
    },
    
    // Build individual gallery item
    buildGalleryItem(item, globalIndex, galleryImages) {
        const imageUrl = item.file_url || '';
        const title = item.gallery_name || this.currentItemName || 'Gallery Item';
        
        let content = `
            <div class="gallery-item bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group cursor-pointer shadow-lg" 
                 style="height: 280px;">
                <div class="gallery-item-media relative" style="height: 220px; overflow: hidden;">
        `;
        
        if (item.type === 'image' && imageUrl) {
            const galleryIndex = galleryImages.findIndex(img => img.url === imageUrl);
            
            content += `
                    <img src="${imageUrl}" 
                         alt="${title}" 
                         class="w-full h-full object-cover group-hover:opacity-90 transition-opacity" 
                         onclick="GlobalImageModal.openGallery(${JSON.stringify(galleryImages).replace(/"/g, '&quot;')}, ${galleryIndex}, '${this.currentItemName}')"
                         style="object-fit: cover; width: 100%; height: 100%;"
                         loading="lazy"
                         onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm\\' style=\\'height: 220px;\\'>Image Error</div>';">
            `;
        } else if (item.type === 'youtube' && item.youtube_url) {
            const videoId = this.extractYouTubeId(item.youtube_url);
            const thumbnailUrl = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
            
            content += `
                    ${thumbnailUrl ? 
                        `<img src="${thumbnailUrl}" alt="${title}" class="w-full h-full object-cover" style="object-fit: cover; width: 100%; height: 100%;" loading="lazy">` :
                        `<div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm" style="height: 220px;">YouTube Video</div>`
                    }
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity" 
                         onclick="GlobalGalleryLoader.openYouTubeModal('${item.youtube_url}', '${title}')">
                        <div class="bg-red-600 rounded-full p-4 group-hover:bg-red-700 transition-colors shadow-lg">
                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
            `;
        } else {
            content += `
                    <div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm" style="height: 220px;">
                        <div class="text-center">
                            <i class="fas fa-file text-2xl mb-2"></i>
                            <p>Unknown Type</p>
                            <p class="text-xs text-gray-400">${item.type || 'N/A'}</p>
                        </div>
                    </div>
            `;
        }
        
        content += `
                    <!-- Enhanced Type Badge -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded-full font-medium">
                            ${this.getTypeIcon(item.type)} ${item.type}
                        </span>
                    </div>
                    
                    ${item.sequence ? `
                    <div class="absolute top-2 left-2">
                        <span class="bg-blue-500 bg-opacity-90 text-white text-xs px-2 py-1 rounded-full font-medium">
                            #${item.sequence}
                        </span>
                    </div>` : ''}
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-60 transition-opacity pointer-events-none"></div>
                </div>
                
                <!-- Enhanced Content Area -->
                <div class="gallery-item-content p-3 bg-gradient-to-b from-slate-700 to-slate-800" style="height: 60px; display: flex; flex-direction: column; justify-content: center;">
                    <h4 class="text-white font-semibold text-sm mb-1 truncate" title="${title}">${title}</h4>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-400 text-xs">Item ${globalIndex + 1}</p>
                        <span class="text-yellow-400 text-xs font-medium">Page ${this.currentPage}</span>
                    </div>
                </div>
            </div>
        `;
        
        return content;
    },
    
    // Build empty placeholder for consistent grid
    buildEmptyPlaceholder() {
        return `
            <div class="gallery-item-placeholder bg-slate-800 bg-opacity-50 rounded-xl border-2 border-dashed border-slate-600" 
                 style="height: 280px;">
                <div class="w-full h-full flex items-center justify-center text-slate-500">
                    <div class="text-center">
                        <div class="text-4xl mb-2">⋯</div>
                        <p class="text-sm">Empty Slot</p>
                    </div>
                </div>
            </div>
        `;
    },
    
    // Calculate optimal grid height
    calculateGridHeight() {
        const baseItemHeight = 280;
        const rowsOnCurrentPage = Math.ceil(Math.min(this.itemsPerPage, this.allItems.length - (this.currentPage - 1) * this.itemsPerPage) / 2);
        const gapHeight = (rowsOnCurrentPage - 1) * 16; // 16px gap between rows
        return (rowsOnCurrentPage * baseItemHeight) + gapHeight;
    },
    
    // Get appropriate icon for file type
    getTypeIcon(type) {
        const icons = {
            'image': '🖼️',
            'youtube': '📺',
            'video': '🎬',
            'document': '📄',
            'pdf': '📕',
            'audio': '🎵'
        };
        return icons[type] || '📁';
    },
    
    // Build enhanced pagination controls
    buildEnhancedPaginationControls() {
        const totalItems = this.allItems.length;
        const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.itemsPerPage, totalItems);
        
        let paginationHtml = `
            <div class="enhanced-pagination bg-gradient-to-r from-slate-800 to-slate-700 rounded-xl shadow-lg border border-slate-600 p-5 mt-6">
                <!-- Pagination Header -->
                <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
                    <div class="text-gray-300 text-sm mb-2 sm:mb-0">
                        <span class="inline-flex items-center gap-2">
                            <span class="text-yellow-400">📊</span>
                            Showing <span class="font-bold text-white bg-slate-600 px-2 py-1 rounded">${startItem}-${endItem}</span> 
                            of <span class="font-bold text-yellow-400">${totalItems}</span> items
                        </span>
                    </div>
                    
                    <div class="text-gray-400 text-xs">
                        Page <span class="text-white font-semibold">${this.currentPage}</span> of 
                        <span class="text-yellow-400 font-semibold">${this.totalPages}</span>
                    </div>
                </div>
                
                <!-- Pagination Navigation -->
                <div class="flex flex-wrap items-center justify-center gap-2">
        `;
        
        // First page button
        if (this.totalPages > 5 && this.currentPage > 3) {
            paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(1)" 
                            class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-all duration-200 shadow-sm">
                        « First
                    </button>
            `;
        }
        
        // Previous button
        paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage - 1})" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm ${
                                this.currentPage === 1 
                                    ? 'bg-slate-800 text-gray-500 cursor-not-allowed opacity-50' 
                                    : 'bg-slate-700 text-gray-300 hover:bg-slate-600 hover:text-white'
                            }"
                            ${this.currentPage === 1 ? 'disabled' : ''}>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>
        `;
        
        // Page numbers with smart display
        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(this.totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        // Ellipsis before
        if (startPage > 1) {
            if (startPage > 2) {
                paginationHtml += `<span class="px-2 text-gray-500 text-sm">...</span>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === this.currentPage;
            paginationHtml += `
                        <button onclick="GlobalGalleryLoader.goToPage(${i})" 
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm ${
                                    isActive 
                                        ? 'bg-gradient-to-r from-yellow-500 to-yellow-400 text-black font-bold shadow-lg transform scale-105' 
                                        : 'bg-slate-700 text-gray-300 hover:bg-slate-600 hover:text-white hover:shadow-md'
                                }">
                            ${i}
                        </button>
            `;
        }
        
        // Ellipsis after
        if (endPage < this.totalPages) {
            if (endPage < this.totalPages - 1) {
                paginationHtml += `<span class="px-2 text-gray-500 text-sm">...</span>`;
            }
        }
        
        // Next button
        paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage + 1})" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm ${
                                this.currentPage === this.totalPages 
                                    ? 'bg-slate-800 text-gray-500 cursor-not-allowed opacity-50' 
                                    : 'bg-slate-700 text-gray-300 hover:bg-slate-600 hover:text-white'
                            }"
                            ${this.currentPage === this.totalPages ? 'disabled' : ''}>
                        Next
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
        `;
        
        // Last page button
        if (this.totalPages > 5 && this.currentPage < this.totalPages - 2) {
            paginationHtml += `
                    <button onclick="GlobalGalleryLoader.goToPage(${this.totalPages})" 
                            class="px-3 py-2 text-sm font-medium text-gray-400 bg-slate-700 rounded-lg hover:bg-slate-600 hover:text-white transition-all duration-200 shadow-sm">
                        Last »
                    </button>
            `;
        }
        
        paginationHtml += `
                </div>
                
                <!-- Quick Navigation -->
                <div class="mt-4 pt-3 border-t border-slate-600 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-400">Quick jump to page:</span>
                        <select onchange="GlobalGalleryLoader.goToPage(parseInt(this.value))" 
                                class="bg-slate-700 text-white text-sm px-3 py-1 rounded border border-slate-600 focus:border-yellow-400 focus:outline-none">
                            ${Array.from({length: this.totalPages}, (_, i) => i + 1).map(page => 
                                `<option value="${page}" ${page === this.currentPage ? 'selected' : ''}>${page}</option>`
                            ).join('')}
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span>🎯 ${this.itemsPerPage} items per page</span>
                        <span>⚡ Enhanced Grid View</span>
                    </div>
                </div>
            </div>
        `;
        
        return paginationHtml;
    },
    
    // Build gallery statistics
    buildGalleryStats(totalItems, currentPageItems) {
        const imageCount = this.allItems.filter(item => item.type === 'image').length;
        const videoCount = this.allItems.filter(item => item.type === 'youtube' || item.type === 'video').length;
        const otherCount = totalItems - imageCount - videoCount;
        
        return `
            <div class="gallery-stats mt-4 bg-slate-800 bg-opacity-50 rounded-lg p-3 text-center">
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="flex items-center gap-1 text-blue-400">
                        <span>🖼️</span>
                        <span>${imageCount} Images</span>
                    </div>
                    <div class="flex items-center gap-1 text-red-400">
                        <span>📺</span>
                        <span>${videoCount} Videos</span>
                    </div>
                    ${otherCount > 0 ? `
                    <div class="flex items-center gap-1 text-green-400">
                        <span>📁</span>
                        <span>${otherCount} Others</span>
                    </div>` : ''}
                    <div class="flex items-center gap-1 text-yellow-400">
                        <span>📊</span>
                        <span>Total: ${totalItems}</span>
                    </div>
                </div>
            </div>
        `;
    },
    
    // Apply enhanced grid styling
    applyEnhancedGridStyling() {
        // Add custom CSS for enhanced styling
        if (!document.getElementById('enhanced-gallery-styles')) {
            const styleElement = document.createElement('style');
            styleElement.id = 'enhanced-gallery-styles';
            styleElement.textContent = `
                .enhanced-gallery-grid {
                    transition: all 0.3s ease;
                }
                
                .gallery-item {
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    border: 1px solid rgba(71, 85, 105, 0.3);
                }
                
                .gallery-item:hover {
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                    border-color: rgba(251, 191, 36, 0.5);
                }
                
                .gallery-item-placeholder {
                    transition: all 0.3s ease;
                }
                
                .gallery-item-placeholder:hover {
                    border-color: rgba(71, 85, 105, 0.6);
                    background-color: rgba(71, 85, 105, 0.1);
                }
                
                .enhanced-pagination button:not([disabled]):hover {
                    transform: translateY(-1px);
                }
                
                .enhanced-pagination select:focus {
                    box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
                }
                
                @media (max-width: 640px) {
                    .enhanced-gallery-grid {
                        grid-template-columns: 1fr;
                    }
                    
                    .gallery-item {
                        height: 320px !important;
                    }
                    
                    .gallery-item-media {
                        height: 260px !important;
                    }
                }
            `;
            document.head.appendChild(styleElement);
        }
    },
    
    // Navigate to specific page
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
            this.currentPage = page;
            this.displayCurrentPage();
            
            // Smooth scroll to top of gallery
            const container = document.getElementById(this.currentContainerId);
            if (container) {
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
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
            <div class="text-center py-16 bg-slate-800 bg-opacity-30 rounded-xl border-2 border-dashed border-slate-600">
                <div class="text-yellow-400 text-8xl mb-6 animate-pulse">${emoji}</div>
                <h3 class="text-white text-2xl font-bold mb-4">${title}</h3>
                <p class="text-gray-400 text-lg mb-6 max-w-md mx-auto">
                    "${itemName}" doesn't have any gallery items yet.
                </p>
                <div class="flex items-center justify-center gap-2 text-gray-500 text-sm bg-slate-700 bg-opacity-50 inline-flex px-4 py-2 rounded-full">
                    <i class="fas fa-info-circle"></i>
                    <span>No data available to display</span>
                </div>
            </div>
        `;
    },
    
    // Show loading state
    showLoading(container, itemName, type, id) {
        container.innerHTML = `
            <div class="flex items-center justify-center py-16 bg-slate-800 bg-opacity-30 rounded-xl">
                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="animate-spin rounded-full h-16 w-16 border-4 border-slate-600 border-t-yellow-400 mx-auto"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-2xl">${type === 'award' ? '🏆' : '🖼️'}</div>
                        </div>
                    </div>
                    <h3 class="text-white text-xl font-semibold mb-2">Loading Gallery...</h3>
                    <p class="text-gray-400 text-lg mb-3">${itemName}</p>
                    <div class="text-gray-500 text-sm space-y-1">
                        <p>Type: <span class="font-medium text-gray-300">${type.charAt(0).toUpperCase() + type.slice(1)}</span></p>
                        <p>ID: <span class="font-medium text-gray-300">${id}</span></p>
                        <p class="text-xs text-yellow-400 mt-2">⏱️ Will show "No Data" if not found within 8 seconds</p>
                    </div>
                </div>
            </div>
        `;
    },
    
    // Show error state
    showError(container, itemName, type, id, error, attemptedUrls) {
        container.innerHTML = `
            <div class="text-center py-16 bg-red-900 bg-opacity-20 border border-red-700 rounded-xl">
                <div class="text-red-400 text-6xl mb-4">⚠️</div>
                <h3 class="text-white text-xl font-semibold mb-2">Error Loading Gallery</h3>
                <p class="text-gray-400 mb-6">Failed to load gallery items for "${itemName}".</p>
                
                <div class="text-left bg-slate-800 p-4 rounded-lg text-sm max-w-2xl mx-auto mb-6">
                    <h4 class="text-gray-300 font-semibold mb-3">🔍 Debug Information:</h4>
                    <div class="space-y-2 text-gray-400">
                        <p><span class="text-gray-300">Type:</span> ${type.charAt(0).toUpperCase() + type.slice(1)}</p>
                        <p><span class="text-gray-300">ID:</span> ${id}</p>
                        <p><span class="text-gray-300">Error:</span> ${error}</p>
                    </div>
                    
                    <details class="mt-4">
                        <summary class="text-gray-300 cursor-pointer hover:text-white">Attempted URLs (${attemptedUrls.length})</summary>
                        <ul class="text-gray-400 text-xs mt-2 ml-4 space-y-1">
                            ${attemptedUrls.map((url, index) => `<li>${index + 1}. ${url}</li>`).join('')}
                        </ul>
                    </details>
                </div>
                
                <button onclick="location.reload()" 
                        class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-400 text-black font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-300 transition-all duration-200 shadow-lg">
                    🔄 Reload Page
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
    
    // Enhanced YouTube modal
    openYouTubeModal(youtubeUrl, title) {
        const videoId = this.extractYouTubeId(youtubeUrl);
        const embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&showinfo=0` : youtubeUrl;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-[110] flex items-center justify-center p-4';
        modal.style.backdropFilter = 'blur(10px)';
        
        modal.innerHTML = `
            <div class="relative w-full max-w-6xl max-h-full bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
                <!-- Header -->
                <div class="flex justify-between items-center p-4 bg-slate-800 border-b border-slate-700">
                    <div class="flex items-center gap-3">
                        <span class="text-red-500 text-2xl">📺</span>
                        <div>
                            <h3 class="text-white text-lg font-semibold">${title}</h3>
                            <p class="text-gray-400 text-sm">YouTube Video</p>
                        </div>
                    </div>
                    <button onclick="this.closest('.fixed').remove(); document.body.style.overflow = '';" 
                            class="text-white bg-slate-700 rounded-full p-2 hover:bg-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Video Container -->
                <div class="relative w-full bg-black" style="padding-bottom: 56.25%; height: 0;">
                    <iframe 
                        class="absolute top-0 left-0 w-full h-full" 
                        src="${embedUrl}" 
                        title="${title}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                
                <!-- Footer -->
                <div class="p-4 bg-slate-800 text-center">
                    <a href="${youtubeUrl}" target="_blank" 
                       class="inline-flex items-center gap-2 text-red-400 hover:text-red-300 text-sm transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        Watch on YouTube
                    </a>
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

// Enhanced GlobalImageModal integration
if (typeof window.GlobalImageModal === 'undefined') {
    window.GlobalImageModal = {
        currentImages: [],
        currentIndex: 0,
        currentGalleryName: '',
        
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
        
        showModal() {
            if (this.currentIndex < 0 || this.currentIndex >= this.currentImages.length) {
                return;
            }
            
            const currentImage = this.currentImages[this.currentIndex];
            const existingModal = document.getElementById('global-image-modal-enhanced');
            if (existingModal) existingModal.remove();
            
            const modal = document.createElement('div');
            modal.id = 'global-image-modal-enhanced';
            modal.className = 'fixed inset-0 bg-black bg-opacity-98 z-[120] flex items-center justify-center p-4';
            modal.style.backdropFilter = 'blur(15px)';
            
            modal.innerHTML = `
                <div class="relative w-full h-full max-w-7xl flex flex-col">
                    <!-- Enhanced Header -->
                    <div class="flex justify-between items-center mb-4 bg-slate-900 bg-opacity-70 rounded-lg px-4 py-3 backdrop-blur-sm">
                        <div class="text-white">
                            <h3 class="text-xl font-bold">${this.currentGalleryName}</h3>
                            <p class="text-gray-300 text-sm flex items-center gap-2">
                                <span>🖼️</span>
                                Image ${this.currentIndex + 1} of ${this.currentImages.length}
                            </p>
                        </div>
                        <button onclick="GlobalImageModal.closeModal()" 
                                class="text-white bg-red-600 rounded-full p-3 hover:bg-red-500 transition-all duration-200 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Enhanced Image Container -->
                    <div class="flex-1 flex items-center justify-center relative bg-slate-900 bg-opacity-30 rounded-lg overflow-hidden">
                        <img id="modal-image-enhanced" 
                             src="${currentImage.url}" 
                             alt="${currentImage.alt || currentImage.title}" 
                             class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                             style="opacity: 0; transition: opacity 0.4s ease;"
                             onload="this.style.opacity='1';">
                        
                        <!-- Enhanced Loading -->
                        <div id="modal-loading-enhanced" class="absolute inset-0 flex items-center justify-center bg-slate-800 bg-opacity-50 backdrop-blur-sm rounded-lg">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-16 w-16 border-4 border-slate-600 border-t-yellow-400 mx-auto mb-4"></div>
                                <p class="text-white text-lg">Loading image...</p>
                            </div>
                        </div>
                        
                        <!-- Enhanced Navigation -->
                        ${this.currentImages.length > 1 ? `
                            <button onclick="GlobalImageModal.previousImage()" 
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-slate-800 bg-opacity-80 text-white rounded-full p-4 hover:bg-opacity-100 transition-all duration-200 shadow-xl ${this.currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-110'}"
                                    ${this.currentIndex === 0 ? 'disabled' : ''}>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button onclick="GlobalImageModal.nextImage()" 
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-slate-800 bg-opacity-80 text-white rounded-full p-4 hover:bg-opacity-100 transition-all duration-200 shadow-xl ${this.currentIndex === this.currentImages.length - 1 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-110'}"
                                    ${this.currentIndex === this.currentImages.length - 1 ? 'disabled' : ''}>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                    
                    <!-- Enhanced Footer -->
                    <div class="mt-4 text-center bg-slate-900 bg-opacity-70 rounded-lg p-4 backdrop-blur-sm">
                        <h4 class="text-white text-lg font-semibold mb-2">${currentImage.title}</h4>
                        ${this.currentImages.length > 1 ? `
                            <div class="flex justify-center items-center gap-1 mb-3">
                                ${this.currentImages.slice(0, 10).map((_, index) => `
                                    <button onclick="GlobalImageModal.goToImage(${index})"
                                            class="w-3 h-3 rounded-full transition-all duration-200 ${
                                                index === this.currentIndex 
                                                    ? 'bg-yellow-400 scale-125' 
                                                    : 'bg-slate-600 hover:bg-slate-500'
                                            }"></button>
                                `).join('')}
                                ${this.currentImages.length > 10 ? `<span class="text-gray-400 text-sm ml-2">+${this.currentImages.length - 10} more</span>` : ''}
                            </div>
                        ` : ''}
                        <div class="text-gray-400 text-sm">
                            Use arrow keys or swipe to navigate • Press ESC to close
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            const img = document.getElementById('modal-image-enhanced');
            const loading = document.getElementById('modal-loading-enhanced');
            
            img.onload = function() {
                loading.style.display = 'none';
                this.style.opacity = '1';
            };
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) GlobalImageModal.closeModal();
            });
            
            document.addEventListener('keydown', this.handleKeyPress);
        },
        
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
        
        closeModal() {
            const modal = document.getElementById('global-image-modal-enhanced');
            if (modal) modal.remove();
            document.body.style.overflow = '';
            document.removeEventListener('keydown', this.handleKeyPress);
        },
        
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

// Enhanced backward compatibility functions
window.loadAwardGallery = async function(awardId, awardName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, awardName, 'award', awardId);
    
    console.log(`🎯 Enhanced loadAwardGallery for ID: ${awardId}, Name: ${awardName}`);
    
    const loadingTimeout = setTimeout(() => {
        console.log('⏰ Enhanced loading timeout - showing empty state');
        GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
    }, 8000);
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('award', awardId, awardName);
        clearTimeout(loadingTimeout);
        
        console.log('📊 Enhanced Award Gallery Result:', result);
        
        if (!result || !result.success || !result.data) {
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        let items = null;
        if (result.data.success === true && result.data.items) {
            items = result.data.items;
        } else if (result.data.success === false) {
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        } else if (Array.isArray(result.data)) {
            items = result.data;
        } else if (result.data.items) {
            items = result.data.items;
        }
        
        if (!items || !Array.isArray(items) || items.length === 0) {
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        const validItems = items.filter(item => item !== null && item !== undefined);
        if (validItems.length === 0) {
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            return;
        }
        
        console.log('🎉 Enhanced award gallery displaying items:', validItems.length);
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, awardName, 'award');
        
    } catch (error) {
        clearTimeout(loadingTimeout);
        console.error('🚨 Enhanced error in loadAwardGallery:', error);
        GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
    }
};

window.loadGalleryItems = async function(galleryId, galleryName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, galleryName, 'gallery', galleryId);
    
    console.log(`🎯 Enhanced loadGalleryItems for ID: ${galleryId}, Name: ${galleryName}`);
    
    const loadingTimeout = setTimeout(() => {
        console.log('⏰ Enhanced loading timeout - showing empty state');
        GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
    }, 8000);
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('gallery', galleryId, galleryName);
        clearTimeout(loadingTimeout);
        
        console.log('📊 Enhanced Gallery Result:', result);
        
        if (!result || !result.success || !result.data) {
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        let items = null;
        if (result.data.success === true && result.data.items) {
            items = result.data.items;
        } else if (result.data.success === false) {
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        } else if (Array.isArray(result.data)) {
            items = result.data;
        } else if (result.data.items) {
            items = result.data.items;
        }
        
        if (!items || !Array.isArray(items) || items.length === 0) {
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        const validItems = items.filter(item => item !== null && item !== undefined);
        if (validItems.length === 0) {
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            return;
        }
        
        console.log('🎉 Enhanced gallery displaying items:', validItems.length);
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, galleryName, 'gallery');
        
    } catch (error) {
        clearTimeout(loadingTimeout);
        console.error('🚨 Enhanced error in loadGalleryItems:', error);
        GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
    }
};

console.log('🚀 Enhanced Global Gallery Loader with improved 2x3 grid and pagination loaded successfully!');
</script>
