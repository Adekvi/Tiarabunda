<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:access-superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::view('/', 'superadmin.index')->name('dashboard');

        // Visi & Misi
        // Route::resource('visimisi', VisimisiController::class)->except(['show']);
        // Route::patch('visimisi/{visimisi}/toggle', [VisimisiController::class, 'toggle'])
        //     ->name('visimisi.toggle');

        // // Berita
        // Route::resource('berita', BeritaController::class)->except(['show']);
        // Route::patch('berita/{berita}/toggle', [BeritaController::class, 'toggle'])
        //     ->name('berita.toggle');

        // // Faq
        // Route::resource('faq', FaqController::class)->except(['show']);
        // Route::patch('faq/{faq}/toggle', [FaqController::class, 'toggle'])
        //     ->name('faq.toggle');

        Route::view('/pengguna', 'superadmin.users')->name('users');
        Route::view('/galeri', 'superadmin.gallery')->name('gallery');
        Route::view('/kalender', 'superadmin.calendar')->name('calendar');
        Route::get('/konten/{slug}', fn(string $slug) => view('superadmin.konten', compact('slug')))
            ->name('konten');
    });
