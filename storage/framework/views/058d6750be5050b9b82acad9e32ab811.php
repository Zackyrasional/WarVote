

<?php $__env->startSection('title', 'Voting'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3"><?php echo e($poll->title); ?></h3>

        <?php if($alreadyVoted): ?>
            <div class="alert alert-info">
                Anda sudah memberikan suara untuk polling ini.
            </div>
            <a href="<?php echo e(route('polls.dashboard', $poll->id)); ?>" class="btn btn-primary">
                Lihat Dashboard
            </a>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('polls.vote.submit', $poll->id)); ?>">
                <?php echo csrf_field(); ?>

                <p>Pilih opsi voting:</p>

                <?php if($poll->allow_multiple): ?>
                    <?php $__currentLoopData = $poll->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox"
                                   name="options[]" value="<?php echo e($opt->id); ?>" id="opt<?php echo e($opt->id); ?>">
                            <label class="form-check-label" for="opt<?php echo e($opt->id); ?>">
                                <?php echo e($opt->option_text); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php $__currentLoopData = $poll->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="option_id" value="<?php echo e($opt->id); ?>" id="opt<?php echo e($opt->id); ?>">
                            <label class="form-check-label" for="opt<?php echo e($opt->id); ?>">
                                <?php echo e($opt->option_text); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary mt-3">Kirim Suara</button>
                <a href="<?php echo e(route('polls.dashboard', $poll->id)); ?>" class="btn btn-secondary mt-3">
                    Lihat Dashboard
                </a>
            </form>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/polls/vote.blade.php ENDPATH**/ ?>