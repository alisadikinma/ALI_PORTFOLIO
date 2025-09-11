<!-- Gallery Section -->
<?php if($konf->gallery_section_active ?? true): ?>
<section id="gallery" class="w-full max-w-screen-xl mx-auto px-3 sm:px-4 py-6 flex flex-col items-center gap-6 sm:gap-10">
    <div class="flex flex-col gap-2 text-center">
        <h2 class="text-yellow-400 text-3xl sm:text-5xl font-bold leading-tight tracking-tight">
            Gallery
        </h2>
        <p class="text-neutral-400 text-base sm:text-lg font-normal leading-6 tracking-tight">
            Explore the visual journey of my work, from concept to impactful solutions
        </p>
    </div>

    <?php if(isset($galeri) && $galeri->count() > 0): ?>
    <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 w-full">
    <?php $__currentLoopData = $galeri->where('status', 'Active')->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div>
    <div class="relative group rounded-lg bg-slate-900 outline outline-1 outline-slate-500 overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-md cursor-pointer" onclick="openGalleryModal(<?php echo e($row->id_galeri); ?>, '<?php echo e(addslashes($row->nama_galeri)); ?>')">
    <?php if($row->thumbnail): ?>
    <img src="<?php echo e(asset('file/galeri/' . $row->thumbnail)); ?>" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php elseif($row->galleryItems->where('status', 'Active')->first()): ?>
    <?php
    $firstItem = $row->galleryItems->where('status', 'Active')->first();
    ?>
    <?php if($firstItem->type === 'image'): ?>
    <img src="<?php echo e(asset('file/galeri/' . $firstItem->file_name)); ?>" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php elseif($firstItem->type === 'youtube'): ?>
    <?php
    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $firstItem->youtube_url, $matches);
    $videoId = $matches[1] ?? null;
    ?>
    <?php if($videoId): ?>
    <img src="https://img.youtube.com/vi/<?php echo e($videoId); ?>/maxresdefault.jpg" alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" class="w-full h-auto rounded-lg aspect-square object-cover" />
    <?php endif; ?>
    <?php endif; ?>
    <?php else: ?>
    <div class="w-full aspect-square bg-gray-700 rounded-lg flex items-center justify-center">
    <i class="fas fa-image text-gray-500 text-3xl"></i>
    </div>
    <?php endif; ?>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center">
                            <p class="text-white text-sm font-semibold mb-2"><?php echo e($row->nama_galeri ?? 'Gallery'); ?></p>
                            <div class="inline-flex items-center px-3 py-2 bg-yellow-400 text-black rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Gallery
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Gallery Modal -->
        <div id="galleryModal" class="gallery-modal">
            <div class="gallery-modal-content">
                <!-- Header -->
                <div class="gallery-modal-header">
                    <div class="gallery-header-content">
                        <h2 id="galleryModalTitle" class="gallery-title">Gallery Title</h2>
                        <p id="galleryModalSubtitle" class="gallery-subtitle">Gallery description</p>
                    </div>
                    <button onclick="closeGalleryModal()" class="gallery-close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Gallery Grid -->
                <div id="galleryModalContent" class="gallery-modal-body">
                    <!-- Gallery content will be loaded here -->
                </div>
                
                <!-- Navigation -->
                <div class="gallery-navigation">
                    <button id="galleryPrevBtn" class="gallery-nav-btn" onclick="previousGalleryPage()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>PREV</span>
                    </button>
                    
                    <div class="gallery-counter">
                        <span id="galleryCurrentPage">1</span>
                        <span class="gallery-divider">/</span>
                        <span id="galleryTotalPages">1</span>
                    </div>
                    
                    <button id="galleryNextBtn" class="gallery-nav-btn" onclick="nextGalleryPage()">
                        <span>NEXT</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Hidden gallery data for JavaScript -->
        <script type="application/json" id="galleryData">
        {
            <?php $__currentLoopData = $galeri->where('status', 'Active'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            "<?php echo e($gallery->id_galeri); ?>": {
                "id": <?php echo e($gallery->id_galeri); ?>,
                "name": "<?php echo e(addslashes($gallery->nama_galeri)); ?>",
                "items": [
                    <?php $__currentLoopData = $gallery->galleryItems->where('status', 'Active'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        "id": <?php echo e($item->id_gallery_item); ?>,
                        "type": "<?php echo e($item->type); ?>",
                        "sequence": <?php echo e($item->sequence); ?>,
                        "status": "<?php echo e($item->status); ?>",
                        "file_name": "<?php echo e($item->file_name); ?>",
                        "youtube_url": "<?php echo e($item->youtube_url); ?>",
                        "file_url": "<?php echo e($item->file_name ? asset('file/galeri/' . $item->file_name) : ''); ?>"
                    }<?php if(!$loop->last): ?>,<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }<?php if(!$loop->last): ?>,<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        }
        </script>
    <?php else: ?>
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
        <h3 class="text-white text-xl font-semibold mb-2">No Gallery Images Yet</h3>
        <p class="text-gray-400 text-center max-w-md">
            We're building our gallery showcase. Stay tuned to see visual examples of our AI projects and solutions in action!
        </p>
    </div>
    <?php endif; ?>
</section>

<!-- Gallery Modal Styles -->
<style>
/* Gallery Modal Styles */
.gallery-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    backdrop-filter: blur(10px);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.gallery-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
}

.gallery-modal-content {
    background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
    border-radius: 16px;
    width: 95%;
    max-width: 1200px;
    height: 85vh;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.15);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: modalSlideIn 0.4s ease-out;
    position: relative;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.gallery-modal-header {
    padding: 20px 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(44, 62, 80, 0.9);
    position: relative;
    flex-shrink: 0;
}

.gallery-header-content {
    margin-right: 60px;
}

.gallery-title {
    color: #ffd700;
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 8px 0;
    line-height: 1.2;
}

.gallery-subtitle {
    color: #a0aec0;
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}

.gallery-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.5);
    border: none;
    border-radius: 6px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #ffffff;
    transition: all 0.3s ease;
    z-index: 10;
}

.gallery-close::before {
    content: "IMAGE";
    position: absolute;
    top: -25px;
    right: 0;
    font-size: 10px;
    color: #888;
    background: rgba(0, 0, 0, 0.7);
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.gallery-close:hover {
    background: rgba(255, 0, 0, 0.3);
}

.gallery-modal-body {
    flex: 1;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.gallery-grid-6 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 15px;
    width: 100%;
    max-width: 1000px;
    height: 500px;
}

.gallery-item-6 {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #34495e;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.gallery-item-6:hover {
    transform: scale(1.02);
    border-color: #ffd700;
    box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
}

.gallery-item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item-6:hover .gallery-item-image {
    transform: scale(1.05);
}

.gallery-item-type {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.8);
    color: #ffd700;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.gallery-item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    padding: 15px 12px 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item-6:hover .gallery-item-overlay {
    opacity: 1;
}

.gallery-item-title {
    color: white;
    font-size: 12px;
    font-weight: 600;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    line-height: 1.2;
}

.gallery-youtube-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 0, 0, 0.9);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.gallery-item-6:hover .gallery-youtube-overlay {
    background: rgba(255, 0, 0, 1);
    transform: translate(-50%, -50%) scale(1.1);
}

.gallery-youtube-icon {
    width: 20px;
    height: 20px;
    color: white;
    margin-left: 2px;
}

.gallery-no-media {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #34495e;
    color: #95a5a6;
}

.gallery-no-media-text {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
    text-align: center;
}

.gallery-no-media-desc {
    font-size: 10px;
    text-align: center;
    opacity: 0.7;
    line-height: 1.3;
}

.gallery-navigation {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 15px 30px;
    background: rgba(26, 37, 47, 0.95);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.gallery-nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(52, 73, 94, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.gallery-nav-btn:hover:not(:disabled) {
    background: #ffd700;
    color: #000000;
    border-color: #ffd700;
}

.gallery-nav-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.gallery-counter {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 16px;
    font-weight: 700;
    color: #ffd700;
    background: rgba(255, 215, 0, 0.15);
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.gallery-divider {
    color: #7f8c8d;
    font-weight: 400;
}

/* Responsive Design */
@media (max-width: 768px) {
    .gallery-modal-content {
        width: 98%;
        height: 95vh;
    }
    
    .gallery-modal-header {
        padding: 15px 20px;
    }
    
    .gallery-title {
        font-size: 20px;
    }
    
    .gallery-subtitle {
        font-size: 12px;
    }
    
    .gallery-modal-body {
        padding: 15px;
    }
    
    .gallery-grid-6 {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 10px;
        height: 450px;
    }
    
    .gallery-navigation {
        padding: 10px 20px;
        gap: 15px;
    }
    
    .gallery-nav-btn {
        padding: 6px 12px;
        font-size: 10px;
    }
    
    .gallery-counter {
        font-size: 14px;
        padding: 6px 12px;
    }
}

@media (max-width: 480px) {
    .gallery-grid-6 {
        grid-template-columns: 1fr;
        grid-template-rows: repeat(6, 1fr);
        height: 480px;
    }
}
</style>

<script>
// Gallery pagination variables
let currentGalleryData = [];
let currentPage = 1;
let itemsPerPage = 6; // 3x2 grid (6 items per page)
let totalPages = 1;
let currentGalleryName = '';

// Load gallery data from embedded JSON
let galleryData = {};
try {
    const dataScript = document.getElementById('galleryData');
    if (dataScript) {
        galleryData = JSON.parse(dataScript.textContent);
        console.log('Gallery data loaded:', galleryData);
    }
} catch (error) {
    console.error('Error loading gallery data:', error);
}

function openGalleryModal(galleryId, galleryName) {
    console.log('Opening gallery modal for ID:', galleryId, 'Name:', galleryName);
    
    // Set title and subtitle
    document.getElementById('galleryModalTitle').textContent = galleryName || 'Gallery';
    document.getElementById('galleryModalSubtitle').textContent = 'AI automation, a fusion of artificial intelligence and process automation, streamlines repetitive tasks and decision-making through intelligent algorithms';
    
    const modal = document.getElementById('galleryModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Get gallery data for this ID
    const gallery = galleryData[galleryId.toString()];
    
    if (!gallery) {
        console.log('No gallery data found for ID:', galleryId);
        showEmptyGallery(galleryId, galleryName);
        return;
    }
    
    if (!gallery.items || gallery.items.length === 0) {
        showEmptyGallery(galleryId, galleryName);
        return;
    }
    
    // Set up pagination data
    currentGalleryData = gallery.items.sort((a, b) => a.sequence - b.sequence);
    currentGalleryName = galleryName;
    currentPage = 1;
    totalPages = Math.ceil(currentGalleryData.length / itemsPerPage);
    
    // Update pagination display
    updatePagination();
    
    // Display first page
    displayCurrentPage();
}

function displayCurrentPage() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentItems = currentGalleryData.slice(startIndex, endIndex);
    
    displayGalleryItems(currentItems, currentGalleryName);
}

function displayGalleryItems(items, galleryName) {
    console.log('Displaying items:', items);
    
    if (!items || items.length === 0) {
        showEmptyGallery(0, galleryName);
        return;
    }
    
    let content = '<div class="gallery-grid-6">';
    
    // Fill exactly 6 slots
    for (let i = 0; i < 6; i++) {
        const item = items[i];
        
        if (item) {
            const imageUrl = item.file_url || '';
            const title = galleryName || 'Gallery Item';
            
            if (item.type === 'image' && imageUrl) {
                content += `
                    <div class="gallery-item-6" onclick="openImageModal('${imageUrl}', '${title}')">
                        <img src="${imageUrl}" alt="${title}" class="gallery-item-image" 
                             onerror="this.parentElement.innerHTML='<div class=\\"gallery-no-media\\"><div class=\\"gallery-no-media-text\\">Image Error</div></div>';">
                        <div class="gallery-item-type">Image</div>
                        <div class="gallery-item-overlay">
                            <div class="gallery-item-title">${title}</div>
                        </div>
                    </div>
                `;
            } else if (item.type === 'youtube' && item.youtube_url) {
                const videoId = extractYouTubeId(item.youtube_url);
                const thumbnailUrl = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
                
                content += `
                    <div class="gallery-item-6" onclick="openYouTubeModal('${item.youtube_url}', '${title}')">
                        ${thumbnailUrl ? 
                            `<img src="${thumbnailUrl}" alt="${title}" class="gallery-item-image">` :
                            `<div class="gallery-no-media"><div class="gallery-no-media-text">YouTube Video</div></div>`
                        }
                        <div class="gallery-item-type">Video</div>
                        <div class="gallery-youtube-overlay">
                            <svg class="gallery-youtube-icon" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <div class="gallery-item-overlay">
                            <div class="gallery-item-title">${title}</div>
                        </div>
                    </div>
                `;
            } else {
                content += `
                    <div class="gallery-item-6">
                        <div class="gallery-no-media">
                            <div class="gallery-no-media-text">No Media Available</div>
                        </div>
                    </div>
                `;
            }
        } else {
            // Empty slot
            content += `
                <div class="gallery-item-6">
                    <div class="gallery-no-media">
                        <div class="gallery-no-media-text">Empty Slot</div>
                    </div>
                </div>
            `;
        }
    }
    
    content += '</div>';
    document.getElementById('galleryModalContent').innerHTML = content;
}

function updatePagination() {
    document.getElementById('galleryCurrentPage').textContent = currentPage;
    document.getElementById('galleryTotalPages').textContent = totalPages;
    
    const prevBtn = document.getElementById('galleryPrevBtn');
    const nextBtn = document.getElementById('galleryNextBtn');
    
    prevBtn.disabled = currentPage <= 1;
    nextBtn.disabled = currentPage >= totalPages;
}

function previousGalleryPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
        displayCurrentPage();
    }
}

function nextGalleryPage() {
    if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
        displayCurrentPage();
    }
}

function showEmptyGallery(galleryId, galleryName) {
    document.getElementById('galleryModalContent').innerHTML = `
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">🖼️</div>
            <h3 class="text-white text-xl font-semibold mb-2">No Gallery Data</h3>
            <p class="text-gray-400 mb-4">Gallery "${galleryName}" (ID: ${galleryId})</p>
        </div>
    `;
}

function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function extractYouTubeId(url) {
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function openYouTubeModal(youtubeUrl, title) {
    const videoId = extractYouTubeId(youtubeUrl);
    const embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=1` : youtubeUrl;
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-60 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative w-full max-w-4xl">
            <div class="relative w-full" style="padding-bottom: 56.25%;">
                <iframe 
                    class="absolute top-0 left-0 w-full h-full" 
                    src="${embedUrl}" 
                    title="${title}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function openImageModal(imageUrl, title) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-60 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-full max-h-full">
            <img src="${imageUrl}" alt="${title}" class="max-w-full max-h-full object-contain">
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal) {
        galleryModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && galleryModal.classList.contains('show')) {
            closeGalleryModal();
        }
    });
});
</script>

<?php endif; ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/gallery.blade.php ENDPATH**/ ?>