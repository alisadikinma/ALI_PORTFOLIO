@extends('layouts.web')

@section('title', 'Gallery - ' . ($konf->instansi_setting ?? 'Portfolio'))
@section('meta_description', 'Explore our comprehensive gallery showcase featuring visual journey of innovative solutions, AI projects, and digital transformations.')
@section('meta_keywords', 'gallery, portfolio, AI projects, digital solutions, innovation showcase, visual portfolio')

@section('isi')
{{-- Include Global Gallery Components --}}
@include('partials.global-image-modal')
@include('partials.global-gallery-loader')

<!-- Gallery Page Header -->
<section class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
    <div class="text-center mb-12">
        <h1 class="text-yellow-400 text-4xl sm:text-6xl font-bold mb-4">
            Gallery
        </h1>
        <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto leading-relaxed">
            Explore the complete visual journey of my work, from innovative AI concepts to impactful digital solutions and transformations
        </p>
        <!-- Breadcrumb -->
        <nav class="flex justify-center mt-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ url('/') }}" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                        Home
                    </a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-300">Gallery</li>
            </ol>
        </nav>
    </div>

    @if(isset($galeri) && $galeri->count() > 0)
    <!-- Complete Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 w-full">
        @foreach ($galeri->where('status', 'Active')->sortBy('sequence') as $index => $row)
        <div class="gallery-card group relative rounded-lg bg-slate-900 outline outline-1 outline-slate-500 overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-pointer" 
             onclick="openGalleryModal({{ $row->id_galeri }}, '{{ addslashes($row->nama_galeri) }}')">
            
            <!-- Thumbnail Image -->
            <div class="aspect-square overflow-hidden">
                @if($row->thumbnail)
                    <img src="{{ asset('file/galeri/' . $row->thumbnail) }}" 
                         alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                @elseif($row->galleryItems->where('status', 'Active')->first())
                    @php
                    $firstItem = $row->galleryItems->where('status', 'Active')->first();
                    @endphp
                    @if($firstItem->type === 'image')
                        <img src="{{ asset('file/galeri/' . $firstItem->file_name) }}" 
                             alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                    @elseif($firstItem->type === 'youtube')
                        @php
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $firstItem->youtube_url, $matches);
                        $videoId = $matches[1] ?? null;
                        @endphp
                        @if($videoId)
                            <div class="relative">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                     alt="{{ $row->nama_galeri ?? 'Gallery image' }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                <!-- YouTube Play Icon -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-red-600 rounded-full p-3 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-image text-gray-500 text-4xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Gallery Info Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute bottom-0 left-0 right-0 p-4">
                    <h3 class="text-white text-lg font-semibold mb-2 truncate">
                        {{ $row->nama_galeri ?? 'Gallery Collection' }}
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300 text-sm">
                            {{ $row->galleryItems->where('status', 'Active')->count() }} Items
                        </span>
                        <div class="flex items-center px-3 py-2 bg-yellow-400 text-black rounded-lg text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Badge -->
            @if($row->nama_galeri)
            <div class="absolute top-3 left-3 px-3 py-1 bg-yellow-400 text-black text-xs font-semibold rounded-full">
                {{ Str::limit($row->nama_galeri, 15) }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
        
    @else
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-24">
        <div class="text-yellow-400 text-8xl mb-6">🖼️</div>
        <h2 class="text-white text-2xl font-semibold mb-4">No Gallery Content Available</h2>
        <p class="text-gray-400 text-center max-w-md text-lg leading-relaxed">
            Our gallery showcase is currently being prepared. Please check back soon to explore our visual portfolio of innovative solutions and projects.
        </p>
        <a href="{{ url('/') }}" 
           class="mt-8 inline-flex items-center gap-3 px-6 py-3 bg-yellow-400 text-black font-semibold rounded-lg hover:bg-yellow-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Home
        </a>
    </div>
    @endif
</section>

<!-- Enhanced Gallery Modal using Global Gallery System -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl w-full max-w-6xl h-auto max-h-[90vh] overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-slate-600 bg-slate-700">
            <h3 id="galleryModalTitle" class="text-xl font-bold text-white truncate mr-4">Gallery Collection</h3>
            <div class="flex items-center gap-2">
                <!-- Close X Button -->
                <button onclick="closeGalleryModal()" 
                        class="text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div id="galleryModalContent" class="p-6 overflow-y-auto" style="max-height: calc(90vh - 100px);">
            <!-- Gallery content will be loaded here -->
        </div>
    </div>
</div>

<style>
/* Gallery Card Enhancements */
.gallery-card {
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
}

.gallery-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    border-color: rgba(251, 191, 36, 0.5);
}

/* Responsive Grid Adjustments */
@media (max-width: 640px) {
    .gallery-card {
        margin-bottom: 1rem;
    }
}

@media (min-width: 640px) and (max-width: 1024px) {
    .gallery-card:hover {
        transform: translateY(-6px) scale(1.02);
    }
}

/* Loading Animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

<script>
async function openGalleryModal(galleryId, galleryName) {
    console.log('Opening gallery modal for gallery ID:', galleryId, 'Name:', galleryName);
    
    document.getElementById('galleryModalTitle').textContent = `${galleryName} - Collection`;
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

// Smooth scroll to top when page loads
window.addEventListener('load', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

console.log('Gallery page with Global Gallery Loader loaded');
</script>

@endsection
