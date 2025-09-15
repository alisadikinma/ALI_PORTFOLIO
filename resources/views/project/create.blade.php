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
                        <label for="summary_description">Ringkasan Project</label>
                        <textarea name="summary_description" id="summary_description" cols="30" rows="3" class="form-control" placeholder="Masukkan ringkasan singkat project...">{{ old('summary_description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi Project <span class="text-danger">*</span></label>
                        <textarea name="description" id="editor" cols="30" rows="10" class="form-control" placeholder="Masukkan deskripsi lengkap project...">{{ old('description') }}</textarea>
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

<!-- CKEditor 5 with Full Features & Image Upload -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageIndex = 1;

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

    // Form validation
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
    });

    // Initialize
    updateRemoveButtons();
    attachImagePreview();
});
</script>
@endsection
