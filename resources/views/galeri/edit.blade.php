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
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('Error'))
                        <div class="alert alert-danger">
                            <strong>Error!</strong> {{ session('Error') }}
                        </div>
                    @endif

                    <form action="{{ route('galeri.update', $galeri->id_galeri) }}" method="POST" enctype="multipart/form-data" id="galleryForm">
                        @csrf
                        @method('PUT')

                        {{-- Gallery Info Section --}}
                        <div class="row">
                            <div class="col-md-8">
                                {{-- Nama Galeri --}}
                                <div class="form-group mb-3">
                                    <label for="nama_galeri">Nama Galeri <abbr title="Required" style="color: red">*</abbr></label>
                                    <input type="text" class="form-control" id="nama_galeri"
                                           placeholder="Masukkan nama galeri..."
                                           name="nama_galeri" value="{{ old('nama_galeri', $galeri->nama_galeri) }}" required>
                                </div>

                                {{-- Company dan Period Row --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="company">Company</label>
                                            <input type="text" class="form-control" id="company"
                                                   placeholder="Telkomsel / BEKRAF / FENOX / etc..."
                                                   name="company" value="{{ old('company', $galeri->company) }}">
                                            <small class="text-muted">Informasi perusahaan/organisasi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="period">Period</label>
                                            <input type="text" class="form-control" id="period"
                                                   placeholder="2024 / Q1-2024 / Jan-Dec 2024 / etc..."
                                                   name="period" value="{{ old('period', $galeri->period) }}">
                                            <small class="text-muted">Periode waktu terkait</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="form-group mb-3">
                                    <label for="deskripsi_galeri">Deskripsi</label>
                                    <textarea name="deskripsi_galeri" id="editor" class="form-control" rows="6" placeholder="Deskripsi galeri...">{{ old('deskripsi_galeri', $galeri->deskripsi_galeri) }}</textarea>
                                </div>

                                {{-- Award untuk seluruh galeri --}}
                                <div class="form-group mb-3">
                                    <label for="id_award">Award (Optional)</label>
                                    <select name="id_award" class="form-control">
                                        <option value="">Tidak terkait dengan award tertentu</option>
                                        @foreach($awards as $award)
                                            <option value="{{ $award->id_award }}" {{ old('id_award', $galeri->galleryItems->first()->id_award ?? '') == $award->id_award ? 'selected' : '' }}>
                                                {{ $award->nama_award }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Award ini akan berlaku untuk semua item dalam galeri</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                {{-- Current Thumbnail --}}
                                @if($galeri->thumbnail)
                                    <div class="form-group mb-3">
                                        <label>Thumbnail Saat Ini</label>
                                        <div>
                                            <img src="{{ asset('file/galeri/' . $galeri->thumbnail) }}" 
                                                 alt="Current thumbnail" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif

                                {{-- New Thumbnail --}}
                                <div class="form-group mb-3">
                                    <label for="thumbnail">{{ $galeri->thumbnail ? 'Ganti Thumbnail' : 'Upload Thumbnail' }}</label>
                                    <input id="inputThumb" type="file" accept="image/*" name="thumbnail" class="form-control">
                                    <img class="d-none w-100 mt-2" id="previewThumb" src="#" alt="Preview thumbnail" style="max-height: 200px; object-fit: cover;">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah thumbnail</small>
                                </div>

                                {{-- Sequence dan Status Row --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="sequence">Urutan Tampil</label>
                                            <input type="number" class="form-control" name="sequence" value="{{ old('sequence', $galeri->sequence) }}" min="0">
                                            <small class="text-muted">Angka kecil tampil lebih dulu</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="status">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="Active" {{ old('status', $galeri->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ old('status', $galeri->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Dynamic Gallery Items Section --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Gallery Items</h5>
                                <button type="button" class="btn btn-success btn-sm" id="addGalleryItem">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                            </div>

                            <div id="galleryItemsContainer">
                                <!-- Existing gallery items will be loaded here -->
                            </div>

                            {{-- Hidden field to ensure gallery_items array exists --}}
                            <input type="hidden" name="gallery_items_exist" value="1">
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('galeri.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Update Gallery
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Gallery Item Template --}}
    <template id="galleryItemTemplate">
        <div class="card gallery-item mb-3" data-index="">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Gallery Item #<span class="item-number"></span></h6>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="gallery_items[0][type]" class="form-control item-type" required>
                                <option value="">Pilih Type</option>
                                <option value="image">Image</option>
                                <option value="youtube">YouTube</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sequence</label>
                            <input type="number" name="gallery_items[0][sequence]" class="form-control" value="0" min="0">
                            <small class="text-muted">Urutan tampil item</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="item-preview" style="width: 60px; height: 60px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #999;">
                                Preview
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File upload (for image) -->
                <div class="file-upload-section" style="display: none;">
                    <div class="form-group">
                        <label class="file-label">Image File</label>
                        <input type="file" name="gallery_items[0][file]" class="form-control file-input" accept="image/*">
                        <small class="text-muted">Upload gambar baru (kosongkan jika tidak ingin mengubah)</small>
                    </div>
                    <div class="existing-file-info" style="display: none;">
                        <small class="text-info">File saat ini: <span class="current-filename"></span></small>
                        <input type="hidden" name="gallery_items[0][existing_file]" class="existing-file-input">
                    </div>
                </div>

                <!-- YouTube URL (for youtube) -->
                <div class="youtube-section" style="display: none;">
                    <div class="form-group">
                        <label>YouTube URL</label>
                        <input type="url" name="gallery_items[0][youtube_url]" class="form-control youtube-input" placeholder="https://www.youtube.com/watch?v=...">
                        <small class="text-muted">Masukkan URL YouTube video</small>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Scripts --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });

        // Thumbnail preview
        document.getElementById('inputThumb').addEventListener('change', function() {
            previewImage(this, 'previewThumb');
        });

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).setAttribute('src', e.target.result);
                    document.getElementById(previewId).classList.remove("d-none");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Gallery Items Management
        let itemIndex = 0;
        
        // Existing gallery items from database
        const existingItems = [
            @foreach($galeri->galleryItems as $item)
            {
                type: '{{ $item->type }}',
                sequence: {{ $item->sequence ?? 0 }},
                file_name: '{{ $item->file_name }}',
                youtube_url: '{{ $item->youtube_url }}',
                status: '{{ $item->status ?? "Active" }}'
            }@if(!$loop->last),@endif
            @endforeach
        ];

        document.getElementById('addGalleryItem').addEventListener('click', function() {
            addGalleryItem();
        });

        function addGalleryItem(existingData = null) {
            const template = document.getElementById('galleryItemTemplate');
            const clone = template.content.cloneNode(true);
            
            // Update indices
            const card = clone.querySelector('.gallery-item');
            card.setAttribute('data-index', itemIndex);
            clone.querySelector('.item-number').textContent = itemIndex + 1;
            
            // Update input names with proper indices
            const inputs = clone.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace('[0]', `[${itemIndex}]`);
                }
            });

            // If existing data, populate the form
            if (existingData) {
                const typeSelect = clone.querySelector('.item-type');
                typeSelect.value = existingData.type;
                
                const sequenceInput = clone.querySelector('input[type="number"]');
                sequenceInput.value = existingData.sequence || 0;
                
                if (existingData.type === 'image' && existingData.file_name) {
                    const fileSection = clone.querySelector('.file-upload-section');
                    const existingFileInfo = clone.querySelector('.existing-file-info');
                    const currentFilename = clone.querySelector('.current-filename');
                    const existingFileInput = clone.querySelector('.existing-file-input');
                    const preview = clone.querySelector('.item-preview');
                    
                    fileSection.style.display = 'block';
                    existingFileInfo.style.display = 'block';
                    currentFilename.textContent = existingData.file_name;
                    existingFileInput.value = existingData.file_name;
                    
                    // Show preview
                    preview.innerHTML = `<img src="{{ asset('file/galeri/') }}/${existingData.file_name}" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
                
                if (existingData.type === 'youtube' && existingData.youtube_url) {
                    const youtubeSection = clone.querySelector('.youtube-section');
                    const youtubeInput = clone.querySelector('.youtube-input');
                    const preview = clone.querySelector('.item-preview');
                    
                    youtubeSection.style.display = 'block';
                    youtubeInput.value = existingData.youtube_url;
                    
                    // Show preview
                    const videoId = extractYouTubeId(existingData.youtube_url);
                    if (videoId) {
                        preview.innerHTML = `<img src="https://img.youtube.com/vi/${videoId}/mqdefault.jpg" style="width: 100%; height: 100%; object-fit: cover;">`;
                    }
                }
            }

            // Add event listeners
            const typeSelect = clone.querySelector('.item-type');
            typeSelect.addEventListener('change', function() {
                handleTypeChange(this);
            });

            const removeBtn = clone.querySelector('.remove-item');
            removeBtn.addEventListener('click', function() {
                removeGalleryItem(this);
            });

            const fileInput = clone.querySelector('.file-input');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    handleFilePreview(this);
                });
            }

            const youtubeInput = clone.querySelector('.youtube-input');
            if (youtubeInput) {
                youtubeInput.addEventListener('input', function() {
                    handleYoutubePreview(this);
                });
            }

            document.getElementById('galleryItemsContainer').appendChild(clone);
            itemIndex++;
        }

        function removeGalleryItem(button) {
            button.closest('.gallery-item').remove();
            updateItemNumbers();
        }

        function updateItemNumbers() {
            const items = document.querySelectorAll('.gallery-item');
            items.forEach((item, index) => {
                item.querySelector('.item-number').textContent = index + 1;
            });
        }

        function handleTypeChange(select) {
            const card = select.closest('.gallery-item');
            const fileSection = card.querySelector('.file-upload-section');
            const youtubeSection = card.querySelector('.youtube-section');

            // Hide all sections first
            fileSection.style.display = 'none';
            youtubeSection.style.display = 'none';

            switch(select.value) {
                case 'image':
                    fileSection.style.display = 'block';
                    break;
                case 'youtube':
                    youtubeSection.style.display = 'block';
                    break;
            }
        }

        function handleFilePreview(input) {
            const preview = input.closest('.gallery-item').querySelector('.item-preview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function handleYoutubePreview(input) {
            const preview = input.closest('.gallery-item').querySelector('.item-preview');
            const url = input.value;
            
            if (url) {
                const videoId = extractYouTubeId(url);
                if (videoId) {
                    preview.innerHTML = `<img src="https://img.youtube.com/vi/${videoId}/mqdefault.jpg" style="width: 100%; height: 100%; object-fit: cover;">`;
                } else {
                    preview.innerHTML = 'Invalid YouTube URL';
                }
            } else {
                preview.innerHTML = 'Preview';
            }
        }

        function extractYouTubeId(url) {
            const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }

        // Load existing gallery items on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Loading existing items:', existingItems);
            
            // Load existing items
            existingItems.forEach(function(item) {
                addGalleryItem(item);
            });
            
            // Add one empty item if no existing items
            if (existingItems.length === 0) {
                addGalleryItem();
            }
        });

        // Form submission validation
        document.getElementById('galleryForm').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('.gallery-item');
            
            if (items.length === 0) {
                e.preventDefault();
                alert('Anda harus menambahkan minimal 1 gallery item');
                return false;
            }

            // Validate each item
            let isValid = true;
            items.forEach((item, index) => {
                const type = item.querySelector('.item-type').value;
                if (!type) {
                    isValid = false;
                    alert(`Gallery Item #${index + 1}: Tipe harus dipilih`);
                    return;
                }

                if (type === 'image') {
                    const fileInput = item.querySelector('.file-input');
                    const existingFile = item.querySelector('.existing-file-input');
                    if ((!fileInput.files || fileInput.files.length === 0) && (!existingFile || !existingFile.value)) {
                        isValid = false;
                        alert(`Gallery Item #${index + 1}: File gambar harus diupload`);
                        return;
                    }
                }

                if (type === 'youtube') {
                    const youtubeUrl = item.querySelector('.youtube-input').value;
                    if (!youtubeUrl) {
                        isValid = false;
                        alert(`Gallery Item #${index + 1}: YouTube URL harus diisi`);
                        return;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // Disable submit button to prevent double submission
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        });
    </script>
@endsection
