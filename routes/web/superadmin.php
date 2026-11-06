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
use App\Http\Controllers\Superadmin\Konten\Profil\PimpinanController;
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

        Route::prefix('pimpinan')->name('pimpinan.')->group(function () {
            Route::get('/',               [PimpinanController::class, 'index'])->name('index');
            Route::get('/sampah',         [PimpinanController::class, 'trashed'])->name('trashed');
            Route::get('/create',         [PimpinanController::class, 'create'])->name('create');
            Route::post('/',              [PimpinanController::class, 'store'])->name('store');

            // Statis dulu
            Route::delete('/sampah/kosongkan', [PimpinanController::class, 'emptyTrash'])->name('empty-trash');
            Route::post('/sampah/{id}/restore', [PimpinanController::class, 'restore'])->name('restore');
            Route::delete('/sampah/{id}/force', [PimpinanController::class, 'forceDelete'])->name('force-delete');

            Route::get('/{pimpinan}/edit', [PimpinanController::class, 'edit'])->name('edit');
            Route::put('/{pimpinan}',     [PimpinanController::class, 'update'])->name('update');
            Route::delete('/{pimpinan}',  [PimpinanController::class, 'destroy'])->name('destroy');
            Route::patch('/{pimpinan}/toggle', [PimpinanController::class, 'toggle'])->name('toggle');
        });


        /*
        | Pendidikan
        */
        Route::prefix('fakultas')->name('fakultas.')->group(function () {
            Route::get('/',               [FakultasController::class, 'index'])->name('index');
            Route::get('/sampah',         [FakultasController::class, 'trashed'])->name('trashed');
            Route::get('/create',         [FakultasController::class, 'create'])->name('create');
            Route::post('/',              [FakultasController::class, 'store'])->name('store');

            // Statis dulu
            Route::delete('/sampah/kosongkan', [FakultasController::class, 'emptyTrash'])->name('empty-trash');
            Route::post('/sampah/{id}/restore', [FakultasController::class, 'restore'])->name('restore');
            Route::delete('/sampah/{id}/force', [FakultasController::class, 'forceDelete'])->name('force-delete');

            Route::get('/{fakultas}/edit', [FakultasController::class, 'edit'])->name('edit');
            Route::put('/{fakultas}',     [FakultasController::class, 'update'])->name('update');
            Route::delete('/{fakultas}',  [FakultasController::class, 'destroy'])->name('destroy');
            Route::patch('/{fakultas}/toggle', [FakultasController::class, 'toggle'])->name('toggle');
        });

        Route::prefix('prodi')->name('prodi.')->group(function () {
            Route::get('/', [ProdiController::class, 'index'])->name('index');
            Route::get('/sampah', [ProdiController::class, 'trashed'])->name('trashed');
            Route::get('/create', [ProdiController::class, 'create'])->name('create');
            Route::post('/', [ProdiController::class, 'store'])->name('store');

            Route::delete('/sampah/kosongkan', [ProdiController::class, 'emptyTrash'])->name('empty-trash');
            Route::post('/sampah/{id}/restore', [ProdiController::class, 'restore'])->name('restore');
            Route::delete('/sampah/{id}/force', [ProdiController::class, 'forceDelete'])->name('force-delete');

            Route::get('/{prodi}/edit', [ProdiController::class, 'edit'])->name('edit');
            Route::put('/{prodi}', [ProdiController::class, 'update'])->name('update');
            Route::delete('/{prodi}', [ProdiController::class, 'destroy'])->name('destroy');
            Route::patch('/{prodi}/toggle', [ProdiController::class, 'toggle'])->name('toggle');
        });

        Route::prefix('pmb')->name('pmb.')->group(function () {
            Route::get('/', [PmbController::class, 'index'])->name('index');
            Route::get('/create', [PmbController::class, 'create'])->name('create');
            Route::post('/', [PmbController::class, 'store'])->name('store');

            Route::get('/trash', [PmbController::class, 'trashed'])->name('trashed');
            Route::delete('/trash/empty', [PmbController::class, 'emptyTrash'])->name('empty-trash');
            Route::patch('/trash/{id}/restore', [PmbController::class, 'restore'])->name('restore');
            Route::delete('/trash/{id}/force', [PmbController::class, 'forceDelete'])->name('force-delete');
            // -------------------------------------------------------------

            Route::get('/{pmb}/edit', [PmbController::class, 'edit'])->name('edit');
            Route::put('/{pmb}', [PmbController::class, 'update'])->name('update');
            Route::delete('/{pmb}', [PmbController::class, 'destroy'])->name('destroy');
            Route::patch('/{pmb}/toggle', [PmbController::class, 'toggle'])->name('toggle');
        });

        Route::prefix('beasiswa')->name('beasiswa.')->group(function () {
            Route::get('/', [BeasiswaController::class, 'index'])->name('index');
            Route::get('/create', [BeasiswaController::class, 'create'])->name('create');
            Route::post('/', [BeasiswaController::class, 'store'])->name('store');

            Route::get('/trash', [BeasiswaController::class, 'trashed'])->name('trashed');
            Route::delete('/trash/empty', [BeasiswaController::class, 'emptyTrash'])->name('empty-trash');
            Route::patch('/trash/{id}/restore', [BeasiswaController::class, 'restore'])->name('restore');
            Route::delete('/trash/{id}/force', [BeasiswaController::class, 'forceDelete'])->name('force-delete');
            // -------------------------------------------------------------

            Route::get('/{beasiswa}/edit', [BeasiswaController::class, 'edit'])->name('edit');
            Route::put('/{beasiswa}', [BeasiswaController::class, 'update'])->name('update');
            Route::delete('/{beasiswa}', [BeasiswaController::class, 'destroy'])->name('destroy');
            Route::patch('/{beasiswa}/toggle', [BeasiswaController::class, 'toggle'])->name('toggle');
        });

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
