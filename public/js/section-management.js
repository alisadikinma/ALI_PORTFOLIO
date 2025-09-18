// SIMPLE SECTION DRAG & DROP - VERSION 3.0
// Fokus pada reliability dan kesederhanaan

console.log('🚀 Loading Simple Section Drag & Drop v3.0...');

let sortableInstance = null;

// Wait for everything to load
function initializeDragDrop() {
    console.log('🔧 Initializing drag & drop...');
    
    const container = document.getElementById('sortable-sections');
    
    if (!container) {
        console.error('❌ Container #sortable-sections not found!');
        return;
    }
    
    console.log('✅ Container found with', container.children.length, 'sections');
    
    // Ensure Sortable library is loaded
    if (typeof Sortable === 'undefined') {
        console.error('❌ Sortable.js library not loaded!');
        setTimeout(initializeDragDrop, 500); // Retry in 500ms
        return;
    }
    
    try {
        // Destroy existing instance
        if (sortableInstance) {
            sortableInstance.destroy();
            console.log('🗑️ Destroyed previous instance');
        }
        
        // Create new sortable instance
        sortableInstance = new Sortable(container, {
            handle: '.drag-handle',
            animation: 300,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            
            onStart: function(evt) {
                console.log('🎯 Drag started on item:', evt.item.dataset.section);
                document.body.style.cursor = 'grabbing';
                evt.item.style.opacity = '0.7';
            },
            
            onEnd: function(evt) {
                console.log('✅ Drag ended - from', evt.oldIndex + 1, 'to', evt.newIndex + 1);
                document.body.style.cursor = 'default';
                evt.item.style.opacity = '1';
                
                // Only update if position changed
                if (evt.oldIndex !== evt.newIndex) {
                    updateSectionOrder();
                    showSuccessMessage('Section order updated! Don\'t forget to save.');
                }
            }
        });
        
        console.log('✅ Sortable initialized successfully!');
        
        // Initialize form handling
        setupFormSubmission();
        
        // Show ready message
        showSuccessMessage('Drag & Drop ready! Use the ⋮⋮ handles to reorder sections.');
        
    } catch (error) {
        console.error('❌ Error creating sortable:', error);
        showErrorMessage('Failed to initialize drag & drop: ' + error.message);
    }
}

// Update section order numbers and hidden inputs
function updateSectionOrder() {
    console.log('🔄 Updating section order...');
    
    const sections = document.querySelectorAll('.section-item');
    
    sections.forEach((section, index) => {
        const newOrder = index + 1;
        
        // Update visible sequence number
        const sequenceEl = section.querySelector('.sequence-number');
        if (sequenceEl) {
            sequenceEl.textContent = newOrder;
        }
        
        // Update hidden input for form submission
        const orderInput = section.querySelector('.order-input');
        if (orderInput) {
            orderInput.value = newOrder;
            console.log(`  ${section.dataset.section}: ${newOrder}`);
        }
    });
    
    console.log('✅ Section order updated');
}

// Show success message
function showSuccessMessage(text) {
    showMessage(text, 'success');
}

// Show error message
function showErrorMessage(text) {
    showMessage(text, 'error');
}

// Generic message function
function showMessage(text, type) {
    // Remove existing message
    const existing = document.querySelector('.status-message');
    if (existing) {
        existing.remove();
    }
    
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const message = document.createElement('div');
    message.className = `status-message fixed top-4 right-4 ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm`;
    message.innerHTML = `
        <div class="flex items-center space-x-2">
            <span>💬</span>
            <span>${text}</span>
        </div>
    `;
    
    document.body.appendChild(message);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (message.parentNode) {
            message.remove();
        }
    }, 4000);
}

// Setup form submission
function setupFormSubmission() {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    if (!form || !submitBtn) {
        console.error('❌ Form or submit button not found');
        return;
    }
    
    form.addEventListener('submit', function(e) {
        console.log('📤 Form submitting...');
        
        // Final update before submission
        updateSectionOrder();
        
        // Show loading state
        submitBtn.innerHTML = '💾 Saving...';
        submitBtn.disabled = true;
        
        showMessage('Saving section settings...', 'info');
    });
    
    console.log('✅ Form submission setup complete');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDragDrop);
} else {
    // DOM already loaded
    initializeDragDrop();
}

// Also try to initialize on window load as backup
window.addEventListener('load', function() {
    if (!sortableInstance) {
        console.log('🔄 Backup initialization on window load...');
        initializeDragDrop();
    }
});

console.log('📝 Section management script v3.0 loaded');

// Global debug function for testing
window.testSortable = function() {
    if (!sortableInstance) {
        alert('❌ Sortable not initialized yet!');
        return;
    }
    
    const container = document.getElementById('sortable-sections');
    const sections = container.querySelectorAll('.section-item');
    
    if (sections.length < 2) {
        alert('❌ Need at least 2 sections to test!');
        return;
    }
    
    // Move first section to end
    container.appendChild(sections[0]);
    updateSectionOrder();
    showSuccessMessage('Test completed - first section moved to end!');
    
    console.log('🧪 Test completed');
};
