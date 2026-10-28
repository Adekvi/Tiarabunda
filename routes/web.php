<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/{page}', [LandingController::class, 'page'])
    ->where('page', 'profil|pendidikan|layanan|informasi')
    ->name('landing.page');

// URL lama -> halaman baru
Route::redirect('/fakultas', '/pendidikan#prodi', 301);
Route::redirect('/pmb', '/pendidikan#pmb', 301);
Route::redirect('/akreditasi', '/pendidikan#akreditasi', 301);
Route::redirect('/dokumen', '/layanan#dokumen', 301);

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user.php';
require __DIR__ . '/web/admin.php';
require __DIR__ . '/web/superadmin.php';
