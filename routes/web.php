<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

foreach (['profil', 'pendidikan', 'layanan', 'informasi'] as $p) {
    Route::view("/$p", "landing.$p")->name("landing.$p");
}

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user.php';
require __DIR__ . '/web/admin.php';
require __DIR__ . '/web/superadmin.php';
