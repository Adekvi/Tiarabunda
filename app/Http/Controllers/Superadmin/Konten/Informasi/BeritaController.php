<?php

namespace App\Http\Controllers\Superadmin\Informasi;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        return view('superadmin.konten.informasi.berita.index');
    }
}
