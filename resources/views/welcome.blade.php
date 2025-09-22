@extends('layouts.web')

@section('isi')
{{-- Include Global Gallery Components - RE-ENABLED with fixes --}}
@include('partials.global-image-modal')
@include('partials.global-gallery-loader')
<!-- Hero Section -->

@if (session('success'))
<div id="successAlert"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-auto px-4 py-3 bg-green-600 text-white font-medium rounded-xl shadow-lg flex items-center justify-between gap-4 animate-fade-in">
    <span>{{ session('success') }}</span>
    <button id="closeAlertBtn" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close alert">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
@endif

@if (isset($fallback_mode) && $fallback_mode)
<div id="fallbackAlert" role="alert" aria-live="polite"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-2xl w-full mx-auto px-4 py-3 bg-amber-100 border-l-4 border-amber-500 text-amber-800 rounded-lg shadow-lg animate-fade-in">
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-medium text-sm">
                ⚠️ System Status: Emergency Mode
            </p>
            <p class="text-sm mt-1">
                We're experiencing temporary technical difficulties. Some features may be limited while we resolve this issue.
                @if (isset($test_mode) && $test_mode)
                    <span class="font-medium">(Test Mode Active)</span>
                @endif
            </p>
        </div>
        <button id="closeFallbackAlert" class="flex-shrink-0 text-amber-600 hover:text-amber-800 focus:outline-none" aria-label="Close alert">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endif

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" 
        class="fixed bottom-8 right-8 z-50 p-4 bg-yellow-400 hover:bg-yellow-500 text-black rounded-full shadow-xl transition-all duration-300 opacity-0 invisible transform translate-y-4 hover:scale-110"
        style="position: fixed !important; bottom: 2rem !important; right: 2rem !important; z-index: 9999 !important;">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
    </svg>
</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Success Alert Handler
    const alert = document.getElementById('successAlert');
    const closeBtn = document.getElementById('closeAlertBtn');

    if (alert && closeBtn) {
        closeBtn.addEventListener('click', () => {
            alert.classList.add('animate-fade-out');
            setTimeout(() => {
                alert.remove();
            }, 300);
        });

        setTimeout(() => {
            if (alert) {
                alert.classList.add('animate-fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    }

    // Fallback Alert Handler
    const fallbackAlert = document.getElementById('fallbackAlert');
    const closeFallbackBtn = document.getElementById('closeFallbackAlert');

    if (fallbackAlert && closeFallbackBtn) {
        closeFallbackBtn.addEventListener('click', () => {
            fallbackAlert.classList.add('animate-fade-out');
            setTimeout(() => {
                fallbackAlert.remove();
            }, 300);
        });

        // Auto-hide fallback alert after 10 seconds (longer than success alert due to importance)
        setTimeout(() => {
            if (fallbackAlert) {
                fallbackAlert.classList.add('animate-fade-out');
                setTimeout(() => {
                    fallbackAlert.remove();
                }, 300);
            }
        }, 10000);
    }

    // Scroll to Top Button Handler
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    
    // Show/hide scroll to top button based on scroll position
    function toggleScrollButton() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'invisible', 'translate-y-4');
            scrollToTopBtn.classList.add('opacity-100', 'visible', 'translate-y-0');
        } else {
            scrollToTopBtn.classList.add('opacity-0', 'invisible', 'translate-y-4');
            scrollToTopBtn.classList.remove('opacity-100', 'visible', 'translate-y-0');
        }
    }

    // Scroll to top functionality
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Listen for scroll events
    window.addEventListener('scroll', toggleScrollButton);

    // DISABLED: Menu highlighting now handled by layouts/web.blade.php IntersectionObserver
    // This prevents double processing and conflicts
    console.log('📝 Menu highlighting handled by IntersectionObserver in layout');
    
    // Only handle scroll button
    toggleScrollButton();
});
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    @keyframes fade-out {
        from {
            opacity: 1;
            transform: translate(-50%, 0);
        }
        to {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-in-out;
    }

    .animate-fade-out {
        animation: fade-out 0.3s ease-in-out;
    }
</style>

<style>
/* Custom CSS untuk hover effect social media icons dan Send Message Button */
.social-icon:hover svg {
    color: #000000 !important;
}

/* PERBAIKAN 1: Memastikan font Send Message tetap hitam */
.send-message-btn {
    background-color: #fbbf24 !important;
    color: #000000 !important;
}

.send-message-btn:hover {
    background-color: #f59e0b !important;
    color: #000000 !important;
}

.send-message-btn span {
    color: #000000 !important;
}

.send-message-btn:hover span {
    color: #000000 !important;
}

.send-message-btn svg {
    color: #000000 !important;
    stroke: #000000 !important;
}

.send-message-btn:hover svg {
    color: #000000 !important;
    stroke: #000000 !important;
}

/* PERBAIKAN 2: Gap yang lebih lebar antara Gallery dan Contact Section */
#contact {
    margin-top: 4rem !important; /* 96px - Desktop */
}

@media (max-width: 768px) {
    #contact {
        margin-top: 4rem !important; /* 64px - Mobile */
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    #contact {
        margin-top: 5rem !important; /* 80px - Tablet */
    }
}

/* Gap sebelum Articles section juga diperlebar */
#articles {
    margin-top: 4rem !important;
    margin-bottom: 3rem !important;
}
</style>

<!-- Gallery Modal Styles -->
<style>
.gallery-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.gallery-modal.show {
    display: flex;
}

.gallery-modal-content {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 24px;
    max-width: 95vw;
    max-height: 95vh;
    width: 1200px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
}

.gallery-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 40px 40px 20px 40px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.gallery-header-content {
    flex: 1;
    max-width: calc(100% - 60px);
}

.gallery-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fbbf24;
    margin-bottom: 12px;
    line-height: 1.2;
}

.gallery-subtitle {
    font-size: 1rem;
    color: #94a3b8;
    line-height: 1.6;
    margin: 0;
}

.gallery-close {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #94a3b8;
    font-size: 24px;
    cursor: pointer;
    padding: 12px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.gallery-close:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.gallery-modal-body {
    padding: 20px 40px;
    flex: 1;
    overflow-y: auto;
    max-height: calc(95vh - 200px);
}

/* Gallery Grid - 2x2 Layout */
.gallery-grid-2x2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-width: 100%;
}

.gallery-item-2x2 {
    position: relative;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    overflow: hidden;
    aspect-ratio: 16/10;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.gallery-item-2x2:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.08);
    border-color: #fbbf24;
}

.gallery-item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item-2x2:hover .gallery-item-image {
    transform: scale(1.02);
}

.gallery-item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
    padding: 20px;
    color: white;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-item-2x2:hover .gallery-item-overlay {
    transform: translateY(0);
}

.gallery-item-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.gallery-item-type {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.7);
    color: #fbbf24;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

/* YouTube Play Button */
.gallery-youtube-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.8);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.gallery-item-2x2:hover .gallery-youtube-overlay {
    background: rgba(251, 191, 36, 0.9);
    transform: translate(-50%, -50%) scale(1.1);
}

.gallery-youtube-icon {
    width: 32px;
    height: 32px;
    color: white;
}

/* Navigation */
.gallery-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px 30px 40px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.gallery-nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #94a3b8;
    padding: 12px 20px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 100px;
    justify-content: center;
}

.gallery-nav-btn:hover:not(:disabled) {
    background: rgba(251, 191, 36, 0.2);
    border-color: #fbbf24;
    color: #fbbf24;
}

.gallery-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.gallery-counter {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
    font-size: 1rem;
    font-weight: 500;
}

.gallery-divider {
    color: #475569;
}

/* No Media State */
.gallery-no-media {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 300px;
    color: #64748b;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    border: 2px dashed rgba(255, 255, 255, 0.1);
}

.gallery-no-media-icon {
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.gallery-no-media-text {
    font-size: 1.125rem;
    font-weight: 500;
    margin-bottom: 8px;
}

.gallery-no-media-desc {
    font-size: 0.875rem;
    opacity: 0.7;
    text-align: center;
    max-width: 300px;
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-modal-content {
        width: 95vw;
        margin: 10px;
    }
    
    .gallery-modal-header,
    .gallery-modal-body,
    .gallery-navigation {
        padding-left: 20px;
        padding-right: 20px;
    }
    
    .gallery-title {
        font-size: 1.875rem;
    }
    
    .gallery-grid-2x2 {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .gallery-nav-btn {
        min-width: 80px;
        padding: 10px 16px;
    }
}

@media (max-width: 480px) {
    .gallery-modal {
        padding: 10px;
    }
    
    .gallery-modal-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .gallery-header-content {
        max-width: 100%;
    }
    
    .gallery-close {
        align-self: flex-end;
    }
    
    .gallery-title {
        font-size: 1.5rem;
    }
    
    .gallery-subtitle {
        font-size: 0.875rem;
    }
}
</style>

<section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/3 w-48 h-48 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full filter blur-2xl animate-float"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-14">
        <div class="w-full flex flex-col lg:flex-row items-center gap-8 lg:gap-16">

            <!-- Profile Image with Modern Effects -->
            <div class="relative w-full max-w-[400px] lg:max-w-[500px]">
                <!-- Floating decorative elements -->
                <div class="absolute -top-6 -right-6 w-12 h-12 bg-yellow-400 rounded-full animate-float"></div>
                <div class="absolute -bottom-6 -left-6 w-8 h-8 bg-pink-400 rounded-full animate-float" style="animation-delay: 1.5s;"></div>
                <div class="absolute top-1/2 -right-8 w-6 h-6 bg-blue-400 rounded-full animate-float" style="animation-delay: 2s;"></div>

                <div class="relative overflow-hidden rounded-3xl animate-pulse-glow">
                    <img src="{{ asset('favicon/' . $konf->favicon_setting) }}" alt="Profile image"
                        class="w-full h-auto" />
                    <!-- Gradient overlay for modern effect -->
                    <div class="absolute inset-0 bg-gradient-to-t from-purple-900/20 via-transparent to-cyan-400/10"></div>
                </div>
            </div>

            <!-- Content Section with Gen Z Design -->
            <div class="flex flex-col gap-6 lg:gap-8 text-center lg:text-left max-w-2xl">

                <!-- Dynamic Greeting -->
                <div class="animate-slide-in-left">
                    <p class="text-hero-subtitle text-yellow-400 mb-4 flex items-center justify-center lg:justify-start gap-2">
                        <span class="text-2xl animate-bounce">🚀</span>
                        Digital Transformation Consultant
                    </p>
                </div>

                <!-- Large Display Name with Gradient -->
                <div class="animate-fade-in" style="animation-delay: 0.3s;">
                    <h1 class="text-display text-white mb-4">
                        {{ $konf->pimpinan_setting ?? 'Ali Sadikin' }}
                        <br />
                        <span class="text-gradient-hero animate-gradient">
                            Manufacturing AI Expert
                        </span>
                    </h1>

                    <!-- SEO-optimized subtitle with consulting focus -->
                    <div class="text-hero-subtitle text-yellow-400 uppercase tracking-wider font-bold">
                        {{ $konf->profile_title ?? '🏭 Manufacturing Digital Transformation Specialist' }}
                    </div>
                </div>

                <!-- SEO-optimized description for Digital Transformation Consulting -->
                <div class="animate-slide-up" style="animation-delay: 0.6s;">
                    <p class="text-modern-body text-xl text-gray-300 leading-relaxed">
                        <span class="text-yellow-400 font-semibold">Digital Transformation Consultant</span> specializing in
                        <span class="text-blue-400 font-semibold">manufacturing AI implementation</span>.
                        From production engineer to AI innovator – helping manufacturing companies modernize operations with
                        <span class="text-pink-400 font-semibold">proven smart factory solutions</span>. 🏭
                        <br><br>
                        <strong>16+ years manufacturing experience</strong> • <strong>54K+ followers</strong> •
                        <span class="text-green-400 font-semibold">99% project success rate</span>.
                        Transform your manufacturing operations with AI today! 🚀
                    </p>
                </div>

                <!-- Gen Z Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 animate-slide-up" style="animation-delay: 0.9s;">
                    {{-- Primary Cyber Button --}}
                    <a href="{{ !empty($konf->primary_button_link) ? $konf->primary_button_link : (!empty($konf->view_cv_url) ? $konf->view_cv_url : '#contact') }}"
                       target="{{ (!empty($konf->primary_button_link) && (Str::startsWith($konf->primary_button_link, 'http') || Str::startsWith($konf->primary_button_link, 'https'))) || (!empty($konf->view_cv_url) && (Str::startsWith($konf->view_cv_url, 'http') || Str::startsWith($konf->view_cv_url, 'https'))) ? '_blank' : '_self' }}"
                       class="btn-cyber px-8 py-4 rounded-xl font-bold text-white text-lg flex items-center justify-center gap-3 min-w-[200px] transition-all duration-300 hover:scale-105">
                        <span class="relative z-10">
                            {{ $konf->primary_button_title ?? '📄 Check My Work' }}
                        </span>
                        @if((!empty($konf->primary_button_link) && (Str::startsWith($konf->primary_button_link, 'http') || Str::startsWith($konf->primary_button_link, 'https'))) || (!empty($konf->view_cv_url) && (Str::startsWith($konf->view_cv_url, 'http') || Str::startsWith($konf->view_cv_url, 'https'))))
                        <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        @endif
                    </a>

                    {{-- Secondary Glass Button --}}
                    <a href="{{ !empty($konf->secondary_button_link) ? $konf->secondary_button_link : url('portfolio') }}"
                       target="{{ !empty($konf->secondary_button_link) && (Str::startsWith($konf->secondary_button_link, 'http') || Str::startsWith($konf->secondary_button_link, 'https')) ? '_blank' : '_self' }}"
                       class="card-modern px-8 py-4 rounded-xl font-semibold text-white text-lg flex items-center justify-center gap-3 min-w-[200px] transition-all duration-300 hover:scale-105 border border-white/20">
                        <span>
                            {{ $konf->secondary_button_title ?? '🚀 View Portfolio' }}
                        </span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gen Z Stats Section -->
<section class="py-20 bg-gradient-to-b from-slate-900 to-black relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-gradient-to-r from-yellow-400 to-pink-400 rounded-full filter blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full filter blur-xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-modern-heading text-4xl lg:text-5xl text-white mb-4">
                The Numbers Don't Lie 📊
            </h2>
            <p class="text-modern-body text-xl text-gray-300">
                16+ years of delivering <span class="text-gradient-neon">game-changing</span> results
            </p>
        </div>

        <!-- Modern Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            <!-- Experience Stat -->
            <div class="card-modern p-8 rounded-2xl text-center group cursor-pointer">
                <div class="flex flex-col items-center gap-4">
                    <!-- Modern Experience Icon -->
                    <div class="p-4 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl">🚀</span>
                    </div>
                    <div class="text-gradient-neon text-5xl font-black animate-pulse-glow">
                        {{ $konf->years_experience ?? '16+' }}
                    </div>
                    <div class="text-gray-300 text-lg font-semibold">
                        Years Experience
                    </div>
                </div>
            </div>

            <!-- Followers Stat -->
            <div class="card-modern p-8 rounded-2xl text-center group cursor-pointer">
                <div class="flex flex-col items-center gap-4">
                    <!-- Modern Followers Icon -->
                    <div class="p-4 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="text-gradient-neon text-5xl font-black animate-pulse-glow">
                        {{ $konf->followers_count ?? '54K+' }}
                    </div>
                    <div class="text-gray-300 text-lg font-semibold">
                        Followers
                    </div>
                </div>
            </div>

            <!-- Projects Stat -->
            <div class="card-modern p-8 rounded-2xl text-center group cursor-pointer">
                <div class="flex flex-col items-center gap-4">
                    <!-- Modern Projects Icon -->
                    <div class="p-4 bg-gradient-to-r from-green-400 to-teal-400 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl">💼</span>
                    </div>
                    <div class="text-gradient-neon text-5xl font-black animate-pulse-glow">
                        {{ $konf->project_delivered ?? '18+' }}
                    </div>
                    <div class="text-gray-300 text-lg font-semibold">
                        Projects Delivered
                    </div>
                </div>
            </div>

            <!-- Cost Savings Stat -->
            <div class="card-modern p-8 rounded-2xl text-center group cursor-pointer">
                <div class="flex flex-col items-center gap-4">
                    <!-- Modern Cost Savings Icon -->
                    <div class="p-4 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="text-gradient-neon text-5xl font-black animate-pulse-glow">
                        {{ $konf->cost_savings ?? '$250K+' }}
                    </div>
                    <div class="text-gray-300 text-lg font-semibold">
                        Cost Savings
                    </div>
                </div>
            </div>

            <!-- Success Rate Stat -->
            <div class="card-modern p-8 rounded-2xl text-center group cursor-pointer">
                <div class="flex flex-col items-center gap-4">
                    <!-- Modern Success Icon -->
                    <div class="p-4 bg-gradient-to-r from-purple-400 to-indigo-400 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div class="text-gradient-neon text-5xl font-black animate-pulse-glow">
                        {{ $konf->success_rate ?? '99%' }}
                    </div>
                    <div class="text-gray-300 text-lg font-semibold">
                        Success Rate
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Dynamic sections based on database configuration --}}
@php
    // Get active sections with their configurations from the database
    $activeSections = [];
    if (isset($sectionConfigs) && !empty($sectionConfigs)) {
        foreach ($sectionConfigs as $code => $config) {
            if ($config['is_active']) {
                $activeSections[$code] = $config;
            }
        }
        // Sort by sort_order
        uasort($activeSections, function($a, $b) {
            return ($a['sort_order'] ?? 999) <=> ($b['sort_order'] ?? 999);
        });
    } else {
        // Fallback to default sections if no database config
        $activeSections = [
            'about' => ['title' => 'About', 'is_active' => true],
            'services' => ['title' => 'Services', 'is_active' => true],
            'portfolio' => ['title' => 'Portfolio', 'is_active' => true],
            'awards' => ['title' => 'Awards', 'is_active' => true],
            'testimonials' => ['title' => 'Testimonials', 'is_active' => true],
            'gallery' => ['title' => 'Gallery', 'is_active' => true],
            'articles' => ['title' => 'Articles', 'is_active' => true],
            'contact' => ['title' => 'Contact', 'is_active' => true],
        ];
    }
@endphp

@foreach($activeSections as $sectionCode => $sectionConfig)
    @switch($sectionCode)
        @case('about')
            <!-- About Section - DYNAMIC TITLE FROM DATABASE -->
            @if($sectionConfig['is_active'] ?? true)
            <section id="about" class="w-full max-w-screen-2xl mx-auto px-6 py-12">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-12">
                    
                    <!-- Left Content -->
                    <div class="flex flex-col gap-8 max-w-2xl flex-1 order-2 lg:order-1">
                        <div class="flex flex-col gap-6">
                            <h2 class="text-3xl lg:text-4xl font-bold text-white leading-snug">
                                {{-- DYNAMIC TITLE: Using about_section_title from database --}}
                                {{ $konf->about_section_title ?? 'With over 16+ years of experience in manufacturing and technology' }}
                            </h2>
                            @if(isset($konf->about_section_subtitle) && $konf->about_section_subtitle)
                            <h3 class="text-xl lg:text-2xl font-semibold text-yellow-400">
                                {{ $konf->about_section_subtitle }}
                            </h3>
                            @endif
                            <div class="text-gray-400 text-lg leading-relaxed">
                                {!! $konf->about_section_description ?? "I've dedicated my career to bridging the gap between traditional manufacturing and cutting-edge AI solutions.<br><br>From my early days as a Production Engineer to becoming an AI Generalist, I've consistently focused on delivering measurable business impact through innovative technology solutions." !!}
                            </div>
                        </div>
                    </div>

                    <!-- Right Image - FIXED POSITION & BALANCED SIZE -->
                    <div class="flex-1 flex items-stretch justify-center order-1 lg:order-2">
                        <div class="w-full max-w-lg lg:max-w-xl xl:max-w-2xl p-6 bg-slate-800 rounded-2xl outline outline-2 outline-orange-400">
                            @if(isset($konf->about_section_image) && $konf->about_section_image && file_exists(public_path('images/about/' . $konf->about_section_image)))
                                <img src="{{ asset('images/about/' . $konf->about_section_image) }}" 
                                     alt="About Section Image" 
                                     class="w-full h-full object-cover rounded-xl" />
                            @elseif(isset($award) && $award->count() > 0)
                                <!-- Company Logos Grid from Awards -->
                                <div class="grid grid-cols-2 gap-6 w-full h-full place-content-center">
                                    @foreach($award->take(6) as $award_item)
                                    <div class="p-6 bg-slate-700/60 rounded-xl outline outline-1 outline-slate-600 flex items-center justify-center aspect-square min-h-[120px]">
                                        <img src="{{ asset('file/award/' . $award_item->gambar_award) }}" 
                                             alt="{{ $award_item->nama_award }}" 
                                             class="max-w-full max-h-full object-contain opacity-80" />
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Default placeholder -->
                                <div class="text-orange-400 text-4xl text-center flex flex-col items-center justify-center h-full">
                                    Put image here
                                    <div class="mt-6">
                                        <svg class="w-20 h-20 mx-auto" fill="currentColor" viewBox="0 0 100 100">
                                            <path d="M50 10 L90 50 L50 90 Z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @endif
            @break

        @case('awards')
            @include('partials.awards-updated')
            @break

        @case('services')
            @include('partials.services')
            @break

        @case('portfolio')
            @include('partials.portfolio')
            @break

        @case('testimonials')
            <!-- Testimonials Section - DYNAMIC TITLE FROM DATABASE -->
            @if($sectionConfig['is_active'] ?? true)
            <section class="testimonials-section" id="testimonials">
                <div class="content-wrapper">
                    <h2 class="testimonials-title">
                        {{-- DYNAMIC TITLE: Using lookup_description from database --}}
                        {{ $sectionConfig['description'] ?? 'Testimonials' }}
                    </h2>
                    <p class="about-content">
                        Real stories from clients who transformed their business with AI and automation.
                    </p>

                    @if(isset($testimonial) && $testimonial->count() > 0)
                    <div class="testimonials-wrapper relative overflow-hidden">
                        <div class="testimonials-grid flex transition-transform duration-500 ease-in-out" id="testimonialSlider">
                            @foreach ($testimonial as $row)
                            <div class="testimonial-item flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4 text-center">
                                <img src="{{ asset('file/testimonial/' . $row->gambar_testimonial) }}" alt="{{ $row->judul_testimonial }}" class="testimonial-image mx-auto rounded-full w-20 h-20 object-cover border-4 border-yellow-400">
                                <div class="testimonial-text mt-4 text-white">"{!! $row->deskripsi_testimonial !!}"</div>
                                <div class="testimonial-author mt-2 font-semibold text-yellow-400">
                                    {{ $row->judul_testimonial ?? 'Testimonial' }}
                                </div>
                                <p class="text-gray-400 text-sm">{{ $row->jabatan }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-center mt-6 gap-2" id="testimonialDots"></div>
                    </div>
                    @else
                    <!-- No Data State -->
                    <div class="flex flex-col items-center justify-center py-16">
                        <div class="text-yellow-400 text-6xl mb-4">💬</div>
                        <h3 class="text-white text-xl font-semibold mb-2">No Testimonials Yet</h3>
                        <p class="text-gray-400 text-center max-w-md">
                            We're working on collecting testimonials from our clients. Check back soon to see what our customers are saying about our AI solutions!
                        </p>
                    </div>
                    @endif
                </div>
            </section>
            @endif
            @break

        @case('gallery')
            @include('partials.gallery-updated')
            @break

        @case('articles')
            <!-- Articles Section - DYNAMIC TITLE FROM DATABASE -->
            @if($sectionConfig['is_active'] ?? true)
            <section id="articles" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 flex flex-col items-center gap-8 sm:gap-14">
                <div class="flex flex-col gap-3 text-center">
                    <h2 class="text-yellow-400 text-3xl sm:text-5xl font-extrabold leading-tight sm:leading-[56px]">
                        {{-- DYNAMIC TITLE: Using lookup_description from database --}}
                        {{ $sectionConfig['description'] ?? 'Latest Article' }}
                    </h2>
                    <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
                        Weekly insights on AI, technology, and innovation
                    </p>
                </div>
                
                @if(isset($article) && $article->count() > 0)
                <div class="flex flex-col sm:flex-row gap-6 sm:gap-8">
                    <div class="flex flex-col gap-6 sm:gap-8">
                        @foreach ($article->take(3) as $row)
                        <div class="p-6 sm:p-9 bg-slate-800 rounded-xl outline outline-1 outline-blue-950 outline-offset--1 flex flex-col sm:flex-row gap-4 sm:gap-8">
                            <img src="{{ !empty($row->gambar_berita) ? asset('file/berita/' . $row->gambar_berita) : asset('file/berita/placeholder.png') }}" alt="{{ $row->judul_berita }} thumbnail" class="w-full sm:w-48 h-auto sm:h-32 object-cover rounded-xl" />
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-600 text-sm sm:text-base font-medium leading-normal">
                                            {{ \Carbon\Carbon::parse($row->tanggal_berita)->format('M d, Y') }}
                                        </span>
                                        <div class="px-2 sm:px-3 py-1 sm:py-2 bg-yellow-400/10 rounded-sm">
                                            <span class="text-yellow-400 text-xs font-medium font-['Fira_Sans'] uppercase leading-3">
                                                {{ $row->kategori_berita ?? 'AI & Tech' }}
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-white text-base sm:text-xl font-bold leading-6 sm:leading-7 max-w-full sm:max-w-96">
                                        {{ $row->judul_berita }}
                                    </h3>
                                </div>
                                <p class="text-slate-500 text-sm sm:text-base font-medium leading-normal max-w-full sm:max-w-96">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($row->isi_berita), 150, '...') !!}
                                    <a href="{{ route('article.detail', $row->slug_berita) }}" class="text-yellow-400 hover:text-yellow-500 font-medium">Read More</a>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if ($article->count() > 0)
                    @php
                    $featuredArticle = $article->first();
                    @endphp
                    <div class="p-6 sm:p-12 bg-slate-800 rounded-xl outline outline-1 outline-blue-950 outline-offset--1 flex flex-col gap-6 sm:gap-8">
                        <img src="{{ !empty($featuredArticle->gambar_berita) ? asset('file/berita/' . $featuredArticle->gambar_berita) : asset('file/berita/placeholder.png') }}" alt="{{ $featuredArticle->judul_berita }} featured thumbnail" class="w-full max-w-[640px] h-auto rounded-xl object-cover" />
                        <div class="flex flex-col gap-6 sm:gap-8">
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-600 text-sm sm:text-base font-medium leading-normal">
                                            {{ \Carbon\Carbon::parse($featuredArticle->tanggal_berita)->format('M d, Y') }}
                                        </span>
                                        <div class="px-2 sm:px-3 py-1 sm:py-2 bg-yellow-400/10 rounded-sm">
                                            <span class="text-yellow-400 text-xs font-medium font-['Fira_Sans'] uppercase leading-3">
                                                {{ $featuredArticle->kategori_berita ?? 'AI & Tech' }}
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-white text-xl sm:text-2xl font-bold leading-loose max-w-full sm:max-w-[641px]">
                                        {{ $featuredArticle->judul_berita }}
                                    </h3>
                                </div>
                                <p class="text-slate-500 text-sm sm:text-base font-medium leading-normal max-w-full sm:max-w-[641px]">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($featuredArticle->isi_berita), 150, '...') !!}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('article.detail', $featuredArticle->slug_berita) }}" class="text-yellow-400 text-base sm:text-xl font-semibold leading-normal tracking-tight hover:text-yellow-500">
                                    Read More
                                </a>
                                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="yellow" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <a href="{{ url('articles') }}" class="px-6 sm:px-8 py-3 sm:py-4 rounded-xl outline outline-1 outline-yellow-400 outline-offset--1 backdrop-blur-[2px] flex items-center gap-2.5">
                    <span class="text-yellow-400 text-base font-semibold leading-tight tracking-tight">See More</span>
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="yellow" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <!-- No Data State -->
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="text-yellow-400 text-6xl mb-4">📝</div>
                    <h3 class="text-white text-xl font-semibold mb-2">No Articles Yet</h3>
                    <p class="text-gray-400 text-center max-w-md">
                        We're working on creating insightful articles about AI, technology, and innovation. Check back soon for the latest updates!
                    </p>
                    <a href="{{ url('articles') }}" class="mt-6 px-6 py-3 rounded-xl outline outline-1 outline-yellow-400 text-yellow-400 hover:bg-yellow-400 hover:text-black transition-all duration-300">
                        <span class="font-semibold">Explore Articles</span>
                    </a>
                </div>
                @endif
            </section>
            @endif
            @break

        @case('contact')
            <!-- Contact Section - Enhanced Accessibility -->
            @if($sectionConfig['is_active'] ?? true)
            <section id="contact"
                     class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 bg-slate-800 rounded-3xl border border-slate-700 -m-1 flex flex-col lg:flex-row gap-8 lg:gap-12"
                     role="region"
                     aria-labelledby="contact-section-title">
                <div class="flex flex-col gap-6 sm:gap-8 max-w-full lg:max-w-md">
                    <div class="flex flex-col gap-4">
                        <h2 id="contact-section-title" class="text-white text-xl sm:text-2xl font-semibold leading-loose">
                            {{-- Can be dynamic based on section config --}}
                            Have a project or question in mind? Just send me a message.
                        </h2>
                        <p class="text-gray-400 text-sm sm:text-base font-light leading-normal">
                            Let's discuss how AI and automation can drive innovation and efficiency in your organization.
                        </p>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300 focus-within:ring-2 focus-within:ring-electric-purple">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-neon-green rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Call me now</span>
                                <a href="tel:{{ $konf->no_hp_setting }}"
                                   class="text-white text-base font-normal leading-normal hover:text-neon-green transition-colors truncate focus-ring"
                                   aria-label="Call {{ $konf->no_hp_setting }}">{{ $konf->no_hp_setting }}</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300 focus-within:ring-2 focus-within:ring-electric-purple">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-cyber-pink rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Chat with me</span>
                                <a href="mailto:{{ $konf->email_setting }}"
                                   class="text-white text-base font-normal leading-normal hover:text-cyber-pink transition-colors truncate focus-ring"
                                   aria-label="Send email to {{ $konf->email_setting }}">{{ $konf->email_setting }}</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-aurora-blue rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Location</span>
                                <span class="text-white text-base font-normal leading-normal" aria-label="Location: {{ $konf->alamat_setting }}">{{ $konf->alamat_setting }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-7 bg-slate-900 rounded-2xl flex flex-col gap-4 hover:bg-slate-700 transition-all duration-300">
                        <span class="text-white text-base font-normal leading-normal">Follow me on social media</span>
                        <div class="flex gap-3 justify-center">
                            <a href="https://www.instagram.com/{{ $konf->instagram_setting }}" target="_blank" class="social-icon p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-2a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                                </svg>
                            </a>
                            <a href="https://www.tiktok.com/@<?php echo $konf->tiktok_setting; ?>" target="_blank" class="social-icon p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.321 5.562a5.124 5.124 0 0 1-.443-.258 6.228 6.228 0 0 1-1.137-.966c-.849-.849-1.254-1.99-1.254-3.338h-2.341v10.466c0 2.059-1.68 3.739-3.739 3.739-2.059 0-3.739-1.68-3.739-3.739s1.68-3.739 3.739-3.739c.659 0 1.254.18 1.787.493v-2.402c-.533-.09-1.076-.135-1.787-.135C5.67 5.683 2 9.352 2 13.989s3.67 8.306 8.307 8.306 8.306-3.669 8.306-8.306V9.072c1.181.849 2.628 1.344 4.163 1.344V7.861c-1.27 0-2.435-.413-3.455-1.299z"/>
                                </svg>
                            </a>                
                            <a href="https://www.youtube.com/{{ $konf->youtube_setting }}" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300 group">
                                <svg class="w-5 h-5 text-yellow-400 group-hover:text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.8 8s-.2-1.4-.8-2c-.7-.8-1.5-.8-1.9-.9C16.7 4.8 12 4.8 12 4.8h-.1s-4.7 0-7.1.3c-.4 0-1.2.1-1.9.9-.6.6-.8 2-.8 2S2 9.6 2 11.3v1.3c0 1.7.2 3.3.2 3.3s.2 1.4.8 2c.7.8 1.7.7 2.1.8 1.6.2 6.9.3 6.9.3s4.7 0 7.1-.3c.4 0 1.2-.1 1.9-.9.6-.6.8-2 .8-2s.2-1.6.2-3.3v-1.3c0-1.7-.2-3.3-.2-3.3zM10 14.6V9.4l5.2 2.6-5.2 2.6z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/{{ $konf->linkedin_setting }}" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 transition-all duration-300 group">
                                <svg class="w-5 h-5 text-yellow-400 group-hover:text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>                
                            <a href="https://wa.me/{{ $konf->no_hp_setting }}" target="_blank" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 hover:text-black transition-all duration-300">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 2C6.486 2 2 6.484 2 12c0 1.73.443 3.361 1.22 4.78L2.1 21.712l5.056-1.323A9.951 9.951 0 0012.017 22c5.531 0 10.017-4.484 10.017-10S17.548 2 12.017 2zm5.23 14.314c-.251.714-1.233 1.334-2.005 1.491-.549.111-1.268.183-3.685-.825-2.831-1.18-4.673-4.057-4.814-4.247-.142-.19-1.157-1.569-1.157-2.993 0-1.425.731-2.127 1.012-2.421.281-.295.611-.369.815-.369.204 0 .407.002.584.011.189.009.441-.072.69.536.25.608.855 2.176.928 2.334.074.157.123.342.025.548-.099.206-.148.332-.296.51-.148.178-.311.394-.444.53-.133.136-.272.282-.118.553.154.271.685 1.166 1.471 1.888 1.01.928 1.862 1.215 2.128 1.351.266.136.421.114.576-.07.155-.185.662-.8.839-1.077.177-.276.354-.23.597-.138.243.093 1.54.748 1.805.884.266.136.443.204.509.318.066.115.066.663-.184 1.377z"/>
                                </svg>
                            </a>
                            <a href="mailto:{{ $konf->email_setting }}" class="p-3 bg-slate-800 rounded-full hover:bg-yellow-400 hover:text-black transition-all duration-300">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 2v.01L12 13 20 6.01V6H4zm0 12h16V8l-8 7-8-7v10z" />
                                </svg>
                            </a>                
                        </div>
                    </div>
                </div>
                <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-6 sm:gap-8 flex-1" role="form" aria-labelledby="contact-form-title">
                    @csrf
                    <h2 id="contact-form-title" class="text-white text-xl sm:text-2xl font-semibold leading-loose">Just say 👋 Hi</h2>

                    <fieldset class="flex flex-col gap-4">
                        <legend class="sr-only">Contact Information Form</legend>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <label for="full_name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Full Name <span class="text-red-400" aria-label="required">*</span>
                                </label>
                                <input type="text"
                                       id="full_name"
                                       name="full_name"
                                       required
                                       aria-describedby="full_name_error"
                                       class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-electric-purple focus:border-electric-purple transition-all duration-300"
                                       placeholder="Enter your full name" />
                                <div id="full_name_error" class="text-red-400 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="w-full sm:w-1/2">
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                    Email Address <span class="text-red-400" aria-label="required">*</span>
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       required
                                       aria-describedby="email_error"
                                       class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-electric-purple focus:border-electric-purple transition-all duration-300"
                                       placeholder="Enter your email address" />
                                <div id="email_error" class="text-red-400 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">
                                Subject
                            </label>
                            <input type="text"
                                   id="subject"
                                   name="subject"
                                   aria-describedby="subject_help"
                                   class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-electric-purple focus:border-electric-purple transition-all duration-300"
                                   placeholder="What's this about?" />
                            <div id="subject_help" class="text-gray-400 text-xs mt-1">Optional: Brief description of your inquiry</div>
                        </div>

                        <div>
                            <label for="service" class="block text-sm font-medium text-gray-300 mb-2">
                                Service of Interest
                            </label>
                            <select id="service"
                                    name="service"
                                    aria-describedby="service_help"
                                    class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white focus:outline-none focus:ring-2 focus:ring-electric-purple focus:border-electric-purple transition-all duration-300">
                                <option value="" disabled selected>Select a service (optional)</option>
                                <option value="digital-transformation">Digital Transformation 4.0 Consultant</option>
                                <option value="ai-automation">AI AGENT AUTOMATION Solution</option>
                                <option value="custom-gpt">CUSTOM GPT/GEM Solution</option>
                                <option value="content-creator">Content Creator Endorsement</option>
                                <option value="other">Other - Please specify in message</option>
                            </select>
                            <div id="service_help" class="text-gray-400 text-xs mt-1">Help us understand your needs better</div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                                Message <span class="text-red-400" aria-label="required">*</span>
                            </label>
                            <textarea id="message"
                                      name="message"
                                      required
                                      aria-describedby="message_help message_error"
                                      rows="4"
                                      class="w-full bg-slate-800 rounded-md border border-slate-600 px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-electric-purple focus:border-electric-purple resize-none transition-all duration-300"
                                      placeholder="Tell me about your project or question..."></textarea>
                            <div id="message_help" class="text-gray-400 text-xs mt-1">Minimum 10 characters required</div>
                            <div id="message_error" class="text-red-400 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                        </div>

                        <button type="submit"
                                class="btn-cyber w-full sm:w-auto px-8 py-4 rounded-xl flex items-center gap-3 justify-center group transition-all duration-300 focus-ring"
                                aria-describedby="submit_help">
                            <span class="relative z-10 text-white text-base font-semibold group-hover:text-white">
                                Send Message
                            </span>
                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white group-hover:text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <div id="submit_help" class="text-gray-400 text-xs">I'll get back to you within 24 hours</div>
                    </fieldset>
                </form>
            </section>
            @endif
            @break

        @default
            {{-- Handle any custom sections that might be added via database --}}
    @endswitch
@endforeach

<!-- Image Error Handling Script -->
<script>
// Global image error handler for missing project images
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Handle image loading errors
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            img.addEventListener('error', function() {
                try {
                    // Create placeholder for missing project images
                    if (this.src.includes('file/project/')) {
                        const canvas = document.createElement('canvas');
                        canvas.width = 400;
                        canvas.height = 300;
                        const ctx = canvas.getContext('2d');
                        
                        // Create gradient background
                        const gradient = ctx.createLinearGradient(0, 0, 400, 300);
                        gradient.addColorStop(0, '#1e293b');
                        gradient.addColorStop(1, '#0f172a');
                        ctx.fillStyle = gradient;
                        ctx.fillRect(0, 0, 400, 300);
                        
                        // Add text
                        ctx.fillStyle = '#fbbf24';
                        ctx.font = 'bold 24px Arial';
                        ctx.textAlign = 'center';
                        ctx.fillText('Project Image', 200, 130);
                        
                        ctx.fillStyle = '#94a3b8';
                        ctx.font = '16px Arial';
                        ctx.fillText('Image Not Available', 200, 160);
                        
                        ctx.fillStyle = '#64748b';
                        ctx.font = '12px Arial';
                        ctx.fillText('Please update project image', 200, 180);
                        
                        // Replace image with canvas
                        this.src = canvas.toDataURL();
                    }
                    // For other images, use a simple placeholder
                    else if (!this.src.includes('data:image')) {
                        this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0nNDAwJyBoZWlnaHQ9JzMwMCcgdmlld0JveD0nMCAwIDQwMCAzMDAnIGZpbGw9J25vbmUnIHhtbG5zPSdodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Zyc+PHJlY3Qgd2lkdGg9JzQwMCcgaGVpZ2h0PSczMDAnIGZpbGw9JyMxZTI5M2InLz48dGV4dCB4PScyMDAnIHk9JzE1MCcgZmlsbD0nI2ZiYmYyNCcgZm9udC1zaXplPScyNCcgZm9udC1mYW1pbHk9J0FyaWFsJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+';
                    }
                } catch (e) {
                    console.warn('Error handling image placeholder:', e);
                }
            });
        });
    } catch (e) {
        console.warn('Error initializing image error handler:', e);
    }
});
</script>

<!-- Testimonials Section Styles -->
<style>
.testimonials-section {
    width: 100%;
    padding: 40px 0;
    text-align: center;
    background: #1e2b44;
    overflow: hidden;
}

.testimonials-title {
    font-size: 32px;
    font-weight: 700;
    color: #ffd300;
    margin-bottom: 16px;
}

.testimonials-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    padding: 20px 0;
}

.testimonials-grid {
    display: flex;
    gap: 20px;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
}

.testimonial-item {
    background: #132138;
    border-radius: 12px;
    padding: 20px;
    text-align: left;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    min-width: 300px;
    flex-shrink: 0;
    scroll-snap-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.testimonial-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
    border: 2px solid #ffd300;
    transition: transform 0.4s ease;
}

.testimonial-text {
    font-size: 16px;
    color: #ffffff;
    margin-bottom: 10px;
    line-height: 1.4;
    text-align: center;
}

.testimonial-author {
    font-size: 14px;
    color: #989898;
    font-style: italic;
    text-align: center;
}

.testimonial-item:hover {
    transform: translateY(-10px) scale(1.05) rotate(2deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

.testimonial-item:hover .testimonial-image {
    transform: scale(1.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    try {
        const slider = document.getElementById("testimonialSlider");
        const dotsContainer = document.getElementById("testimonialDots");
        
        if (!slider || !dotsContainer) {
            console.warn('Testimonial elements not found');
            return;
        }
        
        let currentIndex = 0;
        let slideInterval;

        function getTotalPages() {
            const itemsPerPage = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 640 ? 2 : 1;
            return Math.ceil(slider.children.length / itemsPerPage);
        }

        function renderDots() {
            try {
                dotsContainer.innerHTML = "";
                const totalPages = getTotalPages();
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement("span");
                    dot.className = "dot w-3 h-3 rounded-full bg-gray-500 inline-block cursor-pointer transition";
                    dot.addEventListener("click", () => {
                        showSlide(i);
                        stopAutoSlide();
                        startAutoSlide();
                    });
                    dotsContainer.appendChild(dot);
                }
            } catch (e) {
                console.warn('Error rendering dots:', e);
            }
        }

        function showSlide(index) {
            try {
                const wrapper = slider.parentElement;
                const wrapperWidth = wrapper.offsetWidth;
                const totalPages = getTotalPages();

                if (index < 0) index = totalPages - 1;
                if (index >= totalPages) index = 0;

                currentIndex = index;
                const offset = -index * wrapperWidth;
                slider.style.transform = `translateX(${offset}px)`;

                const dots = dotsContainer.querySelectorAll(".dot");
                dots.forEach((dot, i) => {
                    dot.classList.toggle("bg-yellow-400", i === index);
                    dot.classList.toggle("bg-gray-500", i !== index);
                });
            } catch (e) {
                console.warn('Error showing slide:', e);
            }
        }

        function startAutoSlide() {
            try {
                if (getTotalPages() > 1) {
                    slideInterval = setInterval(() => {
                        showSlide(currentIndex + 1);
                    }, 5000);
                }
            } catch (e) {
                console.warn('Error starting auto slide:', e);
            }
        }

        function stopAutoSlide() {
            if (slideInterval) {
                clearInterval(slideInterval);
            }
        }

        renderDots();
        showSlide(0);
        startAutoSlide();

        window.addEventListener("resize", () => {
            try {
                renderDots();
                showSlide(currentIndex);
            } catch (e) {
                console.warn('Error on resize:', e);
            }
        });
    } catch (e) {
        console.error('Error initializing testimonial slider:', e);
    }
});
</script>

@endsection