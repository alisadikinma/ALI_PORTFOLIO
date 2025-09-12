{{-- Portfolio Section with Responsive Sliding Cards --}}
<section class="portfolio-section py-5" id="portfolio">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title mb-3">Portfolio</h1>
            <p class="section-subtitle">Discover my latest projects and achievements in AI, web development, and digital innovation</p>
        </div>
        
        <div class="slider-container position-relative">
            <div class="slider-wrapper d-flex" id="sliderWrapper">
                {{-- Portfolio cards will be populated by JavaScript --}}
            </div>
            
            <button class="slider-nav prev position-absolute" id="prevBtn">
                <svg viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            
            <button class="slider-nav next position-absolute" id="nextBtn">
                <svg viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>
        </div>
        
        <div class="slider-dots d-flex justify-content-center mt-4" id="sliderDots">
            {{-- Dots will be populated by JavaScript --}}
        </div>
    </div>
</section>

<style>
    .portfolio-section {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .section-title {
        font-size: 3.5rem;
        font-weight: bold;
        color: #FFD700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: #B0C4DE;
        max-width: 600px;
        margin: 0 auto;
    }

    .slider-container {
        overflow: hidden;
        padding: 0 60px;
    }

    .slider-wrapper {
        transition: transform 0.5s ease-in-out;
        gap: 30px;
    }

    .portfolio-card {
        min-width: 350px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        border: 1px solid rgba(255, 215, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .portfolio-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 215, 0, 0.1), rgba(30, 60, 114, 0.2));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .portfolio-card:hover::before {
        opacity: 1;
    }

    .portfolio-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(255, 215, 0, 0.3);
        border-color: #FFD700;
    }

    .card-content {
        position: relative;
        z-index: 2;
    }

    .card-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px;
        overflow: hidden;
        border: 4px solid #FFD700;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .portfolio-card:hover .card-image img {
        transform: scale(1.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #FFD700;
        margin-bottom: 15px;
    }

    .card-category {
        font-size: 0.9rem;
        color: #B0C4DE;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-description {
        font-size: 1rem;
        color: #FFFFFF;
        line-height: 1.6;
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .card-btn {
        background: linear-gradient(45deg, #FFD700, #FFA500);
        color: #1e3c72;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
    }

    .card-btn:hover {
        background: linear-gradient(45deg, #FFA500, #FFD700);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        color: #1e3c72;
        text-decoration: none;
    }

    .slider-nav {
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 215, 0, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 2px solid #FFD700;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .slider-nav:hover {
        background: #FFD700;
        transform: translateY(-50%) scale(1.1);
    }

    .slider-nav.prev {
        left: 10px;
    }

    .slider-nav.next {
        right: 10px;
    }

    .slider-nav svg {
        width: 20px;
        height: 20px;
        fill: #FFD700;
        transition: fill 0.3s ease;
    }

    .slider-nav:hover svg {
        fill: #1e3c72;
    }

    .slider-dots {
        gap: 10px;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 215, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .dot.active {
        background: #FFD700;
        transform: scale(1.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .slider-container {
            padding: 0 30px;
        }

        .portfolio-card {
            min-width: 300px;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .slider-nav {
            width: 40px;
            height: 40px;
        }

        .slider-nav svg {
            width: 16px;
            height: 16px;
        }
    }

    @media (max-width: 480px) {
        .slider-container {
            padding: 0 20px;
        }

        .portfolio-card {
            min-width: 280px;
            padding: 20px;
        }

        .section-title {
            font-size: 2rem;
        }

        .card-image {
            width: 100px;
            height: 100px;
        }
    }

    /* Auto-slide animation */
    @keyframes slideAnimation {
        0% { opacity: 0.7; }
        50% { opacity: 1; }
        100% { opacity: 0.7; }
    }

    .portfolio-card.active {
        animation: slideAnimation 3s infinite;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch portfolio data from server - with error handling
    let portfolioData = [];
    try {
        @php
            $projects = \App\Models\Project::active()->ordered()->get();
            $portfolioDataArray = $projects->map(function($project) {
                return [
                    'id' => $project->id_project ?? 0,
                    'image' => $project->main_image ?? asset('images/placeholder/project-placeholder.jpg'),
                    'title' => $project->project_name ?? 'Untitled Project',
                    'category' => $project->project_category ?? 'General',
                    'description' => \Str::limit(strip_tags($project->description ?? 'No description available'), 120),
                    'link' => $project->slug_project ? route('project.public.show', $project->slug_project) : '#'
                ];
            });
        @endphp
        portfolioData = @json($portfolioDataArray);
    } catch (error) {
        console.warn('Error loading portfolio data:', error);
        portfolioData = [];
    }

    let currentSlide = 0;
    let autoSlideInterval;

    function getCardsPerView() {
        if (window.innerWidth <= 480) return 1;
        if (window.innerWidth <= 768) return 2;
        return 3;
    }

    const cardsPerView = getCardsPerView();

    function initializeSlider() {
        const sliderWrapper = document.getElementById('sliderWrapper');
        const sliderDots = document.getElementById('sliderDots');
        
        if (!sliderWrapper || !sliderDots) return;
        
        // Clear existing content
        sliderWrapper.innerHTML = '';
        sliderDots.innerHTML = '';
        
        if (portfolioData.length === 0) {
            sliderWrapper.innerHTML = '<p class="text-center text-white">No projects available</p>';
            return;
        }
        
        // Create portfolio cards
        portfolioData.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'portfolio-card';
            card.innerHTML = `
                <div class="card-content">
                    <div class="card-image">
                        <img src="${item.image || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face'}" alt="${item.title || 'Portfolio Item'}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face'">
                    </div>
                    <h3 class="card-title">${item.title || 'Untitled'}</h3>
                    <p class="card-category">${item.category || 'General'}</p>
                    <p class="card-description">${item.description || 'No description available'}</p>
                    <a href="${item.link || '#'}" class="card-btn">
                        View Project
                    </a>
                </div>
            `;
            sliderWrapper.appendChild(card);
        });
        
        // Create dots
        const totalSlides = Math.ceil(portfolioData.length / cardsPerView);
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = 'dot';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            sliderDots.appendChild(dot);
        }
        
        // Reset current slide
        currentSlide = 0;
        updateSlider();
        startAutoSlide();
    }

    function updateSlider() {
        const sliderWrapper = document.getElementById('sliderWrapper');
        const dots = document.querySelectorAll('.dot');
        
        if (!sliderWrapper || dots.length === 0) return;
        
        const cardWidth = 350 + 30; // card width + gap
        const offset = currentSlide * cardWidth * cardsPerView;
        
        sliderWrapper.style.transform = `translateX(-${offset}px)`;
        
        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
        
        // Update card animations
        const cards = document.querySelectorAll('.portfolio-card');
        cards.forEach((card, index) => {
            const startIndex = currentSlide * cardsPerView;
            const endIndex = startIndex + cardsPerView;
            card.classList.toggle('active', index >= startIndex && index < endIndex);
        });
    }

    function nextSlide() {
        const totalSlides = Math.ceil(portfolioData.length / cardsPerView);
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlider();
    }

    function prevSlide() {
        const totalSlides = Math.ceil(portfolioData.length / cardsPerView);
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSlider();
    }

    function goToSlide(slideIndex) {
        currentSlide = slideIndex;
        updateSlider();
        stopAutoSlide();
        startAutoSlide();
    }

    function startAutoSlide() {
        stopAutoSlide();
        if (portfolioData.length > cardsPerView) {
            autoSlideInterval = setInterval(nextSlide, 4000);
        }
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }

    // Event listeners
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoSlide();
            startAutoSlide();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoSlide();
            startAutoSlide();
        });
    }

    // Pause auto-slide on hover
    const sliderContainer = document.querySelector('.slider-container');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', stopAutoSlide);
        sliderContainer.addEventListener('mouseleave', startAutoSlide);
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const newCardsPerView = getCardsPerView();
            if (newCardsPerView !== cardsPerView) {
                location.reload();
            }
        }, 250);
    });

    // Initialize slider
    initializeSlider();
});
</script>
