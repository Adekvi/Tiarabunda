<?php

namespace App\Http\Controllers\Landing\Profil\Visi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanVisiController extends Controller
{
    public function index()
    {
        return view('landing.profil.visi.visimisi');
    }
}
