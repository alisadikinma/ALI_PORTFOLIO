import './bootstrap';
import { NavigationManager } from './modules/navigation';
import { PortfolioManager } from './modules/portfolio';
import { GalleryManager } from './modules/gallery';
import { TestimonialManager } from './modules/testimonials';

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Portfolio App Initializing...');
    
    try {
        new NavigationManager();
        new PortfolioManager();
        new GalleryManager();
        new TestimonialManager();
        
        console.log('Portfolio App Initialized Successfully!');
    } catch (error) {
        console.error('Error initializing Portfolio App:', error);
    }
});

// Performance monitoring
window.addEventListener('load', () => {
    const perfData = performance.getEntriesByType("navigation")[0];
    const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
    
    if (loadTime > 3000) {
        console.warn(`Page load time: ${loadTime}ms - Consider optimization`);
    }
});
