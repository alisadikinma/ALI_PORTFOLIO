<!-- Gallery Section - ENHANCED VERSION with Global Gallery System -->
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
                <img src="{{ asset('file/galeri/' . $row->thumbnail) }}" 
                     alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                     class="w-full h-auto rounded-lg aspect-square object-cover" />
            @elseif($row->galleryItems->where('status', 'Active')->first())
                @php
                $firstItem = $row->galleryItems->where('status', 'Active')->first();
                @endphp
                @if($firstItem->type === 'image')
                    <img src="{{ asset('file/galeri/' . $firstItem->file_name) }}" 
                         alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                         class="w-full h-auto rounded-lg aspect-square object-cover" />
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
    
    const container = document.getElementById('galleryModalContent');
    
    // Show loading first
    container.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400 mx-auto mb-4"></div>
                <p class="text-gray-400 text-lg">Loading gallery...</p>
            </div>
        </div>
    `;
    
    try {
        // Try to load with a shorter timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
        
        const baseUrl = window.location.origin;
        const currentPath = window.location.pathname;
        let basePath = '';
        if (currentPath.includes('/ALI_PORTFOLIO/')) {
            basePath = '/ALI_PORTFOLIO';
        }
        
        const response = await fetch(`${baseUrl}${basePath}/public/global_gallery_api.php?type=gallery&id=${galleryId}`, {
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
            
            if (data.success && data.items && data.items.length > 0) {
                // Display images in a grid
                let content = `<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">`;
                
                data.items.forEach((item, index) => {
                    if (item.type === 'image' && item.file_url) {
                        content += `
                            <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group cursor-pointer" style="height: 280px;">
                                <div class="relative" style="height: 220px; overflow: hidden;">
                                    <img src="${item.file_url}" 
                                         alt="${galleryName} - Image ${index + 1}" 
                                         class="w-full h-full object-cover group-hover:opacity-90 transition-opacity" 
                                         style="object-fit: cover; width: 100%; height: 100%;"
                                         onclick="openImageModal('${item.file_url}', '${galleryName} - Image ${index + 1}')"
                                         onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm\\' style=\\'height: 220px;\\'>Image Error</div>';">
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">Image</span>
                                    </div>
                                </div>
                                <div class="p-3" style="height: 60px; display: flex; flex-direction: column; justify-content: center;">
                                    <h4 class="text-white font-semibold text-sm mb-1 truncate">${galleryName}</h4>
                                    <p class="text-gray-400 text-xs">Image ${index + 1} of ${data.items.length}</p>
                                </div>
                            </div>
                        `;
                    } else if (item.type === 'youtube' && item.youtube_url) {
                        const videoId = extractYouTubeId(item.youtube_url);
                        const thumbnailUrl = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
                        
                        content += `
                            <div class="bg-slate-700 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group cursor-pointer" style="height: 280px;">
                                <div class="relative" style="height: 220px; overflow: hidden;">
                                    ${thumbnailUrl ? 
                                        `<img src="${thumbnailUrl}" alt="${galleryName} - Video ${index + 1}" class="w-full h-full object-cover" style="object-fit: cover; width: 100%; height: 100%;">` :
                                        `<div class="w-full h-full flex items-center justify-center bg-slate-600 text-white text-sm" style="height: 220px;">YouTube Video</div>`
                                    }
                                    <div class="absolute inset-0 flex items-center justify-center" onclick="window.open('${item.youtube_url}', '_blank')">
                                        <div class="bg-red-600 rounded-full p-3 group-hover:bg-red-700 transition-colors">
                                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">Video</span>
                                    </div>
                                </div>
                                <div class="p-3" style="height: 60px; display: flex; flex-direction: column; justify-content: center;">
                                    <h4 class="text-white font-semibold text-sm mb-1 truncate">${galleryName}</h4>
                                    <p class="text-gray-400 text-xs">Video ${index + 1} of ${data.items.length}</p>
                                </div>
                            </div>
                        `;
                    }
                });
                
                content += '</div>';
                container.innerHTML = content;
            } else {
                throw new Error('No items found');
            }
        } else {
            throw new Error('API request failed');
        }
    } catch (error) {
        console.log('Gallery loading failed:', error.message);
        // Show no gallery message if loading fails
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-yellow-400 text-6xl mb-4">🖼️</div>
                <h3 class="text-white text-xl font-semibold mb-2">No Gallery Items</h3>
                <p class="text-gray-400 mb-4">"${galleryName}" doesn't have any gallery items yet.</p>
                <div class="text-gray-500 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    ${error.name === 'AbortError' ? 'Loading timed out' : 'Gallery temporarily unavailable'}
                </div>
            </div>
        `;
    }
}

function closeGalleryModal() {
    document.getElementById('galleryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Helper function to extract YouTube ID
function extractYouTubeId(url) {
    if (!url) return null;
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// Simple image modal function
function openImageModal(imageUrl, title) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-60 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full">
            <img src="${imageUrl}" alt="${title}" class="max-w-full max-h-full object-contain">
            <button onclick="this.parentElement.parentElement.remove(); document.body.style.overflow = '';" 
                    class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="absolute bottom-4 left-4 right-4 text-center">
                <h3 class="text-white text-lg font-semibold">${title}</h3>
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

@endif