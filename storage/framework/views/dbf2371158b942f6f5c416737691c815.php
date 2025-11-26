

<?php $__env->startSection('title', 'Kelola Polling'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-4">Kelola Polling Vote</h3>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pembuat</th>
                    <th>Status</th>
                    <th>Ditutup?</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($poll->title); ?></td>
                        <td><?php echo e(optional($poll->creator)->name); ?></td>
                        <td>
                            <?php if($poll->status === 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif($poll->status === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($poll->is_closed): ?>
                                <span class="text-success fw-bold">Ya</span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            
                            <?php if($poll->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('admin.polls.approve', $poll->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.polls.reject', $poll->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(!$poll->is_closed && $poll->status === 'approved'): ?>
                                <form method="POST" action="<?php echo e(route('admin.polls.close', $poll->id)); ?>"
                                      class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Tutup Voting
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <a href="<?php echo e(route('admin.polls.edit', $poll->id)); ?>"
                               class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            
                            <form method="POST" action="<?php echo e(route('admin.polls.delete', $poll->id)); ?>"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus polling ini?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada polling.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/admin/polls/index.blade.php ENDPATH**/ ?>