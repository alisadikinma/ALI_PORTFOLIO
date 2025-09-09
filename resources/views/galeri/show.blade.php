@extends('layouts.web')

@section('title', $galeri->nama_galeri . ' - Gallery')

@section('isi')
<section class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-yellow-400 text-4xl sm:text-6xl font-bold mb-4">{{ $galeri->nama_galeri }}</h1>
        <div class="flex items-center justify-center gap-4 mb-6">
            <span class="text-yellow-400 text-lg">{{ $galeri->company ?? '—' }}</span>
            <span class="text-gray-400">•</span>
            <span class="text-gray-400 text-lg">{{ $galeri->period ?? '—' }}</span>
        </div>
        @if($galeri->deskripsi_galeri)
            <p class="text-neutral-400 text-lg max-w-3xl mx-auto">{{ $galeri->deskripsi_galeri }}</p>
        @endif
    </div>

    <!-- Gallery Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galeri->items as $item)
            <a href="#" class="gallery-item-card block cursor-pointer group" 
               data-id="{{ $item->id_gallery_item }}"
               data-title="{{ $galeri->nama_galeri }}">
                
                <div class="relative rounded-2xl aspect-[16/10] overflow-hidden bg-slate-800 shadow-xl hover:shadow-2xl transition-all duration-300">
                    @if($item->type === 'image' && $item->file_name)
                        <img src="{{ asset('file/galeri/' . $item->file_name) }}" 
                             alt="{{ $galeri->nama_galeri }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                    @elseif($item->type === 'youtube' && $item->youtube_url)
                        @php
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $item->youtube_url, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp
                        @if($videoId)
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                 alt="{{ $galeri->nama_galeri }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="bg-red-500 rounded-full p-4">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full bg-slate-700 flex items-center justify-center">
                            <div class="text-slate-400 text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm">No Image</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                        <div class="text-center text-white transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 mb-3 border border-white/30 mx-auto w-16 h-16 flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-semibold">View Detail</p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-slate-400 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">No Items Available</h3>
                    <p class="text-sm">This gallery has no items yet.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Back Button -->
    <div class="text-center mt-12">
        <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Gallery
        </a>
    </div>
</section>

<!-- Custom Gallery Modal (Tailwind CSS) -->
<div id="galleryModal" class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 hidden">
    <div class="relative w-full h-full flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-700/50 bg-slate-900/30">
            <div class="flex flex-col">
                <h2 id="gmTitle" class="text-white text-xl sm:text-2xl font-semibold">Gallery Item</h2>
                <div class="flex items-center gap-4 mt-1">
                    <span id="gmCompany" class="text-yellow-400 text-sm"></span>
                    <span id="gmPeriod" class="text-gray-400 text-sm"></span>
                </div>
            </div>
            <button id="closeGalleryModal" class="text-white hover:text-yellow-400 transition-colors p-2 rounded-full hover:bg-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-4xl">
                <!-- Media Content -->
                <div id="gmMedia" class="mb-6 text-center">
                    <!-- Media will be loaded here -->
                </div>
                
                <!-- Description -->
                <div class="text-center">
                    <p id="gmDesc" class="text-slate-300 text-lg">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Gallery item click handlers for individual gallery page
document.addEventListener('DOMContentLoaded', function() {
    const galleryCards = document.querySelectorAll('.gallery-item-card');
    
    galleryCards.forEach(card => {
        card.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            
            if (!id) {
                console.error('No item ID found');
                return;
            }
            
            try {
                const response = await fetch(`/gallery-item/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (!response.ok) {
                    throw new Error('Item not found');
                }
                
                const data = await response.json();
                
                // Update modal title
                document.getElementById('gmTitle').textContent = data.title || data.galeri?.nama_galeri || 'Gallery Item';
                
                // Update company/period info
                document.getElementById('gmCompany').textContent = data.galeri?.company || '—';
                document.getElementById('gmPeriod').textContent = data.galeri?.period || '—';
                
                // Update description
                document.getElementById('gmDesc').textContent = data.galeri?.deskripsi_galeri || 'No description available.';
                
                // Update media content
                const mediaBox = document.getElementById('gmMedia');
                mediaBox.innerHTML = '';
                
                if (data.type === 'image' && data.image_url) {
                    mediaBox.innerHTML = `<img src="${data.image_url}" class="w-full max-w-3xl mx-auto rounded-lg shadow-lg" alt="Gallery image" style="max-height: 70vh; object-fit: contain;">`;
                } else if (data.type === 'video' && data.video_url) {
                    mediaBox.innerHTML = `<video src="${data.video_url}" class="w-full max-w-3xl mx-auto rounded-lg shadow-lg" controls playsinline style="max-height: 70vh;"></video>`;
                } else if (data.type === 'youtube' && data.youtube_embed) {
                    mediaBox.innerHTML = `
                        <div class="w-full max-w-3xl mx-auto">
                            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                <iframe src="${data.youtube_embed}" 
                                        title="YouTube video" 
                                        allowfullscreen 
                                        class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg"
                                        style="border: none;"></iframe>
                            </div>
                        </div>
                    `;
                } else {
                    mediaBox.innerHTML = '<div class="text-center text-slate-400 py-12"><p class="text-lg">No media available</p></div>';
                }
                
                // Show modal
                showGalleryModal();
                
            } catch (error) {
                console.error('Error loading gallery item:', error);
                alert('Item tidak ditemukan atau terjadi kesalahan.');
            }
        });
    });
    
    // Modal controls
    document.getElementById('closeGalleryModal').addEventListener('click', closeGalleryModal);
    
    // Close on backdrop click
    document.getElementById('galleryModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGalleryModal();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                e.preventDefault();
                closeGalleryModal();
            }
        }
    });
});

function showGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
