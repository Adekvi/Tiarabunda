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
        return $this->renderList($request, trashed: false);
    }

    public function trashed(Request $request): View
    {
        return $this->renderList($request, trashed: true);
    }

    private function renderList(Request $request, bool $trashed): View
    {
        $filters = [
            'q'       => $request->string('q')->trim()->toString(),
            'status'  => $request->input('status'),
            'bagean'  => $request->input('bagean'),
        ];

        $items = Akreditasi::query()
            ->select([
                'id',
                'jeneng',
                'status_akred',
                'bagean',
                'path_gambar',
                'urutan',
                'urip',
                'updated_at',
                'deleted_at'
            ])
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when(! $trashed, fn($q) => $q->whereNull('deleted_at'))
            ->when($filters['status'] === 'active', fn($q) => $q->active())
            ->when($filters['status'] === 'inactive', fn($q) => $q->inactive())
            ->when(filled($filters['bagean']), fn($q) => $q->where('bagean', $filters['bagean']))
            ->when(filled($filters['q']), function ($q) use ($filters) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('jeneng', 'like', $t)
                        ->orWhere('status_akred', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'       => Akreditasi::query()->count(),
            'aktif'       => Akreditasi::query()->active()->count(),
            'nonaktif'    => Akreditasi::query()->inactive()->count(),
            'terhapus'    => Akreditasi::onlyTrashed()->count(),
            'institusi'   => Akreditasi::query()->where('bagean', 'institusi')->count(),
            'prodi'       => Akreditasi::query()->where('bagean', 'prodi')->count(),
            'ada_gambar'  => Akreditasi::query()->whereNotNull('path_gambar')->where('path_gambar', '!=', '')->count(),
        ];

        dd($items);

        return view('superadmin.konten.pendidikan.akreditasi.index', [
            'items'    => $items,
            'stats'    => $stats,
            'trashed'  => $trashed,
            'filtered' => filled($filters['q']) || filled($filters['status']) || filled($filters['bagean']),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.akreditasi.form', [
            'item' => new Akreditasi(['bagean' => 'institusi', 'urutan' => 0, 'urip' => true]),
            'prodiList' => Prodi::query()->active()->ordered()->get(['id', 'jeneng', 'undhak']),
            'selectedProdis' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('path_gambar')) {
            $data['path_gambar'] = $request->file('path_gambar')->store('akreditasi', 'public');
        }

        $akreditasi = Akreditasi::query()->create($data);

        $this->syncProdi($akreditasi, $request->input('prodi_ids', []));

        return redirect()
            ->route('superadmin.akreditasi.index')
            ->with('success', 'Data akreditasi ditambahkan.');
    }

    public function edit(Akreditasi $akreditasi): View
    {
        return view('superadmin.konten.pendidikan.akreditasi.form', [
            'item' => $akreditasi,
            'prodiList' => Prodi::query()->active()->ordered()->get(['id', 'jeneng', 'undhak']),
            'selectedProdis' => Prodi::where('akreditasi_id', $akreditasi->id)->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Akreditasi $akreditasi): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('path_gambar')) {
            if ($akreditasi->path_gambar) {
                Storage::disk('public')->delete($akreditasi->path_gambar);
            }
            $data['path_gambar'] = $request->file('path_gambar')->store('akreditasi', 'public');
        }

        $akreditasi->update($data);

        $this->syncProdi($akreditasi, $request->input('prodi_ids', []));

        return redirect()
            ->route('superadmin.akreditasi.index')
            ->with('success', 'Data akreditasi diperbarui.');
    }

    private function syncProdi(Akreditasi $akreditasi, array $prodiIds)
    {
        Prodi::where('akreditasi_id', $akreditasi->id)
            ->update(['akreditasi_id' => null]);

        if (!empty($prodiIds)) {
            Prodi::whereIn('id', $prodiIds)
                ->update(['akreditasi_id' => $akreditasi->id]);
        }
    }

    public function destroy(Akreditasi $akreditasi): RedirectResponse
    {
        $akreditasi->delete();
        return back()->with('success', 'Data akreditasi dipindahkan ke sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Akreditasi::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->route('superadmin.akreditasi.trashed')->with('success', 'Akreditasi dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Akreditasi::onlyTrashed()->findOrFail($id);
        if ($item->path_gambar) {
            Storage::disk('public')->delete($item->path_gambar);
        }
        $item->forceDelete();
        return redirect()->route('superadmin.akreditasi.trashed')->with('success', 'Akreditasi dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $count = 0;
        Akreditasi::onlyTrashed()->get()->each(function ($item) use (&$count) {
            if ($item->path_gambar) {
                Storage::disk('public')->delete($item->path_gambar);
            }
            $item->forceDelete();
            $count++;
        });
        return redirect()->route('superadmin.akreditasi.trashed')->with('success', "{$count} data dihapus permanen.");
    }

    public function toggle(Akreditasi $akreditasi): RedirectResponse
    {
        $akreditasi->toggleUrip();
        return back()->with('success', $akreditasi->urip ? 'Akreditasi diaktifkan.' : 'Akreditasi dinonaktifkan.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'jeneng' => ['required', 'string', 'max:150'],
            'bagean' => ['required', Rule::in(['institusi', 'prodi'])],
            // 'prodi_id' => [...], <-- HAPUS INI
            'status_akred' => ['nullable', 'string', 'max:50'],
            'katrangan' => ['nullable', 'string'],
            'path_gambar' => ['nullable', 'image', 'mimes:png,jpeg,jpg,webp', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'jeneng.required' => 'Judul wajib diisi.',
            'path_gambar.image' => 'File harus berupa gambar.',
            'path_gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        unset($data['path_gambar']);
        return $data;
    }
}
