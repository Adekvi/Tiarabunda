<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan\Fasilitas;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
    ) {}

    public function index(Request $request): View
    {
        // Kolom minimal — path saja, bukan file binary
        $items = Fasilitas::query()
            ->select(['id', 'jeneng', 'path_gambar', 'path_thumb', 'urutan', 'urip', 'updated_at'])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where('jeneng', 'like', $t);
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Fasilitas::query()->count(),
            'aktif' => Fasilitas::query()->active()->count(),
            'nonaktif' => Fasilitas::query()->inactive()->count(),
            'ada_gambar' => Fasilitas::query()->whereNotNull('path_gambar')->count(),
        ];

        return view('superadmin.konten.layanan.fasilitas.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.fasilitas.form', [
            'item' => new Fasilitas(['urutan' => 0, 'urip' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('path_gambar')) {
            $stored = $this->images->store($request->file('path_gambar'), 'fasilitas');
            $data['path_gambar'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        Fasilitas::query()->create($data);

        return redirect()
            ->route('superadmin.fasilitas.index')
            ->with('success', 'Fasilitas ditambahkan.');
    }

    public function edit(Fasilitas $fasilita): View
    {
        // resource "fasilitas" → {fasilita}
        return view('superadmin.konten.layanan.fasilitas.form', [
            'item' => $fasilita,
        ]);
    }

    public function update(Request $request, Fasilitas $fasilita): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('path_gambar')) {
            $this->images->delete($fasilita->path_gambar, $fasilita->path_thumb);
            $stored = $this->images->store($request->file('path_gambar'), 'fasilitas');
            $data['path_gambar'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        $fasilita->update($data);

        return redirect()
            ->route('superadmin.fasilitas.index')
            ->with('success', 'Fasilitas diperbarui.');
    }

    public function destroy(Fasilitas $fasilita): RedirectResponse
    {
        // Soft delete: file boleh tetap; hapus fisik jika ingin hemat disk:
        // $this->images->delete($fasilita->path_gambar, $fasilita->path_thumb);
        $fasilita->delete();

        return back()->with('success', 'Fasilitas dihapus.');
    }

    public function toggle(Fasilitas $fasilita): RedirectResponse
    {
        $fasilita->toggleUrip();

        return back()->with(
            'success',
            $fasilita->urip ? 'Fasilitas diaktifkan.' : 'Fasilitas dinonaktifkan.'
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'jeneng' => ['required', 'string', 'max:150'],
            'katrangan' => ['nullable', 'string'],
            'path_gambar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'jeneng.required' => 'Nama fasilitas wajib diisi.',
            'path_gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['path_gambar']);

        return $data;
    }
}
