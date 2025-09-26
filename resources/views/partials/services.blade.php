<!-- Services Section -->
@if(($konf->services_section_active ?? true) && isset($layanan) && $layanan->count() > 0)
<section id="services" class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <!-- Header -->
    <div class="text-center mb-12">
        <h2 class="text-yellow-400 text-4xl sm:text-6xl font-bold mb-4">
            Services
        </h2>
        <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto">
            Comprehensive AI and automation solutions for your business transformation
        </p>
    </div>

    <!-- Services Layout -->
    <div class="flex flex-col lg:flex-row gap-4 lg:gap-4 items-start">
        <!-- Left Side - Service Cards (30%) -->
        <div class="lg:w-3/10 xl:w-3/10 service-left-panel flex flex-col pl-4">
            @foreach ($layanan->where('status', 'Active')->sortBy('sequence') as $index => $row)
            <div class="service-card {{ $index == 0 ? 'active' : '' }}" 
                 data-service-id="{{ $row->id_layanan ?? $index }}"
                 data-service-type="{{ Str::slug($row->nama_layanan) }}"
                 data-image="{{ \App\Helpers\ImageHelper::optimizedAsset('file/layanan/' . $row->gambar_layanan) }}"
                 data-description="{!! htmlspecialchars($row->keterangan_layanan ?? '', ENT_QUOTES) !!}">
                <div class="service-icon">
                    @if($row->icon_layanan)
                        <img src="{{ asset('file/layanan/icons/' . $row->icon_layanan) }}" alt="{{ $row->nama_layanan }} icon" style="width: 28px; height: 28px; object-fit: contain;">
                    @else
                        @if(str_contains(strtolower($row->nama_layanan), 'gpt') || str_contains(strtolower($row->nama_layanan), 'custom'))
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        @elseif(str_contains(strtolower($row->nama_layanan), 'video'))
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        @elseif(str_contains(strtolower($row->nama_layanan), 'visual') || str_contains(strtolower($row->nama_layanan), 'inspection'))
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        @elseif(str_contains(strtolower($row->nama_layanan), 'consultation') || str_contains(strtolower($row->nama_layanan), 'speaking'))
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        @endif
                    @endif
                </div>
                <div class="service-content">
                    <h3 class="service-title">{{ $row->nama_layanan }}</h3>
                    @if($row->sub_nama_layanan)
                    <p class="service-subtitle-main">{{ $row->sub_nama_layanan }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Right Side - Content Display (70%) -->
        <div class="lg:w-7/10 xl:w-7/10 service-right-panel flex flex-col">
            <div class="service-display">
                <!-- Service Image -->
                <div class="service-image-container">
                    <img id="currentServiceImage"
                         src="{{ \App\Helpers\ImageHelper::optimizedAsset('file/layanan/' . ($layanan->where('status', 'Active')->sortBy('sequence')->first()->gambar_layanan ?? 'default.jpg')) }}"
                         alt="Service Image"
                         class="service-main-image">
                </div>
                
                <!-- Service Description -->
                <!--div class="service-description">
                    <div id="currentServiceDescription" class="description-text">
                        Loading service description...
                    </div>
                </div-->
                
                <div class="service-action">
                    <a href="{{ url('/#contact') }}" class="request-quote-btn-services">
                       REQUEST QUOTE →
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
/* Services Section Styles */
#services {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    min-height: 800px;
}

/* Responsive Services Layout */
@media (min-width: 1024px) {
    .service-left-panel {
        width: 30% !important;
        flex: 0 0 30%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: stretch;
        min-height: 500px;
        gap: 0.75rem;
        margin-top: 0;
        padding-top: 0;
        transform: translateY(32px);
    }
    
    .service-left-panel > .service-card {
        margin-bottom: 0.75rem;
    }
    
    .service-left-panel > .service-card:last-child {
        margin-bottom: 0;
    }
    
    .service-right-panel {
        width: 70% !important;
        flex: 0 0 70%;
        display: flex;
        flex-direction: column;
        justify-content: stretch;
        align-items: stretch;
        min-height: 500px;
    }
    
    .service-display {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
}

.service-card {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.6));
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 16px;
    padding: 2rem 1.8rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    min-height: 150px;
    max-height: 180px;
    margin-right: 0.1rem;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(145deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.service-card:hover,
.service-card.active {
    border-color: #fbbf24;
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(251, 191, 36, 0.3);
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.8));
}

.service-card:hover::before,
.service-card.active::before {
    opacity: 1;
}

.service-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 16px rgba(251, 191, 36, 0.4);
    margin-left: 0.5rem;
    margin-right: 0.5rem;
}

.service-icon svg {
    width: 28px;
    height: 28px;
    color: #1f2937;
}

.service-content {
    flex: 1;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 60px;
}

.service-title {
    color: #fbbf24;
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.375rem;
    line-height: 1.25;
}

.service-subtitle-main {
    color: #e2e8f0;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    opacity: 0.9;
}

.service-subtitle {
    color: #94a3b8;
    font-size: 1rem;
    line-height: 1.4;
    margin: 0;
    opacity: 0.9;
}

.service-display {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.6));
    border-radius: 20px;
    padding: 1.75rem;
    border: 1px solid rgba(59, 130, 246, 0.2);
    backdrop-filter: blur(10px);
    min-height: 450px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.service-image-container {
    margin-bottom: 1.2rem;
    border-radius: 16px;
    overflow: hidden;
    background: #0f172a;
    position: relative;
    margin-top: 0;
}

.service-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Back to cover for full frame fit */
    transition: all 0.5s ease;
    border-radius: 16px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%);
}

.service-description {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.6rem;
    font-size: 1.2rem;
}

.description-text {
    color: #94a3b8;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 0.7rem;
    text-align: left;
}

.service-action {
    display: flex;
    justify-content: flex-start;
    margin-top: 1.5rem;
}

.request-quote-btn-services {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #1f2937;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 4px 16px rgba(251, 191, 36, 0.3);
}

.request-quote-btn-services:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(251, 191, 36, 0.4);
}

/* Responsive Design */
@media (max-width: 1024px) {
    /* Tablet and mobile: full width stacked */
    .service-left-panel,
    .service-right-panel {
        width: 100% !important;
        flex: 0 0 100%;
        transform: none;
        margin-top: 0;
        padding-top: 0;
    }
    
    .service-card {
        padding: 1.25rem;
    }
    
    .service-icon {
        width: 40px;
        height: 40px;
    }
    
    .service-icon svg {
        width: 20px;
        height: 20px;
    }
    
    .service-title {
        font-size: 1.125rem;
    }
    
    .service-main-image {
        height: 250px; /* Mobile optimization for 1080x608 */
    }
}

@media (max-width: 480px) {
    .service-main-image {
        height: 200px; /* Small mobile screens */
    }
}

@media (max-width: 768px) {
    #services {
        padding: 2rem 1rem;
    }
    
    .service-display {
        padding: 1.5rem;
    }
    
    .service-main-image {
        height: 200px;
    }
    
    .service-card {
        padding: 1rem;
    }
    
    .flex.flex-col.lg\:flex-row {
        flex-direction: column;
        gap: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCards = document.querySelectorAll('.service-card');
    const serviceImage = document.getElementById('currentServiceImage');
    const serviceDescription = document.getElementById('currentServiceDescription');
    
    // Add null checks to prevent errors
    if (!serviceCards.length) {
        return;
    }
    
    serviceCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            serviceCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Get data attributes with fallback
            const newImage = this.dataset.image;
            let newDescription = this.dataset.description;
            const serviceId = this.dataset.serviceId;
            
            // Update image with fade effect - ADD NULL CHECKS
            if (newImage && serviceImage && newImage !== serviceImage.src) {
                serviceImage.style.opacity = '0';
                setTimeout(() => {
                    serviceImage.src = newImage;
                    serviceImage.onload = () => {
                        if (serviceImage) serviceImage.style.opacity = '1';
                    };
                    // Fallback in case onload doesn't fire
                    setTimeout(() => {
                        if (serviceImage) serviceImage.style.opacity = '1';
                    }, 200);
                }, 150);
            }
            
            // Update description - ADD NULL CHECKS
            if (serviceDescription) {
                if (newDescription && newDescription.trim() !== '') {
                    serviceDescription.style.opacity = '0';
                    setTimeout(() => {
                        // Decode HTML entities and render as HTML
                        const decodedDescription = newDescription
                            .replace(/&quot;/g, '"')
                            .replace(/&#039;/g, "'")
                            .replace(/&lt;/g, '<')
                            .replace(/&gt;/g, '>')
                            .replace(/&amp;/g, '&');
                        if (serviceDescription) {
                            serviceDescription.innerHTML = decodedDescription;
                            serviceDescription.style.opacity = '1';
                        }
                    }, 150);
                } else {
                    // Fallback descriptions only if database has no content
                    const fallbackDescriptions = [
                        'I provide tailored AI solutions and custom GPT models designed to meet your business needs and industry requirements. From understanding your challenges to delivering a solution, I make sure the AI tools we create truly support your goals and make your processes smarter.',
                        'Work smarter, not harder with intelligent automation solutions that streamline your business processes and eliminate repetitive tasks.',
                        'Turn AI into your creative weapon with custom GPT models and intelligent content generation systems tailored for your specific needs.',
                        'Where Strategy Meets Creativity - comprehensive content creation services that blend strategic thinking with creative execution.'
                    ];
                    
                    serviceDescription.style.opacity = '0';
                    setTimeout(() => {
                        if (serviceDescription) {
                            serviceDescription.innerHTML = fallbackDescriptions[index] || fallbackDescriptions[0];
                            serviceDescription.style.opacity = '1';
                        }
                    }, 150);
                }
            }
        });
    });
    
    // Initialize first service
    if (serviceCards.length > 0) {
        // Add a small delay to ensure DOM is ready
        setTimeout(() => {
            if (serviceCards[0]) {
                serviceCards[0].click();
            }
        }, 100);
    } else {
        // Service cards found, continue initialization
    }
});
</script>
@endif