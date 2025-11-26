

<?php $__env->startSection('title', 'Aspirasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Aspirasi Disetujui</h3>

    <?php if(auth()->user()->role === 'user'): ?>
        <a href="<?php echo e(route('aspirasi.create')); ?>" class="btn btn-success">+ Buat Aspirasi</a>
    <?php endif; ?>
</div>

<?php if($aspirasis->isEmpty()): ?>
    <div class="alert alert-info">Belum ada aspirasi yang disetujui.</div>
<?php else: ?>
    <div class="list-group">
        <?php $__currentLoopData = $aspirasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('aspirasi.show', $a->id_aspirasi)); ?>"
               class="list-group-item list-group-item-action">
                <div class="fw-bold"><?php echo e($a->judul); ?></div>
                <small class="text-muted">Oleh: <?php echo e($a->user->name); ?> | <?php echo e($a->tanggal_post); ?></small>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-3">
        <?php echo e($aspirasis->links()); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/aspirasi/index.blade.php ENDPATH**/ ?>