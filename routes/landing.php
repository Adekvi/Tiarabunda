<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Definisikan daftar menu yang valid
$menus = 'profil|pendidikan|layanan|informasi';

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/cari', [LandingController::class, 'search'])->name('landing.search');

// Berikan constraint ->where() agar hanya memproses 4 menu tersebut
Route::get('/{menu}/{sub}/{prodi}', [LandingController::class, 'page'])
    ->where(['menu' => $menus, 'sub' => '.*', 'prodi' => '.*'])
    ->name('landing.prodi');

Route::get('/{menu}/{sub}', [LandingController::class, 'page'])
    ->where(['menu' => $menus, 'sub' => '.*'])
    ->name('landing.page');

Route::get('/{menu}', [LandingController::class, 'menu'])
    ->where(['menu' => $menus])
    ->name('landing.menu');

// URL lama -> halaman baru
Route::redirect('/fakultas', '/pendidikan/program-pendidikan', 301);
Route::redirect('/pmb', '/pendidikan/penerimaan-mahasiswa-baru', 301);
Route::redirect('/akreditasi', '/pendidikan/akreditasi', 301);
Route::redirect('/dokumen', '/layanan/dokumen', 301);
