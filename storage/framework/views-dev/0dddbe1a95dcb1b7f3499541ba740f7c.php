<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo e($title); ?></h3>
                <a href="<?php echo e(route('berita.create')); ?>" class="btn btn-dark btn-sm" style="float: right;">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body table table-responsive">
                <?php if($message = Session::get('Sukses')): ?>
                <div class="alert alert-success">
                    <p><?php echo e($message); ?></p>
                </div>
                <?php endif; ?>
                <table class="table table-bordered" id="example2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Isi</th>
                            <th>Thumbnail</th>
                            <th>Related Berita</th> 
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $berita; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($row->tanggal_berita)->isoFormat('dddd, D MMMM Y')); ?></td>
                            <td><?php echo e($row->judul_berita); ?></td>
                            <td><?php echo e($row->kategori_berita); ?></td>
                            <td><?php echo Str::limit($row->isi_berita, 100, '...'); ?></td>
                            <td>
                                <img src="<?php echo e(asset('file/berita/'.$row->gambar_berita)); ?>"
                                    alt="<?php echo e($row->judul_berita); ?>"
                                    style="width: 100px; height: 100px;">
                            </td>
                            <td>
                                <?php
                                $related = $row->relatedBerita();
                                ?>

                                <?php if($related->count() > 0): ?>
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="<?php echo e(route('berita.show', $item->slug_berita)); ?>">
                                            <?php echo e($item->judul_berita); ?>

                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <?php else: ?>
                                <span class="text-muted">Tidak ada</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?php echo e(route('berita.show', $row->slug_berita)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </a>
                                <a href="<?php echo e(route('berita.edit', $row->id_berita)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('berita.destroy', $row->id_berita)); ?>" method="POST" style="display: inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm btn-flat show_confirm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        let deleteForms = document.querySelectorAll('.show_confirm');
        deleteForms.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
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
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/berita/index.blade.php ENDPATH**/ ?>