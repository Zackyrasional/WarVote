

<?php $__env->startSection('title', 'Detail Aspirasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Detail Aspirasi</h3>
            <a href="<?php echo e(route('aspirasi.index')); ?>" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title mb-2"><?php echo e($aspirasi->judul); ?></h4>

                <p class="mb-1">
                    Oleh: <?php echo e($aspirasi->user->name ?? '-'); ?>

                </p>
                <p class="mb-1">
                    Tanggal: <?php echo e($aspirasi->tanggal_post); ?>

                </p>
                <p class="mb-2">
                    Status:
                    <?php if($aspirasi->status === 'approved'): ?>
                        <span class="badge bg-success">Disetujui Admin</span>
                    <?php elseif($aspirasi->status === 'rejected'): ?>
                        <span class="badge bg-danger">Ditolak Admin</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                    <?php endif; ?>
                </p>

                <hr>

                <h6>Deskripsi Aspirasi</h6>
                <p style="white-space: pre-line;">
                    <?php echo e($aspirasi->deskripsi); ?>

                </p>
            </div>
        </div>

        <?php
            $totalSetuju = $aspirasi->votings->where('nilai', 'setuju')->count();
            $totalTidakSetuju = $aspirasi->votings->where('nilai', 'tidak_setuju')->count();
            $totalSuara = $aspirasi->votings->count();
            $sudahVote = auth()->check()
                ? $aspirasi->votings->where('id_user', auth()->id())->first()
                : null;
        ?>

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3">Ringkasan Voting</h6>
                <div class="row text-center">
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Total Suara</div>
                            <div class="fs-4 fw-bold"><?php echo e($totalSuara); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Setuju</div>
                            <div class="fs-4 fw-bold"><?php echo e($totalSetuju); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Tidak Setuju</div>
                            <div class="fs-4 fw-bold"><?php echo e($totalTidakSetuju); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(auth()->guard()->check()): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Beri Suara Anda</h6>

                    <?php if($aspirasi->status !== 'approved'): ?>
                        <div class="alert alert-warning mb-0">
                            Aspirasi ini belum disetujui admin, sehingga voting belum dibuka.
                        </div>
                    <?php elseif($sudahVote): ?>
                        <div class="alert alert-info mb-0">
                            Anda sudah memberikan suara:
                            <strong><?php echo e($sudahVote->nilai === 'setuju' ? 'Setuju' : 'Tidak Setuju'); ?></strong>.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?php echo e(route('aspirasi.vote', $aspirasi->id_aspirasi)); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label d-block">Pilihan Suara</label>

                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input <?php $__errorArgs = ['nilai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        type="radio"
                                        name="nilai"
                                        id="nilai_setuju"
                                        value="setuju"
                                        <?php echo e(old('nilai') === 'setuju' ? 'checked' : ''); ?>

                                        required
                                    >
                                    <label class="form-check-label" for="nilai_setuju">
                                        Setuju
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input <?php $__errorArgs = ['nilai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        type="radio"
                                        name="nilai"
                                        id="nilai_tidak_setuju"
                                        value="tidak_setuju"
                                        <?php echo e(old('nilai') === 'tidak_setuju' ? 'checked' : ''); ?>

                                        required
                                    >
                                    <label class="form-check-label" for="nilai_tidak_setuju">
                                        Tidak Setuju
                                    </label>
                                </div>

                                <?php $__errorArgs = ['nilai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Kirim Suara
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Silakan login terlebih dahulu untuk memberikan suara.
            </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/aspirasi/show.blade.php ENDPATH**/ ?>