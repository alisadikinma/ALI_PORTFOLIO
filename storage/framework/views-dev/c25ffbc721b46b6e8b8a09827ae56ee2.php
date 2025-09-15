<!-- Portfolio Section -->
<section id="portfolio" class="py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Portfolio</h2>
            <div class="w-24 h-1 bg-yellow-400 mx-auto mb-8"></div>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Discover my latest projects and achievements in AI, web development, and digital innovation.
            </p>
        </div>

        <!-- Portfolio Cards Slider -->
        <div class="relative max-w-7xl mx-auto">
            <!-- Navigation Arrows -->
            <button id="prevBtn" class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button id="nextBtn" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Cards Container -->
            <div class="overflow-hidden mx-16">
                <div id="portfolioSlider" class="flex transition-transform duration-500 ease-in-out">
                    <!-- Cards will be dynamically populated here -->
                </div>
            </div>

            <!-- Dots Indicator -->
            <div id="dotsContainer" class="flex justify-center mt-8 space-x-2">
                <!-- Dots will be dynamically populated here -->
            </div>
        </div>

        <!-- View All Projects Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(url('portfolio')); ?>" class="inline-flex items-center bg-yellow-400 hover:bg-yellow-300 text-gray-900 px-8 py-3 rounded-lg font-semibold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-4-4m4 4l-4 4"></path>
                </svg>
                VIEW ALL PROJECTS
            </a>
        </div>
    </div>
</section>

<style>
.portfolio-card {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    text-align: center;
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 0 0.5rem;
    flex: 0 0 calc(33.333% - 1rem);
}

.portfolio-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.3);
}

.portfolio-card img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    margin: 0 auto 1.5rem;
}

.portfolio-card h3 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: white;
}

.portfolio-card p {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    margin-bottom: 2rem;
    flex-grow: 1;
}

.portfolio-card .view-more-btn {
    background: white;
    color: #4F46E5;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.portfolio-card .view-more-btn:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
        margin-left: 3rem;
        margin-right: 3rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Portfolio data from database
    let portfolioData = [];
    
    try {
        <?php
            $projects = \App\Models\Project::active()->ordered()->get();
            $portfolioDataArray = $projects->map(function($project) {
                return [
                    'id' => $project->id_project ?? 0,
                    'image' => $project->main_image ?? asset('images/placeholder/project-placeholder.jpg'),
                    'title' => $project->project_name ?? 'Untitled Project',
                    'description' => $project->summary_description ?? 'The lorem text the section contains header having open andclose functionality.',
                    'link' => $project->slug_project ? route('project.public.show', $project->slug_project) : '#'
                ];
            });
        ?>
        portfolioData = <?php echo json_encode($portfolioDataArray, 15, 512) ?>;
    } catch (error) {
        console.log('Loading sample data...');
        portfolioData = [];
    }

    // Add sample data if we have less than 10 items
    const sampleProjects = [
        {
            id: 1,
            image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face",
            title: "David Dell",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 2,
            image: "https://images.unsplash.com/photo-1494790108755-2616b25a9d7e?w=80&h=80&fit=crop&crop=face",
            title: "Rose Bush",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 3,
            image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face",
            title: "Jones Gail",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 4,
            image: "https://images.unsplash.com/photo-1519345182560-3f2917c472ef?w=80&h=80&fit=crop&crop=face",
            title: "Alex Morgan",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 5,
            image: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&h=80&fit=crop&crop=face",
            title: "Sarah Wilson",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 6,
            image: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face",
            title: "Mike Johnson",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 7,
            image: "https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?w=80&h=80&fit=crop&crop=face",
            title: "Emma Davis",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 8,
            image: "https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?w=80&h=80&fit=crop&crop=face",
            title: "Tom Anderson",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 9,
            image: "https://images.unsplash.com/photo-1502323777036-f29e3972d82f?w=80&h=80&fit=crop&crop=face",
            title: "Lisa Brown",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        },
        {
            id: 10,
            image: "https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=80&h=80&fit=crop&crop=face",
            title: "Chris Taylor",
            description: "The lorem text the section contains header having open andclose functionality.",
            link: "#"
        }
    ];

    // Fill up to 10 items
    while (portfolioData.length < 10) {
        const sampleIndex = portfolioData.length % sampleProjects.length;
        portfolioData.push(sampleProjects[sampleIndex]);
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
        totalSlides = Math.ceil(portfolioData.length / cardsPerView);
    }

    function renderCards() {
        const slider = document.getElementById('portfolioSlider');
        if (!slider) return;
        
        slider.innerHTML = '';

        portfolioData.forEach((project, index) => {
            const card = document.createElement('div');
            card.className = 'portfolio-card';
            card.innerHTML = `
                <div>
                    <img src="${project.image}" alt="${project.title}" onerror="this.src='https://via.placeholder.com/80x80/4F46E5/FFFFFF?text=${project.title.charAt(0)}'">
                    <h3>${project.title}</h3>
                    <p>${project.description}</p>
                </div>
                <a href="${project.link}" class="view-more-btn">View More</a>
            `;
            slider.appendChild(card);
        });
    }

    function renderDots() {
        const dotsContainer = document.getElementById('dotsContainer');
        if (!dotsContainer) return;
        
        dotsContainer.innerHTML = '';

        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = `dot ${i === 0 ? 'active' : ''}`;
            dot.addEventListener('click', () => goToSlide(i));
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
    
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Auto-play
    let autoPlayInterval = setInterval(nextSlide, 5000);

    // Pause auto-play on hover
    const portfolioSection = document.getElementById('portfolio');
    if (portfolioSection) {
        portfolioSection.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });
        
        portfolioSection.addEventListener('mouseleave', () => {
            autoPlayInterval = setInterval(nextSlide, 5000);
        });
    }

    // Initialize
    updateCardsPerView();
    renderCards();
    renderDots();
    updateSlider();

    // Handle window resize
    window.addEventListener('resize', () => {
        updateCardsPerView();
        renderDots();
        updateSlider();
    });
});
</script><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/partials/portfolio.blade.php ENDPATH**/ ?>