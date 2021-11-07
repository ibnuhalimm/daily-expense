<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group( function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/expense', function() {
        return view('expense');
    })->name('expense');

    Route::get('/categories', function() {
        return view('categories');
    })->name('categories');
});


Route::any('register', function () {
    abort(404);
});
