<!-- Gallery Section - ENHANCED VERSION with Global System Integration (Updated 9/14/2025) -->
@if($konf->gallery_section_active ?? true)
<section id="gallery" class="w-full max-w-screen-xl mx-auto px-3 sm:px-4 py-6 flex flex-col items-center gap-6 sm:gap-10">
    <!-- Header -->
    <div class="flex flex-col gap-2 text-center">
        <h2 class="text-yellow-400 text-3xl sm:text-5xl font-bold leading-tight tracking-tight">
            Gallery
        </h2>
        <p class="text-neutral-400 text-base sm:text-lg font-normal leading-6 tracking-tight">
            Explore the visual journey of my work, from concept to impactful solutions
        </p>
    </div>

    @if(isset($galeri) && $galeri->count() > 0)
    @php
    // Filter active gallery items and sort by sequence
    $activeGallery = $galeri->where('status', 'Active')->sortBy('sequence');
    $displayGallery = $activeGallery->take(6); // Show only 6 items in 2x3 grid
    $hasMore = $activeGallery->count() > 6; // Check if there are more than 6 items
    @endphp
    
    <!-- Gallery Grid - 2x3 Layout (6 images max) -->
    <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 w-full max-w-4xl">
        @foreach ($displayGallery as $index => $row)
        <div class="relative group rounded-lg bg-slate-900 outline outline-1 outline-slate-500 overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-md cursor-pointer" 
             onclick="openGalleryModal({{ $row->id_galeri }}, '{{ addslashes($row->nama_galeri) }}')">
            
            @if($row->thumbnail)
                {!! \App\Helpers\ImageHelper::generateResponsiveImage('file/galeri/' . $row->thumbnail, $row->nama_galeri ?? 'Gallery image', ['w-full', 'h-auto', 'rounded-lg', 'aspect-square', 'object-cover']) !!}
            @elseif($row->galleryItems->where('status', 'Active')->first())
                @php
                $firstItem = $row->galleryItems->where('status', 'Active')->first();
                @endphp
                @if($firstItem->type === 'image')
                    {!! \App\Helpers\ImageHelper::generateResponsiveImage('file/galeri/' . $firstItem->file_name, $row->nama_galeri ?? 'Gallery image', ['w-full', 'h-auto', 'rounded-lg', 'aspect-square', 'object-cover']) !!}
                @elseif($firstItem->type === 'youtube')
                    @php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $firstItem->youtube_url, $matches);
                    $videoId = $matches[1] ?? null;
                    @endphp
                    @if($videoId)
                        <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                             alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                             class="w-full h-auto rounded-lg aspect-square object-cover" />
                    @endif
                @endif
            @else
                <div class="w-full aspect-square bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-500 text-3xl"></i>
                </div>
            @endif
            
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center">
                    <p class="text-white text-sm font-semibold mb-2">{{ $row->nama_galeri ?? 'Gallery' }}</p>
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
        @endforeach
    </div>
    
    <!-- See More Button - Show if more than 6 items -->
    @if($hasMore)
    <div class="mt-6">
        <a href="{{ url('/gallery') }}" 
           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black font-semibold rounded-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <span>See More</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
    @endif
        
    @else
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
        <h3 class="text-white text-xl font-semibold mb-2">No Gallery Images Yet</h3>
        <p class="text-gray-400 text-center max-w-md">
            We're building our gallery showcase. Stay tuned to see visual examples of our AI projects and solutions in action!
        </p>
    </div>
    @endif
</section>

<!-- Enhanced Gallery Modal with Global System Integration -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-85 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px);">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl w-full max-w-6xl h-auto max-h-[90vh] overflow-hidden shadow-2xl border border-slate-600">
        <!-- Enhanced Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-slate-600 bg-gradient-to-r from-slate-700 to-slate-800">
            <div class="flex items-center gap-3">
                <span class="text-yellow-400 text-2xl">🖼️</span>
                <div>
                    <h3 id="galleryModalTitle" class="text-xl font-bold text-white">Gallery</h3>
                </div>
            </div>
            <button onclick="closeGalleryModal()" 
                    class="text-gray-400 hover:text-white bg-slate-700 hover:bg-slate-600 transition-all duration-200 p-2 rounded-lg shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Enhanced Modal Content -->
        <div id="galleryModalContent" class="p-6 overflow-y-auto bg-gradient-to-b from-slate-900 to-slate-800" style="max-height: calc(90vh - 100px);">
            <!-- Enhanced gallery content will be loaded here via GlobalGalleryLoader -->
        </div>
    </div>
</div>

<script>
async function openGalleryModal(galleryId, galleryName) {
    console.log('🚀 Enhanced Opening gallery modal for gallery ID:', galleryId, 'Name:', galleryName);
    
    document.getElementById('galleryModalTitle').textContent = `${galleryName}`;
    document.getElementById('galleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    const container = document.getElementById('galleryModalContent');
    
    // Use enhanced GlobalGalleryLoader
    if (window.GlobalGalleryLoader) {
        console.log('🎯 Using enhanced GlobalGalleryLoader for Gallery');
        
        // Show enhanced loading
        GlobalGalleryLoader.showLoading(container, galleryName, 'gallery', galleryId);
        
        try {
            // Load data with enhanced loader
            const result = await GlobalGalleryLoader.loadGalleryItems('gallery', galleryId, galleryName);
            
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
                        console.log('🎉 Enhanced gallery displaying', validItems.length, 'items with pagination');
                        
                        // Create a container for the enhanced gallery
                        container.innerHTML = '<div id="galleryEnhancedContainer"></div>';
                        
                        // Use enhanced display with 2x3 grid pagination
                        GlobalGalleryLoader.displayGalleryItems(validItems, 'galleryEnhancedContainer', galleryName, 'gallery');
                        return;
                    }
                }
            }
            
            // Fallback to empty state
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
            
        } catch (error) {
            console.error('🚨 Enhanced gallery loading error:', error);
            GlobalGalleryLoader.showEmptyGallery(container, galleryName, 'gallery');
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

// Enhanced debug logging
console.log('🚀 Enhanced Gallery with Global Gallery System loaded (Updated 9/14/2025)');
console.log('📍 Current location:', window.location.href);
console.log('🔧 GlobalGalleryLoader available:', typeof window.GlobalGalleryLoader !== 'undefined');
console.log('🖼️ GlobalImageModal available:', typeof window.GlobalImageModal !== 'undefined');
</script>

@endif