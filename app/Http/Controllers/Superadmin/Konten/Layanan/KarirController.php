<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class KarirController extends Controller
{
    public function index()
    {
        return view('superadmin.konten.layanan.karier.index');
    }
}
