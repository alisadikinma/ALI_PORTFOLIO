<!-- Portfolio Section -->
<section id="portfolio" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-14 flex flex-col items-center gap-8 sm:gap-12" role="region" aria-labelledby="portfolio-heading">
    <div class="flex flex-col gap-3 text-center">
        <h2 id="portfolio-heading" class="text-yellow-400 text-3xl sm:text-5xl font-extrabold leading-tight sm:leading-[56px]">
            Portfolio
        </h2>
        <p class="text-neutral-400 text-lg sm:text-2xl font-normal leading-6 sm:leading-7 tracking-tight">
            Discover my latest projects and achievements in AI, web development, and digital innovation
        </p>
    </div>

    @if(isset($projects) && $projects->count() > 0)
    <!-- Portfolio Slider -->
    <div class="relative w-full max-w-6xl">
        <!-- Navigation Arrows -->
        <button id="prevBtn"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-black hover:bg-yellow-500 transition-colors shadow-lg focus-visible-enhanced"
                aria-label="Previous portfolio projects"
                title="Previous projects">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button id="nextBtn"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-black hover:bg-yellow-500 transition-colors shadow-lg focus-visible-enhanced"
                aria-label="Next portfolio projects"
                title="Next projects">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Cards Container -->
        <div class="overflow-hidden mx-16" role="region" aria-live="polite" aria-label="Portfolio projects carousel">
            <div id="portfolioSlider" class="flex transition-transform duration-500 ease-in-out">
                @foreach($projects as $project)
                <article class="portfolio-card flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-2" role="article">
                    <div class="bg-slate-800 rounded-xl p-6 h-full flex flex-col transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                        <div class="mb-4">
                            @if($project->featured_image)
                            <img src="{{ asset('images/projects/' . $project->featured_image) }}"
                                 alt="{{ $project->project_name }} project screenshot"
                                 class="w-full h-48 object-cover rounded-lg"
                                 loading="lazy" decoding="async"
                                 onerror="this.src='{{ asset('images/placeholder/project-placeholder.svg') }}'" />
                            @else
                            <div class="w-full h-48 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                <span class="text-2xl font-bold text-black">{{ substr($project->project_name, 0, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 flex flex-col">
                            <h3 class="text-white text-xl font-bold mb-2 line-clamp-2">{{ $project->project_name }}</h3>
                            
                            @if($project->project_category)
                            <div class="mb-3">
                                <span class="inline-block bg-yellow-400/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $project->project_category }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Client and Location Info -->
                            @if($project->client_name || $project->location)
                            <div class="project-client mb-3">
                                {{ $project->client_name ?? 'Unknown Client' }} • {{ $project->location ?? 'Unknown Location' }}
                            </div>
                            @endif
                            
                            <p class="text-gray-400 text-sm leading-relaxed mb-4 flex-1 line-clamp-3">
                                {!! Str::limit(strip_tags($project->summary_description ?? ''), 120) !!}
                            </p>
                            
                            <a href="{{ route('portfolio.detail', $project->slug_project) }}"
                               class="inline-flex items-center justify-center bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold transition-colors mt-auto focus-visible-enhanced"
                               aria-label="View details for {{ $project->project_name }} project">
                                View Project
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>

        <!-- Dots Indicator -->
        <div id="dotsContainer" class="flex justify-center mt-8 space-x-2" role="tablist" aria-label="Portfolio navigation dots">
            <!-- Dots will be dynamically populated here -->
        </div>
    </div>
    @else
    <!-- No Data State -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-yellow-400 text-6xl mb-4">🚀</div>
        <h3 class="text-white text-xl font-semibold mb-2">No Projects Yet</h3>
        <p class="text-gray-400 text-center max-w-md">
            I'm working on exciting projects that showcase AI innovation and cutting-edge technology. Check back soon to see my latest work!
        </p>
    </div>
    @endif

    <!-- View All Projects Button -->
    <div class="text-center">
        <a href="{{ url('portfolio/all') }}"
           class="inline-flex items-center bg-transparent border-2 border-yellow-400 hover:bg-yellow-400 hover:text-black text-yellow-400 px-8 py-3 rounded-lg font-semibold transition-all duration-300 focus-visible-enhanced"
           aria-label="View all portfolio projects">
           &nbsp;&nbsp;VIEW ALL PROJECTS&nbsp;&nbsp;
        </a>
    </div>
</section>

<style>
.portfolio-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.portfolio-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(251, 191, 36, 0.2);
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: #FBBF24;
    transform: scale(1.2);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Client info styles */
.project-client {
    color: #94a3b8;
    font-size: 0.75rem;
    font-style: italic;
}

@media (max-width: 1024px) {
    .portfolio-card {
        flex: 0 0 calc(50% - 1rem);
    }
}

@media (max-width: 640px) {
    .portfolio-card {
        flex: 0 0 calc(100% - 1rem);
    }
    
    .overflow-hidden.mx-16 {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get portfolio data from blade
    const portfolioCards = document.querySelectorAll('.portfolio-card');
    const totalCards = portfolioCards.length;
    
    if (totalCards === 0) {
        console.log('No portfolio cards found');
        return;
    }

    let currentSlide = 0;
    let cardsPerView = 3;
    let totalSlides = 0;

    function updateCardsPerView() {
        if (window.innerWidth >= 1024) {
            cardsPerView = 3;
        } else if (window.innerWidth >= 640) {
            cardsPerView = 2;
        } else {
            cardsPerView = 1;
        }
        totalSlides = Math.ceil(totalCards / cardsPerView);
    }

    function renderDots() {
        const dotsContainer = document.getElementById('dotsContainer');
        if (!dotsContainer) return;

        dotsContainer.innerHTML = '';

        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `dot ${i === 0 ? 'active' : ''} focus-visible-enhanced`;
            dot.setAttribute('role', 'tab');
            dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dot.setAttribute('tabindex', i === 0 ? '0' : '-1');
            dot.addEventListener('click', () => goToSlide(i));
            dot.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    goToSlide(i);
                }
            });
            dotsContainer.appendChild(dot);
        }
    }

    function updateSlider() {
        const slider = document.getElementById('portfolioSlider');
        if (!slider) return;
        
        const translateX = -currentSlide * (100 / cardsPerView);
        slider.style.transform = `translateX(${translateX}%)`;

        // Update dots
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
            dot.setAttribute('aria-selected', index === currentSlide ? 'true' : 'false');
            dot.setAttribute('tabindex', index === currentSlide ? '0' : '-1');
        });
    }

    function goToSlide(slideIndex) {
        if (slideIndex >= 0 && slideIndex < totalSlides) {
            currentSlide = slideIndex;
            updateSlider();
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlider();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSlider();
    }

    // Event listeners
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
        nextBtn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                nextSlide();
            }
        });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
        prevBtn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                prevSlide();
            }
        });
    }

    // Keyboard navigation for arrow keys
    document.addEventListener('keydown', (e) => {
        const portfolioSection = document.getElementById('portfolio');
        if (!portfolioSection || !portfolioSection.matches(':focus-within')) return;

        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextSlide();
        }
    });

    // Auto-play
    let autoPlayInterval = setInterval(nextSlide, 5000);

    // Pause auto-play on hover and focus
    const portfolioSection = document.getElementById('portfolio');
    if (portfolioSection) {
        portfolioSection.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        portfolioSection.addEventListener('mouseleave', () => {
            autoPlayInterval = setInterval(nextSlide, 5000);
        });

        portfolioSection.addEventListener('focusin', () => {
            clearInterval(autoPlayInterval);
        });

        portfolioSection.addEventListener('focusout', () => {
            autoPlayInterval = setInterval(nextSlide, 5000);
        });
    }

    // Initialize
    updateCardsPerView();
    renderDots();
    updateSlider();

    // Handle window resize
    window.addEventListener('resize', () => {
        updateCardsPerView();
        renderDots();
        updateSlider();
    });
});
</script>
