

<?php $__env->startSection('title', 'Edit Polling'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-4">Edit Polling</h3>

        <form method="POST" action="<?php echo e(route('admin.polls.update', $poll->id)); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Judul Polling</label>
                <input type="text" name="title" class="form-control"
                       value="<?php echo e(old('title', $poll->title)); ?>" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="allow_multiple" class="form-check-input"
                       id="allow_multiple"
                    <?php echo e(old('allow_multiple', $poll->allow_multiple) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="allow_multiple">
                    Izinkan beberapa pilihan (multi vote)
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline (opsional)</label>
                <input type="datetime-local" name="deadline" class="form-control"
                       value="<?php echo e($poll->deadline ? $poll->deadline->format('Y-m-d\TH:i') : ''); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?php echo e(route('admin.polls.index')); ?>" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/admin/polls/edit.blade.php ENDPATH**/ ?>