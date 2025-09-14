<!-- Gallery Loader Component with Fixed Layout -->
<script>
// Disable gallery loader in admin panel
if (document.body.classList.contains('admin-panel')) {
    console.log('Gallery loader disabled in admin panel');
} else {

// Gallery Loader Object
window.GlobalGalleryLoader = {
    
    // Pagination settings - 2x3 grid layout
    itemsPerPage: 6,
    currentPage: 1,
    totalPages: 1,
    allItems: [],
    currentType: '',
    currentId: '',
    currentItemName: '',
    currentContainerId: '',
    
    // Load gallery items for any type (award/gallery)
    async loadGalleryItems(type, id, itemName = '') {
        console.log(`🚀 Loading ${type} gallery for ID: ${id}`);
        
        const baseUrl = window.location.origin;
        let apiPath = '/ALI_PORTFOLIO/public/gallery_api.php';
        const apiUrl = `${baseUrl}${apiPath}?type=${type}&id=${id}`;
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 8000);
            
            const response = await fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            if (response.ok) {
                const data = await response.json();
                return {
                    success: true,
                    data: data,
                    url: apiUrl
                };
            } else {
                return {
                    success: true,
                    data: { success: false, items: [] },
                    error: `HTTP ${response.status}`
                };
            }
            
        } catch (error) {
            return {
                success: true,
                data: { success: false, items: [] },
                error: error.name === 'AbortError' ? 'Request timeout' : error.message
            };
        }
    },
    
    // Initialize gallery with pagination
    initializeGallery(items, containerElementId, galleryName, type = 'general') {
        this.allItems = items || [];
        this.currentType = type;
        this.currentItemName = galleryName;
        this.currentContainerId = containerElementId;
        this.currentPage = 1;
        this.totalPages = Math.ceil(this.allItems.length / this.itemsPerPage);
        
        this.displayCurrentPage();
    },
    
    // Display current page items with optimized layout
    displayCurrentPage() {
        const container = document.getElementById(this.currentContainerId);
        if (!container) {
            console.error('Container element not found:', this.currentContainerId);
            return;
        }
        
        if (!this.allItems || this.allItems.length === 0) {
            this.showEmptyGallery(container, this.currentItemName, this.currentType);
            return;
        }
        
        const validItems = this.allItems.filter(item => item);
        
        // Check if all items are demo items (no real data)
        const allDemo = validItems.every(item => item.is_demo === true);
        if (allDemo) {
            this.showNoDataGallery(container, this.currentItemName, this.currentType);
            return;
        }
        
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const currentPageItems = validItems.slice(startIndex, endIndex);
        
        // Prepare images for modal
        const galleryImages = validItems
            .filter(item => item.type === 'image' && item.file_url)
            .map((item, index) => ({
                url: item.file_url,
                title: `${this.currentItemName}`,
                alt: `${this.currentType} image ${index + 1}`
            }));
        
        // Build optimized gallery layout
        let content = `
            <!-- Compact Gallery Grid -->
            <div class="gallery-grid grid grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        `;
        
        // Only add actual items, no empty placeholders
        currentPageItems.forEach((item, index) => {
            const globalIndex = startIndex + index;
            content += this.buildGalleryItem(item, globalIndex, galleryImages);
        });
        
        content += '</div>';
        
        // Add compact pagination
        if (this.totalPages > 1) {
            content += this.buildCompactPagination();
        }
        
        container.innerHTML = content;
        this.applyCompactStyling();
    },
    
    // Build individual gallery item with fixed title position
    buildGalleryItem(item, globalIndex, galleryImages) {
        const imageUrl = item.file_url || '';
        const title = item.gallery_name || this.currentItemName || 'Gallery Item';
        
        let content = `
            <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-lg overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group cursor-pointer shadow-lg border border-slate-600 hover:border-yellow-500/50" 
                 style="height: 220px;">
                
                <!-- Image Container with Fixed Height -->
                <div class="relative" style="height: 160px; overflow: hidden;">
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
                         onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm\\'>Image Error</div>';">
            `;
        } else if (item.type === 'youtube' && item.youtube_url) {
            const videoId = this.extractYouTubeId(item.youtube_url);
            const thumbnailUrl = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
            
            content += `
                    ${thumbnailUrl ? 
                        `<img src="${thumbnailUrl}" alt="${title}" class="w-full h-full object-cover" style="object-fit: cover; width: 100%; height: 100%;" loading="lazy">` :
                        `<div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm">YouTube Video</div>`
                    }
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity" 
                         onclick="GlobalGalleryLoader.openYouTubeModal('${item.youtube_url}', '${title}')">
                        <div class="bg-red-600 rounded-full p-3 group-hover:bg-red-700 transition-colors shadow-xl">
                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
            `;
        } else {
            content += `
                    <div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm">
                        <div class="text-center">
                            <i class="fas fa-file text-xl mb-1"></i>
                            <p>Unknown Type</p>
                        </div>
                    </div>
            `;
        }
        
        content += `
                    <!-- Type Badge - Moved to top-right -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-black bg-opacity-80 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                            ${this.getTypeIcon(item.type)} ${item.type}
                        </span>
                    </div>
                    
                    ${item.sequence ? `
                    <div class="absolute top-2 left-2">
                        <span class="bg-blue-500 bg-opacity-90 text-white text-xs px-2 py-1 rounded-full font-medium">
                            #${item.sequence}
                        </span>
                    </div>` : ''}
                </div>
                
                <!-- Fixed Title Area - Visible and not overlapping -->
                <div class="p-3 bg-gradient-to-b from-slate-700 to-slate-800" style="height: 60px; display: flex; flex-direction: column; justify-content: center;">
                    <h4 class="text-white font-medium text-sm leading-tight mb-1" title="${title}" style="
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        line-height: 1.2;
                        max-height: 2.4em;
                    ">${title}</h4>
                </div>
            </div>
        `;
        
        return content;
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
    
    // Build compact pagination controls
    buildCompactPagination() {
        const totalItems = this.allItems.length;
        const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.itemsPerPage, totalItems);
        
        return `
            <div class="compact-pagination bg-gradient-to-r from-slate-800 to-slate-700 rounded-lg border border-slate-600 p-3 shadow-lg">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-300">
                        Showing ${startItem}-${endItem} of ${totalItems}
                    </span>
                    <span class="text-gray-400">
                        Page ${this.currentPage} of ${this.totalPages}
                    </span>
                </div>
                
                <div class="flex items-center justify-center gap-2">
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage - 1})" 
                            class="px-3 py-1 text-sm rounded transition-all duration-200 flex items-center gap-1 ${
                                this.currentPage === 1 
                                    ? 'bg-slate-700 text-gray-500 cursor-not-allowed' 
                                    : 'bg-slate-600 text-white hover:bg-slate-500'
                            }"
                            ${this.currentPage === 1 ? 'disabled' : ''}>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Prev
                    </button>
                    
                    <div class="flex items-center gap-1">
                        ${this.buildPageNumbers()}
                    </div>
                    
                    <button onclick="GlobalGalleryLoader.goToPage(${this.currentPage + 1})" 
                            class="px-3 py-1 text-sm rounded transition-all duration-200 flex items-center gap-1 ${
                                this.currentPage === this.totalPages 
                                    ? 'bg-slate-700 text-gray-500 cursor-not-allowed' 
                                    : 'bg-slate-600 text-white hover:bg-slate-500'
                            }"
                            ${this.currentPage === this.totalPages ? 'disabled' : ''}>
                        Next
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    },
    
    // Build compact page numbers
    buildPageNumbers() {
        let pageNumbers = '';
        
        for (let i = 1; i <= this.totalPages; i++) {
            if (i === this.currentPage) {
                pageNumbers += `
                    <span class="px-2 py-1 bg-yellow-400 text-black rounded font-semibold text-sm">
                        ${i}
                    </span>
                `;
            } else {
                pageNumbers += `
                    <button onclick="GlobalGalleryLoader.goToPage(${i})" 
                            class="px-2 py-1 bg-slate-600 text-white rounded text-sm hover:bg-slate-500 transition-all duration-200">
                        ${i}
                    </button>
                `;
            }
        }
        
        return pageNumbers;
    },
    
    // Apply compact styling to fit everything in one screen
    applyCompactStyling() {
        if (!document.getElementById('compact-gallery-styles')) {
            const styleElement = document.createElement('style');
            styleElement.id = 'compact-gallery-styles';
            styleElement.textContent = `
                .gallery-grid {
                    display: grid !important;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
                    gap: 1rem !important;
                    width: 100%;
                    max-width: 100%;
                    height: auto;
                }
                
                .gallery-grid > div {
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                    transition: all 0.3s ease;
                }
                
                .gallery-grid > div:hover {
                    box-shadow: 0 8px 25px rgba(251, 191, 36, 0.1), 0 4px 12px rgba(0, 0, 0, 0.3);
                }
                
                @media (max-width: 768px) {
                    .gallery-grid {
                        grid-template-columns: 1fr !important;
                        gap: 0.75rem !important;
                    }
                    .gallery-grid > div {
                        height: 180px !important;
                    }
                    .gallery-grid > div > div:first-child {
                        height: 120px !important;
                    }
                    .gallery-grid > div > div:last-child {
                        height: 60px !important;
                    }
                }
                
                @media (min-width: 769px) and (max-width: 1199px) {
                    .gallery-grid {
                        grid-template-columns: repeat(2, 1fr) !important;
                    }
                }
                
                @media (min-width: 1200px) {
                    .gallery-grid {
                        grid-template-columns: repeat(3, 1fr) !important;
                    }
                }
                
                .compact-pagination {
                    margin-top: 1rem;
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
        }
    },
    
    // Display gallery items in a container
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
        
        // Check if all items are demo items (no real data)
        const allDemo = items.every(item => item.is_demo === true);
        if (allDemo) {
            this.showNoDataGallery(container, galleryName, type);
            return;
        }
        
        this.initializeGallery(items, containerElementId, galleryName, type);
    },
    
    // Show empty gallery state
    showEmptyGallery(container, itemName, type) {
        const emoji = '🖼️';
        const title = type === 'award' ? 'NO AWARD GALLERY' : 'NO GALLERY ITEMS';
        
        container.innerHTML = `
            <div class="text-center py-12 bg-slate-800/30 rounded-lg border-2 border-dashed border-slate-600">
                <div class="text-yellow-400 text-6xl mb-4">${emoji}</div>
                <h3 class="text-white text-xl font-bold mb-3">${title}</h3>
            </div>
        `;
    },
    
    // Show no data gallery state (when only demo items exist)
    showNoDataGallery(container, itemName, type) {
        const emoji = type === 'award' ? '🏆' : '🖼️';
        const title = 'Gallery No Data';
        
        container.innerHTML = `
            <div class="text-center py-16 bg-slate-800/40 rounded-lg border border-slate-600">
                <div class="text-slate-400 text-8xl mb-6 opacity-60">${emoji}</div>
                <h3 class="text-white text-2xl font-bold mb-4">${title}</h3>
                <p class="text-gray-400 text-lg mb-6 max-w-lg mx-auto">
                    No gallery data available for "${itemName}".
                </p>
                <div class="inline-flex items-center gap-2 text-gray-500 text-sm bg-slate-700/50 px-6 py-3 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>No images or content to display</span>
                </div>
            </div>
        `;
    },
    
    // Show loading state
    showLoading(container, itemName, type, id) {
        container.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-600 border-t-yellow-400 mx-auto mb-4"></div>
                    <h3 class="text-white text-lg font-semibold mb-2">Loading Gallery...</h3>
                    <p class="text-gray-400">${itemName}</p>
                </div>
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
    
    // YouTube modal
    openYouTubeModal(youtubeUrl, title) {
        const videoId = this.extractYouTubeId(youtubeUrl);
        const embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&showinfo=0` : youtubeUrl;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-[110] flex items-center justify-center p-4';
        
        modal.innerHTML = `
            <div class="relative w-full max-w-4xl bg-slate-900 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4 bg-slate-800 border-b border-slate-600">
                    <h3 class="text-white text-lg font-semibold">${title}</h3>
                    <button onclick="this.closest('.fixed').remove(); document.body.style.overflow = '';" 
                            class="text-white bg-red-600 hover:bg-red-500 rounded-full p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="relative w-full bg-black" style="padding-bottom: 56.25%; height: 0;">
                    <iframe 
                        class="absolute top-0 left-0 w-full h-full" 
                        src="${embedUrl}" 
                        title="${title}" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
                document.body.style.overflow = '';
            }
        });
    }
};

// Backward compatibility functions
window.loadAwardGallery = async function(awardId, awardName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, awardName, 'award', awardId);
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('award', awardId, awardName);
        
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
        
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, awardName, 'award');
        
    } catch (error) {
        console.error('Error in loadAwardGallery:', error);
        GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
    }
};

window.loadGalleryItems = async function(galleryId, galleryName, containerId) {
    const container = document.getElementById(containerId);
    GlobalGalleryLoader.showLoading(container, galleryName, 'gallery', galleryId);
    
    try {
        const result = await GlobalGalleryLoader.loadGalleryItems('gallery', galleryId, galleryName);
        
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
        
        GlobalGalleryLoader.displayGalleryItems(validItems, containerId, galleryName, 'gallery');
        
    } catch (error) {
        console.error('Error in loadGalleryItems:', error);
        GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
    }
};

console.log('🚀 Gallery Loader ready!');
}
</script>
