<!-- Gallery Section - ENHANCED VERSION with Global Gallery System -->
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
    <!-- Gallery Grid -->
    <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 w-full">
        <?php $__currentLoopData = $galeri->where('status', 'Active')->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="relative group rounded-lg bg-slate-900 outline outline-1 outline-slate-500 overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-md cursor-pointer" 
             onclick="openGalleryModal(<?php echo e($row->id_galeri); ?>, '<?php echo e(addslashes($row->nama_galeri)); ?>')">
            
            <?php if($row->thumbnail): ?>
                <img src="<?php echo e(asset('file/galeri/' . $row->thumbnail)); ?>" 
                     alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" 
                     class="w-full h-auto rounded-lg aspect-square object-cover" />
            <?php elseif($row->galleryItems->where('status', 'Active')->first()): ?>
                <?php
                $firstItem = $row->galleryItems->where('status', 'Active')->first();
                ?>
                <?php if($firstItem->type === 'image'): ?>
                    <img src="<?php echo e(asset('file/galeri/' . $firstItem->file_name)); ?>" 
                         alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" 
                         class="w-full h-auto rounded-lg aspect-square object-cover" />
                <?php elseif($firstItem->type === 'youtube'): ?>
                    <?php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $firstItem->youtube_url, $matches);
                    $videoId = $matches[1] ?? null;
                    ?>
                    <?php if($videoId): ?>
                        <img src="https://img.youtube.com/vi/<?php echo e($videoId); ?>/maxresdefault.jpg" 
                             alt="<?php echo e($row->nama_galeri ?? 'Gallery image'); ?>" 
                             class="w-full h-auto rounded-lg aspect-square object-cover" />
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
        
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

<!-- Enhanced Gallery Modal using Global Gallery System -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl w-full max-w-4xl h-auto max-h-[85vh] overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-600 bg-slate-700">
            <h3 id="galleryModalTitle" class="text-lg font-bold text-white truncate mr-4">Gallery</h3>
            <div class="flex items-center gap-2">
                <!-- Close X Button -->
                <button onclick="closeGalleryModal()" 
                        class="text-gray-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div id="galleryModalContent" class="p-4 overflow-y-auto" style="max-height: calc(85vh - 80px);">
            <!-- Gallery content will be loaded here -->
        </div>
    </div>
</div>

<script>
async function openGalleryModal(galleryId, galleryName) {
    console.log('Opening gallery modal for gallery ID:', galleryId, 'Name:', galleryName);
    
    document.getElementById('galleryModalTitle').textContent = `${galleryName} - Gallery`;
    document.getElementById('galleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Use global gallery loader
    const container = document.getElementById('galleryModalContent');
    GlobalGalleryLoader.showLoading(container, galleryName, 'gallery', galleryId);
    
    const result = await GlobalGalleryLoader.loadGalleryItems('gallery', galleryId, galleryName);
    
    if (result.success && result.data.success) {
        GlobalGalleryLoader.displayGalleryItems(result.data.items, 'galleryModalContent', galleryName, 'gallery');
    } else {
        GlobalGalleryLoader.showError(container, galleryName, 'gallery', galleryId, result.error, result.attemptedUrls);
    }
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Modal event listeners
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) closeGalleryModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const galleryModal = document.getElementById('galleryModal');
        if (!galleryModal.classList.contains('hidden')) {
            closeGalleryModal();
        }
    }
});

// Debug: Log when page loads
console.log('Enhanced Gallery with Global Gallery Loader loaded');
console.log('Current location:', window.location);
</script>

<?php endif; ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/gallery-updated.blade.php ENDPATH**/ ?>