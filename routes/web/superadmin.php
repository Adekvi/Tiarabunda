<?php

use App\Http\Controllers\Superadmin\Konten\Informasi\BeritaController;
use App\Http\Controllers\Superadmin\Konten\Informasi\FaqController;
use App\Http\Controllers\Superadmin\Konten\Informasi\KegiatanController;
use App\Http\Controllers\Superadmin\Konten\Layanan\FasilitasController;
use App\Http\Controllers\Superadmin\Konten\Layanan\KarirController;
use App\Http\Controllers\Superadmin\Konten\Layanan\KemahaController;
use App\Http\Controllers\Superadmin\Konten\Layanan\PerpusController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\AkreditasiController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\BeasiswaController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\PmbController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\Program\FakultasController;
use App\Http\Controllers\Superadmin\Konten\Pendidikan\Program\ProdiController;
use App\Http\Controllers\Superadmin\Konten\Profil\SejarahController;
use App\Http\Controllers\Superadmin\Konten\Profil\VisimisiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:access-superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::view('/', 'superadmin.index')->name('dashboard');

        /*
        | Profil
        */
        Route::resource('sejarah', SejarahController::class)->except(['show']);
        Route::patch('sejarah/{sejarah}/toggle', [SejarahController::class, 'toggle'])
            ->name('sejarah.toggle');

        Route::resource('visimisi', VisimisiController::class)->except(['show']);
        Route::patch('visimisi/{visimisi}/toggle', [VisimisiController::class, 'toggle'])
            ->name('visimisi.toggle');

        /*
        | Pendidikan
        */
        Route::resource('fakultas', FakultasController::class)->except(['show']);
        Route::patch('fakultas/{fakultas}/toggle', [FakultasController::class, 'toggle'])
            ->name('fakultas.toggle');

        Route::resource('prodi', ProdiController::class)->except(['show']);
        Route::patch('prodi/{prodi}/toggle', [ProdiController::class, 'toggle'])
            ->name('prodi.toggle');

        Route::resource('pmb', PmbController::class)->except(['show']);
        Route::patch('pmb/{pmb}/toggle', [PmbController::class, 'toggle'])
            ->name('pmb.toggle');

        Route::resource('beasiswa', BeasiswaController::class)->except(['show']);
        Route::patch('beasiswa/{beasiswa}/toggle', [BeasiswaController::class, 'toggle'])
            ->name('beasiswa.toggle');

        Route::resource('akreditasi', AkreditasiController::class)->except(['show']);
        Route::patch('akreditasi/{akreditasi}/toggle', [AkreditasiController::class, 'toggle'])
            ->name('akreditasi.toggle');

        /*
        | Layanan
        */
        Route::resource('perpus', PerpusController::class)->except(['show']);
        Route::patch('perpus/{perpus}/toggle', [PerpusController::class, 'toggle'])
            ->name('perpus.toggle');

        Route::resource('kemaha', KemahaController::class)->except(['show']);
        Route::patch('kemaha/{kemaha}/toggle', [KemahaController::class, 'toggle'])
            ->name('kemaha.toggle');

        Route::resource('karir', KarirController::class)->except(['show']);
        Route::patch('karir/{karir}/toggle', [KarirController::class, 'toggle'])
            ->name('karir.toggle');

        Route::prefix('fasilitas')->name('fasilitas.')->group(function () {
            // Trash dulu supaya tidak ketabrak {fasilita}
            Route::get('trashed',            [FasilitasController::class, 'trashed'])->name('trashed');
            Route::post('trashed/empty',     [FasilitasController::class, 'emptyTrash'])->name('empty-trash');
            Route::patch('trashed/{id}/restore', [FasilitasController::class, 'restore'])->name('restore');
            Route::delete('trashed/{id}/force',  [FasilitasController::class, 'forceDelete'])->name('force-delete');

            Route::get('/',           [FasilitasController::class, 'index'])->name('index');
            Route::get('create',      [FasilitasController::class, 'create'])->name('create');
            Route::post('/',          [FasilitasController::class, 'store'])->name('store');
            Route::get('{fasilita}/edit', [FasilitasController::class, 'edit'])->name('edit');
            Route::put('{fasilita}',  [FasilitasController::class, 'update'])->name('update');
            Route::delete('{fasilita}', [FasilitasController::class, 'destroy'])->name('destroy');
            Route::patch('{fasilita}/toggle', [FasilitasController::class, 'toggle'])->name('toggle');
        });

        /*
        | Informasi
        */
        Route::resource('berita', BeritaController::class)->except(['show']);
        Route::patch('berita/{berita}/toggle', [BeritaController::class, 'toggle'])
            ->name('berita.toggle');
        Route::post('berita/{id}/restore', [BeritaController::class, 'restore'])
            ->name('berita.restore');
        Route::delete('berita/{id}/force', [BeritaController::class, 'forceDestroy'])
            ->name('berita.force-destroy');

        Route::resource('kegiatan', KegiatanController::class)->except(['show']);
        Route::patch('kegiatan/{kegiatan}/toggle', [KegiatanController::class, 'toggle'])
            ->name('kegiatan.toggle');
        Route::post('/{id}/restore', [KegiatanController::class, 'restore'])
            ->whereNumber('id')
            ->name('restore');
        Route::delete('/{id}/force-delete', [KegiatanController::class, 'forceDelete'])
            ->whereNumber('id')
            ->name('force-delete');

        Route::resource('faq', FaqController::class)->except(['show']);
        Route::patch('faq/{faq}/toggle', [FaqController::class, 'toggle'])
            ->name('faq.toggle');

        /*
        | Sistem (placeholder view — pastikan file blade ada)
        */
        Route::view('/pengguna', 'superadmin.users')->name('users');
        Route::view('/galeri', 'superadmin.gallery')->name('gallery');
        Route::view('/kalender', 'superadmin.calendar')->name('calendar');
    });
