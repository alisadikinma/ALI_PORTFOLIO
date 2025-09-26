@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" id="form-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger" role="alert" aria-live="polite" aria-atomic="true">
                    <strong>Error!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close error alert">&times;</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data" id="projectForm"
                      role="form" aria-labelledby="form-title" aria-describedby="form-description">
                    <div id="form-description" class="sr-only">Form untuk membuat project portfolio baru dengan upload gambar dan detail project</div>
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="client_name">Nama Client <span class="text-danger" aria-label="required">*</span></label>
                                <input type="text" class="form-control" id="client_name" placeholder="Masukkan nama client disini...."
                                       name="client_name" value="{{ old('client_name') }}"
                                       aria-required="true" aria-describedby="client_name_help">
                                <small id="client_name_help" class="form-text text-muted sr-only">Field wajib diisi. Masukkan nama lengkap client atau perusahaan.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="location">Lokasi Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Masukkan alamat client disini...." value="{{ old('location') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="project_name">Nama Project <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="project_name" placeholder="Masukkan Judul Project disini...." name="project_name" value="{{ old('project_name') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_category">Kategori Project <span class="text-danger">*</span></label>
                                <select name="project_category" id="project_category" class="form-control">
                                    <option value="">Pilih Kategori Project</option>
                                    @foreach($projectCategories as $category)
                                        <option value="{{ $category->lookup_name }}" {{ old('project_category') == $category->lookup_name ? 'selected' : '' }}>
                                            {{ $category->lookup_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="url_project">URL Project</label>
                                <input type="url" class="form-control" id="url_project" placeholder="https://example.com" name="url_project" value="{{ old('url_project') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug_project">Slug Project <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="slug_project" name="slug_project" placeholder="auto-generated-slug" value="{{ old('slug_project') }}" readonly>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Slug akan otomatis dihasilkan dari Nama Project + Kategori. 
                            <button type="button" id="editSlugBtn" class="btn btn-link btn-sm p-0 ml-2">
                                <i class="fas fa-edit"></i> Edit Manual
                            </button>
                            <button type="button" id="autoSlugBtn" class="btn btn-link btn-sm p-0 ml-2" style="display: none;">
                                <i class="fas fa-sync"></i> Auto Generate
                            </button>
                        </small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sequence">Urutan Tampilan</label>
                        <input type="number" class="form-control" id="sequence" name="sequence" value="{{ old('sequence', 0) }}" min="0">
                        <small class="form-text text-muted">Angka kecil akan ditampilkan lebih dulu</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="summary_description">Summary Description <span class="text-danger">*</span></label>
                        <textarea name="summary_description" id="summary_description" cols="30" rows="3" class="form-control" placeholder="Masukkan deskripsi singkat untuk tampilan portfolio...">{{ old('summary_description') }}</textarea>
                        <small class="form-text text-muted">Deskripsi singkat ini akan tampil di portfolio slider</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Detail Project <span class="text-danger">*</span></label>
                        
                        <!-- Loading indicator for editor -->
                        <div id="editor-loading" class="editor-loading mb-3">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Loading CKEditor 5...</span>
                        </div>
                        
                        <textarea name="description" id="editor" cols="30" rows="10" class="form-control" placeholder="Masukkan detail lengkap project...">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Editor mendukung: 
                            <strong>upload gambar</strong> (drag & drop / copy-paste), 
                            <strong>tabel</strong>, 
                            <strong>formatting lengkap</strong>, 
                            <strong>code blocks</strong>, 
                            <strong>templates project</strong>, dan banyak lagi!
                        </small>
                    </div>

                    <!-- Dynamic Image Upload Section -->
                    <div class="form-group mb-3">
                        <label>Project Images <span class="text-danger">*</span></label>
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success" id="addImageBtn">
                                        <i class="fas fa-plus"></i> Tambah Gambar
                                    </button>
                                    <small class="form-text text-muted">Format: JPEG, JPG, PNG, GIF, WebP. Maksimal 2MB per file.</small>
                                </div>
                                
                                <div id="imageContainer">
                                    <!-- Initial image upload field -->
                                    <div class="image-upload-item mb-3" data-index="0">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <input type="file" name="images[]" class="form-control image-input" accept="image/*" data-index="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="radio" name="featured_image_index" value="0" class="form-check-input featured-radio" id="featured_0" checked>
                                                    <label class="form-check-label" for="featured_0">
                                                        Gambar Utama
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-image" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <img class="preview-image d-none img-thumbnail" style="max-width: 200px; max-height: 150px;" alt="Preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Projects Section -->
                    <div class="form-group mb-3">
                        <label>Other Projects</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success btn-sm" id="addOtherProjectBtn">
                                        <i class="fas fa-plus"></i> Tambah Project Lain
                                    </button>
                                    <small class="form-text text-muted">Tambahkan project lain yang terkait dengan project ini</small>
                                </div>
                                
                                <div id="otherProjectsContainer">
                                    <!-- Initial other project field -->
                                    <div class="other-project-item mb-3" data-index="0">
                                        <div class="row align-items-center">
                                            <div class="col-md-10">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control other-project-input" 
                                                           name="other_projects[]" 
                                                           placeholder="Ketik minimal 3 karakter untuk mencari project lain..." 
                                                           value="{{ old('other_projects.0') }}" 
                                                           data-index="0"
                                                           autocomplete="off">
                                                    <div class="other-projects-dropdown dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-other-project" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Selected projects display -->
                                <div id="selectedProjectsDisplay" style="display: none;">
                                    <hr>
                                    <label class="font-weight-bold">Selected Other Projects:</label>
                                    <div id="selectedProjectsList" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-right">
                            <a href="{{ route('project.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Project
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

<style>
.image-upload-item {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 1rem;
    background-color: #f8f9fc;
}

.image-upload-item:hover {
    background-color: #f5f5f5;
}

.preview-image {
    border-radius: 0.35rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.featured-radio:checked + label {
    font-weight: bold;
    color: #007bff;
}

/* CKEditor 5 Custom Styles */
.ck-editor__editable {
    min-height: 400px;
}

.ck.ck-editor {
    max-width: 100%;
}

.ck.ck-editor__main>.ck-editor__editable {
    background: #ffffff;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
}

.ck.ck-editor__main>.ck-editor__editable:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.editor-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.editor-loading i {
    font-size: 1.5rem;
    margin-right: 10px;
    color: #6c757d;
}

/* Other Projects Autocomplete Styles */
.other-project-item {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 1rem;
    background-color: #f8f9fc;
}

.other-project-item:hover {
    background-color: #f5f5f5;
}

.other-projects-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.other-projects-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.other-projects-item:last-child {
    border-bottom: none;
}

.other-projects-item:hover {
    background-color: #f8f9fa;
}

.other-projects-item.active {
    background-color: #007bff;
    color: white;
}

.other-projects-title {
    font-weight: bold;
    color: #333;
    margin-bottom: 4px;
}

.other-projects-subtitle {
    font-size: 0.875em;
    color: #666;
    margin-bottom: 0;
}

.other-projects-item.active .other-projects-title,
.other-projects-item.active .other-projects-subtitle {
    color: white;
}

.loading-indicator {
    padding: 12px 16px;
    text-align: center;
    color: #666;
    font-style: italic;
}

.selected-project-badge {
    display: inline-flex;
    align-items: center;
    background-color: #007bff;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    margin: 4px;
    font-size: 0.875rem;
}

.selected-project-badge .remove-selected {
    background: none;
    border: none;
    color: white;
    margin-left: 8px;
    cursor: pointer;
    font-size: 1.2em;
    line-height: 1;
    padding: 0;
}

.selected-project-badge .remove-selected:hover {
    color: #ffcccc;
}

/* ACCESSIBILITY IMPROVEMENTS */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Enhanced focus indicators with better contrast */
.form-control:focus,
.btn:focus,
input[type="radio"]:focus + label,
input[type="file"]:focus,
select:focus {
    outline: 3px solid #ffdd00 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 3px rgba(255, 221, 0, 0.4) !important;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .text-muted {
        color: #000000 !important;
    }

    .form-control {
        border: 2px solid #000000 !important;
    }

    .btn {
        border: 2px solid #000000 !important;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Color contrast improvements */
.text-danger {
    color: #dc3545 !important; /* Ensures 4.5:1 contrast ratio */
}

.alert-danger {
    background-color: #f8d7da !important;
    color: #721c24 !important; /* High contrast text */
    border-color: #f5c6cb !important;
}

/* Focus trap helper for modals */
.focus-trap-active {
    overflow: hidden;
}

/* Keyboard navigation helper */
.keyboard-nav .form-group:focus-within {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 8px;
}

/* Better button accessibility */
.btn[aria-pressed="true"] {
    background-color: #0056b3 !important;
    border-color: #004085 !important;
}

/* Enhanced error styling for accessibility */
.form-control.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    color: #721c24 !important;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageIndex = 1;

    // ACCESSIBILITY: Add keyboard navigation support
    document.body.classList.add('keyboard-nav');

    // ACCESSIBILITY: Add status announcements for screen readers
    const statusDiv = document.createElement('div');
    statusDiv.id = 'accessibility-status';
    statusDiv.setAttribute('aria-live', 'polite');
    statusDiv.setAttribute('aria-atomic', 'true');
    statusDiv.className = 'sr-only';
    document.body.appendChild(statusDiv);

    function announceToScreenReader(message) {
        const statusDiv = document.getElementById('accessibility-status');
        statusDiv.textContent = message;
        setTimeout(() => {
            statusDiv.textContent = '';
        }, 1000);
    }

    // ACCESSIBILITY: Keyboard event handlers
    document.addEventListener('keydown', function(e) {
        // ESC key closes dropdowns
        if (e.key === 'Escape') {
            const openDropdowns = document.querySelectorAll('.other-projects-dropdown[style*="display: block"]');
            openDropdowns.forEach(dropdown => {
                hideOtherProjectDropdown(dropdown);
            });
        }

        // Tab navigation improvements
        if (e.key === 'Tab') {
            const focusableElements = document.querySelectorAll(
                'input, textarea, select, button, [tabindex]:not([tabindex="-1"])'
            );
            const currentIndex = Array.from(focusableElements).indexOf(document.activeElement);

            if (e.shiftKey && currentIndex === 0) {
                // Focus last element when shift-tab from first
                e.preventDefault();
                focusableElements[focusableElements.length - 1].focus();
            } else if (!e.shiftKey && currentIndex === focusableElements.length - 1) {
                // Focus first element when tab from last
                e.preventDefault();
                focusableElements[0].focus();
            }
        }
    });

    // Initialize CKEditor 5 with advanced features
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'uploadImage', 'insertTable', 'mediaEmbed', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'alignment', '|',
                    'blockQuote', 'codeBlock', 'horizontalLine', '|',
                    'undo', 'redo', '|',
                    'sourceEditing'
                ]
            },
            language: 'en',
            fontSize: {
                options: [
                    9, 11, 13, 'default', 17, 19, 21, 27, 35
                ],
                supportAllValues: true
            },
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif',
                    'Segoe UI, system-ui, sans-serif'
                ],
                supportAllValues: true
            },
            fontColor: {
                columns: 5,
                documentColors: 10
            },
            fontBackgroundColor: {
                columns: 5,
                documentColors: 10
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'toggleImageCaption', '|',
                    'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', '|',
                    'resizeImage'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        label: 'Original size',
                        value: null
                    },
                    {
                        name: 'resizeImage:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'resizeImage:75',
                        label: '75%',
                        value: '75'
                    }
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells',
                    'tableCellProperties', 'tableProperties'
                ]
            },
            link: {
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    },
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        defaultValue: true,
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    }
                }
            },
            codeBlock: {
                languages: [
                    { language: 'css', label: 'CSS' },
                    { language: 'html', label: 'HTML' },
                    { language: 'javascript', label: 'JavaScript' },
                    { language: 'php', label: 'PHP' },
                    { language: 'python', label: 'Python' },
                    { language: 'sql', label: 'SQL' },
                    { language: 'xml', label: 'XML' },
                    { language: 'json', label: 'JSON' }
                ]
            },
            mediaEmbed: {
                previewsInData: true
            }
        })
        .then(editor => {
            window.editorInstance = editor;
            
            // Remove loading indicator
            const loadingElement = document.getElementById('editor-loading');
            if (loadingElement) {
                loadingElement.remove();
            }
            
            console.log('✨ CKEditor 5 CREATE VERSION initialized successfully!');
            
            // FIXED: Robust image upload adapter with better error handling
            class FixedUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }
                
                upload() {
                    return this.loader.file
                        .then(file => new Promise(async (resolve, reject) => {
                            console.log('🚀 FIXED UPLOAD ADAPTER - Starting upload...');
                            console.log('File details:', {
                                name: file.name,
                                size: file.size + ' bytes (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)',
                                type: file.type
                            });
                            
                            const data = new FormData();
                            data.append('file', file);
                            data.append('_token', '{{ csrf_token() }}');
                            
                            // Prioritized upload URLs - working ones first
                            const uploadUrls = [
                                '{{ url("/debug-simple-upload") }}',
                                '{{ url("/project/upload-editor-image") }}',
                                '{{ url("/upload-image") }}',
                                '{{ url("/test-upload.php") }}'
                            ];
                            
                            console.log('🎯 Will try these upload URLs in order:', uploadUrls);
                            
                            // Try each URL until one works
                            for (let i = 0; i < uploadUrls.length; i++) {
                                try {
                                    console.log(`⏳ Attempt ${i + 1}/${uploadUrls.length}: ${uploadUrls[i]}`);
                                    
                                    const response = await fetch(uploadUrls[i], {
                                        method: 'POST',
                                        body: data,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    });
                                    
                                    console.log(`📡 Response from ${uploadUrls[i]}:`, {
                                        status: response.status,
                                        statusText: response.statusText,
                                        ok: response.ok,
                                        contentType: response.headers.get('content-type')
                                    });
                                    
                                    if (response.ok) {
                                        let result;
                                        const contentType = response.headers.get('content-type') || '';
                                        
                                        if (contentType.includes('application/json')) {
                                            result = await response.json();
                                        } else {
                                            // Try to parse as JSON anyway
                                            const text = await response.text();
                                            try {
                                                result = JSON.parse(text);
                                            } catch (e) {
                                                console.warn('⚠️ Response is not JSON:', text);
                                                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                                            }
                                        }
                                        
                                        console.log('📋 Upload result:', result);
                                        
                                        if (result.success && result.url) {
                                            console.log('✅ SUCCESS! Image uploaded:', result.url);
                                            resolve({
                                                default: result.url
                                            });
                                            return; // Exit the function successfully
                                        } else {
                                            console.warn('⚠️ Upload successful but invalid result format:', result);
                                            // Continue to next URL
                                        }
                                    } else {
                                        console.warn(`⚠️ HTTP ${response.status}: ${response.statusText}`);
                                        // Continue to next URL
                                    }
                                } catch (error) {
                                    console.warn(`⚠️ Error with ${uploadUrls[i]}:`, error.message);
                                    // Continue to next URL
                                }
                            }
                            
                            // If we get here, all URLs failed
                            console.error('❌ ALL UPLOAD URLs FAILED');
                            reject('Upload failed: All available upload methods failed. Please check network connection and server configuration.');
                        }));
                }
                
                abort() {
                    console.log('🛑 Upload aborted by user');
                }
            }
            
            // Set up file repository for image uploads
            if (editor.plugins.has('FileRepository')) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new FixedUploadAdapter(loader);
                };
                console.log('✅ FIXED upload adapter registered successfully');
            } else {
                console.warn('⚠️ FileRepository plugin not available - image upload disabled');
            }
            
            // Auto-save functionality
            let autoSaveTimer;
            editor.model.document.on('change:data', () => {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    const content = editor.getData();
                    localStorage.setItem('project_detail_draft', content);
                    console.log('Content auto-saved to localStorage');
                }, 2000);
            });
            
            // Restore draft on load
            const draft = localStorage.getItem('project_detail_draft');
            if (draft && !editor.getData()) {
                if (confirm('Found unsaved draft. Do you want to restore it?')) {
                    editor.setData(draft);
                }
            }
        })
        .catch(error => {
            console.error('CKEditor initialization failed:', error);
            
            // Remove loading indicator and show error
            const loadingElement = document.getElementById('editor-loading');
            if (loadingElement) {
                loadingElement.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> <span class="text-danger">Editor failed to load. Using fallback textarea.</span>';
            }
            
            // Show the textarea as fallback
            const editorElement = document.querySelector('#editor');
            if (editorElement) {
                editorElement.style.display = 'block';
                editorElement.style.minHeight = '300px';
                editorElement.style.resize = 'vertical';
                console.log('Fallback: Using simple textarea');
            }
        });

    // Dynamic Image Upload functionality
    // Add image upload field
    document.getElementById('addImageBtn').addEventListener('click', function() {
        const container = document.getElementById('imageContainer');
        const newItem = document.createElement('div');
        newItem.className = 'image-upload-item mb-3';
        newItem.setAttribute('data-index', imageIndex);
        
        newItem.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="file" name="images[]" class="form-control image-input" accept="image/*" data-index="${imageIndex}">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="radio" name="featured_image_index" value="${imageIndex}" class="form-check-input featured-radio" id="featured_${imageIndex}">
                        <label class="form-check-label" for="featured_${imageIndex}">
                            Gambar Utama
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-image">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <img class="preview-image d-none img-thumbnail" style="max-width: 200px; max-height: 150px;" alt="Preview">
            </div>
        `;
        
        container.appendChild(newItem);
        imageIndex++;
        updateRemoveButtons();
        attachImagePreview();
    });

    // Remove image upload field
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.image-upload-item');
                const wasSelected = item.querySelector('input[type="radio"]').checked;
                
                item.remove();
                
                // If removed item was featured, select first remaining
                if (wasSelected) {
                    const firstRadio = document.querySelector('input[name="featured_image_index"]');
                    if (firstRadio) {
                        firstRadio.checked = true;
                    }
                }
                
                updateRemoveButtons();
                reindexItems();
            });
        });
    }

    // Update remove button visibility
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.image-upload-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-image');
            removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
        });
        attachRemoveListeners();
    }

    // Reindex items after removal
    function reindexItems() {
        document.querySelectorAll('.image-upload-item').forEach((item, index) => {
            item.setAttribute('data-index', index);
            const input = item.querySelector('input[type="file"]');
            const radio = item.querySelector('input[type="radio"]');
            
            input.setAttribute('data-index', index);
            radio.value = index;
            radio.id = `featured_${index}`;
            radio.nextElementSibling.setAttribute('for', `featured_${index}`);
        });
    }

    // Image preview functionality
    function attachImagePreview() {
        document.querySelectorAll('.image-input').forEach(input => {
            input.addEventListener('change', function() {
                const preview = this.closest('.image-upload-item').querySelector('.preview-image');
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.classList.add('d-none');
                }
            });
        });
    }

    // Enhanced form validation with accessibility
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        const images = document.querySelectorAll('input[name="images[]"]');
        let hasImage = false;
        let errors = [];

        // Clear previous errors
        document.querySelectorAll('.form-control.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // Validate required fields
        const requiredFields = [
            { element: document.getElementById('client_name'), name: 'Nama Client' },
            { element: document.getElementById('location'), name: 'Lokasi Client' },
            { element: document.getElementById('project_name'), name: 'Nama Project' },
            { element: document.getElementById('project_category'), name: 'Kategori Project' },
            { element: document.getElementById('slug_project'), name: 'Slug Project' },
            { element: document.getElementById('summary_description'), name: 'Summary Description' },
            { element: document.querySelector('#editor'), name: 'Detail Project' }
        ];

        requiredFields.forEach(field => {
            if (!field.element.value || field.element.value.trim() === '') {
                field.element.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = `${field.name} wajib diisi.`;
                errorDiv.setAttribute('role', 'alert');
                field.element.parentNode.appendChild(errorDiv);
                errors.push(`${field.name} wajib diisi`);
            }
        });

        // Validate images
        images.forEach(input => {
            if (input.files && input.files.length > 0) {
                hasImage = true;
            }
        });

        if (!hasImage) {
            const imageContainer = document.getElementById('imageContainer').parentElement;
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Minimal harus mengunggah satu gambar project!';
            errorDiv.setAttribute('role', 'alert');
            imageContainer.appendChild(errorDiv);
            errors.push('Minimal harus mengunggah satu gambar project');
        }

        if (errors.length > 0) {
            e.preventDefault();

            // Focus on first error field
            const firstErrorField = document.querySelector('.form-control.is-invalid, input[name="images[]"]');
            if (firstErrorField) {
                firstErrorField.focus();
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Announce errors to screen reader
            announceToScreenReader(`Form memiliki ${errors.length} error: ${errors.join(', ')}`);

            return;
        }
        
        // Validate other projects - ensure no empty values are submitted
        const otherProjectInputs = document.querySelectorAll('input[name="other_projects[]"]');
        otherProjectInputs.forEach(input => {
            if (!input.value || input.value.trim() === '') {
                input.disabled = true; // Disable empty inputs so they won't be submitted
            }
        });

        // Clear draft when successfully submitting
        localStorage.removeItem('project_detail_draft');
        
        console.log('🚀 Form submitted - validations passed!');
    });

    // Multiple Other Projects functionality
    let otherProjectIndex = 1;
    let selectedOtherProjects = new Set(); // Track selected projects to avoid duplicates
    
    // Initialize with existing other projects for create mode (should be empty)
    document.querySelectorAll('.other-project-input').forEach(input => {
        if (input.value && input.value.trim()) {
            selectedOtherProjects.add(input.value.trim());
        }
    });
    
    // Add new other project input
    document.getElementById('addOtherProjectBtn').addEventListener('click', function() {
        const container = document.getElementById('otherProjectsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'other-project-item mb-3';
        newItem.setAttribute('data-index', otherProjectIndex);
        
        newItem.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-10">
                    <div class="position-relative">
                        <input type="text" class="form-control other-project-input" 
                               name="other_projects[]" 
                               placeholder="Ketik minimal 3 karakter untuk mencari project lain..." 
                               data-index="${otherProjectIndex}"
                               autocomplete="off">
                        <div class="other-projects-dropdown dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-other-project">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newItem);
        otherProjectIndex++;
        updateOtherProjectButtons();
        attachOtherProjectsListeners();
    });

    // Remove other project input
    function attachOtherProjectRemoveListeners() {
        document.querySelectorAll('.remove-other-project').forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.other-project-item');
                const input = item.querySelector('.other-project-input');
                
                // Remove from selected set if it was selected
                if (input.value) {
                    selectedOtherProjects.delete(input.value);
                }
                
                item.remove();
                updateOtherProjectButtons();
                updateSelectedProjectsDisplay();
            });
        });
    }

    // Update remove button visibility
    function updateOtherProjectButtons() {
        const items = document.querySelectorAll('.other-project-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-other-project');
            removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
        });
        attachOtherProjectRemoveListeners();
    }

    // Attach all other projects event listeners
    function attachOtherProjectsListeners() {
        document.querySelectorAll('.other-project-input').forEach(input => {
            // Remove existing listeners to avoid duplicates
            input.removeEventListener('input', handleOtherProjectInput);
            input.removeEventListener('keydown', handleOtherProjectKeydown);
            input.removeEventListener('blur', handleOtherProjectBlur);
            
            // Add fresh listeners
            input.addEventListener('input', handleOtherProjectInput);
            input.addEventListener('keydown', handleOtherProjectKeydown);
            input.addEventListener('blur', handleOtherProjectBlur);
        });
    }

    // Handle input for other projects search
    function handleOtherProjectInput(e) {
        const input = e.target;
        const dropdown = input.parentElement.querySelector('.other-projects-dropdown');
        const query = input.value.trim();
        
        clearTimeout(input.searchTimeout);
        
        if (query.length < 3) {
            hideOtherProjectDropdown(dropdown);
            return;
        }
        
        input.searchTimeout = setTimeout(() => {
            searchOtherProjects(query, input, dropdown);
        }, 300);
    }

    // Handle keyboard navigation
    function handleOtherProjectKeydown(e) {
        const input = e.target;
        const dropdown = input.parentElement.querySelector('.other-projects-dropdown');
        
        if (dropdown.style.display === 'none') return;
        
        const items = dropdown.querySelectorAll('.other-projects-item');
        let selectedIndex = Array.from(items).findIndex(item => item.classList.contains('active'));
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateOtherProjectSelection(dropdown, selectedIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateOtherProjectSelection(dropdown, selectedIndex);
                break;
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    selectOtherProject(input, dropdown, items[selectedIndex]);
                }
                break;
            case 'Escape':
                hideOtherProjectDropdown(dropdown);
                break;
        }
    }

    // Handle blur event
    function handleOtherProjectBlur(e) {
        const dropdown = e.target.parentElement.querySelector('.other-projects-dropdown');
        // Delay hiding to allow click on dropdown items
        setTimeout(() => {
            hideOtherProjectDropdown(dropdown);
        }, 200);
    }

    // Search for other projects
    function searchOtherProjects(query, input, dropdown) {
        showOtherProjectLoading(dropdown);
        
        fetch(`{{ route('project.search') }}?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Filter out already selected projects
                const availableProjects = data.data.filter(project => 
                    !selectedOtherProjects.has(project.text)
                );
                displayOtherProjectResults(availableProjects, input, dropdown);
            } else {
                console.error('Search failed:', data.message);
                hideOtherProjectDropdown(dropdown);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            hideOtherProjectDropdown(dropdown);
        });
    }

    // Show loading indicator
    function showOtherProjectLoading(dropdown) {
        dropdown.innerHTML = '<div class="loading-indicator"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        dropdown.style.display = 'block';
    }

    // Display search results
    function displayOtherProjectResults(results, input, dropdown) {
        if (results.length === 0) {
            dropdown.innerHTML = '<div class="loading-indicator">No available projects found</div>';
        } else {
            const html = results.map((project, index) => `
                <div class="other-projects-item" data-project-text="${escapeHtml(project.text)}" data-project-subtitle="${escapeHtml(project.subtitle)}">
                    <div class="other-projects-title">${escapeHtml(project.text)}</div>
                    <div class="other-projects-subtitle">${escapeHtml(project.subtitle)}</div>
                </div>
            `).join('');
            dropdown.innerHTML = html;
            
            // Add click listeners to dropdown items
            dropdown.querySelectorAll('.other-projects-item').forEach(item => {
                item.addEventListener('click', () => {
                    selectOtherProject(input, dropdown, item);
                });
            });
        }
        dropdown.style.display = 'block';
    }

    // Update selection highlighting
    function updateOtherProjectSelection(dropdown, selectedIndex) {
        const items = dropdown.querySelectorAll('.other-projects-item');
        items.forEach((item, index) => {
            item.classList.toggle('active', index === selectedIndex);
        });
    }

    // Select a project
    function selectOtherProject(input, dropdown, item) {
        const projectText = item.dataset.projectText;
        const projectSubtitle = item.dataset.projectSubtitle;
        
        input.value = projectText;
        selectedOtherProjects.add(projectText);
        hideOtherProjectDropdown(dropdown);
        updateSelectedProjectsDisplay();
        
        // Add visual feedback
        input.classList.add('is-valid');
        setTimeout(() => {
            input.classList.remove('is-valid');
        }, 2000);
    }

    // Hide dropdown
    function hideOtherProjectDropdown(dropdown) {
        dropdown.style.display = 'none';
    }

    // Update the selected projects display
    function updateSelectedProjectsDisplay() {
        const selectedProjects = Array.from(selectedOtherProjects);
        const displayDiv = document.getElementById('selectedProjectsDisplay');
        const listDiv = document.getElementById('selectedProjectsList');
        
        if (selectedProjects.length > 0) {
            displayDiv.style.display = 'block';
            listDiv.innerHTML = selectedProjects.map(project => `
                <span class="selected-project-badge">
                    ${escapeHtml(project)}
                    <button type="button" class="remove-selected" onclick="removeSelectedProject('${escapeHtml(project)}')" title="Remove project">
                        ×
                    </button>
                </span>
            `).join('');
        } else {
            displayDiv.style.display = 'none';
        }
    }

    // Remove selected project (global function)
    window.removeSelectedProject = function(projectText) {
        selectedOtherProjects.delete(projectText);
        
        // Clear the input that contains this project
        document.querySelectorAll('.other-project-input').forEach(input => {
            if (input.value === projectText) {
                input.value = '';
            }
        });
        
        updateSelectedProjectsDisplay();
    };

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize other projects functionality
    updateOtherProjectButtons();
    attachOtherProjectsListeners();

    // Initialize
    updateRemoveButtons();
    attachImagePreview();
    
    // === SLUG FUNCTIONALITY ===
    let isSlugManual = false;
    
    // Function to generate slug from project name and category
    function generateSlug() {
        const projectName = document.getElementById('project_name').value.trim();
        const category = document.getElementById('project_category').value;
        
        if (!projectName) return '';
        
        let baseSlug = projectName.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single
            .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
        
        if (category) {
            const categorySlug = category.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            baseSlug = baseSlug + '-' + categorySlug;
        }
        
        return baseSlug;
    }
    
    // Auto-generate slug when project name or category changes
    function updateSlugIfAuto() {
        if (!isSlugManual) {
            const slug = generateSlug();
            document.getElementById('slug_project').value = slug;
        }
    }
    
    // Event listeners for auto-generation
    document.getElementById('project_name').addEventListener('input', updateSlugIfAuto);
    document.getElementById('project_category').addEventListener('change', updateSlugIfAuto);
    
    // Toggle between manual and auto mode
    document.getElementById('editSlugBtn').addEventListener('click', function() {
        isSlugManual = true;
        document.getElementById('slug_project').removeAttribute('readonly');
        document.getElementById('slug_project').focus();
        this.style.display = 'none';
        document.getElementById('autoSlugBtn').style.display = 'inline-block';
    });
    
    document.getElementById('autoSlugBtn').addEventListener('click', function() {
        isSlugManual = false;
        document.getElementById('slug_project').setAttribute('readonly', true);
        updateSlugIfAuto();
        this.style.display = 'none';
        document.getElementById('editSlugBtn').style.display = 'inline-block';
    });
    
    // Generate initial slug
    updateSlugIfAuto();
    
    console.log('🎯 CREATE FORM LOADED - Upload system ready with multiple fallback URLs!');
});
</script>
@endsection
