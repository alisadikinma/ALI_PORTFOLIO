@extends('layouts.web')

@section('isi')
<!-- Responsive Sliding Cards Portfolio Section - EXACT MATCH TO ATTACHMENT -->.
<section class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header with Exact Title from Image -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                    Responsive Sliding Cards in HTML CSS JS
                </span>
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                Discover my latest projects and achievements in AI, web development, and digital innovation
            </p>
        </div>

        <!-- Navigation Controls -->
        <div class="flex justify-between items-center mb-12">
            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-3">
                <button class="filter-btn active px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="all">
                    All Projects
                </button>
                @if(isset($jenis_projects) && count($jenis_projects) > 0)
                    @foreach ($jenis_projects as $jenis)
                    <button class="filter-btn px-6 py-3 rounded-full font-semibold transition-all duration-300" data-filter="{{ $jenis }}">
                        {{ $jenis }}
                    </button>
                    @endforeach
                @endif
            </div>

            <!-- Slider Navigation -->
            <div class="flex items-center gap-4">
                <button id="prevBtn" class="nav-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="nextBtn" class="nav-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sliding Cards Container - MATCHES ATTACHMENT EXACTLY -->
        <div class="relative overflow-hidden rounded-3xl">
            <div id="slider-container" class="flex transition-all duration-700 ease-out" style="transform: translateX(0%)">
                @if(isset($projects) && count($projects) > 0)
                    @foreach ($projects as $index => $project)
                    <div class="portfolio-card flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4" data-category="{{ $project->project_category ?? 'uncategorized' }}">
                        <div class="card-inner bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 h-full shadow-2xl border border-blue-400/30 hover:border-yellow-400/50 transition-all duration-300">
                            <!-- Profile Image Circle - Like David Dell, Rose Bush, Jones Gail -->
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                                        <img src="{{ asset('file/project/' . ($project->featured_image ?? 'default.jpg')) }}" 
                                             alt="{{ $project->project_name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($project->project_name) }}&background=3b82f6&color=fff&size=96'">
                                    </div>
                                    <!-- Online Status Dot -->
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white"></div>
                                </div>
                            </div>

                            <!-- Name & Title -->
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $project->project_name }}</h3>
                                <p class="text-blue-200 text-sm font-medium">{{ $project->project_category ?? 'Project Specialist' }}</p>
                            </div>

                            <!-- Description -->
                            <div class="text-center mb-8">
                                <p class="text-blue-100 text-sm leading-relaxed">
                                    {{ Str::limit(strip_tags($project->description), 120) }}
                                </p>
                            </div>

                            <!-- View More Button -->
                            <div class="text-center">
                                <button class="view-more-btn w-full py-3 bg-white/20 hover:bg-white hover:text-blue-800 text-white font-semibold rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 hover:border-white">
                                    View More
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Sample Cards for Demo - Exactly like attachment -->
                    <div class="portfolio-card flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4" data-category="ai">
                        <div class="card-inner bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 h-full shadow-2xl border border-blue-400/30">
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                                        <img src="https://ui-avatars.com/api/?name=David+Dell&background=3b82f6&color=fff&size=96" alt="David Dell" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white"></div>
                                </div>
                            </div>
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">David Dell</h3>
                                <p class="text-blue-200 text-sm font-medium">AI Development Specialist</p>
                            </div>
                            <div class="text-center mb-8">
                                <p class="text-blue-100 text-sm leading-relaxed">
                                    The lorem text this section contain contains header having open endpoints functionality.
                                </p>
                            </div>
                            <div class="text-center">
                                <button class="view-more-btn w-full py-3 bg-white/20 hover:bg-white hover:text-blue-800 text-white font-semibold rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 hover:border-white">
                                    View More
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="portfolio-card flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4" data-category="web">
                        <div class="card-inner bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 h-full shadow-2xl border border-blue-400/30">
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                                        <img src="https://ui-avatars.com/api/?name=Rose+Bush&background=ec4899&color=fff&size=96" alt="Rose Bush" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white"></div>
                                </div>
                            </div>
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">Rose Bush</h3>
                                <p class="text-blue-200 text-sm font-medium">Web Development Expert</p>
                            </div>
                            <div class="text-center mb-8">
                                <p class="text-blue-100 text-sm leading-relaxed">
                                    The lorem text this section contain contains header having open endpoints functionality.
                                </p>
                            </div>
                            <div class="text-center">
                                <button class="view-more-btn w-full py-3 bg-white/20 hover:bg-white hover:text-blue-800 text-white font-semibold rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 hover:border-white">
                                    View More
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="portfolio-card flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-4" data-category="design">
                        <div class="card-inner bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 h-full shadow-2xl border border-blue-400/30">
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                                        <img src="https://ui-avatars.com/api/?name=Jones+Gail&background=10b981&color=fff&size=96" alt="Jones Gail" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white"></div>
                                </div>
                            </div>
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">Jones Gail</h3>
                                <p class="text-blue-200 text-sm font-medium">Digital Innovation Lead</p>
                            </div>
                            <div class="text-center mb-8">
                                <p class="text-blue-100 text-sm leading-relaxed">
                                    The lorem text this section contain contains header having open endpoints functionality.
                                </p>
                            </div>
                            <div class="text-center">
                                <button class="view-more-btn w-full py-3 bg-white/20 hover:bg-white hover:text-blue-800 text-white font-semibold rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 hover:border-white">
                                    View More
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Slide Indicators -->
        <div class="flex justify-center gap-3 mt-12" id="indicators">
            <!-- Generated by JavaScript -->
        </div>
    </div>
</section>

<style>
/* Portfolio Specific Styles - EXACTLY MATCHING ATTACHMENT */
.filter-btn {
    background: rgba(59, 130, 246, 0.2);
    color: #93c5fd;
    border: 1px solid rgba(59, 130, 246, 0.3);
    backdrop-filter: blur(10px);
}

.filter-btn.active,
.filter-btn:hover {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    border-color: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
}

.nav-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.nav-btn:hover {
    background: #fbbf24;
    color: #1f2937;
    border-color: #fbbf24;
    transform: scale(1.1);
}

.nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
}

.portfolio-card {
    min-height: 400px;
}

.card-inner {
    backdrop-filter: blur(20px);
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.card-inner:hover {
    transform: translateY(-10px) rotateX(5deg);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3), 0 0 50px rgba(59, 130, 246, 0.2);
}

.view-more-btn:hover {
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.slide-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.slide-indicator.active {
    background: #fbbf24;
    border-color: #fbbf24;
    transform: scale(1.3);
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
    .portfolio-card {
        width: 100%;
        min-width: 100%;
    }
    
    .card-inner {
        margin: 0 10px;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .portfolio-card {
        width: 50%;
        min-width: 50%;
    }
}

@media (min-width: 1025px) {
    .portfolio-card {
        width: 33.333%;
        min-width: 33.333%;
    }
}

/* Animation for cards entering viewport */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.portfolio-card {
    animation: slideInUp 0.6s ease-out;
}

.portfolio-card:nth-child(2) {
    animation-delay: 0.1s;
}

.portfolio-card:nth-child(3) {
    animation-delay: 0.2s;
}
</style>

<script>
// Enhanced Portfolio Slider with Touch Support - PERFECT MATCH
document.addEventListener('DOMContentLoaded', function() {
    const sliderContainer = document.getElementById('slider-container');
    const cards = document.querySelectorAll('.portfolio-card');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('indicators');
    
    let currentIndex = 0;
    let visibleCards = getVisibleCards();
    let filteredCards = Array.from(cards);
    let autoSlideInterval;
    let isTransitioning = false;
    
    function getVisibleCards() {
        if (window.innerWidth < 641) return 1;
        if (window.innerWidth < 1025) return 2;
        return 3;
    }
    
    function updateVisibleCards() {
        visibleCards = getVisibleCards();
        updateSlider();
        generateIndicators();
    }
    
    function generateIndicators() {
        if (!indicatorsContainer || filteredCards.length === 0) return;
        
        const totalSlides = Math.max(1, Math.ceil(filteredCards.length / visibleCards));
        indicatorsContainer.innerHTML = '';
        
        for (let i = 0; i < totalSlides; i++) {
            const indicator = document.createElement('div');
            indicator.className = `slide-indicator ${i === Math.floor(currentIndex / visibleCards) ? 'active' : ''}`;
            indicator.addEventListener('click', () => goToSlide(i * visibleCards));
            indicatorsContainer.appendChild(indicator);
        }
    }
    
    function updateSlider() {
        if (!sliderContainer || filteredCards.length === 0 || isTransitioning) return;
        
        const cardWidth = 100 / visibleCards;
        const maxIndex = Math.max(0, filteredCards.length - visibleCards);
        currentIndex = Math.min(currentIndex, maxIndex);
        
        const translateX = -(currentIndex * cardWidth);
        sliderContainer.style.transform = `translateX(${translateX}%)`;
        
        // Update indicators
        const indicators = indicatorsContainer.querySelectorAll('.slide-indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === Math.floor(currentIndex / visibleCards));
        });
        
        // Update navigation buttons
        if (prevBtn) {
            prevBtn.disabled = currentIndex === 0;
            prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
        }
        if (nextBtn) {
            nextBtn.disabled = currentIndex >= maxIndex;
            nextBtn.style.opacity = currentIndex >= maxIndex ? '0.4' : '1';
        }
    }
    
    function goToSlide(index) {
        if (isTransitioning) return;
        
        const maxIndex = Math.max(0, filteredCards.length - visibleCards);
        currentIndex = Math.max(0, Math.min(index, maxIndex));
        
        isTransitioning = true;
        updateSlider();
        
        setTimeout(() => {
            isTransitioning = false;
        }, 700);
        
        resetAutoSlide();
    }
    
    function nextSlide() {
        if (isTransitioning) return;
        
        const maxIndex = Math.max(0, filteredCards.length - visibleCards);
        if (currentIndex < maxIndex) {
            currentIndex++;
        } else {
            currentIndex = 0; // Loop back to start
        }
        
        isTransitioning = true;
        updateSlider();
        
        setTimeout(() => {
            isTransitioning = false;
        }, 700);
        
        resetAutoSlide();
    }
    
    function prevSlide() {
        if (isTransitioning) return;
        
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = Math.max(0, filteredCards.length - visibleCards); // Loop to end
        }
        
        isTransitioning = true;
        updateSlider();
        
        setTimeout(() => {
            isTransitioning = false;
        }, 700);
        
        resetAutoSlide();
    }
    
    function filterCards(category) {
        // Hide all cards first
        cards.forEach(card => {
            card.style.display = 'none';
            card.style.opacity = '0';
        });
        
        // Show filtered cards with animation
        if (category === 'all') {
            filteredCards = Array.from(cards);
        } else {
            filteredCards = Array.from(cards).filter(card => 
                card.dataset.category === category
            );
        }
        
        // Animate in filtered cards
        filteredCards.forEach((card, index) => {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.animation = `slideInUp 0.6s ease-out ${index * 0.1}s both`;
            }, 50);
        });
        
        // Reset slider position and update
        currentIndex = 0;
        setTimeout(() => {
            updateSlider();
            generateIndicators();
        }, 100);
        
        resetAutoSlide();
    }
    
    function startAutoSlide() {
        if (filteredCards.length > visibleCards) {
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, 5000);
        }
    }
    
    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }
    
    function resetAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
    }
    
    // Event Listeners
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    
    // Filter buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            const filter = this.dataset.filter;
            filterCards(filter);
        });
    });
    
    // Window resize handler
    window.addEventListener('resize', debounce(updateVisibleCards, 100));
    
    // Touch/swipe support
    let startX = 0;
    let startY = 0;
    let isScrolling = false;
    
    sliderContainer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isScrolling = false;
        stopAutoSlide();
    });
    
    sliderContainer.addEventListener('touchmove', (e) => {
        if (!startX || !startY) return;
        
        const diffX = startX - e.touches[0].clientX;
        const diffY = startY - e.touches[0].clientY;
        
        if (!isScrolling) {
            isScrolling = Math.abs(diffX) < Math.abs(diffY);
        }
        
        if (!isScrolling && Math.abs(diffX) > 10) {
            e.preventDefault();
        }
    });
    
    sliderContainer.addEventListener('touchend', (e) => {
        if (!startX || !startY || isScrolling) {
            startAutoSlide();
            return;
        }
        
        const diffX = startX - e.changedTouches[0].clientX;
        const diffY = startY - e.touches[0].clientY;
        
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
        
        startX = 0;
        startY = 0;
        resetAutoSlide();
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextSlide();
        }
    });
    
    // Initialize
    updateVisibleCards();
    startAutoSlide();
    
    // Pause auto-slide on hover
    sliderContainer.addEventListener('mouseenter', stopAutoSlide);
    sliderContainer.addEventListener('mouseleave', startAutoSlide);
    
    // Utility function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>
@endsection
