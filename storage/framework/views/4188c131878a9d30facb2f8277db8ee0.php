

<?php $__env->startSection('title', 'Kelola Aspirasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <h3>Kelola Aspirasi Warga</h3>
    <p class="text-muted mb-0">
        Admin dapat menyetujui atau menolak aspirasi warga.
    </p>
</div>

<?php if($aspirasis->isEmpty()): ?>
    <div class="alert alert-info">Belum ada aspirasi masuk.</div>
<?php else: ?>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pengaju</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $aspirasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $asp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($aspirasis->firstItem() + $i); ?></td>

                    <td>
                        <div class="fw-semibold"><?php echo e($asp->judul); ?></div>
                        <small class="text-muted">
                            <?php echo e(Str::limit($asp->deskripsi, 70)); ?>

                        </small>
                    </td>

                    <td><?php echo e($asp->user->name); ?></td>
                    <td><?php echo e($asp->tanggal_post); ?></td>

                    <td>
                        <?php if($asp->status === 'approved'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php elseif($asp->status === 'rejected'): ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <a href="<?php echo e(route('aspirasi.show', $asp->id_aspirasi)); ?>"
                               class="btn btn-sm btn-outline-secondary">
                                Detail
                            </a>

                            <?php if($asp->status !== 'approved'): ?>
                            <form method="POST"
                                  action="<?php echo e(route('admin.aspirasi.approve', $asp->id_aspirasi)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-success"
                                        onclick="return confirm('Setujui aspirasi ini?')">
                                    Setujui
                                </button>
                            </form>
                            <?php endif; ?>

                            <?php if($asp->status !== 'rejected'): ?>
                            <form method="POST"
                                  action="<?php echo e(route('admin.aspirasi.reject', $asp->id_aspirasi)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tolak aspirasi ini?')">
                                    Tolak
                                </button>
                            </form>
                            <?php endif; ?>

                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="mt-3">
            <?php echo e($aspirasis->links()); ?>

        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/admin/aspirasi/index.blade.php ENDPATH**/ ?>