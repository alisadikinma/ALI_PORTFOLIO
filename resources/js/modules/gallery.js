export class GalleryManager {
    constructor() {
        this.modal = document.getElementById('imageModal');
        this.modalContainer = document.getElementById('modalMediaContainer');
        this.modalCaption = document.getElementById('modalCaption');
        this.closeBtn = document.getElementById('closeModalBtn');
        this.prevBtn = document.getElementById('prevGalleryBtn');
        this.nextBtn = document.getElementById('nextGalleryBtn');
        this.currentIndex = 0;
        this.galleryData = [];
        
        this.init();
    }
    
    init() {
        if (!this.modal) return;
        
        this.initGalleryData();
        this.initEventListeners();
        this.initLazyLoading();
    }
    
    initGalleryData() {
        const images = document.querySelectorAll('#galleryGrid img.gallery-image');
        
        this.galleryData = Array.from(images).map((img) => ({
            caption: img.dataset.caption || 'Gallery Image',
            sources: [
                img.dataset.gambar,
                img.dataset.gambar1,
                img.dataset.gambar2,
                img.dataset.gambar3
            ].filter(src => src && src !== ""),
            video: img.dataset.video || null
        }));
    }
    
    initEventListeners() {
        // Gallery image clicks
        document.querySelectorAll('#galleryGrid img.gallery-image').forEach((img, index) => {
            img.addEventListener('click', () => this.openModal(index));
        });
        
        // Modal controls
        this.closeBtn?.addEventListener('click', () => this.closeModal());
        this.prevBtn?.addEventListener('click', () => this.navigate('prev'));
        this.nextBtn?.addEventListener('click', () => this.navigate('next'));
        
        // Close on backdrop click
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape': this.closeModal(); break;
                    case 'ArrowLeft': this.navigate('prev'); break;
                    case 'ArrowRight': this.navigate('next'); break;
                }
            }
        });
    }
    
    openModal(index) {
        this.currentIndex = index;
        this.renderModal();
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    closeModal() {
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex');
        this.modalContainer.innerHTML = '';
        document.body.style.overflow = '';
    }
    
    navigate(direction) {
        if (direction === 'next') {
            this.currentIndex = (this.currentIndex + 1) % this.galleryData.length;
        } else {
            this.currentIndex = (this.currentIndex - 1 + this.galleryData.length) % this.galleryData.length;
        }
        this.renderModal();
    }
    
    renderModal() {
        if (!this.modalContainer || !this.galleryData[this.currentIndex]) return;
        
        this.modalContainer.innerHTML = '';
        const data = this.galleryData[this.currentIndex];
        
        const mediaItems = [...data.sources];
        if (data.video) mediaItems.push(data.video);
        
        mediaItems.forEach(src => {
            const wrapper = document.createElement('div');
            wrapper.className = "bg-slate-900 rounded-lg overflow-hidden shadow-lg";
            
            if (src.includes('.mp4') || src.includes('.webm')) {
                const video = document.createElement('video');
                video.src = src;
                video.controls = true;
                video.className = "w-full aspect-video object-contain max-h-screen";
                wrapper.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src = src;
                img.className = "w-full h-auto object-contain max-h-screen";
                img.loading = "lazy";
                wrapper.appendChild(img);
            }
            
            this.modalContainer.appendChild(wrapper);
        });
        
        this.modalCaption.textContent = data.caption;
    }
    
    initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            img.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            imageObserver.observe(img);
        });
    }
}