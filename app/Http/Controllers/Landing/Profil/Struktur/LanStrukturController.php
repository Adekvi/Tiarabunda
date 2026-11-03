<?php

namespace App\Http\Controllers\Landing\Profil\Struktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanStrukturController extends Controller
{
    public function index()
    {
        return view('landing.profil.struktur.struktur');
    }
}
