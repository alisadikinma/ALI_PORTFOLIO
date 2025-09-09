<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e($title); ?></h3>
                    <a href="<?php echo e(route('galeri.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Galeri
                    </a>
                </div>
                <div class="card-body">
                    <?php if(session('Sukses')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('Sukses')); ?>

                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('Error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('Error')); ?>

                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Thumbnail</th>
                                    <th width="20%">Nama Galeri</th>
                                    <th width="15%">Items</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Sequence</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $galeri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <?php if($row->thumbnail): ?>
                                                <img src="<?php echo e(asset('file/galeri/' . $row->thumbnail)); ?>" 
                                                     alt="<?php echo e($row->nama_galeri); ?>" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 80px; max-width: 120px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                     style="height: 80px; width: 120px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($row->nama_galeri); ?></strong>
                                            <?php if($row->company): ?>
                                                <br><small class="text-info"><?php echo e($row->company); ?></small>
                                            <?php endif; ?>
                                            <?php if($row->period): ?>
                                                <br><small class="text-warning"><?php echo e($row->period); ?></small>
                                            <?php endif; ?>
                                            <?php if($row->deskripsi_galeri): ?>
                                                <br><small class="text-muted"><?php echo Str::limit(strip_tags($row->deskripsi_galeri), 50); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php $__currentLoopData = $row->galleryItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-<?php echo e($item->type === 'image' ? 'primary' : 'warning'); ?>">
                                                        <?php echo e(ucfirst($item->type)); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <small class="text-muted"><?php echo e($row->galleryItems->count()); ?> items</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo e($row->status === 'Active' ? 'success' : 'secondary'); ?> status-badge">
                                                <?php echo e($row->status); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info sequence-badge"><?php echo e($row->sequence); ?></span>
                                        </td>
                                        <td>
                                            <?php echo e($row->created_at->format('d/m/Y')); ?><br>
                                            <small class="text-muted"><?php echo e($row->created_at->format('H:i')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('galeri.edit', $row->id_galeri)); ?>" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete(<?php echo e($row->id_galeri); ?>, '<?php echo e($row->nama_galeri); ?>')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data galeri</h5>
                                                <p class="text-muted">Klik tombol "Tambah Galeri" untuk mulai menambahkan galeri</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="galleryItemsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gallery Items</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="galleryItemsContent">
                    <!-- Gallery items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    
    <form id="deleteForm" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <script>
        function confirmDelete(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus galeri "${name}"?\n\nSemua items dalam galeri ini juga akan ikut terhapus.`)) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/galeri/${id}`;
                form.submit();
            }
        }

        function viewGalleryItems(galeriId, galeriName) {
            document.querySelector('#galleryItemsModal .modal-title').textContent = `Gallery Items - ${galeriName}`;
            
            // You can implement AJAX call here to load gallery items
            fetch(`/admin/galeri/${galeriId}/items`)
                .then(response => response.json())
                .then(data => {
                    let content = '';
                    if (data.items && data.items.length > 0) {
                        data.items.forEach(item => {
                            content += `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="preview-container" style="height: 80px;">
                                                    ${item.type === 'image' ? 
                                                        `<img src="${item.file_url}" class="img-fluid rounded" style="height: 100%; object-fit: cover;">` :
                                                        item.type === 'video' ?
                                                        `<video class="img-fluid rounded" style="height: 100%; object-fit: cover;"><source src="${item.file_url}"></video>` :
                                                        `<img src="${item.thumbnail_url}" class="img-fluid rounded" style="height: 100%; object-fit: cover;">`
                                                    }
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <h6>${item.title || 'Untitled'}</h6>
                                                <p class="text-muted small">${item.description || 'No description'}</p>
                                                <div class="d-flex gap-2">
                                                    <span class="badge badge-primary">${item.type}</span>
                                                    <span class="badge badge-info">Seq: ${item.sequence}</span>
                                                    <span class="badge badge-${item.status === 'Active' ? 'success' : 'secondary'}">${item.status}</span>
                                                    ${item.award ? `<span class="badge badge-warning">${item.award.nama_award}</span>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        content = '<div class="text-center text-muted py-4">Tidak ada items dalam galeri ini</div>';
                    }
                    
                    document.getElementById('galleryItemsContent').innerHTML = content;
                    $('#galleryItemsModal').modal('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('galleryItemsContent').innerHTML = '<div class="text-center text-danger py-4">Error loading gallery items</div>';
                    $('#galleryItemsModal').modal('show');
                });
        }
    </script>

    <style>
        /* Badge text color responsive to theme */
        .status-badge,
        .sequence-badge {
            color: black !important;
        }

        /* Dark mode styles */
        [data-theme="dark"] .status-badge,
        [data-theme="dark"] .sequence-badge,
        .dark-mode .status-badge,
        .dark-mode .sequence-badge,
        body.dark .status-badge,
        body.dark .sequence-badge {
            color: white !important;
        }

        /* Additional dark mode selectors for common frameworks */
        @media (prefers-color-scheme: dark) {
            .status-badge,
            .sequence-badge {
                color: white !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTO\resources\views/galeri/index.blade.php ENDPATH**/ ?>