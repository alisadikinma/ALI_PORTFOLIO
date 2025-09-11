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