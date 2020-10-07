<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group( function () {
    Route::get('/', function() {
        return view('dashboard');
    })->name('dashboard');
});
