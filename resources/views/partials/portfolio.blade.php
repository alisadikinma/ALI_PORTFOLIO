<<<<<<< HEAD
{{-- Portfolio Section with 3D Coverflow Slider - REVISED --}}
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
=======
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
>>>>>>> 63027871ae323267b47379017adb239bab443d93
                </svg>
            </button>
            
            <button id="nextBtn" class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
<<<<<<< HEAD
        </div>
        
        <!-- Centered Portfolio Dots -->
        <div class="portfolio-dots d-flex justify-content-center mt-4 mb-4" id="portfolioDots">
            {{-- Dots will be populated by JavaScript --}}
        </div>
        
        <!-- View All Projects Button -->
        <div class="text-center">
            <a href="{{ url('portfolio') }}" class="view-all-btn">
                <span>View All Projects</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
=======

            <!-- Cards Container -->
            <div class="overflow-hidden mx-16">
                <div id="portfolioSlider" class="flex transition-transform duration-500 ease-in-out">
                    <!-- Cards will be dynamically populated here -->
                </div>
>>>>>>> 63027871ae323267b47379017adb239bab443d93
            </div>

            <!-- Dots Indicator -->
            <div id="dotsContainer" class="flex justify-center mt-8 space-x-2">
                <!-- Dots will be dynamically populated here -->
            </div>
        </div>

        <!-- View All Projects Button -->
        <div class="text-center mt-12">
            <a href="{{ url('portfolio') }}" class="inline-flex items-center bg-yellow-400 hover:bg-yellow-300 text-gray-900 px-8 py-3 rounded-lg font-semibold transition-colors">
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
<<<<<<< HEAD

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
        transform: translateX(-160px) rotateY(45deg) scale(0.9);
        filter: brightness(0.8);
    }

    .coverflow-card.right-1 {
        z-index: 9;
        transform: translateX(160px) rotateY(-45deg) scale(0.9);
        filter: brightness(0.8);
    }

    .coverflow-card.left-2 {
        z-index: 8;
        transform: translateX(-300px) rotateY(60deg) scale(0.7);
        filter: brightness(0.6);
        opacity: 0.8;
    }

    .coverflow-card.right-2 {
        z-index: 8;
        transform: translateX(300px) rotateY(-60deg) scale(0.7);
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
        background: rgba(15, 23, 42, 0.8);
        text-align: center;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: bold;
        color: #f59e0b;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    /* NEW: Card Summary Description */
    .card-summary {
        font-size: 0.9rem;
        color: #e2e8f0;
        margin-bottom: 10px;
        line-height: 1.4;
        opacity: 0.9;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-category {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-tech-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .tech-tag {
        font-size: 0.7rem;
        padding: 3px 8px;
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border-radius: 10px;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* NEW: View Project Button in Card */
    .card-view-btn {
        background: linear-gradient(45deg, #f59e0b, #eab308);
        color: #0f172a;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-block;
        margin-top: auto;
    }

    .card-view-btn:hover {
        background: linear-gradient(45deg, #eab308, #f59e0b);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4);
        color: #0f172a;
        text-decoration: none;
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

    /* Portfolio Dots - CENTERED */
    .portfolio-dots {
        gap: 12px;
        margin: 30px auto;
        display: flex !important;
        justify-content: center !important;
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

    /* NEW: View All Projects Button */
    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(245, 158, 11, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 2px solid #f59e0b;
        color: #f59e0b;
        padding: 15px 30px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 20px;
    }

    .view-all-btn:hover {
        background: #f59e0b;
        color: #0f172a;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        text-decoration: none;
    }

    .view-all-btn svg {
        width: 20px;
        height: 20px;
        transition: transform 0.3s ease;
    }

    .view-all-btn:hover svg {
        transform: translateX(3px);
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
=======
    
    .overflow-hidden.mx-16 {
        margin-left: 3rem;
        margin-right: 3rem;
>>>>>>> 63027871ae323267b47379017adb239bab443d93
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Portfolio data from database
    let portfolioData = [];
    
    try {
        @php
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
        @endphp
        portfolioData = @json($portfolioDataArray);
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

<<<<<<< HEAD
        // Clear existing content
        coverflowWrapper.innerHTML = '';
        portfolioDots.innerHTML = '';

        // Create coverflow cards - REVISED
        portfolioData.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'coverflow-card';
            card.dataset.index = index;
            
            const techStackHTML = item.tech_stack && item.tech_stack.length > 0 
                ? `<div class="card-tech-stack">
                     ${item.tech_stack.slice(0, 3).map(tech => `<span class="tech-tag">${tech.trim()}</span>`).join('')}
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
                    <p class="card-summary">${item.summary}</p>
                    <p class="card-category">${item.category}</p>
                    ${techStackHTML}
                    <a href="${item.link}" class="card-view-btn">View Project</a>
                </div>
            `;

            card.addEventListener('click', (e) => {
                // Don't trigger slide change if clicking on the button
                if (e.target.classList.contains('card-view-btn')) {
                    return;
                }
                
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
=======
    // Fill up to 10 items
    while (portfolioData.length < 10) {
        const sampleIndex = portfolioData.length % sampleProjects.length;
        portfolioData.push(sampleProjects[sampleIndex]);
>>>>>>> 63027871ae323267b47379017adb239bab443d93
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
</script>