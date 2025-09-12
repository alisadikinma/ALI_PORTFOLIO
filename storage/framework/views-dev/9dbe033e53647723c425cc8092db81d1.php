<!-- Awards & Recognition Section - ENHANCED VERSION with Global Gallery System -->
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
            // Get company and period from database fields (new enhancement)
            $companyName = $row->company ?? 'Unknown Company';
            $periodInfo = $row->period ?? date('Y');
            $keteranganAward = $row->keterangan_award ?? 'Achieved recognition in prestigious competition, demonstrating innovative solutions and excellence.';
            
            // Limit description to 300 characters with suffix "..."
            $limitedDescription = strlen(strip_tags($keteranganAward)) > 350 
                ? substr(strip_tags($keteranganAward), 0, 350) . '...' 
                : strip_tags($keteranganAward);
            ?>
            
            <!-- Award Card - Side by Side Layout -->
            <div class="award-card-exact group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 cursor-pointer transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl" 
                 onclick="openAwardGallery(<?php echo e($row->id_award); ?>, '<?php echo e(addslashes($row->nama_award)); ?>')">
                
                <!-- Main Content Container -->
                <div class="award-content-container">
                    <!-- Logo & Title Section - Side by Side -->
                    <div class="flex items-start gap-4 mb-6">
                        <!-- Logo - Perbesar dari w-16 h-16 menjadi w-20 h-20 -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center" 
                                 style="background-color: transparent; border: 2px solid rgba(255, 255, 255, 0.1);">
                                <?php if($row->gambar_award && file_exists(public_path('file/award/' . $row->gambar_award))): ?>
                                    <img src="<?php echo e(asset('file/award/' . $row->gambar_award)); ?>" 
                                         alt="<?php echo e($row->nama_award); ?>" 
                                         class="w-14 h-14 object-contain" />
                                <?php else: ?>
                                    <span class="text-white font-bold text-base">
                                        <?php echo e(strtoupper(substr($companyName, 0, 3))); ?>

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
                            
                            <!-- Company & Year - Using new database fields -->
                            <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                                <?php echo e(strtoupper($companyName)); ?> • <?php echo e($periodInfo); ?>

                            </p>
                        </div>
                    </div>
                    
                    <!-- Description - Limited to 300 characters with suffix "..." -->
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        <?php echo e($limitedDescription); ?>

                    </p>
                </div>
                
                <!-- View Gallery Button - Fixed position at bottom right -->
                <button class="view-gallery-btn flex items-center gap-2 text-gray-400 text-sm font-medium uppercase tracking-wide transition-colors hover:text-white">
                    <span>VIEW GALLERY</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
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
    position: relative;
}

.award-card-exact:hover {
    border-color: rgba(71, 85, 105, 0.5);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
}

/* Fixed VIEW GALLERY button positioning */
.view-gallery-btn {
    position: absolute;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 10;
    background: rgba(30, 41, 59, 0.8);
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.view-gallery-btn:hover {
    background: rgba(71, 85, 105, 0.9);
    border-color: rgba(251, 191, 36, 0.5);
    color: #fbbf24 !important;
}

.view-gallery-btn span {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* Content area adjustment to prevent overlap */
.award-content-container {
    padding-bottom: 3rem; /* Space for fixed button */
    min-height: calc(100% - 3.5rem);
}

/* Remove old content area adjustment */

/* Logo transparent background styling - Updated for larger logo */
.award-card-exact .w-20.h-20 {
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.award-card-exact:hover .w-20.h-20 {
    transform: scale(1.05);
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
    
    .award-card-exact .w-20.h-20 {
        width: 4.5rem;
        height: 4.5rem;
    }
    
    .award-card-exact .w-14.h-14 {
        width: 3rem;
        height: 3rem;
    }
    
    .view-gallery-btn {
        bottom: 1rem;
        right: 1rem;
        padding: 0.4rem 0.6rem;
    }
    
    .view-gallery-btn span {
        font-size: 0.7rem;
    }
    
    .award-content-container {
        padding-bottom: 2.5rem;
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
    
    .award-card-exact .w-20.h-20 {
        width: 4rem;
        height: 4rem;
    }
    
    .award-card-exact .w-14.h-14 {
        width: 2.75rem;
        height: 2.75rem;
    }
    
    .view-gallery-btn {
        bottom: 0.875rem;
        right: 0.875rem;
    }
    
    .award-content-container {
        padding-bottom: 2.25rem;
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

<script>
async function openAwardGallery(awardId, awardName) {
    console.log('Opening gallery for award ID:', awardId, 'Name:', awardName);
    
    document.getElementById('awardGalleryTitle').textContent = `${awardName} - Gallery`;
    document.getElementById('awardGalleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Use global gallery loader
    const container = document.getElementById('awardGalleryContent');
    GlobalGalleryLoader.showLoading(container, awardName, 'award', awardId);
    
    const result = await GlobalGalleryLoader.loadGalleryItems('award', awardId, awardName);
    
    if (result.success && result.data.success) {
        GlobalGalleryLoader.displayGalleryItems(result.data.items, 'awardGalleryContent', awardName, 'award');
    } else {
        GlobalGalleryLoader.showError(container, awardName, 'award', awardId, result.error, result.attemptedUrls);
    }
}

function closeAwardGallery() {
    document.getElementById('awardGalleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Modal event listeners
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

// Debug: Log when page loads
console.log('Enhanced Awards gallery script with Global Gallery Loader loaded');
console.log('Current location:', window.location);
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/awards-updated.blade.php ENDPATH**/ ?>