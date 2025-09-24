export class TestimonialManager {
    constructor() {
        this.slider = document.getElementById("testimonialSlider");
        this.dotsContainer = document.getElementById("testimonialDots");
        this.currentIndex = 0;
        this.slideInterval = null;
        this.autoSlideDelay = 5000;
        
        this.init();
    }
    
    init() {
        if (!this.slider || !this.dotsContainer) return;
        
        this.renderDots();
        this.showSlide(0);
        this.initEventListeners();
        this.startAutoSlide();
    }
    
    getTotalPages() {
        const itemsPerPage = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 640 ? 2 : 1;
        return Math.ceil(this.slider.children.length / itemsPerPage);
    }
    
    renderDots() {
        this.dotsContainer.innerHTML = "";
        const totalPages = this.getTotalPages();
        
        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement("span");
            dot.className = "dot w-3 h-3 rounded-full bg-gray-500 inline-block cursor-pointer transition-colors duration-300 hover:bg-gray-300";
            dot.addEventListener("click", () => {
                this.showSlide(i);
                this.stopAutoSlide();
                this.startAutoSlide();
            });
            this.dotsContainer.appendChild(dot);
        }
    }
    
    showSlide(index) {
        const wrapper = this.slider.parentElement;
        const wrapperWidth = wrapper.offsetWidth;
        const totalPages = this.getTotalPages();
        
        if (index < 0) index = totalPages - 1;
        if (index >= totalPages) index = 0;
        
        this.currentIndex = index;
        const offset = -index * wrapperWidth;
        this.slider.style.transform = `translateX(${offset}px)`;
        
        // Update dots
        const dots = this.dotsContainer.querySelectorAll(".dot");
        dots.forEach((dot, i) => {
            dot.classList.toggle("bg-yellow-400", i === index);
            dot.classList.toggle("bg-gray-500", i !== index);
        });
    }
    
    startAutoSlide() {
        if (this.getTotalPages() <= 1) return;
        
        this.slideInterval = setInterval(() => {
            this.showSlide(this.currentIndex + 1);
        }, this.autoSlideDelay);
    }
    
    stopAutoSlide() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
            this.slideInterval = null;
        }
    }
    
    initEventListeners() {
        // Pause on hover
        this.slider.addEventListener('mouseenter', () => this.stopAutoSlide());
        this.slider.addEventListener('mouseleave', () => this.startAutoSlide());
        
        // Update on resize
        let resizeTimeout;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.renderDots();
                this.showSlide(this.currentIndex);
            }, 100);
        });
        
        // Touch/swipe support
        let startX = 0;
        let currentX = 0;
        
        this.slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });
        
        this.slider.addEventListener('touchmove', (e) => {
            currentX = e.touches[0].clientX;
        });
        
        this.slider.addEventListener('touchend', () => {
            const diff = startX - currentX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.showSlide(this.currentIndex + 1);
                } else {
                    this.showSlide(this.currentIndex - 1);
                }
                this.stopAutoSlide();
                this.startAutoSlide();
            }
        });
    }
}