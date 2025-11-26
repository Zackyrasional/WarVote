<?php

use Illuminate\Support\Facades\Route;

// Route API sederhana (opsional)
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});
