<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', fn() => view('superadmin.index'))
        ->name('dashboard')
        ->middleware('can:access-superadmin'); // atau custom middleware role
});

Route::view('/superadmin', 'superadmin.index')->name('superadmin');
