<?php

namespace App\Http\Controllers\Landing\Profil\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanPimpinanController extends Controller
{
    public function index()
    {
        return view('landing.profil.pimpinan.pimpinan');
    }
}
