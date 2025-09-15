<!-- Awards & Recognition Section - ENHANCED VERSION with Global System Integration (Updated 9/14/2025) -->
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
        <!-- Awards Grid - ENHANCED LAYOUT -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $award; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            // Get company and period from database fields
            $companyName = $row->company ?? 'Unknown Company';
            $periodInfo = $row->period ?? date('Y');
            $keteranganAward = $row->keterangan_award ?? 'Achieved recognition in prestigious competition, demonstrating innovative solutions and excellence.';
            
            // Limit description to 350 characters with suffix "..."
            $limitedDescription = strlen(strip_tags($keteranganAward)) > 350 
                ? substr(strip_tags($keteranganAward), 0, 350) . '...' 
                : strip_tags($keteranganAward);
            ?>
            
            <!-- Enhanced Award Card -->
            <div class="award-card-enhanced group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 cursor-pointer transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl border border-slate-700 hover:border-yellow-500/30" 
                 onclick="openAwardGallery(<?php echo e($row->id_award); ?>, '<?php echo e(addslashes($row->nama_award)); ?>')">
                
                <!-- Main Content Container -->
                <div class="award-content-container">
                    <!-- Logo & Title Section -->
                    <div class="flex items-start gap-4 mb-6">
                        <!-- Enhanced Logo -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-800 border-2 border-slate-600 group-hover:border-yellow-400/50 transition-all duration-300" 
                                 style="backdrop-filter: blur(10px);">
                                <?php if($row->gambar_award && file_exists(public_path('file/award/' . $row->gambar_award))): ?>
                                    <img src="<?php echo e(asset('file/award/' . $row->gambar_award)); ?>" 
                                         alt="<?php echo e($row->nama_award); ?>" 
                                         class="w-14 h-14 object-contain group-hover:scale-110 transition-transform duration-300" />
                                <?php else: ?>
                                    <span class="text-white font-bold text-base group-hover:text-yellow-400 transition-colors">
                                        <?php echo e(strtoupper(substr($companyName, 0, 3))); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Title & Company Info -->
                        <div class="flex-1 min-w-0">
                            <!-- Award Title -->
                            <h3 class="text-white text-xl font-bold mb-2 leading-tight group-hover:text-yellow-400 transition-colors duration-300">
                                <?php echo e($row->nama_award); ?>

                            </h3>
                            
                            <!-- Company & Year -->
                            <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400 group-hover:text-yellow-300 transition-colors duration-300">
                                <?php echo e(strtoupper($companyName)); ?> • <?php echo e($periodInfo); ?>

                            </p>
                        </div>
                    </div>
                    
                    <!-- Enhanced Description -->
                    <p class="text-gray-400 text-sm leading-relaxed mb-4 group-hover:text-gray-300 transition-colors duration-300">
                        <?php echo e($limitedDescription); ?>

                    </p>
                </div>
                
                <!-- Enhanced View Gallery Button -->
                <button class="view-gallery-btn-enhanced flex items-center gap-2 text-gray-400 text-sm font-medium uppercase tracking-wide transition-all duration-300 hover:text-yellow-400 hover:transform hover:translateY(-1px)">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-4-4m4 4l-4 4"></path>
                        </svg>
                        VIEW GALLERY
                    </span>
                </button>
                
                <!-- Hover Overlay Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-2xl"></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php else: ?>
        <!-- Enhanced No Data State -->
        <div class="flex flex-col items-center justify-center py-20 bg-slate-800/30 rounded-2xl border-2 border-dashed border-slate-600">
            <div class="text-yellow-400 text-8xl mb-6 animate-bounce">🏆</div>
            <h3 class="text-white text-2xl font-bold mb-4">No Awards Yet</h3>
            <p class="text-gray-400 text-center max-w-md text-lg leading-relaxed">
                We're building our track record of achievements and recognition. Stay tuned to see our upcoming awards and accomplishments!
            </p>
            <div class="mt-6 flex items-center gap-2 text-gray-500 text-sm">
                <i class="fas fa-info-circle"></i>
                <span>Awards will appear here once added</span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Enhanced Styles -->
<style>
/* Enhanced Award Card Styling */
.award-card-enhanced {
    min-height: 320px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    position: relative;
    backdrop-filter: blur(5px);
}

.award-card-enhanced:hover {
    box-shadow: 0 20px 50px rgba(251, 191, 36, 0.1), 0 10px 30px rgba(0, 0, 0, 0.4);
}

/* Enhanced View Gallery Button */
.view-gallery-btn-enhanced {
    position: absolute;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 10;
    background: rgba(30, 41, 59, 0.9);
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    backdrop-filter: blur(12px);
    border: 1px solid rgba(71, 85, 105, 0.4);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.view-gallery-btn-enhanced:hover {
    background: rgba(71, 85, 105, 0.95);
    border-color: rgba(251, 191, 36, 0.6);
    box-shadow: 0 6px 20px rgba(251, 191, 36, 0.2);
}

.view-gallery-btn-enhanced span {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
}

/* Content area adjustment */
.award-content-container {
    padding-bottom: 4rem;
    min-height: calc(100% - 4rem);
}

/* Enhanced hover effects */
.award-card-enhanced .w-16.h-16 {
    transition: all 0.3s ease;
}

.award-card-enhanced:hover .w-16.h-16 {
    transform: scale(1.1);
}

/* Responsive enhancements */
@media (max-width: 768px) {
    .award-card-enhanced {
        min-height: 300px;
        padding: 1.5rem;
    }
    
    .award-card-enhanced .flex.items-start.gap-4 {
        gap: 1rem;
    }
    
    .award-card-enhanced .w-16.h-16 {
        width: 4.5rem;
        height: 4.5rem;
    }
    
    .view-gallery-btn-enhanced {
        bottom: 1rem;
        right: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .view-gallery-btn-enhanced span {
        font-size: 0.7rem;
    }
    
    .award-content-container {
        padding-bottom: 3rem;
    }
}

@media (max-width: 640px) {
    .award-card-enhanced {
        min-height: 280px;
        padding: 1.25rem;
    }
    
    .award-card-enhanced .text-xl {
        font-size: 1.125rem;
    }
    
    .award-card-enhanced .w-16.h-16 {
        width: 4rem;
        height: 4rem;
    }
    
    .view-gallery-btn-enhanced {
        bottom: 0.875rem;
        right: 0.875rem;
    }
    
    .award-content-container {
        padding-bottom: 2.5rem;
    }
}
</style>

<!-- Enhanced Award Gallery Modal with Global System Integration -->
<div id="awardGalleryModal" class="fixed inset-0 bg-black bg-opacity-85 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px);">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl w-full max-w-6xl h-auto max-h-[90vh] overflow-hidden shadow-2xl border border-slate-600">
        <!-- Enhanced Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-slate-600 bg-gradient-to-r from-slate-700 to-slate-800">
            <div class="flex items-center gap-3">
                <span class="text-yellow-400 text-2xl">🏆</span>
                <div>
                    <h3 id="awardGalleryTitle" class="text-xl font-bold text-white">Award Gallery</h3>
                </div>
            </div>
            <button onclick="closeAwardGallery()" 
                    class="text-gray-400 hover:text-white bg-slate-700 hover:bg-slate-600 transition-all duration-200 p-2 rounded-lg shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Enhanced Modal Content -->
        <div id="awardGalleryContent" class="p-6 overflow-y-auto bg-gradient-to-b from-slate-900 to-slate-800" style="max-height: calc(90vh - 100px);">
            <!-- Enhanced gallery content will be loaded here via GlobalGalleryLoader -->
        </div>
    </div>
</div>

<!-- Enhanced JavaScript with Global System Integration -->
<script>
async function openAwardGallery(awardId, awardName) {
    console.log('🚀 Enhanced Opening gallery for award ID:', awardId, 'Name:', awardName);
    
    document.getElementById('awardGalleryTitle').textContent = `${awardName}`;
    document.getElementById('awardGalleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    const container = document.getElementById('awardGalleryContent');
    
    // Use enhanced GlobalGalleryLoader
    if (window.GlobalGalleryLoader) {
        console.log('🎯 Using enhanced GlobalGalleryLoader for Awards gallery');
        
        // Show enhanced loading
        GlobalGalleryLoader.showLoading(container, awardName, 'award', awardId);
        
        try {
            // Load data with enhanced loader
            const result = await GlobalGalleryLoader.loadGalleryItems('award', awardId, awardName);
            
            if (result.success && result.data) {
                let items = null;
                
                // Handle different data structures
                if (result.data.success === true && result.data.items) {
                    items = result.data.items;
                } else if (Array.isArray(result.data)) {
                    items = result.data;
                } else if (result.data.items) {
                    items = result.data.items;
                }
                
                if (items && Array.isArray(items) && items.length > 0) {
                    const validItems = items.filter(item => item !== null && item !== undefined);
                    
                    if (validItems.length > 0) {
                        console.log('🎉 Enhanced award gallery displaying', validItems.length, 'items with pagination');
                        
                        // Create a container for the enhanced gallery
                        container.innerHTML = '<div id="awardGalleryEnhancedContainer"></div>';
                        
                        // Use enhanced display with 2x3 grid pagination
                        GlobalGalleryLoader.displayGalleryItems(validItems, 'awardGalleryEnhancedContainer', awardName, 'award');
                        return;
                    }
                }
            }
            
            // Fallback to empty state
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
            
        } catch (error) {
            console.error('🚨 Enhanced gallery loading error:', error);
            GlobalGalleryLoader.showEmptyGallery(container, awardName, 'award');
        }
    } else {
        // Fallback if GlobalGalleryLoader not available
        console.log('⚠️ GlobalGalleryLoader not found, showing fallback message');
        container.innerHTML = `
            <div class="text-center py-16 bg-slate-800/30 rounded-xl border-2 border-dashed border-slate-600">
                <div class="text-red-400 text-8xl mb-6">⚠️</div>
                <h3 class="text-white text-2xl font-bold mb-4">Gallery System Not Available</h3>
                <p class="text-gray-400 text-lg mb-6 max-w-md mx-auto">
                    The global gallery system is not loaded. Please ensure global-gallery-loader.blade.php is included.
                </p>
                <div class="flex items-center justify-center gap-2 text-gray-500 text-sm bg-slate-700/50 inline-flex px-4 py-2 rounded-full">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Missing dependency: GlobalGalleryLoader</span>
                </div>
            </div>
        `;
    }
}

function closeAwardGallery() {
    document.getElementById('awardGalleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Enhanced modal event listeners
document.getElementById('awardGalleryModal').addEventListener('click', function(e) {
    if (e.target === this) closeAwardGallery();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const awardModal = document.getElementById('awardGalleryModal');
        if (!awardModal.classList.contains('hidden')) {
            closeAwardGallery();
        }
    }
});

// Enhanced debug logging
console.log('🚀 Enhanced Awards with Global Gallery System loaded (Updated 9/14/2025)');
console.log('📍 Current location:', window.location.href);
console.log('🔧 GlobalGalleryLoader available:', typeof window.GlobalGalleryLoader !== 'undefined');
console.log('🖼️ GlobalImageModal available:', typeof window.GlobalImageModal !== 'undefined');
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/awards-updated.blade.php ENDPATH**/ ?>