<?php $__env->startSection('title', 'Kelola Aspirasi Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <h3 class="mb-3">Kelola Aspirasi Warga</h3>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul</th>
                        <th>Pengaju</th>
                        <th style="width: 170px;">Tanggal</th>
                        <th style="width: 130px;">Status</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $aspirasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $aspirasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($aspirasi->judul); ?></td>
                            <td><?php echo e($aspirasi->user?->name ?? '-'); ?></td>

                            
                            <td>
                                <?php echo e($aspirasi->tanggal_post 
                                    ? date('d-m-Y H:i', strtotime($aspirasi->tanggal_post)) 
                                    : '-'); ?>

                            </td>

                            <td>
                                <?php if($aspirasi->status === 'submitted'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <?php echo e($aspirasi->status_label); ?>

                                    </span>
                                <?php elseif($aspirasi->status === 'approved'): ?>
                                    <span class="badge bg-success">
                                        <?php echo e($aspirasi->status_label); ?>

                                    </span>
                                <?php elseif($aspirasi->status === 'rejected'): ?>
                                    <span class="badge bg-danger">
                                        <?php echo e($aspirasi->status_label); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?php echo e($aspirasi->status_label); ?>

                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?php echo e(route('admin.aspirasi.show', $aspirasi->id_aspirasi)); ?>"
                                   class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                <?php if($aspirasi->status === 'submitted'): ?>
                                    <form method="POST"
                                          action="<?php echo e(route('admin.aspirasi.approve', $aspirasi->id_aspirasi)); ?>"
                                          class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Setujui
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="<?php echo e(route('admin.aspirasi.reject', $aspirasi->id_aspirasi)); ?>"
                                          class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Tolak
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada aspirasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/aspirasi/index.blade.php ENDPATH**/ ?>