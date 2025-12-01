<?php $__env->startSection('title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-4">Selamat datang, <?php echo e($user->name); ?>!</h3>

        
        <div class="card mb-4">
            <div class="card-header">
                Pilih atau lihat hasil polling
            </div>
            <div class="card-body">
                <?php if($polls->isEmpty()): ?>
                    <p class="text-muted mb-0">
                        Belum ada polling yang tersedia.
                    </p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('home.poll.go')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Pilih polling</label>
                            <select name="poll_id" class="form-select" required>
                                <option value="">-- pilih polling --</option>
                                <?php $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $now = now();
                                        $deadline = $poll->deadline;
                                        $waktuHabis = false;
                                        if ($poll->is_closed) {
                                            $waktuHabis = true;
                                        } elseif ($deadline && $now->greaterThanOrEqualTo($deadline)) {
                                            $waktuHabis = true;
                                        }
                                    ?>

                                    <option value="<?php echo e($poll->id); ?>">
                                        <?php echo e($poll->title); ?>

                                        <?php if($waktuHabis): ?>
                                            (Ditutup / Selesai - lihat hasil)
                                        <?php else: ?>
                                            (Aktif - bisa ikut voting)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lanjut</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                Aspirasi Warga
            </div>
            <div class="card-body">
                <p>
                    Anda dapat menyampaikan aspirasi kepada pengurus RT melalui sistem WarVote.
                </p>
                <a href="<?php echo e(route('aspirasi.create')); ?>" class="btn btn-success">
                    Ajukan Aspirasi
                </a>
                <a href="<?php echo e(route('aspirasi.index')); ?>" class="btn btn-outline-secondary">
                    Lihat Aspirasi Disetujui
                </a>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/polls/home.blade.php ENDPATH**/ ?>