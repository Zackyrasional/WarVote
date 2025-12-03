<?php $__env->startSection('title', 'Edit Polling'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">

    <h4 class="mb-3">Edit Polling</h4>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
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
                    <label class="form-label">Judul Polling</label>
                    <input
                        type="text"
                        class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        name="title"
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
                        <?php echo e(old('allow_multiple', $poll->allow_multiple) ? 'checked' : ''); ?>

                    >
                    <label for="allow_multiple" class="form-check-label">
                        Izinkan memilih lebih dari satu opsi
                    </label>
                </div>

                
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
                        value="<?php echo e(old('deadline', $poll->deadline ? \Carbon\Carbon::parse($poll->deadline)->format('Y-m-d\TH:i') : '')); ?>"
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
                    <label class="form-label d-block">Opsi yang Sudah Ada</label>

                    <?php $__empty_1 = true; $__currentLoopData = $poll->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Opsi</span>
                            <input
                                type="text"
                                name="options_existing[<?php echo e($option->id); ?>]"
                                class="form-control"
                                value="<?php echo e(old('options_existing.' . $option->id, $option->option_text)); ?>"
                            >
                            <span class="input-group-text">
                                <?php
                                    $hasVotes = \App\Models\PollVote::where('option_id', $option->id)->exists();
                                ?>
                                <?php if($hasVotes): ?>
                                    Tidak bisa dihapus (sudah ada suara)
                                <?php else: ?>
                                    Kosongkan teks untuk menghapus
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted">Belum ada opsi pada polling ini.</p>
                    <?php endif; ?>
                </div>

                
                <div class="mb-3">
                    <label class="form-label d-block">Tambah Opsi Baru</label>
                    <div id="new-options-container">
                        
                        <?php if(is_array(old('options_new'))): ?>
                            <?php $__currentLoopData = old('options_new'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Opsi Baru</span>
                                    <input
                                        type="text"
                                        name="options_new[]"
                                        class="form-control"
                                        value="<?php echo e($text); ?>"
                                    >
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-option">
                        + Tambah Opsi Baru
                    </button>
                    <small class="d-block text-muted mt-1">
                        Anda dapat menambahkan beberapa opsi baru di sini.
                    </small>
                </div>

                
                <div class="mb-3">
                    <small class="text-muted">
                        Pastikan total opsi (lama + baru yang tidak kosong) minimal 2.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.polls.index')); ?>" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnAdd = document.getElementById('btn-add-option');
    const container = document.getElementById('new-options-container');

    if (btnAdd && container) {
        btnAdd.addEventListener('click', function () {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';
            wrapper.innerHTML = `
                <span class="input-group-text">Opsi Baru</span>
                <input type="text" name="options_new[]" class="form-control">
            `;
            container.appendChild(wrapper);
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\New folder\WarVote\resources\views/admin/polls/edit.blade.php ENDPATH**/ ?>