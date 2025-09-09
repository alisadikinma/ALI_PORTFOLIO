@extends('layouts.web')

@section('isi')
<!-- Gallery Section -->
<section id="gallery" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-10">
    <div class="flex flex-col gap-3 text-center">
        <h2 class="text-yellow-400 text-4xl sm:text-6xl font-bold leading-tight sm:leading-[67.2px] tracking-tight">Gallery</h2>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">Explore the visual journey of my work, from concept to impactful solutions</p>
    </div>

    <!-- Filter Buttons -->
    <div class="w-full max-w-full sm:max-w-5xl flex flex-wrap justify-center gap-4 sm:gap-6">
        <button class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[1.5px] outline-yellow-400 hover:bg-slate-700/60 transition-colors flex items-center gap-3" data-filter="all">
            <span class="text-yellow-400 text-base sm:text-lg font-semibold leading-[64px]">All</span>
        </button>
        <button class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[0.5px] outline-slate-500 hover:bg-slate-700/60 transition-colors flex items-center gap-3" data-filter="behind-the-scene">
            <span class="text-white text-base sm:text-lg font-semibold leading-[64px]">Behind-the-scene</span>
        </button>
        <button class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[0.5px] outline-slate-500 hover:bg-slate-700/60 transition-colors flex items-center gap-3" data-filter="event">
            <span class="text-white text-base sm:text-lg font-semibold capitalize leading-[64px]">Event</span>
        </button>
        <button class="filter-btn px-6 sm:px-8 py-3 bg-slate-800/60 rounded-lg outline outline-[0.5px] outline-slate-500 hover:bg-slate-700/60 transition-colors flex items-center gap-3" data-filter="work-in-progress">
            <span class="text-white text-base sm:text-lg font-semibold leading-[64px]">Work-in-progress</span>
        </button>
    </div>

    <!-- Gallery Grid -->
    <div class="w-full max-w-full sm:max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8" id="galleryGrid">
        @forelse($galeri as $gallery)
            @foreach($gallery->items as $item)
                <a href="#" class="gallery-item-card block cursor-pointer group" 
                   data-id="{{ $item->id_gallery_item }}"
                   data-title="{{ $gallery->nama_galeri }}"
                   data-category="{{ $gallery->nama_galeri }}"
                   data-company="{{ $gallery->company }}"
                   data-period="{{ $gallery->period }}">
                    
                    <div class="relative rounded-2xl aspect-[16/10] overflow-hidden bg-slate-800 shadow-xl hover:shadow-2xl transition-all duration-300">
                        @if($item->type === 'image' && $item->file_name)
                            <img src="{{ asset('file/galeri/' . $item->file_name) }}" 
                                 alt="{{ $gallery->nama_galeri }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                        @elseif($item->type === 'youtube' && $item->youtube_url)
                            @php
                                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $item->youtube_url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp
                            @if($videoId)
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                     alt="{{ $gallery->nama_galeri }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-red-500 rounded-full p-4">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        @elseif($gallery->thumbnail)
                            <img src="{{ asset('file/galeri/' . $gallery->thumbnail) }}" 
                                 alt="{{ $gallery->nama_galeri }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
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
                                <p class="text-lg font-semibold mb-1">{{ $gallery->nama_galeri }}</p>
                                <p class="text-sm text-white/80">View Detail</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-slate-400 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">No Gallery Available</h3>
                    <p class="text-sm">Gallery content will be added soon.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($galeri->count() >= 12)
    <button class="px-6 sm:px-8 py-3 rounded-lg outline outline-1 outline-gray-500 hover:bg-slate-700/60 transition-colors flex items-center gap-2.5" id="loadMoreBtn">
        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="gray" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="text-white text-base sm:text-lg font-semibold capitalize leading-[64px]">Load more Images</span>
    </button>
    @endif

    <!-- DEBUG: Test Modal Button -->
    <div class="mt-8 text-center bg-yellow-400/10 border border-yellow-400/30 rounded-lg p-4">
        <p class="text-yellow-400 text-sm mb-2">🔧 DEBUG MODE - Gallery Modal Testing</p>
        <button id="testModalBtn" class="px-4 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-500 transition-colors font-medium mr-4">
            Test Modal (Click Me!)
        </button>
        <button id="checkElementsBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors font-medium">
            Check Elements
        </button>
        <p class="text-gray-400 text-xs mt-2">Use console (F12) to see debug logs</p>
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

<!-- DEBUG: Backend Data Info -->
<script>
// Debug: Log backend data
try {
    console.log('🗺 Backend Gallery Data:');
    const galeriData = {!! json_encode($galeri) !!};
    console.log(galeriData);
    console.log('📋 Total galleries loaded:', {{ $galeri->count() }});
    
    @if($galeri->count() > 0)
        @foreach($galeri as $index => $gallery)
        console.log('Gallery {{ $index + 1 }}:', {
            id: {{ $gallery->id_galeri }},
            name: {!! json_encode($gallery->nama_galeri) !!},
            company: {!! json_encode($gallery->company ?? 'N/A') !!},
            period: {!! json_encode($gallery->period ?? 'N/A') !!},
            items_count: {{ $gallery->items->count() }}
        });
        @endforeach
    @else
        console.log('⚠️ No galleries found in database');
    @endif
    
} catch(e) {
    console.error('❌ Error loading backend data:', e);
}
</script>

<script>
// Simple Gallery Modal Implementation with Tailwind CSS + DEBUG MODE
console.log('🚀 Gallery Modal Script Loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM Content Loaded - Initializing Gallery');
    
    // Debug: Check if modal exists
    const modal = document.getElementById('galleryModal');
    console.log('🔍 Modal element found:', modal ? 'YES' : 'NO', modal);
    
    // Setup filter buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryCards = document.querySelectorAll('.gallery-item-card');
    
    console.log('🔍 Filter buttons found:', filterBtns.length);
    console.log('🔍 Gallery cards found:', galleryCards.length);
    
    // Debug: Log all gallery cards data
    galleryCards.forEach((card, index) => {
        console.log(`📋 Card ${index + 1}:`, {
            id: card.dataset.id,
            title: card.dataset.title,
            category: card.dataset.category,
            company: card.dataset.company,
            period: card.dataset.period
        });
    });
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('🔧 Filter clicked:', this.dataset.filter);
            
            // Update active filter button
            filterBtns.forEach(b => {
                b.querySelector('span').classList.remove('text-yellow-400');
                b.querySelector('span').classList.add('text-white');
                b.classList.remove('outline-yellow-400', 'outline-[1.5px]');
                b.classList.add('outline-slate-500', 'outline-[0.5px]');
            });
            
            this.querySelector('span').classList.remove('text-white');
            this.querySelector('span').classList.add('text-yellow-400');
            this.classList.remove('outline-slate-500', 'outline-[0.5px]');
            this.classList.add('outline-yellow-400', 'outline-[1.5px]');

            // Filter gallery cards
            const filter = this.dataset.filter;
            galleryCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    const title = card.dataset.title.toLowerCase();
                    const company = card.dataset.company.toLowerCase();
                    const category = card.dataset.category.toLowerCase();
                    
                    if (title.includes(filter) || company.includes(filter) || category.includes(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
    
    // Setup gallery item click handlers
    galleryCards.forEach((card, index) => {
        console.log(`🔗 Adding click handler to card ${index + 1}`);
        
        card.addEventListener('click', async function(e) {
            console.log('🖱️ Gallery card clicked!', {
                cardIndex: index + 1,
                id: this.dataset.id,
                title: this.dataset.title
            });
            
            e.preventDefault();
            const id = this.dataset.id;
            
            if (!id) {
                console.error('❌ No item ID found in dataset!');
                console.log('🔍 Dataset:', this.dataset);
                return;
            }
            
            console.log('📡 Making AJAX request to:', `/gallery-item/${id}`);
            
            try {
                const response = await fetch(`/gallery-item/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                console.log('📡 Response status:', response.status);
                console.log('📡 Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: Item not found`);
                }
                
                const data = await response.json();
                console.log('✅ Data received:', data);
                
                // Update modal title
                const titleElement = document.getElementById('gmTitle');
                const companyElement = document.getElementById('gmCompany');
                const periodElement = document.getElementById('gmPeriod');
                const descElement = document.getElementById('gmDesc');
                const mediaElement = document.getElementById('gmMedia');
                
                console.log('🔍 Modal elements check:', {
                    title: titleElement ? 'FOUND' : 'MISSING',
                    company: companyElement ? 'FOUND' : 'MISSING',
                    period: periodElement ? 'FOUND' : 'MISSING',
                    desc: descElement ? 'FOUND' : 'MISSING',
                    media: mediaElement ? 'FOUND' : 'MISSING'
                });
                
                if (titleElement) {
                    titleElement.textContent = data.title || data.galeri?.nama_galeri || 'Gallery Item';
                    console.log('✅ Title updated:', titleElement.textContent);
                }
                
                // Update company/period info
                if (companyElement) {
                    companyElement.textContent = data.galeri?.company || '—';
                    console.log('✅ Company updated:', companyElement.textContent);
                }
                if (periodElement) {
                    periodElement.textContent = data.galeri?.period || '—';
                    console.log('✅ Period updated:', periodElement.textContent);
                }
                
                // Update description
                if (descElement) {
                    descElement.textContent = data.galeri?.deskripsi_galeri || 'No description available.';
                    console.log('✅ Description updated');
                }
                
                // Update media content
                if (mediaElement) {
                    mediaElement.innerHTML = '';
                    console.log('🎬 Processing media type:', data.type);
                    
                    if (data.type === 'image' && data.image_url) {
                        console.log('🖼️ Adding image:', data.image_url);
                        mediaElement.innerHTML = `<img src="${data.image_url}" class="w-full max-w-3xl mx-auto rounded-lg shadow-lg" alt="Gallery image" style="max-height: 70vh; object-fit: contain;">`;
                    } else if (data.type === 'video' && data.video_url) {
                        console.log('🎥 Adding video:', data.video_url);
                        mediaElement.innerHTML = `<video src="${data.video_url}" class="w-full max-w-3xl mx-auto rounded-lg shadow-lg" controls playsinline style="max-height: 70vh;"></video>`;
                    } else if (data.type === 'youtube' && data.youtube_embed) {
                        console.log('📺 Adding YouTube embed:', data.youtube_embed);
                        mediaElement.innerHTML = `
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
                        console.log('❌ No valid media found');
                        mediaElement.innerHTML = '<div class="text-center text-slate-400 py-12"><p class="text-lg">No media available</p></div>';
                    }
                    
                    console.log('✅ Media content updated');
                }
                
                // Show modal
                console.log('🎭 Attempting to show modal...');
                showGalleryModal();
                
            } catch (error) {
                console.error('❌ Error loading gallery item:', error);
                console.error('📋 Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                alert('Item tidak ditemukan atau terjadi kesalahan: ' + error.message);
            }
        });
    });
    
    // Modal controls
    const closeBtn = document.getElementById('closeGalleryModal');
    console.log('🔍 Close button found:', closeBtn ? 'YES' : 'NO');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            console.log('🖱️ Close button clicked');
            closeGalleryModal();
        });
    }
    
    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                console.log('🖱️ Backdrop clicked');
                closeGalleryModal();
            }
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                console.log('⌨️ Escape key pressed');
                e.preventDefault();
                closeGalleryModal();
            }
        }
    });
    
    console.log('✅ Gallery initialization complete!');
    
    // DEBUG: Add test button handlers
    const testModalBtn = document.getElementById('testModalBtn');
    const checkElementsBtn = document.getElementById('checkElementsBtn');
    
    if (testModalBtn) {
        testModalBtn.addEventListener('click', function() {
            console.log('🧪 TEST MODAL button clicked');
            
            // Create test data
            const testData = {
                id: 999,
                type: 'image',
                title: 'Test Gallery Item',
                image_url: 'https://via.placeholder.com/800x600/1f2937/ffffff?text=TEST+MODAL',
                galeri: {
                    nama_galeri: 'Test Gallery',
                    company: 'Test Company',
                    period: '2024',
                    deskripsi_galeri: 'This is a test modal to verify functionality.'
                }
            };
            
            console.log('🧪 Using test data:', testData);
            
            // Update modal with test data
            const titleElement = document.getElementById('gmTitle');
            const companyElement = document.getElementById('gmCompany');
            const periodElement = document.getElementById('gmPeriod');
            const descElement = document.getElementById('gmDesc');
            const mediaElement = document.getElementById('gmMedia');
            
            if (titleElement) titleElement.textContent = testData.title;
            if (companyElement) companyElement.textContent = testData.galeri.company;
            if (periodElement) periodElement.textContent = testData.galeri.period;
            if (descElement) descElement.textContent = testData.galeri.deskripsi_galeri;
            if (mediaElement) {
                mediaElement.innerHTML = `<img src="${testData.image_url}" class="w-full max-w-3xl mx-auto rounded-lg shadow-lg" alt="Test image" style="max-height: 70vh; object-fit: contain;">`;
            }
            
            // Show modal
            showGalleryModal();
        });
    }
    
    if (checkElementsBtn) {
        checkElementsBtn.addEventListener('click', function() {
            console.log('🔍 CHECKING ELEMENTS...');
            
            const elements = {
                modal: document.getElementById('galleryModal'),
                title: document.getElementById('gmTitle'),
                company: document.getElementById('gmCompany'),
                period: document.getElementById('gmPeriod'),
                desc: document.getElementById('gmDesc'),
                media: document.getElementById('gmMedia'),
                closeBtn: document.getElementById('closeGalleryModal'),
                galleryCards: document.querySelectorAll('.gallery-item-card'),
                filterBtns: document.querySelectorAll('.filter-btn')
            };
            
            console.log('📋 ELEMENT CHECK RESULTS:');
            Object.entries(elements).forEach(([key, element]) => {
                if (key === 'galleryCards' || key === 'filterBtns') {
                    console.log(`${key}: ${element.length} found`);
                } else {
                    console.log(`${key}: ${element ? '✅ FOUND' : '❌ MISSING'}`, element);
                }
            });
            
            // Check modal classes
            const modal = elements.modal;
            if (modal) {
                console.log('Modal current classes:', modal.className);
                console.log('Modal is hidden:', modal.classList.contains('hidden'));
                console.log('Modal computed style display:', window.getComputedStyle(modal).display);
                console.log('Modal computed style visibility:', window.getComputedStyle(modal).visibility);
            }
            
            alert('Element check complete! See console for details.');
        });
    }
});

function showGalleryModal() {
    console.log('🎭 showGalleryModal() called');
    const modal = document.getElementById('galleryModal');
    
    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }
    
    console.log('🔍 Modal classes before show:', modal.className);
    console.log('🔍 Modal is hidden:', modal.classList.contains('hidden'));
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    console.log('🔍 Modal classes after show:', modal.className);
    console.log('🔍 Body overflow:', document.body.style.overflow);
    console.log('✅ Modal should now be visible!');
}

function closeGalleryModal() {
    console.log('🎭 closeGalleryModal() called');
    const modal = document.getElementById('galleryModal');
    
    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }
    
    console.log('🔍 Modal classes before close:', modal.className);
    
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    console.log('🔍 Modal classes after close:', modal.className);
    console.log('✅ Modal closed');
}

// Debug: Check if everything is loaded properly
console.log('📋 Debug Summary:', {
    modalExists: !!document.getElementById('galleryModal'),
    galleryCards: document.querySelectorAll('.gallery-item-card').length,
    filterButtons: document.querySelectorAll('.filter-btn').length,
    scriptLoaded: true
});
</script>
@endsection
