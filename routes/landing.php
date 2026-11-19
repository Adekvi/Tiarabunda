<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

$menus = 'profil|pendidikan|layanan|lembaga|informasi';

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/cari', [LandingController::class, 'search'])->name('landing.search');

Route::get('/{menu}/{sub}/{prodi}', [LandingController::class, 'page'])
    ->where(['menu' => $menus, 'sub' => '[^/]+', 'prodi' => '.*'])
    ->name('landing.prodi');

Route::get('/{menu}/{sub}', [LandingController::class, 'page'])
    ->where(['menu' => $menus, 'sub' => '.*'])
    ->name('landing.page');

Route::get('/{menu}', [LandingController::class, 'menu'])
    ->where(['menu' => $menus])
    ->name('landing.menu');

// ============================================================
// REDIRECT URL LAMA (mencegah 404 setelah restrukturisasi)
// ============================================================

Route::redirect('/pmb', 'https://poltektiarabunda.pmbonline.siakad.tech/register', 301);
Route::redirect('/pendidikan/penerimaan-mahasiswa-baru', 'https://poltektiarabunda.pmbonline.siakad.tech/register', 301);

Route::redirect('/pendidikan/akreditasi', '/profil/akreditasi', 301);
Route::redirect('/akreditasi', '/profil/akreditasi', 301);

Route::redirect('/pendidikan/beasiswa', '/layanan/kemahasiswaan', 301);

Route::redirect('/fakultas', '/pendidikan/program-pendidikan', 301);
Route::redirect('/dokumen', '/layanan/dokumen', 301);

Route::redirect('/lppm', '/lembaga/lppm', 301);
Route::redirect('/lembaga/lembaga', '/lembaga/lppm', 301);
