<?php

use App\Http\Controllers\Superadmin\Informasi\BeritaController;
use App\Http\Controllers\Superadmin\Informasi\KegiatanController;
use App\Http\Controllers\Superadmin\Konten\Informasi\FaqController;
use App\Http\Controllers\Superadmin\Konten\Layanan\FasilitasController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\AkreditasiController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\PmbController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\Program\FakultasController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\Program\ProdiController;
use App\Http\Controllers\Superadmin\Konten\Profil\SejarahController;
use App\Http\Controllers\Superadmin\Konten\Profil\VisimisiController;
use App\Http\Controllers\Superadmin\Konten\Layanan\KarirController;
use App\Http\Controllers\Superadmin\Konten\Layanan\KemahaController;
use App\Http\Controllers\Superadmin\Konten\Layanan\PerpusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:access-superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::view('/', 'superadmin.index')->name('dashboard');

        // Profil
        // Sejarah
        Route::resource('sejarah', SejarahController::class)->except(['show']);
        Route::patch('sejarah/{sejarah}/toggle', [SejarahController::class, 'toggle'])->name('sejarah.toggle');

        // Visi & Misi
        Route::resource('visimisi', VisimisiController::class)->except(['show']);
        Route::patch('visimisi/{visimisi}/toggle', [VisimisiController::class, 'toggle'])->name('visimisi.toggle');

        // Program Pendidikan
        // Fakultas
        Route::resource('fakultas', FakultasController::class)->except(['show']);
        Route::patch('fakultas/{fakultas}/toggle', [FakultasController::class, 'toggle'])->name('fakultas.toggle');

        // Prodi
        Route::resource('prodi', ProdiController::class)->except(['show']);
        Route::patch('prodi/{prodi}/toggle', [ProdiController::class, 'toggle'])->name('prodi.toggle');

        // PMB
        Route::resource('pmb', PmbController::class)->except(['show']);
        Route::patch('pmb/{pmb}/toggle', [PmbController::class, 'toggle'])->name('pmb.toggle');

        // Akreditasi
        Route::resource('akreditasi', AkreditasiController::class)->except(['show']);
        Route::patch('akreditasi/{akreditasi}/toggle', [AkreditasiController::class, 'toggle'])->name('akreditasi.toggle');

        // Layanan
        // Perpustakaan
        Route::resource('perpus', PerpusController::class)->except(['show']);
        Route::patch('perpus/{perpus}/toggle', [PerpusController::class, 'toggle'])->name('perpus.toggle');

        // Kemahasiswaan
        Route::resource('kemaha', KemahaController::class)->except(['show']);
        Route::patch('kemaha/{kemaha}/toggle', [KemahaController::class, 'toggle'])->name('kemaha.toggle');

        // Pusat Karir
        Route::resource('karir', KarirController::class)->except(['show']);
        Route::patch('karir/{karir}/toggle', [KarirController::class, 'toggle'])->name('karir.toggle');

        // Fasilitas
        Route::resource('fasilitas', FasilitasController::class)->except(['show']);
        Route::patch('fasilitas/{fasilitas}/toggle', [FasilitasController::class, 'toggle'])->name('fasilitas.toggle');

        // Informasi
        // // Berita
        Route::resource('berita', BeritaController::class)->except(['show']);
        Route::patch('berita/{berita}/toggle', [BeritaController::class, 'toggle'])
            ->name('berita.toggle');

        Route::resource('kegiatan', KegiatanController::class)->except(['show']);
        Route::patch('kegiatan/{kegiatan}/toggle', [KegiatanController::class, 'toggle'])
            ->name('kegiatan.toggle');

        // // Faq
        Route::resource('faq', FaqController::class)->except(['show']);
        Route::patch('faq/{faq}/toggle', [FaqController::class, 'toggle'])
            ->name('faq.toggle');

        Route::view('/pengguna', 'superadmin.users')->name('users');
        Route::view('/galeri', 'superadmin.gallery')->name('gallery');
        Route::view('/kalender', 'superadmin.calendar')->name('calendar');
        Route::get('/konten/{slug}', fn(string $slug) => view('superadmin.konten', compact('slug')))
            ->name('konten');
    });
