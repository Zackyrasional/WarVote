<?php $__env->startSection('title', 'Hasil Voting'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3">Hasil Voting</h3>
        <h4 class="mb-4"><?php echo e($poll->title); ?></h4>

        <?php if($totalVotes == 0): ?>
            <p class="text-muted">Belum ada suara.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pilihan</th>
                        <th>Suara</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $count = $opt->votes_count;
                            $pct = $totalVotes > 0 ? round($count * 100 / $totalVotes, 2) : 0;
                        ?>
                        <tr>
                            <td><?php echo e($opt->option_text); ?></td>
                            <td><?php echo e($count); ?></td>
                            <td><?php echo e($pct); ?>%</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary">
            Kembali ke Beranda
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/polls/result.blade.php ENDPATH**/ ?>