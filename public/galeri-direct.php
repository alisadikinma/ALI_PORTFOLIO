<?php
// Direct Route Handler - bypass Laravel untuk testing
session_start();

// Simulasi routing sederhana
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($script_name, '', $request_uri);
$path = trim($path, '/');

// Remove query string
if (($pos = strpos($path, '?')) !== false) {
    $path = substr($path, 0, $pos);
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Galeri Create - ALI Portfolio</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>";

// Route handler
if (strpos($path, 'galeri/create-test') !== false || strpos($path, 'galeri/create') !== false) {
    
    echo "<div class='container mt-4'>
        <div class='row justify-content-center'>
            <div class='col-md-10'>
                <div class='card'>
                    <div class='card-header bg-primary text-white'>
                        <h4 class='mb-0'><i class='fas fa-images'></i> Tambah Galeri - Test Mode</h4>
                    </div>
                    <div class='card-body'>";
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<div class='alert alert-success'>
            <h5><i class='fas fa-check-circle'></i> Data Berhasil Diterima!</h5>
            <hr>
            <h6>Data yang dikirim:</h6>
            <pre>" . htmlspecialchars(print_r($_POST, true)) . "</pre>
        </div>";
        
        if (isset($_FILES) && !empty($_FILES)) {
            echo "<div class='alert alert-info'>
                <h6>Files yang diupload:</h6>
                <pre>" . htmlspecialchars(print_r($_FILES, true)) . "</pre>
            </div>";
        }
    }
    
    echo "<form action='' method='POST' enctype='multipart/form-data'>
        
        <!-- Gallery Info Section -->
        <div class='row'>
            <div class='col-md-8'>
                <!-- Nama Galeri -->
                <div class='mb-3'>
                    <label for='nama_galeri' class='form-label'>Nama Galeri <span class='text-danger'>*</span></label>
                    <input type='text' class='form-control' id='nama_galeri' name='nama_galeri' 
                           placeholder='Masukkan nama galeri...' required 
                           value='" . ($_POST['nama_galeri'] ?? '') . "'>
                </div>
                
                <!-- Company dan Period -->
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='mb-3'>
                            <label for='company' class='form-label'>Company</label>
                            <input type='text' class='form-control' id='company' name='company'
                                   placeholder='Telkomsel / BEKRAF / FENOX / etc...'
                                   value='" . ($_POST['company'] ?? '') . "'>
                            <small class='text-muted'>Informasi perusahaan/organisasi</small>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='mb-3'>
                            <label for='period' class='form-label'>Period</label>
                            <input type='text' class='form-control' id='period' name='period'
                                   placeholder='2024 / Q1-2024 / Jan-Dec 2024 / etc...'
                                   value='" . ($_POST['period'] ?? '') . "'>
                            <small class='text-muted'>Periode waktu terkait</small>
                        </div>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class='mb-3'>
                    <label for='deskripsi_galeri' class='form-label'>Deskripsi</label>
                    <textarea name='deskripsi_galeri' class='form-control' rows='4' 
                              placeholder='Deskripsi galeri...'>" . ($_POST['deskripsi_galeri'] ?? '') . "</textarea>
                </div>
                
                <!-- Award Selection -->
                <div class='mb-3'>
                    <label for='id_award' class='form-label'>Award (Optional)</label>
                    <select name='id_award' class='form-control'>
                        <option value=''>Tidak terkait dengan award tertentu</option>
                        <option value='1' " . (($_POST['id_award'] ?? '') == '1' ? 'selected' : '') . ">Best Innovation Award 2024</option>
                        <option value='2' " . (($_POST['id_award'] ?? '') == '2' ? 'selected' : '') . ">Excellence in Technology</option>
                        <option value='3' " . (($_POST['id_award'] ?? '') == '3' ? 'selected' : '') . ">Creative Achievement Award</option>
                    </select>
                    <small class='text-muted'>Award ini akan berlaku untuk semua item dalam galeri</small>
                </div>
            </div>
            
            <div class='col-md-4'>
                <!-- Thumbnail -->
                <div class='mb-3'>
                    <label for='thumbnail' class='form-label'>Thumbnail Galeri</label>
                    <input type='file' accept='image/*' name='thumbnail' class='form-control' id='thumbnail'>
                    <img class='d-none w-100 mt-2' id='previewThumb' src='#' alt='Preview thumbnail' 
                         style='max-height: 200px; object-fit: cover;'>
                    <small class='text-muted'>Gambar utama yang akan ditampilkan di daftar galeri</small>
                </div>
                
                <!-- Sequence dan Status -->
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='mb-3'>
                            <label for='sequence' class='form-label'>Urutan Tampil</label>
                            <input type='number' class='form-control' name='sequence' 
                                   value='" . ($_POST['sequence'] ?? '0') . "' min='0'>
                            <small class='text-muted'>Angka kecil tampil lebih dulu</small>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='mb-3'>
                            <label for='status' class='form-label'>Status</label>
                            <select name='status' class='form-control' required>
                                <option value='Active' " . (($_POST['status'] ?? 'Active') == 'Active' ? 'selected' : '') . ">Active</option>
                                <option value='Inactive' " . (($_POST['status'] ?? '') == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        
        <!-- Dynamic Gallery Items Section -->
        <div class='mb-3'>
            <h5><i class='fas fa-plus-circle'></i> Gallery Items</h5>
            <p class='text-muted'>Tambahkan item-item yang akan ditampilkan dalam galeri ini.</p>
            
            <div id='galleryItemsContainer'>
                <!-- Sample Gallery Item -->
                <div class='card mb-3 gallery-item'>
                    <div class='card-header d-flex justify-content-between align-items-center'>
                        <h6 class='mb-0'>Gallery Item #1</h6>
                        <button type='button' class='btn btn-danger btn-sm' onclick='removeItem(this)'>
                            <i class='fas fa-trash'></i>
                        </button>
                    </div>
                    <div class='card-body'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class='mb-3'>
                                    <label class='form-label'>Type <span class='text-danger'>*</span></label>
                                    <select name='gallery_items[0][type]' class='form-control item-type' required onchange='handleTypeChange(this)'>
                                        <option value=''>Pilih Type</option>
                                        <option value='image'>Image</option>
                                        <option value='youtube'>YouTube</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='mb-3'>
                                    <label class='form-label'>Sequence</label>
                                    <input type='number' name='gallery_items[0][sequence]' class='form-control' value='0' min='0'>
                                    <small class='text-muted'>Urutan tampil item</small>
                                </div>
                            </div>
                            <div class='col-md-2'>
                                <div class='mb-3'>
                                    <label class='form-label'>&nbsp;</label>
                                    <div class='item-preview' style='width: 60px; height: 60px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #999;'>
                                        Preview
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- File upload section -->
                        <div class='file-upload-section' style='display: none;'>
                            <div class='mb-3'>
                                <label class='form-label'>Image File</label>
                                <input type='file' name='gallery_items[0][file]' class='form-control file-input' accept='image/*'>
                                <small class='text-muted'>Upload gambar untuk gallery item</small>
                            </div>
                        </div>
                        
                        <!-- YouTube URL section -->
                        <div class='youtube-section' style='display: none;'>
                            <div class='mb-3'>
                                <label class='form-label'>YouTube URL</label>
                                <input type='url' name='gallery_items[0][youtube_url]' class='form-control youtube-input' 
                                       placeholder='https://www.youtube.com/watch?v=...'>
                                <small class='text-muted'>Masukkan URL YouTube video</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type='button' class='btn btn-success btn-sm' onclick='addGalleryItem()'>
                <i class='fas fa-plus'></i> Tambah Item
            </button>
        </div>
        
        <div class='d-flex justify-content-between'>
            <a href='#' class='btn btn-secondary'>
                <i class='fas fa-arrow-left'></i> Back
            </a>
            <button type='submit' class='btn btn-primary'>
                <i class='fas fa-save'></i> Save Gallery
            </button>
        </div>
    </form>";
    
    echo "</div></div></div></div></div>";
    
} else {
    // Route tidak ditemukan
    echo "<div class='container mt-4'>
        <div class='alert alert-warning'>
            <h4><i class='fas fa-exclamation-triangle'></i> Route Test</h4>
            <p><strong>Current Path:</strong> $path</p>
            <p><strong>Expected:</strong> galeri/create-test</p>
            <hr>
            <p><a href='/ALI_PORTFOLIO/public/galeri-direct.php/galeri/create-test' class='btn btn-primary'>Test Galeri Create</a></p>
        </div>
    </div>";
}

echo "
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
<script>
let itemIndex = 1;

function addGalleryItem() {
    const container = document.getElementById('galleryItemsContainer');
    const newItem = document.createElement('div');
    newItem.className = 'card mb-3 gallery-item';
    newItem.innerHTML = `
        <div class='card-header d-flex justify-content-between align-items-center'>
            <h6 class='mb-0'>Gallery Item #\${itemIndex + 1}</h6>
            <button type='button' class='btn btn-danger btn-sm' onclick='removeItem(this)'>
                <i class='fas fa-trash'></i>
            </button>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='mb-3'>
                        <label class='form-label'>Type <span class='text-danger'>*</span></label>
                        <select name='gallery_items[\${itemIndex}][type]' class='form-control item-type' required onchange='handleTypeChange(this)'>
                            <option value=''>Pilih Type</option>
                            <option value='image'>Image</option>
                            <option value='youtube'>YouTube</option>
                        </select>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class='mb-3'>
                        <label class='form-label'>Sequence</label>
                        <input type='number' name='gallery_items[\${itemIndex}][sequence]' class='form-control' value='0' min='0'>
                    </div>
                </div>
                <div class='col-md-2'>
                    <div class='mb-3'>
                        <label class='form-label'>&nbsp;</label>
                        <div class='item-preview' style='width: 60px; height: 60px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #999;'>
                            Preview
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='file-upload-section' style='display: none;'>
                <div class='mb-3'>
                    <label class='form-label'>Image File</label>
                    <input type='file' name='gallery_items[\${itemIndex}][file]' class='form-control file-input' accept='image/*'>
                </div>
            </div>
            
            <div class='youtube-section' style='display: none;'>
                <div class='mb-3'>
                    <label class='form-label'>YouTube URL</label>
                    <input type='url' name='gallery_items[\${itemIndex}][youtube_url]' class='form-control youtube-input' 
                           placeholder='https://www.youtube.com/watch?v=...'>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    itemIndex++;
}

function removeItem(button) {
    const items = document.querySelectorAll('.gallery-item');
    if (items.length > 1) {
        button.closest('.gallery-item').remove();
    } else {
        alert('Minimal harus ada 1 gallery item');
    }
}

function handleTypeChange(select) {
    const card = select.closest('.gallery-item');
    const fileSection = card.querySelector('.file-upload-section');
    const youtubeSection = card.querySelector('.youtube-section');
    
    fileSection.style.display = 'none';
    youtubeSection.style.display = 'none';
    
    if (select.value === 'image') {
        fileSection.style.display = 'block';
    } else if (select.value === 'youtube') {
        youtubeSection.style.display = 'block';
    }
}

// Thumbnail preview
document.getElementById('thumbnail').addEventListener('change', function() {
    const file = this.files[0];
    const preview = document.getElementById('previewThumb');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>";
?>
