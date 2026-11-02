<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        return view('superadmin.konten.layanan.fasilitas.index');
    }
}
