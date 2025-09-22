{{--
PHASE 3: MODERN NAVIGATION COMPONENT
Advanced responsive navigation with Livewire 3.0
--}}

<nav class="modern-navigation fixed top-0 left-0 w-full z-50 transition-all duration-300"
     :class="{ 'nav-scrolled': scrolled }"
     x-data="navigation()"
     x-init="init()">

    {{-- Navigation Background --}}
    <div class="nav-background absolute inset-0 transition-all duration-300"
         :class="scrolled ? 'glass-navbar' : 'bg-transparent'"></div>

    {{-- Navigation Content --}}
    <div class="nav-content relative z-10 container-professional py-4">
        <div class="flex items-center justify-between">

            {{-- Logo Section --}}
            <div class="nav-logo">
                <a href="{{ url('/') }}"
                   class="logo-link flex items-center gap-4 group"
                   @click="closeMobileMenu()">
                    <div class="logo-image relative">
                        <img src="{{ asset('logo/' . $konf->logo_setting) }}"
                             alt="ASM Logo"
                             class="w-12 h-12 object-contain transition-transform duration-300 group-hover:scale-110">

                        {{-- Logo Glow Effect --}}
                        <div class="logo-glow absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                             style="background: radial-gradient(circle, var(--neon-yellow) 0%, transparent 70%); filter: blur(10px);"></div>
                    </div>

                    <div class="logo-text">
                        <span class="text-xl font-bold text-white group-hover:text-neon-yellow transition-colors duration-300">
                            {{ $konf->pimpinan_setting }}
                        </span>
                        <div class="logo-subtitle text-xs text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
                            Digital Transformation Expert
                        </div>
                    </div>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <div class="nav-menu hidden lg:flex">
                <ul class="flex items-center gap-8">
                    {{-- Home Link --}}
                    <li>
                        <a href="{{ url('/') }}"
                           class="nav-link {{ request()->is('/') ? 'nav-link-active' : '' }}"
                           @click="setActiveLink($event)">
                            <span>Home</span>
                            <div class="nav-link-indicator"></div>
                        </a>
                    </li>

                    {{-- Dynamic Menu Items --}}
                    @foreach($menuItems as $item)
                        @php
                            $isActive = $this->isActiveSection($item->lookup_code);
                            $isContactButton = $item->lookup_code === 'contact';
                        @endphp

                        <li>
                            @if($isContactButton)
                                {{-- Contact CTA Button --}}
                                <a href="{{ url('/#' . $item->lookup_code) }}"
                                   class="nav-cta-button btn-cyber btn-sm group"
                                   @click="smoothScrollTo('{{ $item->lookup_code }}')">
                                    <i class="fas fa-paper-plane group-hover:rotate-12 transition-transform duration-300"></i>
                                    <span>{{ $item->lookup_name }}</span>
                                </a>
                            @else
                                {{-- Regular Navigation Link --}}
                                <a href="{{ url('/#' . $item->lookup_code) }}"
                                   class="nav-link {{ $isActive ? 'nav-link-active' : '' }}"
                                   @click="smoothScrollTo('{{ $item->lookup_code }}'); setActiveLink($event)">
                                    <span>{{ $item->lookup_name }}</span>
                                    <div class="nav-link-indicator"></div>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Mobile Menu Toggle --}}
            <div class="mobile-menu-toggle lg:hidden">
                <button @click="toggleMobileMenu()"
                        class="mobile-toggle-btn p-3 rounded-lg glass-card hover:shadow-glow transition-all duration-300"
                        :class="{ 'mobile-toggle-active': mobileMenuOpen }"
                        aria-label="Toggle mobile menu">

                    {{-- Hamburger Animation --}}
                    <div class="hamburger-lines">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation Overlay --}}
    <div class="mobile-overlay fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300"
         x-show="mobileMenuOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeMobileMenu()"
         style="display: none;"></div>

    {{-- Mobile Navigation Menu --}}
    <div class="mobile-menu fixed top-0 right-0 h-full w-80 max-w-[90vw] glass-card transform transition-transform duration-300"
         x-show="mobileMenuOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         style="display: none;">

        {{-- Mobile Menu Header --}}
        <div class="mobile-menu-header p-6 border-b border-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo/' . $konf->logo_setting) }}"
                         alt="Logo"
                         class="w-10 h-10 object-contain">
                    <div>
                        <div class="font-semibold text-white">{{ $konf->pimpinan_setting }}</div>
                        <div class="text-xs text-gray-400">Digital Expert</div>
                    </div>
                </div>

                <button @click="closeMobileMenu()"
                        class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <i class="fas fa-times text-white text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Items --}}
        <div class="mobile-menu-content p-6">
            <ul class="space-y-4">
                {{-- Home Link --}}
                <li>
                    <a href="{{ url('/') }}"
                       class="mobile-nav-link {{ request()->is('/') ? 'mobile-nav-link-active' : '' }}"
                       @click="closeMobileMenu()">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                {{-- Dynamic Menu Items --}}
                @foreach($menuItems as $item)
                    @php
                        $isActive = $this->isActiveSection($item->lookup_code);
                        $isContactButton = $item->lookup_code === 'contact';
                        $iconMap = [
                            'about' => 'fas fa-user',
                            'services' => 'fas fa-cogs',
                            'portfolio' => 'fas fa-briefcase',
                            'awards' => 'fas fa-trophy',
                            'testimonials' => 'fas fa-quote-left',
                            'gallery' => 'fas fa-images',
                            'articles' => 'fas fa-newspaper',
                            'contact' => 'fas fa-envelope'
                        ];
                        $icon = $iconMap[$item->lookup_code] ?? 'fas fa-circle';
                    @endphp

                    <li>
                        @if($isContactButton)
                            {{-- Contact CTA Button --}}
                            <a href="{{ url('/#' . $item->lookup_code) }}"
                               class="mobile-cta-button btn-cyber w-full justify-center mt-6"
                               @click="smoothScrollTo('{{ $item->lookup_code }}'); closeMobileMenu()">
                                <i class="{{ $icon }}"></i>
                                <span>{{ $item->lookup_name }}</span>
                            </a>
                        @else
                            {{-- Regular Mobile Link --}}
                            <a href="{{ url('/#' . $item->lookup_code) }}"
                               class="mobile-nav-link {{ $isActive ? 'mobile-nav-link-active' : '' }}"
                               @click="smoothScrollTo('{{ $item->lookup_code }}'); closeMobileMenu()">
                                <i class="{{ $icon }}"></i>
                                <span>{{ $item->lookup_name }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- Mobile Social Links --}}
            <div class="mobile-social mt-8 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-sm mb-4">Connect with me</p>
                <div class="flex gap-3">
                    <a href="https://linkedin.com/in/{{ $konf->linkedin_setting }}"
                       target="_blank"
                       class="social-mobile-link">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://instagram.com/{{ $konf->instagram_setting }}"
                       target="_blank"
                       class="social-mobile-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com/{{ $konf->youtube_setting }}"
                       target="_blank"
                       class="social-mobile-link">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://tiktok.com/@{{ $konf->tiktok_setting }}"
                       target="_blank"
                       class="social-mobile-link">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Enhanced Styles --}}
<style>
    .modern-navigation {
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .nav-scrolled {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    /* Logo Effects */
    .logo-link {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Navigation Links */
    .nav-link {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #e2e8f0;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0.5rem;
    }

    .nav-link:hover {
        color: var(--neon-yellow);
        background: rgba(251, 191, 36, 0.1);
    }

    .nav-link-indicator {
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--electric-purple), var(--cyber-pink));
        border-radius: 1px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link-active {
        color: var(--neon-yellow);
        background: rgba(251, 191, 36, 0.1);
    }

    .nav-link-active .nav-link-indicator {
        transform: translateX(-50%) scaleX(1);
    }

    /* Mobile Menu Toggle */
    .mobile-toggle-btn {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .hamburger-lines {
        display: flex;
        flex-direction: column;
        gap: 4px;
        width: 20px;
    }

    .hamburger-line {
        width: 100%;
        height: 2px;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }

    .mobile-toggle-active .hamburger-line:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .mobile-toggle-active .hamburger-line:nth-child(2) {
        opacity: 0;
        transform: scaleX(0);
    }

    .mobile-toggle-active .hamburger-line:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }

    /* Mobile Menu */
    .mobile-menu {
        background: rgba(26, 26, 46, 0.95);
        backdrop-filter: blur(20px);
        border-left: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mobile-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        color: #e2e8f0;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .mobile-nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, var(--electric-purple), var(--cyber-pink));
        transform: scaleY(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-nav-link:hover,
    .mobile-nav-link-active {
        background: rgba(251, 191, 36, 0.1);
        color: var(--neon-yellow);
        transform: translateX(8px);
    }

    .mobile-nav-link:hover::before,
    .mobile-nav-link-active::before {
        transform: scaleY(1);
    }

    .mobile-nav-link i {
        width: 20px;
        text-align: center;
        color: var(--aurora-blue);
    }

    .mobile-nav-link:hover i,
    .mobile-nav-link-active i {
        color: var(--neon-yellow);
    }

    /* Social Links */
    .social-mobile-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e2e8f0;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .social-mobile-link:hover {
        background: var(--neon-yellow);
        color: #000;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .nav-menu {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .mobile-menu {
            width: 100vw;
        }

        .logo-text .logo-subtitle {
            display: none;
        }
    }

    /* Scroll behavior */
    .nav-content {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-scrolled .nav-content {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
</style>

{{-- Alpine.js Component --}}
<script>
function navigation() {
    return {
        scrolled: false,
        mobileMenuOpen: false,
        activeSection: 'home',

        init() {
            this.handleScroll();
            this.observeSections();

            // Handle scroll events
            window.addEventListener('scroll', () => {
                this.handleScroll();
            });

            // Handle resize events
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.closeMobileMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.mobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });
        },

        handleScroll() {
            this.scrolled = window.scrollY > 50;
        },

        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;

            // Prevent body scroll when menu is open
            if (this.mobileMenuOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        },

        closeMobileMenu() {
            this.mobileMenuOpen = false;
            document.body.style.overflow = '';
        },

        smoothScrollTo(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                const offset = 80; // Account for fixed nav
                const elementPosition = element.offsetTop - offset;

                window.scrollTo({
                    top: elementPosition,
                    behavior: 'smooth'
                });
            }
        },

        setActiveLink(event) {
            // Remove active class from all links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('nav-link-active');
            });

            // Add active class to clicked link
            event.target.closest('.nav-link').classList.add('nav-link-active');
        },

        observeSections() {
            const sections = document.querySelectorAll('section[id]');

            if (sections.length === 0) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeSection = entry.target.id;
                        this.updateActiveNavigation(entry.target.id);
                    }
                });
            }, {
                threshold: 0.3,
                rootMargin: '-80px 0px -50% 0px'
            });

            sections.forEach(section => {
                observer.observe(section);
            });
        },

        updateActiveNavigation(sectionId) {
            // Update desktop navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('nav-link-active');
                if (link.getAttribute('href') === `/#${sectionId}` ||
                    (sectionId === 'home' && link.getAttribute('href') === '/')) {
                    link.classList.add('nav-link-active');
                }
            });

            // Update mobile navigation
            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.classList.remove('mobile-nav-link-active');
                if (link.getAttribute('href') === `/#${sectionId}` ||
                    (sectionId === 'home' && link.getAttribute('href') === '/')) {
                    link.classList.add('mobile-nav-link-active');
                }
            });
        }
    }
}
</script>