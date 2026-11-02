<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class PmbController extends Controller
{
    public function index()
    {
        return view('superadmin.konten.pendidikan.pmb.index');
    }
}
