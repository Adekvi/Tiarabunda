<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function page(string $page)
    {
        $data = config("landing.pages.$page");
        abort_unless($data, 404);

        return view('landing.page', compact('page', 'data'));
    }
}
