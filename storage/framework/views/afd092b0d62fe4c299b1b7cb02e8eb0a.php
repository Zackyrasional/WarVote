<?php $__env->startSection('title', 'Dashboard Voting'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-3">Dashboard Voting</h3>
        <h4 class="mb-4"><?php echo e($poll->title); ?></h4>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Suara Masuk</h5>
                        <p class="display-6"><?php echo e($totalVotes); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Pemilih Unik</h5>
                        <p class="display-6"><?php echo e($totalVoters); ?></p>
                        <small class="text-muted">
                            <?php echo e($percentage); ?>% dari <?php echo e($totalUsers); ?> warga terdaftar
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Status Waktu</h5>
                        <?php if($waktuHabis): ?>
                            <p class="text-danger fw-bold">Waktu voting selesai</p>
                        <?php elseif($deadline): ?>
                            <p class="mb-1">Sisa waktu:</p>
                            <p class="fw-bold">
                                <?php echo e($sisaJam); ?> jam <?php echo e($sisaMenit); ?> menit
                            </p>
                            <small class="text-muted">
                                Sampai <?php echo e(date('d-m-Y H:i', strtotime((string) $deadline))); ?>

                            </small>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada batas waktu.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <h5>Perolehan Suara Sementara</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilihan</th>
                    <th>Jumlah Suara</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $poll->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $count = $opt->votes()->count();
                    ?>
                    <tr>
                        <td><?php echo e($opt->option_text); ?></td>
                        <td><?php echo e($count); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <?php if($waktuHabis): ?>
            <a href="<?php echo e(route('polls.result', $poll->id)); ?>" class="btn btn-success">
                Lihat Hasil Akhir
            </a>
        <?php endif; ?>

        <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary">
            Kembali ke Beranda
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/polls/dashboard.blade.php ENDPATH**/ ?>