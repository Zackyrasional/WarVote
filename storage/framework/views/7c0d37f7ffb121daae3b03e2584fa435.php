<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Edit Polling</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <p>Terjadi kesalahan pada input:</p>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="<?php echo e(route('admin.polls.update', $poll->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-3">
                    <label class="form-label">Judul / Tujuan Polling</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('title', $poll->title)); ?>"
                        required
                    >
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($error); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-3 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="allow_multiple"
                        name="allow_multiple"
                        value="1"
                        <?php echo e(old('allow_multiple', $poll->allow_multiple) ? 'checked' : ''); ?>

                    >
                    <label class="form-check-label" for="allow_multiple">
                        Izinkan beberapa pilihan
                    </label>
                </div>

                
                <?php
                    use Illuminate\Support\Carbon;

                    // Nilai awal untuk input datetime-local
                    $deadlineValue = old('deadline');

                    if (!$deadlineValue && $poll->deadline) {
                        try {
                            // Paksa parse, baik string maupun Carbon tetap aman
                            $deadlineValue = Carbon::parse($poll->deadline)->format('Y-m-d\TH:i');
                        } catch (\Exception $e) {
                            $deadlineValue = '';
                        }
                    }
                ?>

                <div class="mb-3">
                    <label class="form-label">Tanggal Berakhir (opsional)</label>
                    <input
                        type="datetime-local"
                        class="form-control <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        name="deadline"
                        value="<?php echo e($deadlineValue); ?>"
                    >
                    <small class="text-muted">
                        Kosongkan jika polling tidak memiliki batas waktu.
                    </small>
                    <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-3">
                    <label class="form-label d-block">Status Polling</label>
                    <span class="badge
                        <?php if($poll->status === 'approved'): ?> bg-success
                        <?php elseif($poll->status === 'rejected'): ?> bg-danger
                        <?php else: ?> bg-warning text-dark
                        <?php endif; ?>
                    ">
                        <?php echo e($poll->status_label); ?>

                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Kondisi Polling</label>
                    <?php if($poll->is_closed): ?>
                        <span class="badge bg-secondary">Sudah Ditutup</span>
                    <?php else: ?>
                        <span class="badge bg-primary">Sedang Berjalan</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Informasi Pilihan</label>
                    <small class="text-muted">
                        Pengelolaan pilihan (tambah/hapus opsi) tidak dilakukan di form ini.
                        Opsi tetap seperti saat polling dibuat.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.polls.index')); ?>" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/polls/edit.blade.php ENDPATH**/ ?>