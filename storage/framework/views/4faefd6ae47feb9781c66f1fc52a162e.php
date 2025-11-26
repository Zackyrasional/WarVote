

<?php $__env->startSection('title', 'Beranda WarVote'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">

        <div class="mb-4">
            <h2>WarVote</h2>
            <p>Selamat datang, <?php echo e($user->name); ?>!</p>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Pilih Sesi Voting
            </div>
            <div class="card-body">
                <?php if($polls->isEmpty()): ?>
                    <p class="text-muted">Belum ada polling yang disetujui admin.</p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('polls.go')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Pilih Polling</label>
                            <select name="poll_id" class="form-select" required>
                                <option value="">-- Pilih salah satu --</option>
                                <?php $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($poll->id); ?>">
                                        <?php echo e($poll->title); ?>

                                        <?php if($poll->deadline): ?>
                                            (s.d. <?php echo e($poll->deadline->format('d-m-Y H:i')); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lanjut</button>
                        <a href="<?php echo e(route('polls.create')); ?>" class="btn btn-secondary">Buat Polling Baru</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/polls/home.blade.php ENDPATH**/ ?>