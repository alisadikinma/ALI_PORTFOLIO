<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo e($title); ?></h3>
                <a href="<?php echo e(route('project.create')); ?>" class="btn btn-dark btn-sm" style="float: right;">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body table table-responsive">
                <?php if($message = Session::get('Sukses')): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><?php echo e($message); ?></p>
                </div>
                <?php endif; ?>

                <?php if($message = Session::get('error')): ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><?php echo e($message); ?></p>
                </div>
                <?php endif; ?>

                <table class="table table-bordered" id="example3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Project</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $project; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($row->project_name ?? 'N/A'); ?></td>
                            <td><?php echo e($row->project_category ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($row->status == 'Active' ? 'success' : 'secondary'); ?>">
                                    <?php echo e($row->status ?? 'N/A'); ?>

                                </span>
                            </td>
                           
                            <td>
                                <?php if($row->featured_image): ?>
                                    <img src="<?php echo e(asset('images/projects/' . $row->featured_image)); ?>" alt="<?php echo e($row->project_name); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 12px; color: #6c757d;">
                                        No Image
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('project.edit', $row->id_project)); ?>" class="btn btn-primary btn-xs">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo e(route('project.showAdmin', $row->id_project)); ?>" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <form action="<?php echo e(route('project.destroy', $row->id_project)); ?>" method="POST" style="display: inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-xs btn-flat show_confirm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="p-4">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Projects Found</h5>
                                    <p class="text-muted">Start by creating your first project!</p>
                                    <a href="<?php echo e(route('project.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Project
                                    </a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // tangkap semua tombol dengan class show_confirm
        let deleteForms = document.querySelectorAll('.show_confirm');
        deleteForms.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // hentikan submit form dulu
                let form = this.closest("form");
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // lanjut submit jika klik "Ya"
                    }
                });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/project/index.blade.php ENDPATH**/ ?>