<?php $__env->startSection('content'); ?>

<div class="row">

    
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Portfolio</h6>
                    <h3 class="mb-0"><?php echo e($countProject); ?></h3>
                </div>
                <div>
                    <i class="fas fa-briefcase fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-success">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Galeri</h6>
                    <h3 class="mb-0"><?php echo e($countGaleri); ?></h3>
                </div>
                <div>
                    <i class="fas fa-image fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Artikel</h6>
                    <h3 class="mb-0"><?php echo e($countBerita); ?></h3>
                </div>
                <div>
                    <i class="fas fa-newspaper fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-danger">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Pesan</h6>
                    <h3 class="mb-0"><?php echo e($countPesan); ?></h3>
                </div>
                <div>
                    <i class="fas fa-envelope fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pesan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Layanan</th>
                                <th>Budget</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($key+1); ?></td>
                                    <td><?php echo e($contact->full_name); ?></td>
                                    <td><?php echo e($contact->email); ?></td>
                                    <td><?php echo e($contact->subject); ?></td>
                                    <td><?php echo e($contact->service); ?></td>
                                    <td><?php echo e($contact->budget); ?></td>
                                    <td><?php echo e(Str::limit($contact->message, 50)); ?></td>
                                    <td><?php echo e($contact->created_at->format('d M Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada pesan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/dashboard/index.blade.php ENDPATH**/ ?>