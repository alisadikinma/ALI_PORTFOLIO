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
                            <span>Loading Advanced Editor...</span>
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

<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/yoy173va5xd7hrzyaps0saw7mtvc1kqzsvlb1hbidqjda0wj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageIndex = 1;

    // Initialize TinyMCE with advanced features
    tinymce.init({
        selector: '#editor',
        height: 500,
        menubar: 'file edit view insert format tools table help',
        branding: false,
        
        // Plugins untuk fitur lengkap
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
            'textcolor', 'colorpicker', 'hr', 'pagebreak', 'nonbreaking',
            'save', 'directionality', 'emoticons', 'template', 'codesample',
            'quickbars', 'accordion', 'autoresize', 'autosave', 'importcss'
        ],
        
        // Toolbar yang comprehensive
        toolbar1: 'undo redo | formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | lineheight',
        toolbar2: 'bullist numlist outdent indent | blockquote hr pagebreak | link unlink anchor | image media table accordion | code codesample | fullscreen preview help',
        toolbar3: 'searchreplace | visualblocks | insertdatetime charmap emoticons | template | ltr rtl | wordcount',
        
        // Content styling
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
        
        // Font options
        font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,serif; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times,serif; Verdana=verdana,geneva; Segoe UI=segoe ui,arial,sans-serif;',
        font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt',
        
        // Line height options
        lineheight_formats: '1 1.1 1.2 1.3 1.4 1.5 1.6 1.8 2.0 2.5 3.0',
        
        // Image handling dengan upload capability (untuk copy-paste dan drag-drop)
        images_upload_handler: function (blobInfo, success, failure, progress) {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');
            
            // Show progress if available
            if (progress) {
                progress(0);
            }
            
            // Simulate upload endpoint - sesuaikan dengan route yang akan dibuat
            fetch('/project/upload-editor-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (progress) {
                    progress(100);
                }
                
                if (!response.ok) {
                    throw new Error('HTTP Error: ' + response.status);
                }
                return response.json();
            }).then(result => {
                if (result.success && result.url) {
                    success(result.url);
                } else {
                    failure(result.message || 'Upload failed');
                }
            }).catch(error => {
                console.error('Upload error:', error);
                // Fallback untuk demo - gunakan data URL
                const reader = new FileReader();
                reader.onload = function(e) {
                    success(e.target.result);
                };
                reader.readAsDataURL(blobInfo.blob());
            });
        },
        
        // Image settings
        images_reuse_filename: true,
        images_upload_credentials: true,
        automatic_uploads: true,
        images_file_types: 'jpg,svg,webp,png,gif',
        
        // Paste settings untuk copy-paste gambar
        paste_data_images: true,
        paste_as_text: false,
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles_if_webkit: false,
        paste_merge_formats: true,
        paste_webkit_styles: 'color font-size font-family background-color',
        paste_retain_style_properties: 'color font-size font-family background-color',
        
        // Table settings
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
        table_cell_class_list: [
            {title: 'Default', value: ''},
            {title: 'Header Cell', value: 'table-header'},
            {title: 'Highlighted', value: 'highlighted-cell'}
        ],
        
        // Link settings
        link_assume_external_targets: true,
        target_list: [
            {title: 'Same window', value: '_self'},
            {title: 'New window', value: '_blank'}
        ],
        link_class_list: [
            {title: 'Default', value: ''},
            {title: 'Button Primary', value: 'btn btn-primary'},
            {title: 'Button Secondary', value: 'btn btn-secondary'}
        ],
        
        // Code sample settings
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
        
        // Templates
        templates: [
            {
                title: 'Project Overview Template',
                description: 'Template untuk overview project',
                content: `
                    <h1>📋 Project Overview</h1>
                    <table style="width: 100%; border-collapse: collapse;" border="1">
                        <tbody>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; padding: 12px; width: 30%;">Project Name</td>
                                <td style="padding: 12px;">[Nama Project]</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; padding: 12px;">Technology Stack</td>
                                <td style="padding: 12px;">[Technology yang digunakan]</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; padding: 12px;">Duration</td>
                                <td style="padding: 12px;">[Durasi pengerjaan]</td>
                            </tr>
                            <tr>
                                <td style="background-color: #f8f9fa; font-weight: bold; padding: 12px;">Team Size</td>
                                <td style="padding: 12px;">[Jumlah anggota tim]</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h2>🚀 Key Features</h2>
                    <ul>
                        <li><strong>Feature 1:</strong> [Deskripsi feature 1]</li>
                        <li><strong>Feature 2:</strong> [Deskripsi feature 2]</li>
                        <li><strong>Feature 3:</strong> [Deskripsi feature 3]</li>
                    </ul>
                    
                    <h2>📱 Screenshots</h2>
                    <p><em>[Drag & drop screenshot atau copy-paste gambar di sini]</em></p>
                    
                    <h2>⚡ Challenges & Solutions</h2>
                    <blockquote>
                        <p><strong>Challenge:</strong> [Jelaskan tantangan yang dihadapi]</p>
                        <p><strong>Solution:</strong> [Jelaskan solusi yang diterapkan]</p>
                    </blockquote>
                `
            },
            {
                title: 'Technical Specifications',
                description: 'Template untuk spesifikasi teknis',
                content: `
                    <h1>⚙️ Technical Specifications</h1>
                    
                    <h2>🎨 Frontend</h2>
                    <ul>
                        <li><strong>Framework:</strong> [React/Vue/Angular/Laravel Blade]</li>
                        <li><strong>Language:</strong> [JavaScript/TypeScript/PHP]</li>
                        <li><strong>Styling:</strong> [Bootstrap/Tailwind/Custom CSS]</li>
                        <li><strong>Libraries:</strong> [Library yang digunakan]</li>
                    </ul>
                    
                    <h2>⚡ Backend</h2>
                    <ul>
                        <li><strong>Framework:</strong> [Laravel/Node.js/Django]</li>
                        <li><strong>Database:</strong> [MySQL/PostgreSQL/MongoDB]</li>
                        <li><strong>API:</strong> [REST/GraphQL]</li>
                        <li><strong>Authentication:</strong> [JWT/Session/OAuth]</li>
                    </ul>
                    
                    <h2>🌐 Infrastructure</h2>
                    <ul>
                        <li><strong>Hosting:</strong> [AWS/DigitalOcean/Shared Hosting]</li>
                        <li><strong>Domain:</strong> [Domain website]</li>
                        <li><strong>SSL:</strong> [Let's Encrypt/CloudFlare]</li>
                        <li><strong>CDN:</strong> [CloudFlare/AWS CloudFront]</li>
                    </ul>
                    
                    <h2>🔧 Development Tools</h2>
                    <ul>
                        <li><strong>Version Control:</strong> Git</li>
                        <li><strong>Package Manager:</strong> [Composer/NPM/Yarn]</li>
                        <li><strong>Build Tools:</strong> [Vite/Webpack/Mix]</li>
                        <li><strong>Testing:</strong> [PHPUnit/Jest/Cypress]</li>
                    </ul>
                `
            },
            {
                title: 'Project Results & Metrics',
                description: 'Template untuk hasil dan metrics project',
                content: `
                    <h1>📊 Project Results & Metrics</h1>
                    
                    <h2>🎯 Achievements</h2>
                    <ul>
                        <li>✅ <strong>Achievement 1:</strong> [Pencapaian pertama]</li>
                        <li>✅ <strong>Achievement 2:</strong> [Pencapaian kedua]</li>
                        <li>✅ <strong>Achievement 3:</strong> [Pencapaian ketiga]</li>
                    </ul>
                    
                    <h2>📈 Performance Metrics</h2>
                    <table style="width: 100%; border-collapse: collapse;" border="1">
                        <thead>
                            <tr style="background-color: #007bff; color: white;">
                                <th style="padding: 12px;">Metric</th>
                                <th style="padding: 12px;">Value</th>
                                <th style="padding: 12px;">Target</th>
                                <th style="padding: 12px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px;">Page Load Time</td>
                                <td style="padding: 12px;">[X seconds]</td>
                                <td style="padding: 12px;">&lt; 3 seconds</td>
                                <td style="padding: 12px; background-color: #d4edda; color: #155724;">✅ Achieved</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px;">User Satisfaction</td>
                                <td style="padding: 12px;">[X%]</td>
                                <td style="padding: 12px;">&gt; 90%</td>
                                <td style="padding: 12px; background-color: #d4edda; color: #155724;">✅ Achieved</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px;">Monthly Users</td>
                                <td style="padding: 12px;">[X users]</td>
                                <td style="padding: 12px;">[Target users]</td>
                                <td style="padding: 12px; background-color: #d4edda; color: #155724;">✅ Achieved</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h2>🎉 Client Feedback</h2>
                    <blockquote>
                        <p>"[Masukkan testimonial atau feedback dari client di sini]"</p>
                        <p><strong>- [Nama Client], [Posisi]</strong></p>
                    </blockquote>
                    
                    <h2>🔗 Live Demo</h2>
                    <p>🌐 <strong>Website:</strong> <a href="[URL]" target="_blank">[URL Website]</a></p>
                    <p>📱 <strong>Mobile App:</strong> <a href="[URL]" target="_blank">[Link App Store/Play Store]</a></p>
                `
            }
        ],
        
        // Advanced settings
        browser_spellcheck: true,
        contextmenu: 'link image editimage table',
        object_resizing: true,
        resize: 'both',
        elementpath: true,
        statusbar: true,
        
        // Quickbars
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: 'quickimage quicktable',
        
        // Setup function
        setup: function (editor) {
            // Loading indicator
            editor.on('init', function () {
                document.getElementById('editor-loading')?.remove();
                console.log('Advanced TinyMCE Editor initialized successfully!');
            });
            
            // Auto-save functionality
            let autoSaveTimer;
            editor.on('input change', function () {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    const content = editor.getContent();
                    localStorage.setItem('project_detail_draft', content);
                    console.log('Content auto-saved to localStorage');
                }, 2000);
            });
            
            // Restore draft on load
            editor.on('init', function() {
                const draft = localStorage.getItem('project_detail_draft');
                if (draft && !editor.getContent()) {
                    editor.setContent(draft);
                    console.log('Draft restored from localStorage');
                }
            });
            
            // Custom button untuk clear draft
            editor.ui.registry.addButton('cleardraft', {
                text: 'Clear Draft',
                tooltip: 'Clear saved draft',
                onAction: function () {
                    localStorage.removeItem('project_detail_draft');
                    editor.notificationManager.open({
                        text: 'Draft cleared successfully!',
                        type: 'success',
                        timeout: 2000
                    });
                }
            });
        },
        
        // Error handling
        init_instance_callback: function(editor) {
            console.log('TinyMCE initialized for: ' + editor.id);
        }
    });

    // Dynamic Image Upload functionality (existing code)
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

    // Initialize
    updateRemoveButtons();
    attachImagePreview();
});
</script>
@endsection
