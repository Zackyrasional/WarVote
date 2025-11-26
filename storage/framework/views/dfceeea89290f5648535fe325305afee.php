

<?php $__env->startSection('title', 'Buat Polling'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-4">Form Membuat Polling Vote</h3>

        <form method="POST" action="<?php echo e(route('polls.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Tujuan Polling</label>
                <input type="text" name="title" class="form-control"
                       value="<?php echo e(old('title')); ?>" placeholder="Contoh: Pemilihan Ketua RT 33" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Buat Pilihan</label>

                <?php
                    $oldOptions = old('options', ['', '', '']);
                ?>

                <div id="options-container">
                    <?php $__currentLoopData = $oldOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Pilihan <?php echo e($index+1); ?></span>
                            <input type="text" name="options[]" class="form-control"
                                   value="<?php echo e($opt); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addOption()">
                    + Tambah Pilihan
                </button>
            </div>

            
            <input type="hidden" name="allow_multiple" value="0">

            <div class="mb-3 form-check">
                <input
                    type="checkbox"
                    name="allow_multiple"
                    value="1"
                    class="form-check-input"
                    id="allow_multiple"
                    <?php echo e(old('allow_multiple', 0) == 1 ? 'checked' : ''); ?>

                >
                <label class="form-check-label" for="allow_multiple">
                    Izinkan beberapa pilihan (multi vote)
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal & Waktu Berakhir (opsional)</label>
                <input type="datetime-local" name="deadline" class="form-control"
                       value="<?php echo e(old('deadline')); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Buat Polling Vote</button>
            <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary">Batal</a>
        </form>

    </div>
</div>

<script>
    function addOption() {
        const container = document.getElementById('options-container');
        const count = container.querySelectorAll('.input-group').length + 1;

        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML =
            '<span class="input-group-text">Pilihan ' + count + '</span>' +
            '<input type="text" name="options[]" class="form-control">';

        container.appendChild(div);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\php\warvote\resources\views/polls/create.blade.php ENDPATH**/ ?>