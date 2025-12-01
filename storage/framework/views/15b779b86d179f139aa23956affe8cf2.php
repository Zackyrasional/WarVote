<?php $__env->startSection('title', 'Kelola Polling'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Kelola Polling</h3>

            <a href="<?php echo e(route('admin.polls.create')); ?>" class="btn btn-primary">
                + Buat Polling Baru
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Polling</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 180px;">Batas Waktu</th>
                        <th style="width: 260px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($poll->title); ?></td>

                            
                            <td>
                                <?php if($poll->is_closed): ?>
                                    <span class="badge bg-secondary">
                                        Ditutup
                                    </span>
                                <?php else: ?>
                                    <?php if($poll->status === 'approved'): ?>
                                        <span class="badge bg-success">
                                            <?php echo e($poll->status_label); ?>

                                        </span>
                                    <?php elseif($poll->status === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <?php echo e($poll->status_label); ?>

                                        </span>
                                    <?php elseif($poll->status === 'rejected'): ?>
                                        <span class="badge bg-danger">
                                            <?php echo e($poll->status_label); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <?php echo e($poll->status_label); ?>

                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <?php if($poll->deadline): ?>
                                    <?php echo e(date('d-m-Y H:i', strtotime((string) $poll->deadline))); ?>

                                <?php else: ?>
                                    Tidak dibatasi
                                <?php endif; ?>
                            </td>

                            <td>
                                
                                <?php if(! $poll->is_closed): ?>
                                    <a href="<?php echo e(route('admin.polls.edit', $poll->id)); ?>"
                                       class="btn btn-primary btn-sm">
                                        Edit
                                    </a>
                                <?php endif; ?>

                                
                                <?php if(! $poll->is_closed): ?>
                                    <form method="POST"
                                          action="<?php echo e(route('admin.polls.close', $poll->id)); ?>"
                                          class="d-inline"
                                          onsubmit="return confirm('Tutup polling ini sekarang? Pemilih tidak bisa mengirim suara lagi, tetapi hasil tetap bisa dilihat.')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            Tutup Polling
                                        </button>
                                    </form>
                                <?php endif; ?>

                                
                                <form method="POST"
                                      action="<?php echo e(route('admin.polls.destroy', $poll->id)); ?>"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus polling ini beserta semua suara?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada polling yang dibuat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/polls/index.blade.php ENDPATH**/ ?>