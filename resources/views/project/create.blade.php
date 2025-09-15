@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="client_name">Nama Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" placeholder="Masukkan nama client disini...." name="client_name" value="{{ old('client_name') }}">
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
                                    <option value="Artificial Intelligence" {{ old('project_category') == 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                                    <option value="Web Application" {{ old('project_category') == 'Web Application' ? 'selected' : '' }}>Web Application</option>
                                    <option value="Mobile Application" {{ old('project_category') == 'Mobile Application' ? 'selected' : '' }}>Mobile Application</option>
                                    <option value="Data Visualization" {{ old('project_category') == 'Data Visualization' ? 'selected' : '' }}>Data Visualization</option>
                                    <option value="API Development" {{ old('project_category') == 'API Development' ? 'selected' : '' }}>API Development</option>
                                    <option value="Automation" {{ old('project_category') == 'Automation' ? 'selected' : '' }}>Automation</option>
                                    <option value="Internet of Things" {{ old('project_category') == 'Internet of Things' ? 'selected' : '' }}>Internet of Things</option>
                                    <option value="Blockchain" {{ old('project_category') == 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                                    <option value="Machine Learning" {{ old('project_category') == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                                    <option value="System Integration" {{ old('project_category') == 'System Integration' ? 'selected' : '' }}>System Integration</option>
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
                        <label for="other_projects">Other Projects</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="other_projects" name="other_projects" 
                                   placeholder="Ketik minimal 3 karakter untuk mencari project lain..." 
                                   value="{{ old('other_projects') }}" autocomplete="off">
                            <div id="other_projects_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;"></div>
                        </div>
                        <small class="form-text text-muted">Pilih project lain yang terkait dengan project ini</small>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageIndex = 1;

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
            
            console.log('CKEditor 5 initialized successfully!');
            
            // Custom image upload adapter
            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }
                
                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            console.log('=== CKEditor Upload Debug ===');
                            console.log('Starting image upload...', {
                                filename: file.name,
                                size: file.size,
                                type: file.type
                            });
                            
                            const data = new FormData();
                            data.append('file', file);
                            data.append('_token', '{{ csrf_token() }}');
                            
                            const uploadUrl = '{{ url("/project/upload-editor-image") }}';
                            const fallbackUrl = '{{ url("/upload-image") }}';
                            console.log('Primary Upload URL:', uploadUrl);
                            console.log('Fallback Upload URL:', fallbackUrl);
                            console.log('FormData contents:', {
                                file: file.name,
                                token: '{{ csrf_token() }}'
                            });
                            
                            // Function to try upload with a specific URL
                            const tryUpload = (url) => {
                                return fetch(url, {
                                    method: 'POST',
                                    body: data,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                });
                            };
                            
                            // Try primary URL first, then fallback
                            console.log('Attempting upload with primary URL...');
                            tryUpload(uploadUrl)
                            .then(response => {
                                console.log('Primary upload response:', {
                                    status: response.status,
                                    statusText: response.statusText,
                                    ok: response.ok,
                                    url: response.url
                                });
                                
                                if (!response.ok) {
                                    console.warn('Primary URL failed, trying fallback...');
                                    return tryUpload(fallbackUrl);
                                }
                                return response;
                            })
                            .then(response => {
                                console.log('Final upload response:', {
                                    status: response.status,
                                    statusText: response.statusText,
                                    ok: response.ok,
                                    url: response.url
                                });
                                
                                if (!response.ok) {
                                    return response.text().then(text => {
                                        console.error('Both upload URLs failed. Response body:', text);
                                        throw new Error(`HTTP ${response.status}: ${response.statusText}\nResponse: ${text}`);
                                    });
                                }
                                return response.json();
                            })
                            .then(result => {
                                console.log('Upload result:', result);
                                if (result.success && result.url) {
                                    console.log('✅ Upload successful! Image URL:', result.url);
                                    resolve({
                                        default: result.url
                                    });
                                } else {
                                    console.error('❌ Upload failed - Invalid response format:', result);
                                    reject(result.message || 'Upload failed - Invalid response format');
                                }
                            })
                            .catch(error => {
                                console.error('❌ Upload error:', error);
                                console.error('Error details:', {
                                    message: error.message,
                                    stack: error.stack
                                });
                                reject('Upload failed: ' + error.message);
                            });
                        }));
                }
                
                abort() {
                    console.log('Upload aborted');
                }
            }
            
            // Set up file repository for image uploads
            if (editor.plugins.has('FileRepository')) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
                console.log('Image upload adapter registered successfully');
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

    // Enhanced form validation
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        const images = document.querySelectorAll('input[name="images[]"]');
        let hasImage = false;
        
        images.forEach(input => {
            if (input.files && input.files.length > 0) {
                hasImage = true;
            }
        });

        if (!hasImage) {
            e.preventDefault();
            alert('Minimal harus mengunggah satu gambar project!');
            return;
        }

        // Clear draft when successfully submitting
        localStorage.removeItem('project_detail_draft');
    });

    // Other Projects Autocomplete functionality
    let searchTimeout;
    let selectedIndex = -1;
    let searchResults = [];
    
    const otherProjectsInput = document.getElementById('other_projects');
    const dropdown = document.getElementById('other_projects_dropdown');
    
    if (otherProjectsInput && dropdown) {
        otherProjectsInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 3) {
                hideDropdown();
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchProjects(query);
            }, 300);
        });
        
        otherProjectsInput.addEventListener('keydown', function(e) {
            if (dropdown.style.display === 'none') return;
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, searchResults.length - 1);
                    updateSelection();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && searchResults[selectedIndex]) {
                        selectProject(searchResults[selectedIndex]);
                    }
                    break;
                case 'Escape':
                    hideDropdown();
                    break;
            }
        });
        
        otherProjectsInput.addEventListener('blur', function() {
            // Delay hiding to allow click on dropdown items
            setTimeout(() => {
                hideDropdown();
            }, 200);
        });
    }
    
    function searchProjects(query) {
        showLoading();
        
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
                searchResults = data.data;
                displayResults(searchResults);
            } else {
                console.error('Search failed:', data.message);
                hideDropdown();
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            hideDropdown();
        });
    }
    
    function showLoading() {
        dropdown.innerHTML = '<div class="loading-indicator"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        dropdown.style.display = 'block';
        selectedIndex = -1;
    }
    
    function displayResults(results) {
        if (results.length === 0) {
            dropdown.innerHTML = '<div class="loading-indicator">No projects found</div>';
        } else {
            const html = results.map((project, index) => `
                <div class="other-projects-item" data-index="${index}" onclick="selectProjectByIndex(${index})">
                    <div class="other-projects-title">${escapeHtml(project.text)}</div>
                    <div class="other-projects-subtitle">${escapeHtml(project.subtitle)}</div>
                </div>
            `).join('');
            dropdown.innerHTML = html;
        }
        dropdown.style.display = 'block';
        selectedIndex = -1;
    }
    
    function updateSelection() {
        const items = dropdown.querySelectorAll('.other-projects-item');
        items.forEach((item, index) => {
            item.classList.toggle('active', index === selectedIndex);
        });
    }
    
    function selectProject(project) {
        otherProjectsInput.value = project.text;
        hideDropdown();
    }
    
    function selectProjectByIndex(index) {
        if (searchResults[index]) {
            selectProject(searchResults[index]);
        }
    }
    
    // Make selectProjectByIndex available globally for onclick
    window.selectProjectByIndex = selectProjectByIndex;
    
    function hideDropdown() {
        dropdown.style.display = 'none';
        selectedIndex = -1;
        searchResults = [];
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!otherProjectsInput.contains(e.target) && !dropdown.contains(e.target)) {
            hideDropdown();
        }
    });

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
});
</script>
@endsection
