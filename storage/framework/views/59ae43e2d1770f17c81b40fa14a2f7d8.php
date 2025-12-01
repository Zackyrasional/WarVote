

<?php $__env->startSection('title', 'Detail Aspirasi (Admin)'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-2"><?php echo e($aspirasi->judul); ?></h3>
        <p class="text-muted mb-1">
            Diajukan oleh: <?php echo e($aspirasi->user->name ?? 'Warga'); ?>

        </p>
        <p class="text-muted mb-3">
            Tanggal: <?php echo e(\Carbon\Carbon::parse($aspirasi->tanggal_post)->format('d-m-Y H:i')); ?>

            | Status: <?php echo e(ucfirst($aspirasi->status)); ?>

        </p>

        <div class="card mb-3">
            <div class="card-body">
                <?php echo nl2br(e($aspirasi->deskripsi)); ?>

            </div>
        </div>

        <?php if($aspirasi->status === 'submitted'): ?>
            <form method="POST" action="<?php echo e(route('admin.aspirasi.approve', $aspirasi->id_aspirasi)); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-success" type="submit">Setujui</button>
            </form>

            <form method="POST" action="<?php echo e(route('admin.aspirasi.reject', $aspirasi->id_aspirasi)); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-danger" type="submit">Tolak</button>
            </form>
        <?php endif; ?>

        <a href="<?php echo e(route('admin.aspirasi.index')); ?>" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/aspirasi/show.blade.php ENDPATH**/ ?>