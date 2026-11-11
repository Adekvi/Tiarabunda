<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Hero;
use App\Services\Landing\LandingHomeService;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
        private readonly LandingHomeService $landingService
    ) {}

    public function index(): View
    {
        $item = Hero::query()->first();

        // Kirim data (bisa null) ke view index
        return view('superadmin.konten.landing.hero-section.index', compact('item'));
    }

    public function edit(): View
    {
        $item = Hero::query()->first();
        return view('superadmin.konten.landing.hero-section.form', compact('item'));
    }

    public function update(Request $request): RedirectResponse
    {
        $item = Hero::query()->first() ?? new Hero();

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'sub_judul' => ['nullable', 'string', 'max:500'],
            'teks_tombol_1' => ['required', 'string', 'max:50'],
            'link_tombol_1' => ['required', 'string', 'max:255'],
            'teks_tombol_2' => ['required', 'string', 'max:50'],
            'link_tombol_2' => ['required', 'string', 'max:255'],
            'tipe_media' => ['required', 'in:none,gambar,video'],
            'path_media' => [
                'nullable',
                $request->tipe_media === 'video' ? 'mimes:mp4,webm' : 'image',
                $request->tipe_media === 'video' ? 'max:15000' : 'max:2048'
            ],
            'path_poster' => ['nullable', 'image', 'max:2048'],
            'urip' => ['boolean'],
        ]);

        $data['urip'] = $request->boolean('urip', false);

        // Setelah validasi, sebelum handle file upload:
        if ($request->tipe_media !== $item->tipe_media && !$request->hasFile('path_media')) {
            // Tipe media berubah tapi tidak upload file baru → hapus file lama
            if ($item->path_media) {
                $this->images->delete($item->path_media);
            }
            $data['path_media'] = null;
        }

        // Jika tipe = none, hapus semua media
        if ($request->tipe_media === 'none') {
            if ($item->path_media) {
                $this->images->delete($item->path_media);
            }
            if ($item->path_poster) {
                $this->images->delete($item->path_poster);
            }
            $data['path_media'] = null;
            $data['path_poster'] = null;
        }

        // Handle Upload Poster (Thumbnail Video)
        if ($request->hasFile('path_poster')) {
            if ($item->path_poster) {
                $this->images->delete($item->path_poster);
            }
            $stored = $this->images->store($request->file('path_poster'), 'hero');
            $data['path_poster'] = $stored['path'];
        }

        $item->fill($data)->save();

        // Bersihkan cache landing agar perubahan langsung terlihat
        $this->landingService->forgetHome();

        return redirect()->route('superadmin.hero.index')->with('success', 'Pengaturan Hero berhasil diperbarui.');
    }
}
