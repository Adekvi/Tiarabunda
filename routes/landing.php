<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

$menus = 'profil|pendidikan|layanan|informasi';

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/cari', [LandingController::class, 'search'])->name('landing.search');

Route::get('/{menu}/{sub}/{prodi}', [LandingController::class, 'page'])
    ->where(['menu' => '.*', 'sub' => '.*', 'prodi' => '.*'])
    ->name('landing.prodi');

Route::get('/{menu}/{sub}', [LandingController::class, 'page'])->name('landing.page');

Route::get('/{menu}', [LandingController::class, 'menu'])->name('landing.menu');

// URL lama -> halaman baru
Route::redirect('/fakultas', '/pendidikan/program-pendidikan', 301);
Route::redirect('/pmb', '/pendidikan/penerimaan-mahasiswa-baru', 301);
Route::redirect('/akreditasi', '/pendidikan/akreditasi', 301);
Route::redirect('/dokumen', '/layanan/dokumen', 301);
