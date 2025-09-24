export class PortfolioManager {
    constructor() {
        this.filterBtns = document.querySelectorAll('.filter-btn');
        this.portfolioItems = document.querySelectorAll('.portfolio-item');
        this.init();
    }
    
    init() {
        if (!this.filterBtns.length) return;
        
        this.initFilters();
    }
    
    initFilters() {
        this.filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const filter = btn.getAttribute('data-filter');
                
                // Update active button
                this.updateActiveButton(btn);
                
                // Filter items with animation
                this.filterItems(filter);
            });
        });
    }
    
    updateActiveButton(activeBtn) {
        // Reset all buttons
        this.filterBtns.forEach(btn => {
            btn.classList.remove('bg-yellow-400', 'outline-yellow-400');
            btn.classList.add('bg-slate-800/60', 'outline-slate-500');
            
            const span = btn.querySelector('span');
            if (span) {
                span.classList.remove('text-neutral-900');
                span.classList.add('text-white');
            }
        });
        
        // Set active button
        activeBtn.classList.remove('bg-slate-800/60', 'outline-slate-500');
        activeBtn.classList.add('bg-yellow-400', 'outline-yellow-400');
        
        const activeSpan = activeBtn.querySelector('span');
        if (activeSpan) {
            activeSpan.classList.remove('text-white');
            activeSpan.classList.add('text-neutral-900');
        }
    }
    
    filterItems(filter) {
        this.portfolioItems.forEach(item => {
            const itemType = item.getAttribute('data-jenis');
            const shouldShow = filter === 'all' || itemType === filter;
            
            if (shouldShow) {
                item.style.display = 'block';
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                
                // Animate in
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 50);
            } else {
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }
}