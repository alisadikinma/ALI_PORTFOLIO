{{--
PHASE 3: MODERN HERO SECTION
Enhanced Gen Z Design with Performance Optimization
--}}

<section id="home" class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Enhanced Background with Particle Effects --}}
    <div class="hero-background absolute inset-0">
        {{-- Gradient Orbs --}}
        <div class="gradient-orb gradient-orb-1"></div>
        <div class="gradient-orb gradient-orb-2"></div>
        <div class="gradient-orb gradient-orb-3"></div>

        {{-- Floating Elements --}}
        <div class="floating-elements">
            <div class="floating-element floating-element-1" data-parallax="0.2">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="floating-element floating-element-2" data-parallax="0.3">
                <i class="fas fa-star"></i>
            </div>
            <div class="floating-element floating-element-3" data-parallax="0.1">
                <i class="fas fa-bolt"></i>
            </div>
        </div>

        {{-- Geometric Patterns --}}
        <div class="geometric-patterns">
            <svg class="pattern pattern-1" viewBox="0 0 100 100">
                <polygon points="50,0 100,50 50,100 0,50" fill="url(#gradient1)" opacity="0.1"/>
            </svg>
            <svg class="pattern pattern-2" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="30" fill="url(#gradient2)" opacity="0.1"/>
            </svg>
        </div>

        {{-- SVG Gradients --}}
        <svg width="0" height="0">
            <defs>
                <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#8b5cf6;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    {{-- Main Content Container --}}
    <div class="hero-content relative z-10 container-professional">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

            {{-- Profile Section with Advanced Effects --}}
            <div class="profile-section w-full max-w-md lg:max-w-lg">
                {{-- Profile Image Container --}}
                <div class="profile-container relative">
                    {{-- Floating Decorations --}}
                    <div class="decoration decoration-1 animate-float">
                        <div class="w-8 h-8 bg-neon-yellow rounded-full"></div>
                    </div>
                    <div class="decoration decoration-2 animate-float" style="animation-delay: 1s;">
                        <div class="w-6 h-6 bg-cyber-pink rounded-full"></div>
                    </div>
                    <div class="decoration decoration-3 animate-float" style="animation-delay: 2s;">
                        <div class="w-4 h-4 bg-aurora-blue rounded-full"></div>
                    </div>

                    {{-- Main Profile Image --}}
                    <div class="profile-image-wrapper relative group">
                        <div class="profile-glow"></div>
                        <img src="{{ asset('favicon/' . $konf->favicon_setting) }}"
                             alt="{{ $konf->pimpinan_setting }} - Digital Transformation Consultant"
                             class="profile-image w-full h-auto rounded-3xl shadow-glow-purple group-hover:shadow-glow-pink transition-all duration-500"
                             loading="eager" />

                        {{-- Hover Overlay --}}
                        <div class="profile-overlay absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-electric-purple/30 via-transparent to-cyber-pink/20 rounded-3xl"></div>
                        </div>
                    </div>

                    {{-- Status Indicator --}}
                    <div class="status-indicator absolute bottom-4 right-4 flex items-center gap-2 glass-card px-3 py-2 rounded-full">
                        <div class="w-3 h-3 bg-neon-green rounded-full animate-pulse-glow"></div>
                        <span class="text-xs text-white font-medium">Available for Projects</span>
                    </div>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="content-section flex flex-col gap-8 text-center lg:text-left max-w-2xl">

                {{-- Greeting with Animation --}}
                <div class="greeting-section animate-slide-in-left">
                    <div class="greeting-badge inline-flex items-center gap-2 glass-card px-4 py-2 rounded-full mb-4">
                        <span class="text-2xl animate-bounce">👋</span>
                        <span class="text-neon-yellow font-medium">Hey there! I'm</span>
                    </div>
                </div>

                {{-- Name and Title --}}
                <div class="name-section animate-fade-in" style="animation-delay: 0.3s;">
                    <h1 class="hero-title text-display text-white mb-4">
                        <span class="name-text">{{ $konf->pimpinan_setting ?? 'Ali Sadikin' }}</span>
                        <br />
                        <span class="title-text text-gradient-hero animate-gradient">
                            Digital Transformation Expert
                        </span>
                    </h1>

                    {{-- Subtitle with Typing Effect --}}
                    <div class="subtitle-container">
                        <p class="subtitle text-hero-subtitle text-neon-yellow font-semibold">
                            <span id="typing-text">Manufacturing AI Specialist</span>
                            <span class="typing-cursor">|</span>
                        </p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="description-section animate-slide-up" style="animation-delay: 0.6s;">
                    <p class="hero-description text-modern-body text-xl text-gray-300 leading-relaxed">
                        <span class="highlight-text">16+ years</span> of transforming manufacturing operations with
                        <span class="highlight-text">AI-powered solutions</span>.
                        From traditional production lines to
                        <span class="highlight-text">smart factories</span> –
                        I help companies embrace Industry 4.0.
                    </p>

                    {{-- Achievement Highlights --}}
                    <div class="achievements-grid grid grid-cols-3 gap-4 mt-6">
                        <div class="achievement-item text-center">
                            <div class="achievement-number text-2xl font-bold text-neon-green" data-counter="54">54</div>
                            <div class="achievement-label text-sm text-gray-400">K+ Followers</div>
                        </div>
                        <div class="achievement-item text-center">
                            <div class="achievement-number text-2xl font-bold text-cyber-pink" data-counter="250">250</div>
                            <div class="achievement-label text-sm text-gray-400">K+ Saved</div>
                        </div>
                        <div class="achievement-item text-center">
                            <div class="achievement-number text-2xl font-bold text-aurora-blue" data-counter="99">99</div>
                            <div class="achievement-label text-sm text-gray-400">% Success</div>
                        </div>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="cta-section flex flex-col sm:flex-row gap-4 animate-slide-up" style="animation-delay: 0.9s;">
                    {{-- Primary CTA --}}
                    <a href="{{ !empty($konf->primary_button_link) ? $konf->primary_button_link : '#portfolio' }}"
                       target="{{ !empty($konf->primary_button_link) && Str::startsWith($konf->primary_button_link, 'http') ? '_blank' : '_self' }}"
                       class="btn-cyber btn-lg group magnetic">
                        <span class="relative z-10 flex items-center gap-3">
                            <i class="fas fa-rocket"></i>
                            {{ $konf->primary_button_title ?? 'View My Work' }}
                        </span>
                        @if(!empty($konf->primary_button_link) && Str::startsWith($konf->primary_button_link, 'http'))
                            <i class="fas fa-external-link-alt ml-2 text-sm relative z-10"></i>
                        @endif
                    </a>

                    {{-- Secondary CTA --}}
                    <a href="{{ !empty($konf->secondary_button_link) ? $konf->secondary_button_link : '#contact' }}"
                       target="{{ !empty($konf->secondary_button_link) && Str::startsWith($konf->secondary_button_link, 'http') ? '_blank' : '_self' }}"
                       class="btn-glass btn-lg group magnetic">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-comments"></i>
                            {{ $konf->secondary_button_title ?? "Let's Talk" }}
                        </span>
                    </a>
                </div>

                {{-- Social Proof --}}
                <div class="social-proof-section animate-fade-in" style="animation-delay: 1.2s;">
                    <div class="social-proof-container glass-card p-4 rounded-xl">
                        <p class="text-sm text-gray-400 mb-2">Trusted by industry leaders</p>
                        <div class="social-icons flex justify-center lg:justify-start gap-4">
                            <a href="https://linkedin.com/in/{{ $konf->linkedin_setting }}"
                               target="_blank"
                               class="social-icon-modern group">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://instagram.com/{{ $konf->instagram_setting }}"
                               target="_blank"
                               class="social-icon-modern group">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://youtube.com/{{ $konf->youtube_setting }}"
                               target="_blank"
                               class="social-icon-modern group">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://tiktok.com/@{{ $konf->tiktok_setting }}"
                               target="_blank"
                               class="social-icon-modern group">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce-gentle">
        <div class="scroll-arrow glass-card p-3 rounded-full">
            <i class="fas fa-chevron-down text-neon-yellow"></i>
        </div>
    </div>
</section>

{{-- Enhanced Styles --}}
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--dark-surface) 0%, var(--card-surface) 50%, #0f172a 100%);
        position: relative;
        overflow: hidden;
    }

    /* Background Effects */
    .gradient-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
        animation: float-gentle 8s ease-in-out infinite;
    }

    .gradient-orb-1 {
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, var(--electric-purple) 0%, transparent 70%);
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .gradient-orb-2 {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, var(--cyber-pink) 0%, transparent 70%);
        bottom: 10%;
        right: 10%;
        animation-delay: 2s;
    }

    .gradient-orb-3 {
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, var(--aurora-blue) 0%, transparent 70%);
        top: 50%;
        left: 60%;
        animation-delay: 4s;
    }

    /* Floating Elements */
    .floating-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .floating-element {
        position: absolute;
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-yellow);
        font-size: 24px;
        animation: float-gentle 6s ease-in-out infinite;
    }

    .floating-element-1 {
        top: 20%;
        right: 20%;
        animation-delay: 1s;
    }

    .floating-element-2 {
        bottom: 30%;
        left: 15%;
        animation-delay: 3s;
    }

    .floating-element-3 {
        top: 60%;
        right: 40%;
        animation-delay: 5s;
    }

    /* Geometric Patterns */
    .geometric-patterns {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .pattern {
        position: absolute;
        width: 200px;
        height: 200px;
        animation: rotate-slow 20s linear infinite;
    }

    .pattern-1 {
        top: 15%;
        right: 25%;
    }

    .pattern-2 {
        bottom: 20%;
        left: 20%;
        animation-direction: reverse;
    }

    /* Profile Section */
    .profile-container {
        position: relative;
        max-width: 400px;
        margin: 0 auto;
    }

    .decoration {
        position: absolute;
        z-index: 2;
    }

    .decoration-1 {
        top: -12px;
        right: -12px;
    }

    .decoration-2 {
        bottom: 20%;
        left: -20px;
    }

    .decoration-3 {
        top: 50%;
        right: -15px;
    }

    .profile-image-wrapper {
        position: relative;
        transform: rotate(-2deg);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .profile-image-wrapper:hover {
        transform: rotate(0deg) scale(1.05);
    }

    .profile-glow {
        position: absolute;
        inset: -20px;
        background: linear-gradient(45deg, var(--electric-purple), var(--cyber-pink), var(--aurora-blue));
        border-radius: 2rem;
        opacity: 0.3;
        filter: blur(20px);
        animation: pulse-glow 3s ease-in-out infinite;
    }

    .status-indicator {
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Typography */
    .hero-title {
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .name-text {
        display: block;
        margin-bottom: 0.5rem;
    }

    .title-text {
        background: linear-gradient(135deg, var(--electric-purple) 0%, var(--cyber-pink) 50%, var(--aurora-blue) 100%);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 4s ease infinite;
    }

    /* Typing Effect */
    .typing-cursor {
        animation: typing-blink 1s infinite;
    }

    @keyframes typing-blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }

    /* Highlight Text */
    .highlight-text {
        color: var(--neon-yellow);
        font-weight: 600;
        position: relative;
    }

    .highlight-text::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--neon-yellow), transparent);
        opacity: 0.6;
    }

    /* Achievement Grid */
    .achievement-item {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .achievement-item:hover {
        transform: translateY(-4px);
    }

    /* Social Icons */
    .social-icon-modern {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-yellow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .social-icon-modern:hover {
        background: var(--neon-yellow);
        color: #000;
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 10px 20px rgba(251, 191, 36, 0.4);
    }

    /* Scroll Indicator */
    .scroll-indicator {
        animation: bounce-gentle 2s ease-in-out infinite;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: clamp(2rem, 6vw, 3.5rem);
        }

        .floating-element {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .pattern {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 768px) {
        .gradient-orb {
            width: 200px !important;
            height: 200px !important;
        }

        .achievements-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .cta-section {
            flex-direction: column;
        }

        .decoration {
            display: none;
        }
    }
</style>

{{-- JavaScript for Enhanced Interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Typing effect
    const typingTexts = [
        'Manufacturing AI Specialist',
        'Digital Transformation Expert',
        'Industry 4.0 Consultant',
        'Smart Factory Engineer'
    ];

    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const typingElement = document.getElementById('typing-text');

    function typeText() {
        const currentText = typingTexts[textIndex];

        if (isDeleting) {
            typingElement.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typingElement.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;
        }

        let typeSpeed = 100;

        if (isDeleting) {
            typeSpeed = 50;
        }

        if (!isDeleting && charIndex === currentText.length) {
            typeSpeed = 2000;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            textIndex = (textIndex + 1) % typingTexts.length;
            typeSpeed = 500;
        }

        setTimeout(typeText, typeSpeed);
    }

    typeText();

    // Parallax effect for floating elements
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax);
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Interactive hover effects
    const magneticElements = document.querySelectorAll('.magnetic');

    magneticElements.forEach(element => {
        element.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            this.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
        });

        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0px, 0px)';
        });
    });
});
</script>