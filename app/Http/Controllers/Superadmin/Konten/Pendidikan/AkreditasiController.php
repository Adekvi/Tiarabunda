<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Akreditasi;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AkreditasiController extends Controller
{
    public function index(Request $request): View
    {
        $items = Akreditasi::query()
            ->select([
                'id',
                'prodi_id',
                'jeneng',
                'status_akred',
                'bagean',
                'path_gambar',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->with(['prodi:id,jeneng']) // 1 query ekstra, bukan N+1
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('bagean'), fn($q) => $q->where('bagean', $request->bagean))
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('jeneng', 'like', $t)
                        ->orWhere('status_akred', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Akreditasi::query()->count(),
            'aktif' => Akreditasi::query()->active()->count(),
            'nonaktif' => Akreditasi::query()->inactive()->count(),
            'institusi' => Akreditasi::query()->where('bagean', 'institusi')->count(),
            'prodi' => Akreditasi::query()->where('bagean', 'prodi')->count(),
            'ada_gambar' => Akreditasi::query()->whereNotNull('path_gambar')->count(),
        ];

        return view('superadmin.konten.pendidikan.akreditasi.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.akreditasi.form', [
            'item' => new Akreditasi([
                'bagean' => 'institusi',
                'urutan' => 0,
                'urip' => true,
            ]),
            'prodiList' => $this->prodiOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['path_gambar'] = $this->storeImage($request);

        Akreditasi::query()->create($data);

        return redirect()
            ->route('superadmin.akreditasi.index')
            ->with('success', 'Data akreditasi ditambahkan.');
    }

    public function edit(Akreditasi $akreditasi): View
    {
        return view('superadmin.konten.pendidikan.akreditasi.form', [
            'item' => $akreditasi,
            'prodiList' => $this->prodiOptions(),
        ]);
    }

    public function update(Request $request, Akreditasi $akreditasi): RedirectResponse
    {
        $data = $this->validated($request);

        if ($path = $this->storeImage($request)) {
            if ($akreditasi->path_gambar) {
                Storage::disk('public')->delete($akreditasi->path_gambar);
            }
            $data['path_gambar'] = $path;
        }

        $akreditasi->update($data);

        return redirect()
            ->route('superadmin.akreditasi.index')
            ->with('success', 'Data akreditasi diperbarui.');
    }

    public function destroy(Akreditasi $akreditasi): RedirectResponse
    {
        // Soft delete: file boleh tetap sampai purge manual / job
        $akreditasi->delete();

        return back()->with('success', 'Data akreditasi dihapus.');
    }

    public function toggle(Akreditasi $akreditasi): RedirectResponse
    {
        $akreditasi->toggleUrip();

        return back()->with(
            'success',
            $akreditasi->urip ? 'Akreditasi diaktifkan.' : 'Akreditasi dinonaktifkan.'
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'jeneng' => ['required', 'string', 'max:150'],
            'bagean' => ['required', Rule::in(['institusi', 'prodi'])],
            'prodi_id' => [
                'nullable',
                'required_if:bagean,prodi',
                'exists:prodis,id',
            ],
            'status_akred' => ['nullable', 'string', 'max:50'],
            'katrangan' => ['nullable', 'string'],
            'path_gambar' => ['nullable', 'image', 'mimes:png,jpeg,jpg,webp', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'jeneng.required' => 'Judul wajib diisi.',
            'prodi_id.required_if' => 'Pilih program studi untuk akreditasi prodi.',
            'path_gambar.image' => 'File harus berupa gambar.',
            'path_gambar.max' => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        if (($data['bagean'] ?? '') === 'institusi') {
            $data['prodi_id'] = null;
        }

        unset($data['path_gambar']); // di-set lewat storeImage

        return $data;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('path_gambar')) {
            return null;
        }

        return $request->file('path_gambar')->store('akreditasi', 'public');
    }

    /** Dropdown prodi — 1 query, kolom minimal */
    private function prodiOptions()
    {
        return Prodi::query()
            ->active()
            ->ordered()
            ->get(['id', 'jeneng', 'undhak']);
    }
}
