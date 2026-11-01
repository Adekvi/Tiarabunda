<?php

namespace App\Http\Controllers\Superadmin\Konten\Profil;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class SejarahController extends Controller
{
    public function index()
    {
        return view('superadmin.konten.profil.sejarah.sejarah');
    }

    // public function store(Request $request) {
    //     $vaidate = $request->validate([
    //         ''
    //     ])
    // }
}
