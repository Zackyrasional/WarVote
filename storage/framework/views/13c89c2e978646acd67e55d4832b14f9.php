<?php $__env->startSection('title', 'Aspirasi Disetujui'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <h3 class="mb-4">Daftar Aspirasi Disetujui</h3>

        <?php if($aspirasis->isEmpty()): ?>
            <div class="alert alert-info">
                Belum ada aspirasi yang disetujui.
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php $__currentLoopData = $aspirasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aspirasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('aspirasi.show', $aspirasi->id_aspirasi)); ?>"
                       class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?php echo e($aspirasi->judul); ?></h5>
                            <small><?php echo e(\Carbon\Carbon::parse($aspirasi->tanggal_post)->format('d-m-Y H:i')); ?></small>
                        </div>
                        <p class="mb-1 text-muted">
                            <?php echo e(\Illuminate\Support\Str::limit($aspirasi->deskripsi, 120)); ?>

                        </p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/aspirasi/index.blade.php ENDPATH**/ ?>