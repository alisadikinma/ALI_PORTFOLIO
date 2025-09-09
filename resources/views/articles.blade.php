@extends('layouts.web')

@section('title', 'Articles - Ali Sadikin')

@push('styles')
<style>
    /* CSS khusus articles */
    .outline-offset--1 { outline-offset: -1px; }
    .featured-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        animation: pulse-gold 2s infinite;
    }
    @keyframes pulse-gold {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    .article-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .article-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
        transition: left 0.5s;
    }
    .article-card:hover::before {
        left: 100%;
    }
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    .tag-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .tag-item {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: #a5b4fc;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }
    .tag-item:hover {
        background: rgba(99, 102, 241, 0.2);
        transform: scale(1.05);
    }
    .reading-progress {
        height: 4px;
        background: linear-gradient(to right, #fbbf24, #f59e0b);
        border-radius: 2px;
    }
    .category-filter {
        background: rgba(30, 41, 59, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(71, 85, 105, 0.5);
    }
    .search-box {
        background: rgba(30, 41, 59, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(71, 85, 105, 0.5);
    }
</style>
@endpush

@section('isi')
<section id="articles" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 pt-24 flex flex-col items-center gap-8 sm:gap-14">
    <!-- Header -->
    <div class="flex flex-col gap-3 text-center">
        <h1 class="text-yellow-400 text-3xl sm:text-5xl font-extrabold leading-tight sm:leading-[56px]">
            All Articles
        </h1>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
            Explore our collection of insights on AI, technology, and innovation
        </p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="w-full max-w-4xl flex flex-col sm:flex-row gap-4 items-center">
        <!-- Search Box -->
        <form method="GET" action="{{ route('articles') }}" class="flex-1 w-full sm:w-auto">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search articles, tags, or keywords..." 
                       class="search-box w-full pl-12 pr-4 py-3 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400/50 focus:border-yellow-400/50">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </form>

        <!-- Category Filter -->
        @if(isset($categories) && count($categories) > 0)
        <form method="GET" action="{{ route('articles') }}" class="w-full sm:w-auto">
            <select name="category" 
                    onchange="this.form.submit()"
                    class="category-filter w-full sm:w-48 px-4 py-3 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-400/50">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
                @endforeach
            </select>
        </form>
        @endif

        <!-- Clear Filters -->
        @if(request()->hasAny(['search', 'category', 'tag']))
        <a href="{{ route('articles') }}" 
           class="px-4 py-3 bg-red-600/20 text-red-400 border border-red-600/30 rounded-xl hover:bg-red-600/30 transition flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Clear
        </a>
        @endif
    </div>

    <!-- Popular Tags -->
    @if(isset($popular_tags) && count($popular_tags) > 0)
    <div class="w-full max-w-4xl">
        <h3 class="text-white text-lg font-semibold mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Popular Tags
        </h3>
        <div class="tag-cloud">
            @foreach($popular_tags as $tag => $count)
            <a href="{{ route('articles', ['tag' => $tag]) }}" 
               class="tag-item {{ request('tag') == $tag ? 'bg-yellow-400/20 text-yellow-400 border-yellow-400/50' : '' }}">
                #{{ $tag }} ({{ $count }})
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Results Info -->
    <div class="w-full max-w-4xl flex items-center justify-between text-sm text-neutral-400">
        <div>
            Showing {{ $articles->firstItem() ?? 0 }} - {{ $articles->lastItem() ?? 0 }} of {{ $articles->total() }} articles
            @if(request('search'))
                for "{{ request('search') }}"
            @endif
            @if(request('category'))
                in {{ request('category') }}
            @endif
            @if(request('tag'))
                tagged with #{{ request('tag') }}
            @endif
        </div>
        
        <!-- Sort Options -->
        <form method="GET" action="{{ route('articles') }}" class="hidden sm:block">
            <select name="sort" 
                    onchange="this.form.submit()"
                    class="category-filter px-3 py-1 text-xs rounded-lg">
                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
            </select>
            <!-- Preserve other filters -->
            @foreach(request()->except(['sort', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <!-- Articles Grid -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @forelse ($articles as $article)
            <article class="article-card p-6 sm:p-9 bg-slate-800 rounded-xl outline outline-1 outline-blue-950 outline-offset--1 flex flex-col gap-4 sm:gap-6 relative">
                
                <!-- Featured Badge -->
                @if(isset($article->is_featured) && $article->is_featured)
                <div class="featured-badge absolute top-4 left-4 px-2 py-1 rounded-full text-slate-900 text-xs font-bold flex items-center gap-1 z-10">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Featured
                </div>
                @endif

                <!-- Article Image -->
                <div class="relative overflow-hidden rounded-xl">
                    <img src="{{ !empty($article->gambar_berita) ? asset('file/berita/' . $article->gambar_berita) : asset('file/berita/placeholder.png') }}"
                         alt="{{ $article->judul_berita }} thumbnail"
                         class="w-full h-auto object-cover aspect-[4/3] transition-transform duration-300 hover:scale-105" 
                         loading="lazy" />
                    
                    <!-- Category Badge -->
                    @if(isset($article->kategori_berita) && $article->kategori_berita)
                    <div class="absolute top-3 right-3 px-2 sm:px-3 py-1 sm:py-2 bg-yellow-400/90 backdrop-blur-sm rounded-sm">
                        <span class="text-slate-900 text-xs font-medium font-['Fira_Sans'] uppercase leading-3">
                            {{ $article->kategori_berita }}
                        </span>
                    </div>
                    @endif

                    <!-- Reading Time -->
                    @if(isset($article->reading_time) && $article->reading_time)
                    <div class="absolute bottom-3 left-3 px-2 py-1 bg-slate-900/80 backdrop-blur-sm rounded text-xs text-white flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $article->reading_time }} min
                    </div>
                    @endif
                </div>

                <!-- Article Content -->
                <div class="flex flex-col gap-4 flex-1">
                    <div class="flex flex-col gap-2">
                        <!-- Meta Information -->
                        <div class="flex items-center gap-3 text-slate-600 text-sm">
                            <time datetime="{{ $article->tanggal_berita }}">
                                {{ \Carbon\Carbon::parse($article->tanggal_berita)->format('M d, Y') }}
                            </time>
                            
                            @if(isset($article->views) && $article->views > 0)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ number_format($article->views) }} views
                            </span>
                            @endif
                        </div>

                        <!-- Article Title -->
                        <h2 class="text-white text-base sm:text-xl font-bold leading-6 sm:leading-7 hover:text-yellow-400 transition-colors">
                            <a href="{{ route('article.detail', $article->slug_berita) }}">
                                {{ $article->judul_berita }}
                            </a>
                        </h2>
                    </div>

                    <!-- Meta Description or Featured Snippet -->
                    <div class="text-slate-500 text-sm sm:text-base font-medium leading-normal flex-1">
                        @if(isset($article->meta_description) && !empty($article->meta_description))
                            <p class="mb-2 text-slate-400">{{ \Illuminate\Support\Str::limit($article->meta_description, 120) }}</p>
                        @endif
                        
                        <p>
                            {!! \Illuminate\Support\Str::limit(strip_tags($article->isi_berita), 100, '...') !!}
                            @if (strlen(strip_tags($article->isi_berita)) > 100)
                                <a href="{{ route('article.detail', $article->slug_berita) }}"
                                   class="text-yellow-400 hover:text-yellow-500 font-medium ml-1">Read More →</a>
                            @endif
                        </p>
                    </div>

                    <!-- Tags -->
                    @if(isset($article->tags) && !empty($article->tags))
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice(explode(',', $article->tags), 0, 3) as $tag)
                        <a href="{{ route('articles', ['tag' => trim($tag)]) }}" 
                           class="px-2 py-1 bg-slate-700/50 text-slate-300 text-xs rounded hover:bg-slate-600/50 transition">
                            #{{ trim($tag) }}
                        </a>
                        @endforeach
                        @if(count(explode(',', $article->tags)) > 3)
                        <span class="px-2 py-1 text-slate-400 text-xs">
                            +{{ count(explode(',', $article->tags)) - 3 }} more
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Article Footer -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-700/50">
                        <!-- Read Article Button -->
                        <a href="{{ route('article.detail', $article->slug_berita) }}" 
                           class="flex items-center gap-2 text-yellow-400 hover:text-yellow-500 font-medium text-sm transition-colors">
                            Read Article
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <!-- Share Button -->
                        <button onclick="shareArticle('{{ $article->judul_berita }}', '{{ route('article.detail', $article->slug_berita) }}')" 
                                class="p-2 text-slate-400 hover:text-white transition-colors" 
                                title="Share Article">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full flex flex-col items-center gap-4 py-12">
                <svg class="w-16 h-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div class="text-center">
                    <h3 class="text-white text-xl font-semibold">No Articles Found</h3>
                    <p class="text-slate-400 mt-1">
                        @if(request()->hasAny(['search', 'category', 'tag']))
                            Try adjusting your filters or search terms.
                        @else
                            Check back later for new content!
                        @endif
                    </p>
                </div>
                @if(request()->hasAny(['search', 'category', 'tag']))
                <a href="{{ route('articles') }}" 
                   class="px-6 py-3 bg-yellow-400 text-slate-900 font-medium rounded-xl hover:bg-yellow-500 transition">
                    View All Articles
                </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
    <div class="flex items-center gap-4 sm:gap-8">
        {{-- Prev Button --}}
        @if ($articles->onFirstPage())
            <button class="px-4 sm:px-6 py-3 rounded-xl outline outline-1 outline-neutral-700 opacity-50 cursor-not-allowed text-neutral-400"
                    disabled>
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Prev
            </button>
        @else
            <a href="{{ $articles->previousPageUrl() }}" 
               class="px-4 sm:px-6 py-3 rounded-xl outline outline-1 outline-neutral-700 text-neutral-300 hover:text-white hover:outline-yellow-400 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Prev
            </a>
        @endif

        {{-- Page Info --}}
        <div class="flex items-center gap-2">
            <span class="text-slate-400">Page {{ $articles->currentPage() }}</span>
            <span class="text-slate-600">of</span>
            <span class="text-slate-400">{{ $articles->lastPage() }}</span>
        </div>

        {{-- Next Button --}}
        @if ($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}" 
               class="px-4 sm:px-6 py-3 rounded-xl outline outline-1 outline-yellow-400 text-yellow-400 hover:bg-yellow-400/10 transition flex items-center">
                Next
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <button class="px-4 sm:px-6 py-3 rounded-xl outline outline-1 outline-yellow-400 opacity-50 cursor-not-allowed text-neutral-400 flex items-center"
                    disabled>
                Next
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>
    @endif

    <!-- Additional Navigation Links -->
    <div class="flex flex-wrap items-center justify-center gap-4 pt-8 border-t border-slate-800">
        <a href="{{ url('/') }}" 
           class="px-4 py-2 text-neutral-400 hover:text-yellow-400 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Back to Home
        </a>
        <span class="text-slate-600">•</span>
        <a href="{{ url('/portfolio') }}" 
           class="px-4 py-2 text-neutral-400 hover:text-yellow-400 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            View Portfolio
        </a>
        <span class="text-slate-600">•</span>
        <a href="{{ url('/gallery') }}" 
           class="px-4 py-2 text-neutral-400 hover:text-yellow-400 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Gallery
        </a>
    </div>
</section>

<script>
// Share functionality
function shareArticle(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(console.error);
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            // Show toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-pulse';
            toast.textContent = '✅ Link copied to clipboard!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        });
    }
}

// Auto-submit search form with debounce
let searchTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 800);
        });
    }
});

// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection