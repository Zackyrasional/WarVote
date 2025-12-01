<?php $__env->startSection('title', 'Ajukan Aspirasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Form Pengajuan Aspirasi</h3>

        <form method="POST" action="<?php echo e(route('aspirasi.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Judul Aspirasi</label>
                <input type="text" name="judul" class="form-control"
                       value="<?php echo e(old('judul')); ?>" required maxlength="200">
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi / Uraian Aspirasi</label>
                <textarea name="deskripsi" class="form-control" rows="5" required><?php echo e(old('deskripsi')); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Aspirasi</button>
            <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/aspirasi/create.blade.php ENDPATH**/ ?>