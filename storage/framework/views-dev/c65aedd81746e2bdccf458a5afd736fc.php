<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo e($title); ?></h3>
            </div>
            <div class="card-body">
                <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('project.update', $project->id_project)); ?>" method="POST" enctype="multipart/form-data" id="projectForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="client_name">Nama Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" name="client_name" value="<?php echo e(old('client_name', $project->client_name)); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="location">Lokasi Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo e(old('location', $project->location)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="project_name">Nama Project <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo e(old('project_name', $project->project_name)); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_category">Kategori Project <span class="text-danger">*</span></label>
                                <select name="project_category" id="project_category" class="form-control">
                                    <option value="">Pilih Kategori Project</option>
                                    <option value="Artificial Intelligence" <?php echo e(old('project_category', $project->project_category) == 'Artificial Intelligence' ? 'selected' : ''); ?>>Artificial Intelligence</option>
                                    <option value="Web Application" <?php echo e(old('project_category', $project->project_category) == 'Web Application' ? 'selected' : ''); ?>>Web Application</option>
                                    <option value="Mobile Application" <?php echo e(old('project_category', $project->project_category) == 'Mobile Application' ? 'selected' : ''); ?>>Mobile Application</option>
                                    <option value="Data Visualization" <?php echo e(old('project_category', $project->project_category) == 'Data Visualization' ? 'selected' : ''); ?>>Data Visualization</option>
                                    <option value="API Development" <?php echo e(old('project_category', $project->project_category) == 'API Development' ? 'selected' : ''); ?>>API Development</option>
                                    <option value="Automation" <?php echo e(old('project_category', $project->project_category) == 'Automation' ? 'selected' : ''); ?>>Automation</option>
                                    <option value="Internet of Things" <?php echo e(old('project_category', $project->project_category) == 'Internet of Things' ? 'selected' : ''); ?>>Internet of Things</option>
                                    <option value="Blockchain" <?php echo e(old('project_category', $project->project_category) == 'Blockchain' ? 'selected' : ''); ?>>Blockchain</option>
                                    <option value="Machine Learning" <?php echo e(old('project_category', $project->project_category) == 'Machine Learning' ? 'selected' : ''); ?>>Machine Learning</option>
                                    <option value="System Integration" <?php echo e(old('project_category', $project->project_category) == 'System Integration' ? 'selected' : ''); ?>>System Integration</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="url_project">URL Project</label>
                                <input type="url" class="form-control" id="url_project" name="url_project" value="<?php echo e(old('url_project', $project->url_project)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="sequence">Urutan Tampilan</label>
                        <input type="number" class="form-control" id="sequence" name="sequence" value="<?php echo e(old('sequence', $project->sequence)); ?>" min="0">
                    </div>

                    <div class="form-group mb-3">
                        <label for="summary_description">Ringkasan Project</label>
                        <textarea name="summary_description" id="summary_description" cols="30" rows="3" class="form-control"><?php echo e(old('summary_description', $project->summary_description ?? '')); ?></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi Project <span class="text-danger">*</span></label>
                        <textarea name="description" id="editor" cols="30" rows="10" class="form-control"><?php echo e(old('description', $project->description)); ?></textarea>
                    </div>

                    <!-- Existing Images Section -->
                    <?php
                        $existingImages = $project->images ? json_decode($project->images, true) : [];
                    ?>
                    
                    <?php if(!empty($existingImages)): ?>
                    <div class="form-group mb-3">
                        <label>Gambar Project Saat Ini</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="row" id="existingImagesContainer">
                                    <?php $__currentLoopData = $existingImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 mb-3 existing-image-item" data-image="<?php echo e($image); ?>">
                                        <div class="card">
                                            <img src="<?php echo e(asset('images/projects/' . $image)); ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Project Image">
                                            <div class="card-body p-2">
                                                <div class="form-check mb-2">
                                                    <input type="radio" name="featured_image_index" value="<?php echo e($index); ?>" 
                                                           class="form-check-input" id="existing_featured_<?php echo e($index); ?>"
                                                           <?php echo e($project->featured_image == $image ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="existing_featured_<?php echo e($index); ?>">
                                                        <small>Gambar Utama</small>
                                                    </label>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm delete-existing-image" 
                                                        data-image="<?php echo e($image); ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div id="deleteImagesContainer"></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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
                            <a href="<?php echo e(route('project.index')); ?>" class="btn btn-secondary">
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

<!-- CKEditor 5 with Full Features & Image Upload -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

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
    let imageIndex = 0;

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
                            data.append('_token', '<?php echo e(csrf_token()); ?>');
                            
                            fetch('<?php echo e(route("image.upload")); ?>', {
                                method: 'POST',
                                body: data,
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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

    // Initialize
    attachRemoveListeners();
    attachImagePreview();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/project/edit.blade.php ENDPATH**/ ?>