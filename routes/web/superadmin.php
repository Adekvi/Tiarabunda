<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:access-superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/', fn() => view('superadmin.index'))
            ->name('dashboard');

        // nanti:
        // Route::get('/pengguna', ...)->name('users');
    });
