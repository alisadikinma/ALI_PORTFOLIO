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

<section id="home"
    class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-14 flex flex-col items-center gap-8 sm:gap-16">
    <div class="w-full flex flex-col sm:flex-row items-center gap-8 sm:gap-32">
        <img src="{{ asset('favicon/' . $konf->favicon_setting) }}" alt="Profile image"
            class="w-full max-w-[300px] sm:max-w-[536px] h-auto rounded-2xl" />
        <div class="flex flex-col gap-4 sm:gap-6">
            <div class="flex flex-col gap-4 sm:gap-6">
                <div class="flex items-center gap-4 sm:gap-6">
                    <div class="w-12 sm:w-20 h-0.5 bg-yellow-400"></div>
                    <div class="text-yellow-400 text-sm sm:text-base font-semibold uppercase leading-normal">
                        {{ $konf->profile_title ?? 'TRANSFORMING MANUFACTURING WITH AUTOMATION & ROBOTICS' }}
                    </div>
                </div>
                <h1 class="text-4xl sm:text-7xl font-bold leading-tight sm:leading-[80px] max-w-full sm:max-w-[648px]" style="margin-bottom: 0.25rem;">
                    Hello bro, I'm<br />
                    <span class="text-yellow-400 relative">
                    {{ $konf->pimpinan_setting ?? 'Ali Sadikin' }}
                    <div class="absolute -bottom-2 left-0 w-full h-1 bg-yellow-400 rounded-full"></div>
                    </span>
                </h1>
            </div>
            <p class="text-gray-500 text-lg sm:text-2xl font-normal leading-7 sm:leading-9 max-w-full sm:max-w-[648px]" style="margin-top: 0.25rem;">
                {!! $konf->profile_content ?? 'Strategic Digital Transformation Leader with over 16 years of expertise in Innovation, Leadership, and impactful Transformation within Industry 4.0. Proven ability to drive meaningful results through advanced technology integration spanning AI, IoT, Web & Mobile Development, and Robotic Process Automation.' !!}
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Primary Button - Menggunakan data dari table settings --}}
                <a href="{{ !empty($konf->primary_button_link) ? $konf->primary_button_link : (!empty($konf->view_cv_url) ? $konf->view_cv_url : '#contact') }}" 
                   target="{{ (!empty($konf->primary_button_link) && (Str::startsWith($konf->primary_button_link, 'http') || Str::startsWith($konf->primary_button_link, 'https'))) || (!empty($konf->view_cv_url) && (Str::startsWith($konf->view_cv_url, 'http') || Str::startsWith($konf->view_cv_url, 'https'))) ? '_blank' : '_self' }}" 
                   class="px-6 sm:px-8 py-3 sm:py-4 bg-yellow-400 rounded-lg flex items-center justify-center gap-3" 
                   style="min-width: 180px; text-align: center;">
                    <span class="text-neutral-900 text-base sm:text-lg font-semibold capitalize leading-[40px] sm:leading-[64px]">
                        {{ $konf->primary_button_title ?? 'View CV' }}
                    </span>
                    @if((!empty($konf->primary_button_link) && (Str::startsWith($konf->primary_button_link, 'http') || Str::startsWith($konf->primary_button_link, 'https'))) || (!empty($konf->view_cv_url) && (Str::startsWith($konf->view_cv_url, 'http') || Str::startsWith($konf->view_cv_url, 'https'))))
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    @endif
                </a>

                {{-- Secondary Button - Menggunakan data dari table settings --}}
                <a href="{{ !empty($konf->secondary_button_link) ? $konf->secondary_button_link : url('portfolio') }}" 
                   target="{{ !empty($konf->secondary_button_link) && (Str::startsWith($konf->secondary_button_link, 'http') || Str::startsWith($konf->secondary_button_link, 'https')) ? '_blank' : '_self' }}"
                    class="px-8 sm:px-10 py-3 sm:py-4 bg-slate-800/60 rounded-lg outline outline-1 outline-slate-500 flex items-center justify-center gap-3" style="min-width: 200px; text-align: center;">
                    <span class="text-white text-base sm:text-lg font-semibold capitalize leading-[40px] sm:leading-[64px]">
                        {{ $konf->secondary_button_title ?? 'View Portfolio' }}
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="w-full bg-neutral-900/40 flex flex-col items-center gap-4 sm:gap-6 md:gap-8 lg:gap-11">
        <div class="w-full h-0.5 outline outline-1 outline-neutral-900 outline-offset--1"></div>

        <div class="w-full px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 sm:gap-8">
                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Trade-up Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.6667 23.8332V14.6665H27.5" stroke="#FFD300" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M36.6666 14.6665L27.5 23.8332C25.8811 25.452 25.0726 26.2605 24.0808 26.3503C23.9158 26.365 23.7508 26.365 23.5858 26.3503C22.594 26.2587 21.7855 25.452 20.1666 23.8332C18.5478 22.2143 17.7393 21.4058 16.7475 21.316C16.5828 21.3011 16.4171 21.3011 16.2525 21.316C15.2606 21.4077 14.4521 22.2143 12.8333 23.8332L7.33331 29.3332" stroke="#FFD300" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        {{ $konf->years_experience }}
                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Years Experience
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- People Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.89999 14.8502C9.89999 13.5374 10.4215 12.2783 11.3498 11.35C12.2781 10.4217 13.5372 9.9002 14.85 9.9002C16.1628 9.9002 17.4219 10.4217 18.3502 11.35C19.2785 12.2783 19.8 13.5374 19.8 14.8502C19.8 16.163 19.2785 17.4221 18.3502 18.3504C17.4219 19.2787 16.1628 19.8002 14.85 19.8002C13.5372 19.8002 12.2781 19.2787 11.3498 18.3504C10.4215 17.4221 9.89999 16.163 9.89999 14.8502ZM14.85 7.7002C12.9537 7.7002 11.1351 8.4535 9.79417 9.79438C8.45329 11.1353 7.69999 12.9539 7.69999 14.8502C7.69999 16.7465 8.45329 18.5651 9.79417 19.906C11.1351 21.2469 12.9537 22.0002 14.85 22.0002C16.7463 22.0002 18.5649 21.2469 19.9058 19.906C21.2467 18.5651 22 16.7465 22 14.8502C22 12.9539 21.2467 11.1353 19.9058 9.79438C18.5649 8.4535 16.7463 7.7002 14.85 7.7002ZM27.3614 33.3192C28.545 33.8032 30.0344 34.1002 31.9 34.1002C36.0382 34.1002 38.3262 32.6306 39.5318 30.9454C40.1621 30.0618 40.5566 29.032 40.678 27.9534C40.6888 27.8532 40.6961 27.7527 40.7 27.652V27.5002C40.7 27.0668 40.6146 26.6377 40.4488 26.2373C40.2829 25.837 40.0399 25.4732 39.7334 25.1667C39.427 24.8603 39.0632 24.6172 38.6628 24.4514C38.2625 24.2856 37.8334 24.2002 37.4 24.2002H27.214C27.742 24.8382 28.138 25.584 28.369 26.4002H37.4C37.6917 26.4002 37.9715 26.5161 38.1778 26.7224C38.3841 26.9287 38.5 27.2085 38.5 27.5002V27.619L38.489 27.729C38.4064 28.4269 38.1491 29.0928 37.741 29.665C37.0216 30.6748 35.4596 31.9002 31.9 31.9002C30.2896 31.9002 29.0884 31.6494 28.1886 31.282C28.0082 31.898 27.7464 32.5932 27.3614 33.3192ZM3.29999 28.6002C3.29999 27.4332 3.76356 26.3141 4.58872 25.4889C5.41388 24.6638 6.53304 24.2002 7.69999 24.2002H22C23.1669 24.2002 24.2861 24.6638 25.1113 25.4889C25.9364 26.3141 26.4 27.4332 26.4 28.6002V28.785L26.3956 28.873L26.3736 29.17C26.2177 30.5957 25.7113 31.9607 24.8996 33.1432C23.3574 35.3762 20.3786 37.4002 14.85 37.4002C9.32139 37.4002 6.34259 35.3762 4.80039 33.1454C3.98836 31.9623 3.48196 30.5965 3.32639 29.17C3.31384 29.0419 3.30503 28.9136 3.29999 28.785V28.6002ZM5.49999 28.7322V28.7718L5.51539 28.9544C5.63477 30.009 6.01096 31.0182 6.61099 31.8936C7.68239 33.4424 9.92859 35.2002 14.85 35.2002C19.7714 35.2002 22.0176 33.4424 23.089 31.8936C23.689 31.0182 24.0652 30.009 24.1846 28.9544C24.1934 28.8708 24.1978 28.8099 24.1978 28.7718L24.2 28.7344V28.6002C24.2 28.0167 23.9682 27.4571 23.5556 27.0446C23.143 26.632 22.5835 26.4002 22 26.4002H7.69999C7.11651 26.4002 6.55693 26.632 6.14435 27.0446C5.73177 27.4571 5.49999 28.0167 5.49999 28.6002V28.7322ZM28.6 16.5002C28.6 15.625 28.9477 14.7856 29.5665 14.1667C30.1854 13.5479 31.0248 13.2002 31.9 13.2002C32.7752 13.2002 33.6146 13.5479 34.2334 14.1667C34.8523 14.7856 35.2 15.625 35.2 16.5002C35.2 17.3754 34.8523 18.2148 34.2334 18.8336C33.6146 19.4525 32.7752 19.8002 31.9 19.8002C31.0248 19.8002 30.1854 19.4525 29.5665 18.8336C28.9477 18.2148 28.6 17.3754 28.6 16.5002ZM31.9 11.0002C30.4413 11.0002 29.0424 11.5797 28.0109 12.6111C26.9795 13.6426 26.4 15.0415 26.4 16.5002C26.4 17.9589 26.9795 19.3578 28.0109 20.3893C29.0424 21.4207 30.4413 22.0002 31.9 22.0002C33.3587 22.0002 34.7576 21.4207 35.7891 20.3893C36.8205 19.3578 37.4 17.9589 37.4 16.5002C37.4 15.0415 36.8205 13.6426 35.7891 12.6111C34.7576 11.5797 33.3587 11.0002 31.9 11.0002Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        {{ $konf->followers_count }}
                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Followers
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Briefcase Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M37.125 12.375H6.875C6.11561 12.375 5.5 12.9906 5.5 13.75V35.75C5.5 36.5094 6.11561 37.125 6.875 37.125H37.125C37.8844 37.125 38.5 36.5094 38.5 35.75V13.75C38.5 12.9906 37.8844 12.375 37.125 12.375Z" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M28.875 12.375V9.625C28.875 8.89565 28.5853 8.19618 28.0695 7.68046C27.5538 7.16473 26.8543 6.875 26.125 6.875H17.875C17.1457 6.875 16.4462 7.16473 15.9305 7.68046C15.4147 8.19618 15.125 8.89565 15.125 9.625V12.375" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M38.5 21.708C33.4852 24.6083 27.7931 26.1321 22 26.1252C16.2058 26.1403 10.5117 24.6159 5.5 21.708" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.9375 20.625H24.0625" stroke="#FFD300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        {{ $konf->project_delivered }}
                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Projects Delivered
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Dollar Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 38.0418C21.6368 38.0371 21.2898 37.8907 21.033 37.6338C20.7761 37.377 20.6297 37.03 20.625 36.6668V7.3335C20.625 6.96882 20.7699 6.61909 21.0277 6.36122C21.2856 6.10336 21.6353 5.9585 22 5.9585C22.3647 5.9585 22.7144 6.10336 22.9723 6.36122C23.2301 6.61909 23.375 6.96882 23.375 7.3335V36.6668C23.3703 37.03 23.2239 37.377 22.967 37.6338C22.7102 37.8907 22.3632 38.0371 22 38.0418Z" fill="#FFD300"/>
                        <path d="M24.75 34.3751H12.8334C12.4687 34.3751 12.119 34.2302 11.8611 33.9723C11.6032 33.7145 11.4584 33.3647 11.4584 33.0001C11.4584 32.6354 11.6032 32.2856 11.8611 32.0278C12.119 31.7699 12.4687 31.6251 12.8334 31.6251H24.75C25.9629 31.7331 27.1698 31.3619 28.1122 30.5908C29.0546 29.8198 29.6575 28.7103 29.7917 27.5001C29.6575 26.2898 29.0546 25.1803 28.1122 24.4093C27.1698 23.6382 25.9629 23.267 24.75 23.3751H19.25C18.2866 23.4372 17.3204 23.3084 16.4068 22.9962C15.4933 22.684 14.6503 22.1945 13.9264 21.5557C13.2025 20.917 12.6118 20.1416 12.1883 19.274C11.7648 18.4064 11.5167 17.4637 11.4584 16.5001C11.5167 15.5364 11.7648 14.5937 12.1883 13.7261C12.6118 12.8586 13.2025 12.0831 13.9264 11.4444C14.6503 10.8056 15.4933 10.3161 16.4068 10.0039C17.3204 9.69169 18.2866 9.56295 19.25 9.62505H29.3334C29.698 9.62505 30.0478 9.76992 30.3056 10.0278C30.5635 10.2856 30.7084 10.6354 30.7084 11.0001C30.7084 11.3647 30.5635 11.7145 30.3056 11.9723C30.0478 12.2302 29.698 12.3751 29.3334 12.3751H19.25C18.0372 12.267 16.8303 12.6382 15.8879 13.4093C14.9455 14.1803 14.3426 15.2898 14.2084 16.5001C14.3426 17.7103 14.9455 18.8198 15.8879 19.5908C16.8303 20.3619 18.0372 20.7331 19.25 20.6251H24.75C25.7135 20.5629 26.6797 20.6917 27.5933 21.0039C28.5068 21.3161 29.3497 21.8056 30.0737 22.4444C30.7976 23.0831 31.3883 23.8586 31.8118 24.7261C32.2353 25.5937 32.4834 26.5364 32.5417 27.5001C32.4834 28.4637 32.2353 29.4064 31.8118 30.274C31.3883 31.1416 30.7976 31.917 30.0737 32.5557C29.3497 33.1945 28.5068 33.684 27.5933 33.9962C26.6797 34.3084 25.7135 34.4372 24.75 34.3751Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        {{ $konf->cost_savings }}
                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Cost Savings
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 sm:gap-3 md:gap-4 p-4 rounded-2xl">
                    <!-- Target Icon -->
                    <svg class="w-8 sm:w-10 md:w-12 h-8 sm:h-10 md:h-12" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 40.3332C19.4639 40.3332 17.0805 39.8516 14.85 38.8885C12.6194 37.9254 10.6791 36.6194 9.02913 34.9707C7.37913 33.3219 6.07318 31.3816 5.1113 29.1498C4.14941 26.9181 3.66785 24.5347 3.66663 21.9998C3.66541 19.4649 4.14696 17.0816 5.1113 14.8498C6.07563 12.6181 7.38157 10.6778 9.02913 9.029C10.6767 7.38023 12.617 6.07428 14.85 5.11117C17.083 4.14806 19.4663 3.6665 22 3.6665C24.5336 3.6665 26.917 4.14806 29.15 5.11117C31.383 6.07428 33.3232 7.38023 34.9708 9.029C36.6184 10.6778 37.9249 12.6181 38.8905 14.8498C39.856 17.0816 40.337 19.4649 40.3333 21.9998C40.3296 24.5347 39.8481 26.9181 38.8886 29.1498C37.9292 31.3816 36.6232 33.3219 34.9708 34.9707C33.3184 36.6194 31.3781 37.926 29.15 38.8903C26.9219 39.8547 24.5385 40.3356 22 40.3332ZM22 36.6665C26.0944 36.6665 29.5625 35.2457 32.4041 32.404C35.2458 29.5623 36.6666 26.0943 36.6666 21.9998C36.6666 17.9054 35.2458 14.4373 32.4041 11.5957C29.5625 8.754 26.0944 7.33317 22 7.33317C17.9055 7.33317 14.4375 8.754 11.5958 11.5957C8.75413 14.4373 7.33329 17.9054 7.33329 21.9998C7.33329 26.0943 8.75413 29.5623 11.5958 32.404C14.4375 35.2457 17.9055 36.6665 22 36.6665ZM22 32.9998C18.9444 32.9998 16.3472 31.9304 14.2083 29.7915C12.0694 27.6526 11 25.0554 11 21.9998C11 18.9443 12.0694 16.3471 14.2083 14.2082C16.3472 12.0693 18.9444 10.9998 22 10.9998C25.0555 10.9998 27.6527 12.0693 29.7916 14.2082C31.9305 16.3471 33 18.9443 33 21.9998C33 25.0554 31.9305 27.6526 29.7916 29.7915C27.6527 31.9304 25.0555 32.9998 22 32.9998ZM22 29.3332C24.0166 29.3332 25.743 28.6151 27.1791 27.179C28.6152 25.7429 29.3333 24.0165 29.3333 21.9998C29.3333 19.9832 28.6152 18.2568 27.1791 16.8207C25.743 15.3846 24.0166 14.6665 22 14.6665C19.9833 14.6665 18.2569 15.3846 16.8208 16.8207C15.3847 18.2568 14.6666 19.9832 14.6666 21.9998C14.6666 24.0165 15.3847 25.7429 16.8208 27.179C18.2569 28.6151 19.9833 29.3332 22 29.3332ZM22 25.6665C20.9916 25.6665 20.1287 25.3078 19.4113 24.5903C18.6939 23.8729 18.3345 23.0094 18.3333 21.9998C18.3321 20.9903 18.6914 20.1274 19.4113 19.4112C20.1312 18.6949 20.9941 18.3356 22 18.3332C23.0058 18.3307 23.8694 18.6901 24.5905 19.4112C25.3116 20.1323 25.6703 20.9952 25.6666 21.9998C25.663 23.0045 25.3042 23.868 24.5905 24.5903C23.8767 25.3127 23.0132 25.6714 22 25.6665Z" fill="#FFD300"/>
                    </svg>
                    <div class="text-yellow-400 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center">
                        {{ $konf->success_rate }}
                    </div>
                    <div class="text-neutral-400 text-base sm:text-lg md:text-xl lg:text-2xl font-medium text-center">
                        Success Rate
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full h-0.5 outline outline-1 outline-neutral-900 outline-offset--1"></div>
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
            <!-- Contact Section - DYNAMIC TITLE FROM DATABASE -->
            @if($sectionConfig['is_active'] ?? true)
            <section id="contact" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 bg-slate-800 rounded-3xl border border-slate-700 -m-1 flex flex-col lg:flex-row gap-8 lg:gap-12">
                <div class="flex flex-col gap-6 sm:gap-8 max-w-full lg:max-w-md">
                    <div class="flex flex-col gap-4">
                        <h2 class="text-white text-xl sm:text-2xl font-semibold leading-loose">
                            {{-- Can be dynamic based on section config --}}
                            Have a project or question in mind? Just send me a message.
                        </h2>
                        <p class="text-gray-400 text-sm sm:text-base font-light leading-normal">
                            Let's discuss how AI and automation can drive innovation and efficiency in your organization.
                        </p>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Call me now</span>
                                <a href="tel:{{ $konf->no_hp_setting }}" class="text-white text-base font-normal leading-normal hover:text-yellow-400 transition-colors truncate">{{ $konf->no_hp_setting }}</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Chat with me</span>
                                <a href="mailto:{{ $konf->email_setting }}" class="text-white text-base font-normal leading-normal hover:text-yellow-400 transition-colors truncate">{{ $konf->email_setting }}</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-slate-900 rounded-xl hover:bg-slate-700 transition-all duration-300">
                            <div class="flex-shrink-0 w-12 h-12 p-3 bg-yellow-400 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="black" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1 min-w-0 flex-1">
                                <span class="text-gray-400 text-sm font-light leading-tight">Location</span>
                                <span class="text-white text-base font-normal leading-normal">{{ $konf->alamat_setting }}</span>
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
                <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-6 sm:gap-8 flex-1">
                    @csrf
                    <h2 class="text-white text-xl sm:text-2xl font-semibold leading-loose">Just say 👋 Hi</h2>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" name="full_name" placeholder="Full Name" required class="w-full sm:w-1/2 h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                            <input type="email" name="email" placeholder="Email Address" required class="w-full sm:w-1/2 h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                        </div>
                        <input type="text" name="subject" placeholder="Subject" class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                        <div class="flex flex-col sm:flex-row gap-4">
                            <select name="service" class="w-full h-12 bg-slate-800 rounded-md border border-slate-600 px-4 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                <option value="">Select Service</option>
                                <option value="ai">Digital Transformation 4.0 Consultant</option>
                                <option value="automation">AI AGENT AUTOMATION Solution</option>
                                <option value="automation">CUSTOM GPT/GEM Solution</option>
                                <option value="automation">Content Creator Endorsement</option>
                            </select>
                        </div>
                        <textarea name="message" placeholder="Message" class="w-full h-32 bg-slate-800 rounded-md border border-slate-600 px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 resize-none"></textarea>
                        <button type="submit" class="send-message-btn w-full sm:w-auto px-6 py-3 bg-yellow-400 rounded-xl flex items-center gap-3 hover:bg-yellow-500 transition-all duration-300 shadow-lg hover:shadow-xl justify-center group">
                            <span class="text-black text-base font-semibold capitalize leading-[40px] sm:leading-[72px] group-hover:text-black">
                                Send Message
                            </span>
                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-black group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
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