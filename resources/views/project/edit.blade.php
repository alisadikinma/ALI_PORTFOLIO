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

                <form action="{{ route('project.update', $project->id_project) }}" method="POST" enctype="multipart/form-data" id="projectForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="client_name">Nama Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="location">Lokasi Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $project->location) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="project_name">Nama Project <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="project_name" name="project_name" value="{{ old('project_name', $project->project_name) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_category">Kategori Project <span class="text-danger">*</span></label>
                                <select name="project_category" id="project_category" class="form-control">
                                    <option value="">Pilih Kategori Project</option>
                                    <option value="Artificial Intelligence" {{ old('project_category', $project->project_category) == 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                                    <option value="Web Application" {{ old('project_category', $project->project_category) == 'Web Application' ? 'selected' : '' }}>Web Application</option>
                                    <option value="Mobile Application" {{ old('project_category', $project->project_category) == 'Mobile Application' ? 'selected' : '' }}>Mobile Application</option>
                                    <option value="Data Visualization" {{ old('project_category', $project->project_category) == 'Data Visualization' ? 'selected' : '' }}>Data Visualization</option>
                                    <option value="API Development" {{ old('project_category', $project->project_category) == 'API Development' ? 'selected' : '' }}>API Development</option>
                                    <option value="Automation" {{ old('project_category', $project->project_category) == 'Automation' ? 'selected' : '' }}>Automation</option>
                                    <option value="Internet of Things" {{ old('project_category', $project->project_category) == 'Internet of Things' ? 'selected' : '' }}>Internet of Things</option>
                                    <option value="Blockchain" {{ old('project_category', $project->project_category) == 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                                    <option value="Machine Learning" {{ old('project_category', $project->project_category) == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                                    <option value="System Integration" {{ old('project_category', $project->project_category) == 'System Integration' ? 'selected' : '' }}>System Integration</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="url_project">URL Project</label>
                                <input type="url" class="form-control" id="url_project" name="url_project" value="{{ old('url_project', $project->url_project) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sequence">Urutan Tampilan</label>
                        <input type="number" class="form-control" id="sequence" name="sequence" value="{{ old('sequence', $project->sequence) }}" min="0">
                    </div>

                    <div class="form-group mb-3">
<<<<<<< HEAD
                        <label for="summary_description">Ringkasan Project</label>
                        <textarea name="summary_description" id="summary_description" cols="30" rows="3" class="form-control">{{ old('summary_description', $project->summary_description ?? '') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi Project <span class="text-danger">*</span></label>
                        <textarea name="description" id="editor" cols="30" rows="10" class="form-control">{{ old('description', $project->description) }}</textarea>
=======
                        <label for="summary_description">Summary Description <span class="text-danger">*</span></label>
                        <textarea name="summary_description" id="summary_description" cols="30" rows="3" class="form-control" placeholder="Masukkan deskripsi singkat untuk tampilan portfolio...">{{ old('summary_description', $project->summary_description) }}</textarea>
                        <small class="form-text text-muted">Deskripsi singkat ini akan tampil di portfolio slider</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Detail Project <span class="text-danger">*</span></label>
                        
                        <!-- Loading indicator for editor -->
                        <div id="editor-loading" class="editor-loading mb-3">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Loading Advanced Editor...</span>
                        </div>
                        
                        <textarea name="description" id="editor" cols="30" rows="10" class="form-control">{{ old('description', $project->description) }}</textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Editor mendukung: 
                            <strong>upload gambar</strong> (drag & drop / copy-paste), 
                            <strong>tabel</strong>, 
                            <strong>formatting lengkap</strong>, 
                            <strong>code blocks</strong>, 
                            <strong>templates project</strong>, dan banyak lagi!
                        </small>
>>>>>>> 63027871ae323267b47379017adb239bab443d93
                    </div>

                    <!-- Existing Images Section -->
                    @php
                        $existingImages = $project->images ? json_decode($project->images, true) : [];
                    @endphp
                    
                    @if(!empty($existingImages))
                    <div class="form-group mb-3">
                        <label>Gambar Project Saat Ini</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="row" id="existingImagesContainer">
                                    @foreach($existingImages as $index => $image)
                                    <div class="col-md-4 mb-3 existing-image-item" data-image="{{ $image }}">
                                        <div class="card">
                                            <img src="{{ asset('images/projects/' . $image) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Project Image">
                                            <div class="card-body p-2">
                                                <div class="form-check mb-2">
                                                    <input type="radio" name="featured_image_index" value="{{ $index }}" 
                                                           class="form-check-input" id="existing_featured_{{ $index }}"
                                                           {{ $project->featured_image == $image ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="existing_featured_{{ $index }}">
                                                        <small>Gambar Utama</small>
                                                    </label>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm delete-existing-image" 
                                                        data-image="{{ $image }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="deleteImagesContainer"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- New Images Upload Section -->
                    <div class="form-group mb-3">
                        <label>Tambah Gambar Baru</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success" id="addImageBtn">
                                        <i class="fas fa-plus"></i> Tambah Gambar
                                    </button>
                                    <small class="form-text text-muted">Format: JPEG, JPG, PNG, GIF, WebP. Maksimal 2MB per file.</small>
                                </div>
                                
                                <div id="imageContainer">
                                    <!-- New image upload fields will be added here -->
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
                                <i class="fas fa-save"></i> Update Project
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- CKEditor 5 with Full Features & Image Upload -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
=======
<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/yoy173va5xd7hrzyaps0saw7mtvc1kqzsvlb1hbidqjda0wj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
>>>>>>> 63027871ae323267b47379017adb239bab443d93

<style>
.image-upload-item {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 1rem;
    background-color: #f8f9fc;
}

.existing-image-item .card {
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.existing-image-item .card:hover {
    border-color: #007bff;
}

.featured-radio:checked + label {
    font-weight: bold;
    color: #007bff;
}

<<<<<<< HEAD
/* CKEditor 5 Custom Styles */
.ck-editor__editable {
    min-height: 300px;
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
=======
/* TinyMCE Custom Styles */
.tox-editor-container {
    border-radius: 0.375rem;
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

/* Editor content styling */
.tox-tinymce {
    border-radius: 0.375rem !important;
}

.tox-toolbar {
    background: #f8f9fa !important;
>>>>>>> 63027871ae323267b47379017adb239bab443d93
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageIndex = 0;

<<<<<<< HEAD
    // Initialize CKEditor 5 with Full Features
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'alignment', '|',
                'insertImage', 'insertTable', '|',
                'blockQuote', 'codeBlock', 'horizontalLine', '|',
                'undo', 'redo'
            ],
            fontSize: {
                options: [
                    9, 11, 13, 'default', 17, 19, 21
                ]
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
                    'Verdana, Geneva, sans-serif'
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            }
        })
        .then(editor => {
            window.editor = editor;
            console.log('CKEditor 5 initialized successfully!');
            
            // Custom image upload adapter for copy-paste functionality
            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }
                
                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            console.log('Starting image upload...', file.name);
                            
                            const data = new FormData();
                            data.append('upload', file);
                            data.append('_token', '{{ csrf_token() }}');
                            
                            fetch('{{ route("image.upload") }}', {
                                method: 'POST',
                                body: data,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                console.log('Upload response status:', response.status);
                                return response.json();
                            })
                            .then(result => {
                                console.log('Upload result:', result);
                                if (result.success && result.url) {
                                    resolve({
                                        default: result.url
                                    });
                                } else {
                                    reject(result.message || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                                reject('Upload failed: ' + error.message);
                            });
                        }));
                }
                
                abort() {
                    console.log('Upload aborted');
                }
            }
            
            // Enable image upload on paste/drop
            try {
                if (editor.plugins.has('FileRepository')) {
                    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                        return new MyUploadAdapter(loader);
                    };
                    console.log('Image upload adapter registered successfully');
                } else {
                    console.warn('FileRepository plugin not available');
                }
            } catch (error) {
                console.error('Error setting up image upload:', error);
            }
        })
        .catch(error => {
            console.error('CKEditor initialization failed:', error);
            // Fallback to simple textarea if CKEditor fails
            const editorElement = document.querySelector('#editor');
            if (editorElement) {
                editorElement.style.display = 'block';
                editorElement.style.minHeight = '200px';
                console.log('Fallback: Using simple textarea');
            }
        });
=======
    // Initialize TinyMCE with advanced features (same config as create.blade.php)
    tinymce.init({
        selector: '#editor',
        height: 500,
        menubar: 'file edit view insert format tools table help',
        branding: false,
        
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
            'textcolor', 'colorpicker', 'hr', 'pagebreak', 'nonbreaking',
            'save', 'directionality', 'emoticons', 'template', 'codesample',
            'quickbars', 'accordion'
        ],
        
        toolbar1: 'undo redo | formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | lineheight',
        toolbar2: 'bullist numlist outdent indent | blockquote hr pagebreak | link unlink anchor | image media table accordion | code codesample | fullscreen preview help',
        toolbar3: 'searchreplace | visualblocks | insertdatetime charmap emoticons | template | ltr rtl | wordcount',
        
        content_style: `
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                font-size: 14px; 
                line-height: 1.6; 
                margin: 1rem;
                color: #333;
                background: #fff;
            }
            img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                margin: 0.5rem 0;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin: 1rem 0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #f8f9fa;
                font-weight: bold;
                color: #495057;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            blockquote {
                border-left: 4px solid #007bff;
                margin: 1rem 0;
                padding-left: 1rem;
                color: #6c757d;
                font-style: italic;
                background-color: #f8f9fa;
                border-radius: 0 4px 4px 0;
                padding: 1rem 1rem 1rem 1.5rem;
            }
            code {
                background-color: #f8f9fa;
                padding: 2px 6px;
                border-radius: 4px;
                font-family: 'Courier New', monospace;
                color: #d63384;
                font-size: 0.875em;
            }
            pre {
                background-color: #f8f9fa;
                padding: 1rem;
                border-radius: 8px;
                overflow-x: auto;
                border: 1px solid #e9ecef;
                position: relative;
            }
            h1, h2, h3, h4, h5, h6 {
                color: #2c3e50;
                margin-top: 1.5rem;
                margin-bottom: 1rem;
            }
            h1 { font-size: 2rem; border-bottom: 2px solid #007bff; padding-bottom: 0.5rem; }
            h2 { font-size: 1.5rem; color: #007bff; }
            h3 { font-size: 1.25rem; }
            a { color: #007bff; text-decoration: none; }
            a:hover { text-decoration: underline; }
            ul, ol { padding-left: 2rem; }
            li { margin-bottom: 0.5rem; }
            hr { 
                border: none; 
                height: 2px; 
                background: linear-gradient(to right, #007bff, #6c757d);
                margin: 2rem 0;
                border-radius: 1px;
            }
        `,
        
        font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,serif; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva; Segoe UI=segoe ui,arial,sans-serif;',
        font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt',
        lineheight_formats: '1 1.1 1.2 1.3 1.4 1.5 1.6 1.8 2.0 2.5 3.0',
        
        images_upload_handler: function (blobInfo, success, failure, progress) {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');
            
            if (progress) progress(0);
            
            fetch('/project/upload-editor-image', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(response => {
                if (progress) progress(100);
                if (!response.ok) throw new Error('HTTP Error: ' + response.status);
                return response.json();
            }).then(result => {
                if (result.success && result.url) {
                    success(result.url);
                } else {
                    failure(result.message || 'Upload failed');
                }
            }).catch(error => {
                console.error('Upload error:', error);
                const reader = new FileReader();
                reader.onload = function(e) { success(e.target.result); };
                reader.readAsDataURL(blobInfo.blob());
            });
        },
        
        images_reuse_filename: true,
        images_upload_credentials: true,
        automatic_uploads: true,
        images_file_types: 'jpg,svg,webp,png,gif',
        paste_data_images: true,
        paste_as_text: false,
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles_if_webkit: false,
        paste_merge_formats: true,
        paste_webkit_styles: 'color font-size font-family background-color',
        paste_retain_style_properties: 'color font-size font-family background-color',
        
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        table_appearance_options: false,
        table_grid: false,
        table_class_list: [
            {title: 'Default', value: ''},
            {title: 'Bordered Table', value: 'table-bordered'},
            {title: 'Striped Table', value: 'table-striped'},
            {title: 'Hover Effect', value: 'table-hover'},
            {title: 'Responsive Table', value: 'table-responsive'}
        ],
        
        link_assume_external_targets: true,
        target_list: [
            {title: 'Same window', value: '_self'},
            {title: 'New window', value: '_blank'}
        ],
        
        codesample_languages: [
            {text: 'HTML/XML', value: 'markup'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'CSS', value: 'css'},
            {text: 'PHP', value: 'php'},
            {text: 'Python', value: 'python'},
            {text: 'Java', value: 'java'},
            {text: 'C', value: 'c'},
            {text: 'C++', value: 'cpp'},
            {text: 'C#', value: 'csharp'},
            {text: 'SQL', value: 'sql'},
            {text: 'JSON', value: 'json'},
            {text: 'Bash', value: 'bash'}
        ],
        codesample_global_prismjs: true,
        
        browser_spellcheck: true,
        contextmenu: 'link image editimage table',
        object_resizing: true,
        resize: 'both',
        elementpath: true,
        statusbar: true,
        
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: 'quickimage quicktable',
        
        setup: function (editor) {
            editor.on('init', function () {
                document.getElementById('editor-loading')?.remove();
                console.log('Advanced TinyMCE Editor initialized for edit form!');
            });
            
            let autoSaveTimer;
            editor.on('input change', function () {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    const content = editor.getContent();
                    localStorage.setItem('project_detail_edit_draft', content);
                }, 2000);
            });
            
            editor.on('init', function() {
                const draft = localStorage.getItem('project_detail_edit_draft');
                if (draft && confirm('Found unsaved draft. Do you want to restore it?')) {
                    editor.setContent(draft);
                }
            });
        },
        
        init_instance_callback: function(editor) {
            console.log('TinyMCE initialized for edit form: ' + editor.id);
        }
    });
>>>>>>> 63027871ae323267b47379017adb239bab443d93

    // Delete existing image functionality
    document.querySelectorAll('.delete-existing-image').forEach(button => {
        button.addEventListener('click', function() {
            const imageName = this.getAttribute('data-image');
            const imageItem = this.closest('.existing-image-item');
            const wasSelected = imageItem.querySelector('input[type="radio"]').checked;
            
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                // Add to delete list
                const deleteContainer = document.getElementById('deleteImagesContainer');
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_images[]';
                deleteInput.value = imageName;
                deleteContainer.appendChild(deleteInput);
                
                // Remove from display
                imageItem.remove();
                
                // If removed item was featured, select first remaining
                if (wasSelected) {
                    const firstRadio = document.querySelector('input[name="featured_image_index"]');
                    if (firstRadio) {
                        firstRadio.checked = true;
                    }
                }
                
                reindexExistingImages();
            }
        });
    });

    // Add new image upload field
    document.getElementById('addImageBtn').addEventListener('click', function() {
        const container = document.getElementById('imageContainer');
        const existingCount = document.querySelectorAll('.existing-image-item').length;
        const newImageCount = document.querySelectorAll('.image-upload-item').length;
        const totalIndex = existingCount + newImageCount;
        
        const newItem = document.createElement('div');
        newItem.className = 'image-upload-item mb-3';
        newItem.setAttribute('data-index', imageIndex);
        
        newItem.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="file" name="images[]" class="form-control image-input" accept="image/*" data-index="${totalIndex}">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="radio" name="featured_image_index" value="${totalIndex}" class="form-check-input featured-radio" id="new_featured_${imageIndex}">
                        <label class="form-check-label" for="new_featured_${imageIndex}">
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
        attachRemoveListeners();
        attachImagePreview();
    });

    // Remove new image upload field
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-image').forEach(button => {
            button.removeEventListener('click', removeImageHandler);
            button.addEventListener('click', removeImageHandler);
        });
    }

    function removeImageHandler() {
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
        
        reindexNewImages();
    }

    // Reindex existing images after removal
    function reindexExistingImages() {
        document.querySelectorAll('.existing-image-item').forEach((item, index) => {
            const radio = item.querySelector('input[type="radio"]');
            radio.value = index;
            radio.id = `existing_featured_${index}`;
            radio.nextElementSibling.setAttribute('for', `existing_featured_${index}`);
        });
        reindexNewImages();
    }

    // Reindex new images
    function reindexNewImages() {
        const existingCount = document.querySelectorAll('.existing-image-item').length;
        
        document.querySelectorAll('.image-upload-item').forEach((item, index) => {
            const totalIndex = existingCount + index;
            item.setAttribute('data-index', index);
            
            const input = item.querySelector('input[type="file"]');
            const radio = item.querySelector('input[type="radio"]');
            
            input.setAttribute('data-index', totalIndex);
            radio.value = totalIndex;
            radio.id = `new_featured_${index}`;
            radio.nextElementSibling.setAttribute('for', `new_featured_${index}`);
        });
    }

    // Image preview functionality
    function attachImagePreview() {
        document.querySelectorAll('.image-input').forEach(input => {
            input.removeEventListener('change', imagePreviewHandler);
            input.addEventListener('change', imagePreviewHandler);
        });
    }

    function imagePreviewHandler() {
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
    }

    // Form validation
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        // Clear edit draft when submitting
        localStorage.removeItem('project_detail_edit_draft');
    });

    // Initialize
    attachRemoveListeners();
    attachImagePreview();
});
</script>
@endsection
