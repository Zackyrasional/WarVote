<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AspirasiController;

Route::get('/', function () {
    return redirect()->route('home');
});

// AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ROUTE WAJIB LOGIN
Route::middleware('auth')->group(function () {

    // BERANDA
    Route::get('/home', [PollController::class, 'home'])->name('home');
    Route::post('/home/pilih-poll', [PollController::class, 'goFromHome'])->name('home.poll.go');

    // ASPIRASI (WARGA)
    Route::get('/aspirasi', [AspirasiController::class, 'indexApproved'])->name('aspirasi.index');
    Route::get('/aspirasi/create', [AspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/{aspirasi}', [AspirasiController::class, 'show'])->name('aspirasi.show');

    // POLLING (WARGA HANYA BISA VOTE, BUKAN BIKIN)
    Route::get('/polls/{poll}/vote', [PollController::class, 'showVoteForm'])->name('polls.vote.form');
    Route::post('/polls/{poll}/vote', [PollController::class, 'submitVote'])->name('polls.vote.submit');
    Route::get('/polls/{poll}/dashboard', [PollController::class, 'dashboard'])->name('polls.dashboard');
    Route::get('/polls/{poll}/result', [PollController::class, 'result'])->name('polls.result');

    // ROUTE KHUSUS ADMIN
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // KELOLA POLLING
        Route::get('/polls', [AdminController::class, 'pollIndex'])->name('polls.index');
        Route::get('/polls/create', [AdminController::class, 'pollCreate'])->name('polls.create');
        Route::post('/polls', [AdminController::class, 'pollStore'])->name('polls.store');
        Route::get('/polls/{poll}/edit', [AdminController::class, 'pollEdit'])->name('polls.edit');
        Route::put('/polls/{poll}', [AdminController::class, 'pollUpdate'])->name('polls.update');
        Route::delete('/polls/{poll}', [AdminController::class, 'pollDestroy'])->name('polls.destroy');
        Route::post('/polls/{poll}/close', [AdminController::class, 'pollClose'])->name('polls.close');

        // KELOLA ASPIRASI
        Route::get('/aspirasi', [AdminController::class, 'aspirasiIndex'])->name('aspirasi.index');
        Route::get('/aspirasi/{aspirasi}', [AdminController::class, 'aspirasiShow'])->name('aspirasi.show');
        Route::post('/aspirasi/{aspirasi}/approve', [AdminController::class, 'aspirasiApprove'])->name('aspirasi.approve');
        Route::post('/aspirasi/{aspirasi}/reject', [AdminController::class, 'aspirasiReject'])->name('aspirasi.reject');
    });
});
