<?php

namespace App\Http\Controllers\Landing\Profil\Sejarah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanSejarahController extends Controller
{
    public function index()
    {
        return view('landing.profil.sejarah.sejarah');
    }
}
