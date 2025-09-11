<!-- Awards & Recognition Section - ENHANCED VERSION -->
<?php if($konf->awards_section_active ?? true): ?>
<section id="awards" class="w-full py-16 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-yellow-400 text-5xl sm:text-6xl font-bold mb-4">
                Awards & Recognition
            </h2>
            <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto">
                Innovative solutions that drive real business impact and transformation
            </p>
        </div>

        <?php if(isset($award) && $award->count() > 0): ?>
        <!-- Awards Grid - FIXED LAYOUT -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $award; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            // Logo configurations with transparent background
            $logoConfigs = [
                'nextdev' => [
                    'subtitle' => 'TELKOMSEL • 2018', 
                    'color' => '#60a5fa'
                ],
                'alibaba' => [
                    'subtitle' => 'ALIBABA UNCTAD • 2019', 
                    'color' => '#fb923c'
                ],
                'google' => [
                    'subtitle' => 'GOOGLE • 2018', 
                    'color' => '#60a5fa'
                ],
                'wild' => [
                    'subtitle' => 'FENOX • 2017', 
                    'color' => '#4ade80'
                ],
                'fenox' => [
                    'subtitle' => 'FENOX • 2017', 
                    'color' => '#f87171'
                ],
                'bubu' => [
                    'subtitle' => 'BUBU.com • 2017', 
                    'color' => '#4ade80'
                ],
                'grind' => [
                    'subtitle' => 'GOOGLE • 2024', 
                    'color' => '#60a5fa'
                ],
                'default' => [
                    'subtitle' => date('Y'), 
                    'color' => '#fbbf24'
                ]
            ];
            
            $logoKey = 'default';
            foreach(array_keys($logoConfigs) as $key) {
                if(stripos($row->nama_award, $key) !== false) {
                    $logoKey = $key;
                    break;
                }
            }
            
            $logoConfig = $logoConfigs[$logoKey];
            ?>
            
            <!-- Award Card - Side by Side Layout -->
            <div class="award-card-exact group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 cursor-pointer transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl" 
                 onclick="openAwardGallery(<?php echo e($row->id_award); ?>, '<?php echo e(addslashes($row->nama_award)); ?>')">
                
                <!-- Logo & Title Section - Side by Side -->
                <div class="flex items-start gap-4 mb-6">
                    <!-- Logo - Transparent Background -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center" 
                             style="background-color: transparent; border: 2px solid rgba(255, 255, 255, 0.1);">
                            <?php if($row->gambar_award && file_exists(public_path('file/award/' . $row->gambar_award))): ?>
                                <img src="<?php echo e(asset('file/award/' . $row->gambar_award)); ?>" 
                                     alt="<?php echo e($row->nama_award); ?>" 
                                     class="w-10 h-10 object-contain" />
                            <?php else: ?>
                                <span class="text-white font-bold text-sm">
                                    <?php if(stripos($row->nama_award, 'nextdev') !== false): ?>
                                        NextDev
                                    <?php elseif(stripos($row->nama_award, 'alibaba') !== false): ?>
                                        Ali
                                    <?php else: ?>
                                        <?php echo e(substr($row->nama_award, 0, 3)); ?>

                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Title & Company Info -->
                    <div class="flex-1 min-w-0">
                        <!-- Award Title -->
                        <h3 class="text-white text-xl font-bold mb-2 leading-tight">
                            <?php echo e($row->nama_award); ?>

                        </h3>
                        
                        <!-- Company & Year -->
                        <p class="text-sm font-semibold uppercase tracking-wider" 
                           style="color: <?php echo e($logoConfig['color']); ?>;">
                            <?php echo e($logoConfig['subtitle']); ?>

                        </p>
                    </div>
                </div>
                
                <!-- Description -->
                <p class="text-gray-400 text-sm leading-relaxed mb-8">
                    Achieved first place in prestigious startup competition, demonstrating innovative digital solutions and entrepreneurial excellence.
                </p>
                
                <!-- View Gallery Button - Bottom Right -->
                <div class="flex justify-end items-end mt-auto">
                    <button class="flex items-center gap-2 text-gray-400 text-sm font-medium uppercase tracking-wide transition-colors hover:text-white">
                        <span>VIEW GALLERY</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php else: ?>
        <!-- No Data State -->
        <div class="flex flex-col items-center justify-center py-16">
            <div class="text-yellow-400 text-6xl mb-4">🏆</div>
            <h3 class="text-white text-xl font-semibold mb-2">No Awards Yet</h3>
            <p class="text-gray-400 text-center max-w-md">
                We're building our track record of achievements and recognition. Stay tuned to see our upcoming awards and accomplishments!
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Award Card - Side by Side Layout */
.award-card-exact {
    min-height: 320px;
    border: 1px solid rgba(71, 85, 105, 0.3);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
}

.award-card-exact:hover {
    border-color: rgba(71, 85, 105, 0.5);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
}

/* Logo transparent background styling */
.award-card-exact .w-16.h-16 {
    backdrop-filter: blur(10px);
}

/* Ensure text doesn't wrap awkwardly */
.award-card-exact .flex-1.min-w-0 h3 {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .award-card-exact {
        min-height: 300px;
        padding: 1.5rem;
    }
    
    .award-card-exact .flex.items-start.gap-4 {
        gap: 1rem;
    }
    
    .award-card-exact .w-16.h-16 {
        width: 3.5rem;
        height: 3.5rem;
    }
}

@media (max-width: 640px) {
    .award-card-exact {
        min-height: 280px;
        padding: 1.25rem;
    }
    
    .award-card-exact .text-xl {
        font-size: 1.125rem;
    }
}

/* Image Modal Styles */
.image-modal-overlay {
    z-index: 70 !important;
    backdrop-filter: blur(5px);
}

.image-modal-container {
    animation: fadeInScale 0.3s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<!-- Enhanced Award Gallery Modal -->
<div id="awardGalleryModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl w-full max-w-4xl h-auto max-h-[85vh] overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-600 bg-slate-700">
            <h3 id="awardGalleryTitle" class="text-lg font-bold text-white truncate mr-4">Award Gallery</h3>
            <div class="flex items-center gap-2">
                <!-- OK Button -->
                <button onclick="closeAwardGallery()" 
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                    OK
                </button>
                <!-- Close X Button -->
                <button onclick="closeAwardGallery()" 
                        class="text-gray-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div id="awardGalleryContent" class="p-4 overflow-y-auto" style="max-height: calc(85vh - 80px);">
            <!-- Gallery content will be loaded here -->
        </div>
    </div>
</div>

<!-- Image Preview Modal (Higher Z-Index) -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-95 z-[70] hidden flex items-center justify-center p-4 image-modal-overlay">
    <div class="relative max-w-full max-h-full image-modal-container">
        <img id="imagePreviewImg" 
             src="" 
             alt="Gallery Image Preview" 
             class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        <button onclick="closeImagePreview()" 
                class="absolute top-4 right-4 text-white bg-black bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all duration-300 shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <!-- Image Title -->
        <div class="absolute bottom-4 left-4 right-4 text-center">
            <p id="imagePreviewTitle" class="text-white bg-black bg-opacity-60 px-4 py-2 rounded-lg text-sm"></p>
        </div>
    </div>
</div>

<script>
async function openAwardGallery(awardId, awardName) {
    console.log('Opening gallery for award ID:', awardId, 'Name:', awardName);
    
    document.getElementById('awardGalleryTitle').textContent = `${awardName} - Gallery`;
    document.getElementById('awardGalleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Show loading
    document.getElementById('awardGalleryContent').innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                <p class="text-gray-400 text-lg">Loading gallery...</p>
                <p class="text-gray-500 text-sm mt-2">Award ID: ${awardId}</p>
            </div>
        </div>
    `;
    
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
    
    // Multiple endpoint URLs to try (in order of preference)
    const galleryUrls = [
        `${baseUrl}${basePath}/gallery_api.php?award_id=${awardId}`,
        `${baseUrl}${basePath}/test_gallery_api.php?award_id=${awardId}`,
        `${baseUrl}${basePath}/test/gallery/${awardId}`,
        `${baseUrl}${basePath}/api/award/${awardId}/gallery`,
        `${baseUrl}${basePath}/award/${awardId}/gallery`, 
        `${baseUrl}${basePath}/gallery/${awardId}/items`,
        `${baseUrl}${basePath}/api/gallery/${awardId}/items`
    ];
    
    console.log('Current path:', currentPath);
    console.log('Base path:', basePath);
    console.log('Trying URLs:', galleryUrls);
    
    // Try each URL until one works
    let lastError = null;
    let successUrl = null;
    
    for (const url of galleryUrls) {
        try {
            console.log(`Trying URL: ${url}`);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log(`Response from ${url}:`, response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Gallery data received:', data);
                
                successUrl = url;
                await displayGalleryData(data, awardId, awardName);
                return; // Success! Exit function
            } else {
                lastError = `HTTP ${response.status}`;
            }
            
        } catch (error) {
            console.log(`Error with ${url}:`, error.message);
            lastError = error.message;
        }
    }
    
    // If we get here, all URLs failed
    console.error('All gallery URLs failed');
    document.getElementById('awardGalleryContent').innerHTML = `
        <div class="text-center py-12">
            <div class="text-red-400 text-6xl mb-4">⚠️</div>
            <h3 class="text-white text-xl font-semibold mb-2">Error Loading Gallery</h3>
            <p class="text-gray-400 mb-4">Failed to load gallery items from all endpoints.</p>
            <div class="text-left bg-slate-700 p-4 rounded-lg text-sm max-w-2xl mx-auto">
                <p class="text-gray-300 mb-2"><strong>Debug Info:</strong></p>
                <p class="text-gray-400">Award ID: ${awardId}</p>
                <p class="text-gray-400">Last Error: ${lastError}</p>
                <p class="text-gray-400">Tried URLs:</p>
                <ul class="text-gray-400 text-xs mt-1 ml-4">
                    ${galleryUrls.map(url => `<li>• ${url}</li>`).join('')}
                </ul>
            </div>
            <button onclick="openAwardGallery(${awardId}, '${awardName}')" 
                    class="mt-4 px-6 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-400 transition-colors">
                Retry
            </button>
        </div>
    `;
}

async function displayGalleryData(data, awardId, awardName) {
    if (data.success && data.items && data.items.length > 0) {
        let content = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
        
        // Sort items by sequence for proper ordering
        const sortedItems = data.items.sort((a, b) => (a.sequence || 0) - (b.sequence || 0));
        
        sortedItems.forEach((item, index) => {
            console.log('Processing item:', item);
            
            content += `
                <div class="bg-slate-700 rounded-lg overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                    <div class="aspect-square relative">
            `;
            
            if (item.type === 'image' && item.file_url) {
                content += `
                    <img src="${item.file_url}" 
                         alt="Gallery item ${index + 1}" 
                         class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity" 
                         onclick="openImagePreview('${item.file_url}', '${item.gallery_name || 'Gallery Image'} - ${awardName}')"
                         onerror="this.src='${item.file_url_alt || item.file_url}'; if(this.src.includes('file_url_alt')) this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm\\'>Image not found</div>';">
                `;
            } else if (item.type === 'youtube' && item.youtube_url) {
                const videoId = extractYouTubeId(item.youtube_url);
                if (videoId) {
                    content += `
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/${videoId}" 
                                frameborder="0" 
                                allowfullscreen>
                        </iframe>
                    `;
                } else {
                    content += `
                        <div class="w-full h-full flex items-center justify-center bg-slate-600 text-white">
                            <p class="text-sm">Invalid YouTube URL</p>
                        </div>
                    `;
                }
            } else {
                content += `
                    <div class="w-full h-full flex items-center justify-center bg-slate-600 text-white">
                        <div class="text-center p-2">
                            <p class="text-sm">Unknown item type: ${item.type}</p>
                        </div>
                    </div>
                `;
            }
            
            content += `
                        <div class="absolute top-2 right-2">
                            <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">${item.type}</span>
                        </div>
                        <div class="absolute top-2 left-2">
                            <span class="bg-blue-500 bg-opacity-80 text-white text-xs px-2 py-1 rounded">#${item.sequence || index + 1}</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-white text-sm truncate">${item.gallery_name || 'Gallery Item'}</p>
                        <p class="text-gray-400 text-xs">Item ${index + 1} of ${sortedItems.length}</p>
                    </div>
                </div>
            `;
        });
        
        content += '</div>';
        document.getElementById('awardGalleryContent').innerHTML = content;
    } else {
        console.log('No items found or empty response');
        // ENHANCED: Clean display when no gallery items (removed Response Data and Create Sample Data button)
        document.getElementById('awardGalleryContent').innerHTML = `
            <div class="text-center py-12">
                <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
                <h3 class="text-white text-xl font-semibold mb-2">No Gallery Items</h3>
                <p class="text-gray-400 mb-4">This award doesn't have any gallery items yet.</p>
            </div>
        `;
    }
}

function closeAwardGallery() {
    document.getElementById('awardGalleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ENHANCEMENT: Image Preview Modal Functions
function openImagePreview(imageUrl, title) {
    console.log('Opening image preview:', imageUrl);
    
    const modal = document.getElementById('imagePreviewModal');
    const img = document.getElementById('imagePreviewImg');
    const titleElement = document.getElementById('imagePreviewTitle');
    
    img.src = imageUrl;
    titleElement.textContent = title;
    modal.classList.remove('hidden');
    
    // Prevent body scrolling when image modal is open
    document.body.style.overflow = 'hidden';
}

function closeImagePreview() {
    const modal = document.getElementById('imagePreviewModal');
    modal.classList.add('hidden');
    
    // Restore body scrolling only if award gallery is not open
    const awardModal = document.getElementById('awardGalleryModal');
    if (awardModal.classList.contains('hidden')) {
        document.body.style.overflow = '';
    }
}

function extractYouTubeId(url) {
    if (!url) return null;
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// Modal event listeners
document.getElementById('awardGalleryModal').addEventListener('click', function(e) {
    if (e.target === this) closeAwardGallery();
});

document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeImagePreview();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close image preview first (higher priority), then award gallery
        const imageModal = document.getElementById('imagePreviewModal');
        const awardModal = document.getElementById('awardGalleryModal');
        
        if (!imageModal.classList.contains('hidden')) {
            closeImagePreview();
        } else if (!awardModal.classList.contains('hidden')) {
            closeAwardGallery();
        }
    }
});

// Debug: Log when page loads
console.log('Enhanced Awards gallery script loaded');
console.log('Current location:', window.location);
</script>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/awards.blade.php ENDPATH**/ ?>