<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/*
| AUTH
*/
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
| ROUTE USER TERLOGIN
*/
Route::middleware('auth')->group(function () {

    // WarVote – polling
    Route::get('/home', [PollController::class, 'home'])->name('home');

    Route::post('/polls/go', [PollController::class, 'goFromHome'])->name('polls.go');

    Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');

    Route::get('/polls/{poll}/vote', [PollController::class, 'showVoteForm'])->name('polls.vote.form');
    Route::post('/polls/{poll}/vote', [PollController::class, 'submitVote'])->name('polls.vote.submit');

    Route::get('/polls/{poll}/dashboard', [PollController::class, 'dashboard'])->name('polls.dashboard');
    Route::get('/polls/{poll}/result', [PollController::class, 'result'])->name('polls.result');

    // Aspirasi (warga)
    Route::get('/aspirasi', [AspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/create', [AspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/{id}', [AspirasiController::class, 'show'])->name('aspirasi.show');
});

/*
| ROUTE ADMIN
*/
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // CRUD Polling
    Route::get('/admin/polls', [AdminController::class, 'polls'])
        ->name('admin.polls.index');

    Route::get('/admin/polls/{poll}/edit', [AdminController::class, 'editPoll'])
        ->name('admin.polls.edit');

    Route::post('/admin/polls/{poll}/update', [AdminController::class, 'updatePoll'])
        ->name('admin.polls.update');

    Route::post('/admin/polls/{poll}/delete', [AdminController::class, 'deletePoll'])
        ->name('admin.polls.delete');

    Route::post('/admin/polls/{poll}/approve', [AdminController::class, 'approvePoll'])
        ->name('admin.polls.approve');

    Route::post('/admin/polls/{poll}/reject', [AdminController::class, 'rejectPoll'])
        ->name('admin.polls.reject');

    Route::post('/admin/polls/{poll}/close', [AdminController::class, 'closePoll'])
        ->name('admin.polls.close');

    // Admin aspirasi (opsional, jika Anda sudah punya method-nya di AspirasiController)
    Route::get('/admin/aspirasi', [AspirasiController::class, 'adminIndex'])
        ->name('admin.aspirasi.index');
    Route::post('/admin/aspirasi/{id}/approve', [AspirasiController::class, 'approve'])
        ->name('admin.aspirasi.approve');
    Route::post('/admin/aspirasi/{id}/reject', [AspirasiController::class, 'reject'])
        ->name('admin.aspirasi.reject');
});
