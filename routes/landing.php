<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

$menus = 'profil|pendidikan|layanan|informasi';

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/{menu}', [LandingController::class, 'menu'])
    ->where('menu', $menus)->name('landing.menu');

Route::get('/{menu}/{sub}', [LandingController::class, 'page'])
    ->where(['menu' => $menus, 'sub' => '[a-z0-9-]+'])->name('landing.page');

Route::get('/cari', [LandingController::class, 'search'])->name('landing.search');

// URL lama -> halaman baru
Route::redirect('/fakultas', '/pendidikan/program-pendidikan', 301);
Route::redirect('/pmb', '/pendidikan/penerimaan-mahasiswa-baru', 301);
Route::redirect('/akreditasi', '/pendidikan/akreditasi', 301);
Route::redirect('/dokumen', '/layanan/dokumen', 301);

Route::get('/{menu}', [LandingController::class, 'menu'])
    ->where('menu', '[a-z0-9-]+')
    ->name('landing.menu');

Route::get('/{menu}/{sub}', [LandingController::class, 'page'])
    ->where(['menu' => '[a-z0-9-]+', 'sub' => '[a-z0-9-]+'])
    ->name('landing.page');
