<?php

use Illuminate\Support\Facades\Route;

Route::get('/superadmin/{any?}', fn() => view('superadmin.index'))
    ->where('any', '.*')
    ->name('superadmin');
