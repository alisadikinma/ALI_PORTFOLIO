@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            @if(isset($setting) && $setting->instansi_setting)
                                Manage Sections - {{ $setting->instansi_setting }}
                            @else
                                Section Visibility Management
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Homepage Sections - Drag to reorder, Toggle to show/hide</p>
                    </div>
                </div>
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

                <!-- Instructions -->
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                    <p class="text-sm">
                        <strong>Instructions:</strong> 
                        <br>• <strong>Drag sections</strong> up/down to change their order on the homepage
                        <br>• <strong>Toggle checkboxes</strong> to show/hide sections
                        <br>• <strong>Number badges</strong> show the current sequence order
                        <br>• Disabled sections will not appear on the homepage
                    </p>
                </div>

                <!-- Sortable Sections List -->
                <div id="sortable-sections" class="space-y-4 mb-8">
                    @foreach($sections as $section)
                    <div class="section-item bg-gray-50 p-4 rounded-lg border cursor-move hover:bg-gray-100 transition-colors" 
                         data-section="{{ $section->lookup_code }}"
                         data-id="{{ $section->id }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="sequence-number text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold"
                                     style="background-color: {{ $section->lookup_color ?? '#3b82f6' }}">
                                    {{ $section->sort_order }}
                                </div>
                                <div class="drag-handle text-gray-600 cursor-grab flex items-center justify-center w-10 h-10 bg-gray-200 rounded border-2 border-dashed border-gray-400 hover:border-blue-500 hover:bg-blue-50 transition-all">
                                    <i class="fas fa-grip-vertical text-lg"></i>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="{{ $section->lookup_code }}_section_active" 
                                           name="{{ $section->lookup_code }}_section_active" 
                                           value="1" 
                                           {{ $section->is_active ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <input type="hidden" 
                                           name="{{ $section->lookup_code }}_section_order" 
                                           value="{{ $section->sort_order }}"
                                           class="order-input">
                                    <label for="{{ $section->lookup_code }}_section_active" class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">
                                            <i class="{{ $section->lookup_icon ?? 'fas fa-section' }}"></i>
                                            {{ $section->lookup_name }}
                                        </span>
                                        <p class="text-xs text-gray-500">{{ $section->lookup_description }}</p>
                                    </label>
                                </div>
                            </div>
                            <div class="text-xs font-medium px-2 py-1 rounded-full"
                                 style="background-color: {{ $section->lookup_color ?? '#3b82f6' }}20; color: {{ $section->lookup_color ?? '#3b82f6' }}">
                                {{ ucfirst($section->lookup_code) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
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

<!-- Include SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Include Section Management Script -->
<script src="{{ asset('js/section-management.js') }}"></script>

<style>
/* Enhanced styling for better UX */
.section-item {
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
}

.section-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.section-item.sortable-ghost {
    opacity: 0.4;
}

.section-item.sortable-drag {
    transform: rotate(2deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.sequence-number {
    font-family: 'Courier New', monospace;
    min-width: 32px;
    transition: all 0.3s ease;
}

.drag-handle {
    transition: all 0.3s ease;
    cursor: grab !important;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    min-height: 40px;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    color: #6b7280;
}

.drag-handle:hover {
    color: #3b82f6 !important;
    background: #dbeafe !important;
    border-color: #3b82f6 !important;
    transform: scale(1.05);
}

.drag-handle:active {
    cursor: grabbing !important;
    background: #bfdbfe !important;
    transform: scale(0.98);
}

.drag-handle i {
    font-size: 18px;
    pointer-events: none;
    user-select: none;
}

.section-item {
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
    cursor: default;
}

.section-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.sortable-ghost {
    opacity: 0.4;
    background: #fef3c7 !important;
}

.sortable-chosen {
    background: #f0f9ff !important;
    border-color: #3b82f6 !important;
}

.sortable-drag {
    transform: rotate(2deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Checkbox styling */
input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    accent-color: #059669;
}

.section-item input[type="checkbox"]:checked + input + label {
    opacity: 1;
}

.section-item input[type="checkbox"]:not(:checked) + input + label {
    opacity: 0.6;
}

/* Button styling */
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

/* Responsive design */
@media (max-width: 768px) {
    .flex.items-center.justify-between {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .section-item .flex.items-center.justify-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    
    .sequence-number {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
    
    button[type="submit"] {
        width: 100% !important;
    }
}

/* Animation for when sections are being dragged */
@keyframes drag {
    0% { transform: rotate(0deg); }
    50% { transform: rotate(2deg); }
    100% { transform: rotate(0deg); }
}

.section-item.sortable-chosen {
    animation: drag 0.5s ease-in-out;
    border-color: #3b82f6;
    background-color: #f0f9ff;
}

/* Custom scrollbar for sections container */
#sortable-sections::-webkit-scrollbar {
    width: 6px;
}

#sortable-sections::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#sortable-sections::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#sortable-sections::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>

<script>
// Section Management - Clean Setup
console.log('🔗 Section management page loaded');

// Simple test function for console
window.testDrag = function() {
    if (typeof window.testSortable === 'function') {
        window.testSortable();
    } else {
        console.log('📝 Testing: Try typing testSortable() in console after page loads');
    }
};

// Log current order for debugging
window.logOrder = function() {
    const sections = document.querySelectorAll('.section-item');
    const order = Array.from(sections).map((section, index) => ({
        position: index + 1,
        section: section.dataset.section,
        orderInput: section.querySelector('.order-input')?.value
    }));
    console.table(order);
    return order;
};
</script>
@endsection