<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:access-superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::view('/', 'superadmin.index')->name('dashboard');
        Route::view('/pengguna', 'superadmin.users')->name('users');
        Route::view('/galeri', 'superadmin.gallery')->name('gallery');
        Route::view('/kalender', 'superadmin.calendar')->name('calendar');
        Route::view('/faq', 'superadmin.faq')->name('faq');
        Route::get('/konten/{slug}', fn(string $slug) => view('superadmin.konten', compact('slug')))
            ->name('konten');
    });
