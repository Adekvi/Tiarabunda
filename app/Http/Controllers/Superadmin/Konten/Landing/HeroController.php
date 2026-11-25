<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Rules\SafeUrl;
use App\Http\Controllers\Controller;
use App\Models\Landing\Hero;
use App\Models\Landing\Heromedia;
use App\Services\Landing\LandingHomeService;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HeroController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
        private readonly LandingHomeService $landingService
    ) {}

    public function index(): View
    {
        $item = Hero::with(['videoMedia', 'fotoMedias'])->first();
        return view('superadmin.konten.landing.hero-section.index', compact('item'));
    }

    public function edit(): View
    {
        $item = Hero::with(['videoMedia', 'fotoMedias'])->first();
        return view('superadmin.konten.landing.hero-section.form', compact('item'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'                   => ['required', 'string', 'max:255'],
            'sub_judul'               => ['nullable', 'string', 'max:500'],
            'teks_tombol_1'           => ['required', 'string', 'max:50'],
            'link_tombol_1'           => ['required', 'string', 'max:255', new SafeUrl],
            'teks_tombol_2'           => ['required', 'string', 'max:50'],
            'link_tombol_2'           => ['required', 'string', 'max:255', new SafeUrl],
            'durasi_slider'           => ['required', 'integer', 'min:5', 'max:7'],
            'urip'                    => ['boolean'],

            // Video Utama (maksimal 1)
            'path_video'              => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:15000'],
            'hapus_video'             => ['nullable', 'boolean'],
            'video_urip'              => ['nullable', 'boolean'],
            'path_poster'             => ['nullable', 'image', 'max:2048'],
            'hapus_poster'            => ['nullable', 'boolean'],

            // Upload Foto Baru (tanpa batas jumlah)
            'fotos'                   => ['nullable', 'array'],
            'fotos.*'                 => ['image', 'max:2048'],

            // Pengaturan Foto Lama di tabel hero_media
            'existing_fotos'          => ['nullable', 'array'],
            'existing_fotos.*.urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'existing_fotos.*.urip'   => ['nullable', 'boolean'],
            'hapus_fotos'             => ['nullable', 'array'],
            'hapus_fotos.*'           => ['integer'],
        ]);

        $filesToDelete = [];

        DB::transaction(function () use ($request, $validated, &$filesToDelete) {
            $item = Hero::query()->first() ?? new Hero();

            $item->fill([
                'judul'         => $validated['judul'],
                'sub_judul'     => $validated['sub_judul'] ?? null,
                'teks_tombol_1' => $validated['teks_tombol_1'],
                'link_tombol_1' => $validated['link_tombol_1'],
                'teks_tombol_2' => $validated['teks_tombol_2'],
                'link_tombol_2' => $validated['link_tombol_2'],
                'durasi_slider' => (int) $validated['durasi_slider'],
                'urip'          => $request->boolean('urip', false),
            ])->save();

            /** @var Heromedia|null $videoMedia */
            $videoMedia = $item->videoMedia()->first();

            if ($request->boolean('hapus_video') && ! $request->hasFile('path_video')) {
                if ($videoMedia) {
                    $filesToDelete[] = $videoMedia->path_media;
                    $filesToDelete[] = $videoMedia->path_poster;
                    $videoMedia->delete();
                    $videoMedia = null;
                }
            }

            if ($request->hasFile('path_video')) {
                $newVideoPath = $request->file('path_video')->store('hero', 'public');

                if ($videoMedia) {
                    $filesToDelete[] = $videoMedia->path_media;
                    $videoMedia->update([
                        'path_media' => $newVideoPath,
                        'urutan'     => 0,
                        'urip'       => $request->boolean('video_urip', true),
                    ]);
                } else {
                    /** @var Heromedia $videoMedia */
                    $videoMedia = $item->medias()->create([
                        'tipe'       => 'video',
                        'path_media' => $newVideoPath,
                        'urutan'     => 0,
                        'urip'       => $request->boolean('video_urip', true),
                    ]);
                }
            } elseif ($videoMedia) {
                $videoMedia->update([
                    'urip' => $request->boolean('video_urip', true),
                ]);
            }

            if ($videoMedia) {
                if ($request->boolean('hapus_poster') && ! $request->hasFile('path_poster')) {
                    if ($videoMedia->path_poster) {
                        $filesToDelete[] = $videoMedia->path_poster;
                        $videoMedia->update(['path_poster' => null]);
                    }
                }

                if ($request->hasFile('path_poster')) {
                    if ($videoMedia->path_poster) {
                        $filesToDelete[] = $videoMedia->path_poster;
                    }
                    $storedPoster = $this->images->store($request->file('path_poster'), 'hero');
                    $videoMedia->update(['path_poster' => $storedPoster['path']]);
                }
            }

            $hapusIds = array_map('intval', $request->input('hapus_fotos', []));
            if (! empty($hapusIds)) {
                $toDeleteMedias = $item->fotoMedias()->whereIn('id', $hapusIds)->get();
                /** @var Heromedia $media */
                foreach ($toDeleteMedias as $media) {
                    $filesToDelete[] = $media->path_media;
                    $media->delete();
                }
            }

            $existingInput = $request->input('existing_fotos', []);
            if (is_array($existingInput) && ! empty($existingInput)) {
                $remainingFotos = $item->fotoMedias()->get();
                /** @var Heromedia $foto */
                foreach ($remainingFotos as $foto) {
                    if (isset($existingInput[$foto->id])) {
                        $rowInput = $existingInput[$foto->id];
                        $foto->update([
                            'urutan' => isset($rowInput['urutan']) ? (int) $rowInput['urutan'] : $foto->urutan,
                            'urip'   => ! empty($rowInput['urip']),
                        ]);
                    }
                }
            }

            if ($request->hasFile('fotos')) {
                $nextUrutan = ((int) $item->fotoMedias()->max('urutan')) + 1;

                foreach ($request->file('fotos') as $fotoFile) {
                    if ($fotoFile && $fotoFile->isValid()) {
                        $stored = $this->images->store($fotoFile, 'hero');
                        $item->medias()->create([
                            'tipe'       => 'gambar',
                            'path_media' => $stored['path'],
                            'urutan'     => $nextUrutan++,
                            'urip'       => true,
                        ]);
                    }
                }
            }

            /** @var Heromedia|null $firstActive */
            $firstActive = $item->activeMedias()->first();
            $item->update([
                'tipe_media'  => $firstActive?->tipe ?? 'none',
                'path_media'  => $firstActive?->path_media,
                'path_poster' => $firstActive?->tipe === 'video' ? $firstActive->path_poster : null,
            ]);
        });

        // Hapus file fisik setelah transaksi database sukses
        if (! empty($filesToDelete)) {
            $this->images->delete(...$filesToDelete);
        }

        $this->landingService->forgetHome();

        return redirect()
            ->route('superadmin.hero.index')
            ->with('success', 'Pengaturan Hero & Slider berhasil diperbarui.');
    }
}
