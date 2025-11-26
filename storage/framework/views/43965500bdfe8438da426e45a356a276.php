<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $__env->yieldContent('title', 'WarVote'); ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-warvote {
            background-color: #9a9ca1;
        }
        .navbar-warvote .navbar-brand,
        .navbar-warvote .nav-link,
        .navbar-warvote .navbar-nav .nav-link,
        .navbar-warvote .navbar-text,
        .navbar-warvote .dropdown-toggle {
            color: #ffffff !important;
        }
        .navbar-warvote .nav-link:hover,
        .navbar-warvote .dropdown-toggle:hover {
            color: #f0f0f0 !important;
        }
        .main-wrapper {
            padding-top: 1.5rem;
            padding-bottom: 2rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-warvote">
    <div class="container">
        <a class="navbar-brand"
           href="<?php echo e(auth()->check() ? route('home') : route('login')); ?>">
            WarVote
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarWarvote" aria-controls="navbarWarvote"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarWarvote">
            <ul class="navbar-nav me-auto">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('home')); ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('polls.create')); ?>">Buat Polling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('aspirasi.index')); ?>">Aspirasi</a>
                    </li>

                    <?php if(auth()->user()->role === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Dashboard Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.polls.index')); ?>">Kelola Polling</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.aspirasi.index')); ?>">Kelola Aspirasi</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if(auth()->guard()->guest()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('register')); ?>">Register</a>
                    </li>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if(auth()->user()->role === 'admin'): ?>
                                Admin RT
                            <?php else: ?>
                                <?php echo e(auth()->user()->name); ?>

                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <li class="dropdown-item-text">
                                <?php echo e(auth()->user()->email); ?>

                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container main-wrapper">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($msg); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
<?php /**PATH C:\xampp\php\warvote\resources\views/layouts/app.blade.php ENDPATH**/ ?>