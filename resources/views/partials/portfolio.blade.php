{{-- Portfolio Section with 3D Coverflow Slider --}}
<section class="portfolio-section" id="portfolio">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-5">
                    <h1 class="section-title mb-3">Portfolio</h1>
                    <p class="section-subtitle">Discover my latest projects and achievements in AI, web development, and digital innovation</p>
                </div>
        
        <div class="coverflow-container" id="coverflowContainer">
            <div class="coverflow-wrapper" id="coverflowWrapper">
                {{-- 3D coverflow cards will be populated by JavaScript --}}
            </div>
            
            <button class="coverflow-nav prev" id="prevBtn">
                <svg viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            
            <button class="coverflow-nav next" id="nextBtn">
                <svg viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>
        </div>
        
        <div class="portfolio-dots d-flex justify-content-center mt-4" id="portfolioDots">
            {{-- Dots will be populated by JavaScript --}}
        </div>
        
                <div class="portfolio-info mt-3" id="portfolioInfo">
                    <div class="info-content text-center">
                        <h3 class="info-title" id="infoTitle"></h3>
                        <p class="info-category" id="infoCategory"></p>
                        <p class="info-description" id="infoDescription"></p>
                        <a href="#" class="info-btn" id="infoBtn">View Project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .portfolio-section {
        background: #0f172a;
        min-height: auto;
        display: block;
        position: relative;
        overflow: hidden;
        padding: 80px 0 0 0;
        margin-bottom: 0;
    }

    .portfolio-section .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Force alignment consistency */
    @media (min-width: 1200px) {
        .portfolio-section .container {
            max-width: 1200px !important;
        }
    }

    .portfolio-section .row {
        margin: 0;
    }

    .portfolio-section .col-12 {
        padding: 0;
    }

    .portfolio-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .section-title {
        font-size: 3rem;
        font-weight: 700;
        color: #f59e0b;
        text-align: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #f59e0b, #eab308);
        border-radius: 2px;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto 2rem;
        text-align: center;
        line-height: 1.6;
    }

    /* 3D Coverflow Container */
    .coverflow-container {
        position: relative;
        height: 400px;
        width: 100%;
        perspective: 1200px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coverflow-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transform-style: preserve-3d;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* 3D Portfolio Card */
    .coverflow-card {
        position: absolute;
        width: 280px;
        height: 350px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 2px solid rgba(245, 158, 11, 0.3);
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-style: preserve-3d;
        cursor: pointer;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    }

    /* Card positioning and 3D effects */
    .coverflow-card.center {
        z-index: 10;
        transform: translateX(0) rotateY(0deg) scale(1.1);
        filter: brightness(1.2);
        border-color: #f59e0b;
        box-shadow: 0 25px 50px rgba(245, 158, 11, 0.2);
    }

    .coverflow-card.left-1 {
        z-index: 9;
        transform: translateX(-150px) rotateY(45deg) scale(0.9);
        filter: brightness(0.8);
    }

    .coverflow-card.right-1 {
        z-index: 9;
        transform: translateX(150px) rotateY(-45deg) scale(0.9);
        filter: brightness(0.8);
    }

    .coverflow-card.left-2 {
        z-index: 8;
        transform: translateX(-280px) rotateY(60deg) scale(0.7);
        filter: brightness(0.6);
        opacity: 0.8;
    }

    .coverflow-card.right-2 {
        z-index: 8;
        transform: translateX(280px) rotateY(-60deg) scale(0.7);
        filter: brightness(0.6);
        opacity: 0.8;
    }

    .coverflow-card.hidden {
        z-index: 1;
        transform: translateX(0) rotateY(90deg) scale(0.5);
        opacity: 0;
        pointer-events: none;
    }

    /* Card Content */
    .card-image-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .coverflow-card:hover .card-image {
        transform: scale(1.1);
    }

    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(245, 158, 11, 0.2), rgba(59, 130, 246, 0.2));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .coverflow-card:hover .card-overlay {
        opacity: 1;
    }

    .card-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: rgba(15, 23, 42, 0.6);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: #f59e0b;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .card-category {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-tech-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 15px;
    }

    .tech-tag {
        font-size: 0.7rem;
        padding: 3px 8px;
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border-radius: 10px;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Navigation Buttons */
    .coverflow-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(245, 158, 11, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 2px solid #f59e0b;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 15;
    }

    .coverflow-nav:hover {
        background: #f59e0b;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
    }

    .coverflow-nav.prev {
        left: 20px;
    }

    .coverflow-nav.next {
        right: 20px;
    }

    .coverflow-nav svg {
        width: 24px;
        height: 24px;
        fill: #f59e0b;
        transition: fill 0.3s ease;
    }

    .coverflow-nav:hover svg {
        fill: #0f172a;
    }

    /* Portfolio Dots */
    .portfolio-dots {
        gap: 12px;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .portfolio-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        position: relative;
    }

    .portfolio-dot.active {
        background: #f59e0b;
        transform: scale(1.3);
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.6);
    }

    .portfolio-dot:hover {
        background: #f59e0b;
        transform: scale(1.2);
    }

    /* Portfolio Info Section */
    .portfolio-info {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
        min-height: 80px;
        margin-bottom: 0;
    }

    .portfolio-info.show {
        opacity: 1;
        transform: translateY(0);
    }

    .info-content {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .info-title {
        font-size: 2rem;
        font-weight: bold;
        color: #f59e0b;
        margin-bottom: 10px;
    }

    .info-category {
        font-size: 1rem;
        color: #94a3b8;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-description {
        font-size: 1.1rem;
        color: #e2e8f0;
        line-height: 1.6;
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .info-btn {
        background: linear-gradient(45deg, #f59e0b, #eab308);
        color: #0f172a;
        border: none;
        padding: 15px 30px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
    }

    .info-btn:hover {
        background: linear-gradient(45deg, #eab308, #f59e0b);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        color: #0f172a;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .portfolio-section .container {
            max-width: 960px;
        }
    }

    @media (max-width: 992px) {
        .portfolio-section .container {
            max-width: 720px;
        }
        
        .portfolio-section {
            padding: 20px 0 15px 0;
        }
    }

    @media (max-width: 768px) {
        .portfolio-section .container {
            max-width: 540px;
        }
        
        .portfolio-section {
            padding: 25px 0 15px 0;
        }
        .coverflow-container {
            height: 350px;
        }

        .coverflow-card {
            width: 240px;
            height: 300px;
        }

        .coverflow-card.left-1,
        .coverflow-card.right-1 {
            transform: translateX(-100px) rotateY(45deg) scale(0.8);
        }

        .coverflow-card.left-2,
        .coverflow-card.right-2 {
            transform: translateX(-200px) rotateY(60deg) scale(0.6);
        }

        .section-title {
            font-size: 2.5rem;
        }

        .coverflow-nav {
            width: 50px;
            height: 50px;
        }

        .coverflow-nav svg {
            width: 20px;
            height: 20px;
        }

        .info-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .portfolio-section .container {
            padding: 0 20px;
        }
        
        .portfolio-section {
            padding: 30px 0 15px 0;
        }
        .coverflow-container {
            height: 300px;
        }

        .coverflow-card {
            width: 200px;
            height: 250px;
        }

        .coverflow-card.left-1,
        .coverflow-card.right-1 {
            transform: translateX(-80px) rotateY(50deg) scale(0.7);
        }

        .coverflow-card.left-2,
        .coverflow-card.right-2 {
            display: none;
        }

        .section-title {
            font-size: 2rem;
        }

        .coverflow-nav {
            width: 45px;
            height: 45px;
        }

        .info-content {
            padding: 20px;
        }
    }

    /* Loading Animation */
    .coverflow-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        color: #f59e0b;
        font-size: 1.2rem;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 3px solid rgba(245, 158, 11, 0.3);
        border-top: 3px solid #f59e0b;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Auto-play indicators */
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 10px rgba(245, 158, 11, 0.3); }
        50% { box-shadow: 0 0 25px rgba(245, 158, 11, 0.8); }
    }

    .coverflow-card.center.auto-playing {
        animation: pulseGlow 2s infinite;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch portfolio data from project table
    let portfolioData = [];
    let currentIndex = 0;
    let autoPlayInterval;
    let isAnimating = false;

    try {
        @php
            $projects = \App\Models\Project::active()->ordered()->get();
            $portfolioDataArray = $projects->map(function($project) {
                return [
                    'id' => $project->id_project ?? 0,
                    'image' => $project->main_image ?? asset('images/placeholder/project-placeholder.jpg'),
                    'title' => $project->project_name ?? 'Untitled Project',
                    'category' => $project->project_category ?? 'General',
                    'description' => strip_tags($project->description ?? 'No description available'),
                    'tech_stack' => $project->tech_stack ? explode(',', $project->tech_stack) : [],
                    'link' => $project->slug_project ? route('project.public.show', $project->slug_project) : '#'
                ];
            });
        @endphp
        portfolioData = @json($portfolioDataArray);
    } catch (error) {
        console.warn('Error loading portfolio data:', error);
        portfolioData = [
            {
                id: 1,
                image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop',
                title: 'AI Dashboard',
                category: 'Web Development',
                description: 'Modern dashboard for AI analytics with real-time data visualization and machine learning insights.',
                tech_stack: ['React', 'Node.js', 'Python'],
                link: '#'
            },
            {
                id: 2,
                image: 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop',
                title: 'Mobile App',
                category: 'Mobile Development',
                description: 'Cross-platform mobile application with advanced features and intuitive user experience.',
                tech_stack: ['React Native', 'Firebase'],
                link: '#'
            }
        ];
    }

    function initializeCoverflow() {
        const coverflowWrapper = document.getElementById('coverflowWrapper');
        const portfolioDots = document.getElementById('portfolioDots');
        
        if (!coverflowWrapper || !portfolioDots) return;
        
        if (portfolioData.length === 0) {
            coverflowWrapper.innerHTML = '<div class="coverflow-loading"><div class="loading-spinner"></div>No projects available</div>';
            return;
        }

        // Clear existing content
        coverflowWrapper.innerHTML = '';
        portfolioDots.innerHTML = '';

        // Create coverflow cards
        portfolioData.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'coverflow-card';
            card.dataset.index = index;
            
            const techStackHTML = item.tech_stack && item.tech_stack.length > 0 
                ? `<div class="card-tech-stack">
                     ${item.tech_stack.map(tech => `<span class="tech-tag">${tech.trim()}</span>`).join('')}
                   </div>` 
                : '';

            card.innerHTML = `
                <div class="card-image-container">
                    <img src="${item.image}" alt="${item.title}" class="card-image" loading="lazy" 
                         onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop'">
                    <div class="card-overlay"></div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">${item.title}</h3>
                    <p class="card-category">${item.category}</p>
                    ${techStackHTML}
                </div>
            `;

            card.addEventListener('click', () => {
                if (index !== currentIndex && !isAnimating) {
                    goToSlide(index);
                }
            });

            coverflowWrapper.appendChild(card);
        });

        // Create dots
        portfolioData.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.className = 'portfolio-dot';
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            portfolioDots.appendChild(dot);
        });

        // Initialize positions
        updateCoverflow();
        updatePortfolioInfo();
        startAutoPlay();
    }

    function updateCoverflow() {
        const cards = document.querySelectorAll('.coverflow-card');
        
        cards.forEach((card, index) => {
            card.classList.remove('center', 'left-1', 'right-1', 'left-2', 'right-2', 'hidden', 'auto-playing');
            
            const offset = index - currentIndex;
            
            if (offset === 0) {
                card.classList.add('center');
            } else if (offset === -1) {
                card.classList.add('left-1');
            } else if (offset === 1) {
                card.classList.add('right-1');
            } else if (offset === -2) {
                card.classList.add('left-2');
            } else if (offset === 2) {
                card.classList.add('right-2');
            } else {
                card.classList.add('hidden');
            }
        });

        // Update dots
        const dots = document.querySelectorAll('.portfolio-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function updatePortfolioInfo() {
        const infoSection = document.getElementById('portfolioInfo');
        const titleEl = document.getElementById('infoTitle');
        const categoryEl = document.getElementById('infoCategory');
        const descriptionEl = document.getElementById('infoDescription');
        const btnEl = document.getElementById('infoBtn');

        if (!portfolioData[currentIndex] || !infoSection) return;

        const currentProject = portfolioData[currentIndex];

        // Fade out
        infoSection.classList.remove('show');

        setTimeout(() => {
            titleEl.textContent = currentProject.title;
            categoryEl.textContent = currentProject.category;
            descriptionEl.textContent = currentProject.description;
            btnEl.href = currentProject.link;

            // Fade in
            infoSection.classList.add('show');
        }, 300);
    }

    function goToSlide(index) {
        if (isAnimating || index === currentIndex) return;
        
        isAnimating = true;
        currentIndex = index;
        
        updateCoverflow();
        updatePortfolioInfo();
        
        setTimeout(() => {
            isAnimating = false;
        }, 800);

        // Restart auto-play
        stopAutoPlay();
        startAutoPlay();
    }

    function nextSlide() {
        const nextIndex = (currentIndex + 1) % portfolioData.length;
        goToSlide(nextIndex);
    }

    function prevSlide() {
        const prevIndex = (currentIndex - 1 + portfolioData.length) % portfolioData.length;
        goToSlide(prevIndex);
    }

    function startAutoPlay() {
        stopAutoPlay();
        if (portfolioData.length > 1) {
            autoPlayInterval = setInterval(() => {
                const centerCard = document.querySelector('.coverflow-card.center');
                if (centerCard) {
                    centerCard.classList.add('auto-playing');
                }
                
                setTimeout(() => {
                    nextSlide();
                    const newCenterCard = document.querySelector('.coverflow-card.center');
                    if (newCenterCard) {
                        newCenterCard.classList.remove('auto-playing');
                    }
                }, 1000);
            }, 5000);
        }
    }

    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
        
        // Remove auto-playing class from all cards
        document.querySelectorAll('.coverflow-card').forEach(card => {
            card.classList.remove('auto-playing');
        });
    }

    // Event listeners
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const coverflowContainer = document.getElementById('coverflowContainer');

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoPlay();
            startAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoPlay();
            startAutoPlay();
        });
    }

    // Pause auto-play on hover
    if (coverflowContainer) {
        coverflowContainer.addEventListener('mouseenter', stopAutoPlay);
        coverflowContainer.addEventListener('mouseleave', startAutoPlay);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            stopAutoPlay();
            startAutoPlay();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            stopAutoPlay();
            startAutoPlay();
        }
    });

    // Initialize the coverflow
    initializeCoverflow();

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            updateCoverflow();
        }, 250);
    });
});
</script>
