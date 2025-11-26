<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'WarVote')</title>

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
           href="{{ auth()->check() ? route('home') : route('login') }}">
            WarVote
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarWarvote" aria-controls="navbarWarvote"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarWarvote">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('polls.create') }}">Buat Polling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('aspirasi.index') }}">Aspirasi</a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.polls.index') }}">Kelola Polling</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.aspirasi.index') }}">Kelola Aspirasi</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->role === 'admin')
                                Admin RT
                            @else
                                {{ auth()->user()->name }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <li class="dropdown-item-text">
                                {{ auth()->user()->email }}
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container main-wrapper">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
