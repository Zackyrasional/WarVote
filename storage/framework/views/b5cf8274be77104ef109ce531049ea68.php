<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-4">Dashboard Admin RT</h3>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Pengguna</h5>
                        <p class="display-6"><?php echo e($totalUsers); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Polling</h5>
                        <p class="display-6"><?php echo e($totalPolls); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Suara Masuk</h5>
                        <p class="display-6"><?php echo e($totalVotes); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?php echo e(route('admin.polls.index')); ?>" class="btn btn-primary mt-3">
            Kelola Polling
        </a>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>