@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Section Visibility Management</h3>
                <p class="mt-1 text-sm text-gray-600">Homepage Sections</p>
            </div>

            <!-- Form -->
            <form action="{{ route('setting.sections.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif

                <!-- Sections Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- About Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="about_section_active" 
                                   name="about_section_active" 
                                   value="1" 
                                   {{ (isset($konf->about_section_active) && $konf->about_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="about_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ About Section</span>
                                <p class="text-xs text-gray-500">Show/hide about section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="services_section_active" 
                                   name="services_section_active" 
                                   value="1" 
                                   {{ (isset($konf->services_section_active) && $konf->services_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="services_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Services Section</span>
                                <p class="text-xs text-gray-500">Show/hide services section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Awards Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="awards_section_active" 
                                   name="awards_section_active" 
                                   value="1" 
                                   {{ (isset($konf->awards_section_active) && $konf->awards_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="awards_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Awards Section</span>
                                <p class="text-xs text-gray-500">Show/hide awards section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Portfolio Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="portfolio_section_active" 
                                   name="portfolio_section_active" 
                                   value="1" 
                                   {{ (isset($konf->portfolio_section_active) && $konf->portfolio_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="portfolio_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Portfolio Section</span>
                                <p class="text-xs text-gray-500">Show/hide portfolio section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Testimonials Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="testimonials_section_active" 
                                   name="testimonials_section_active" 
                                   value="1" 
                                   {{ (isset($konf->testimonials_section_active) && $konf->testimonials_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="testimonials_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Testimonials Section</span>
                                <p class="text-xs text-gray-500">Show/hide testimonials section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Gallery Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="gallery_section_active" 
                                   name="gallery_section_active" 
                                   value="1" 
                                   {{ (isset($konf->gallery_section_active) && $konf->gallery_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="gallery_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Gallery Section</span>
                                <p class="text-xs text-gray-500">Show/hide gallery section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Articles Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="articles_section_active" 
                                   name="articles_section_active" 
                                   value="1" 
                                   {{ (isset($konf->articles_section_active) && $konf->articles_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="articles_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Articles Section</span>
                                <p class="text-xs text-gray-500">Show/hide articles section on homepage</p>
                            </label>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="contact_section_active" 
                                   name="contact_section_active" 
                                   value="1" 
                                   {{ (isset($konf->contact_section_active) && $konf->contact_section_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="contact_section_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-900">✅ Contact Section</span>
                                <p class="text-xs text-gray-500">Show/hide contact section on homepage</p>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Info Note -->
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                    <p class="text-sm">
                        <strong>Note:</strong> Disabled sections will not appear on the homepage. You can use this feature to hide sections that don't have content yet or are under maintenance.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('setting.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        ← Back to Settings
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        💾 Save Section Settings
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
/* Ensure button is visible and clickable */
button[type="submit"] {
    background-color: #2563eb !important;
    color: white !important;
    padding: 12px 24px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    border: none !important;
    cursor: pointer !important;
    min-width: 200px !important;
    min-height: 48px !important;
}

button[type="submit"]:hover {
    background-color: #1d4ed8 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Make checkboxes more visible */
input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    accent-color: #059669;
}

/* Better spacing and layout */
.grid {
    gap: 1.5rem !important;
}

.bg-gray-50 {
    background-color: #f9fafb !important;
    padding: 1rem !important;
    border-radius: 8px !important;
    border: 1px solid #e5e7eb !important;
}

.bg-gray-50:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

/* Responsive design */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr !important;
    }
    
    .flex.items-center.justify-between {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    button[type="submit"] {
        width: 100% !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    // Add loading state when submitting
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '⏳ Saving...';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';
    });
    
    // Add visual feedback when checkboxes change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const container = this.closest('.bg-gray-50');
            if (this.checked) {
                container.style.borderColor = '#059669';
                container.style.backgroundColor = '#ecfdf5';
            } else {
                container.style.borderColor = '#e5e7eb';
                container.style.backgroundColor = '#f9fafb';
            }
        });
        
        // Set initial state
        const container = checkbox.closest('.bg-gray-50');
        if (checkbox.checked) {
            container.style.borderColor = '#059669';
            container.style.backgroundColor = '#ecfdf5';
        }
    });
});
</script>
@endsection