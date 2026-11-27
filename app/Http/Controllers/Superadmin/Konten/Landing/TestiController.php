<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Testimoni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TestiController extends Controller
{
    public function index(Request $request): View
    {
        $q        = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));
        $status   = trim((string) $request->query('status', ''));
        $isTrash  = $status === 'trash';

        $query = $isTrash ? Testimoni::onlyTrashed() : Testimoni::query();

        $items = $query
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('peran', 'like', "%{$q}%")
                        ->orWhere('angkatan', 'like', "%{$q}%")
                        ->orWhere('instansi', 'like', "%{$q}%")
                        ->orWhere('sorotan', 'like', "%{$q}%")
                        ->orWhere('kutipan', 'like', "%{$q}%");
                });
            })
            ->when(array_key_exists($kategori, Testimoni::KATEGORI), function ($builder) use ($kategori) {
                $builder->where('kategori', $kategori);
            })
            ->when(! $isTrash && $status === 'aktif', fn($builder) => $builder->where('urip', true))
            ->when(! $isTrash && $status === 'nonaktif', fn($builder) => $builder->where('urip', false))
            ->when($isTrash, fn($builder) => $builder->orderByDesc('deleted_at'))
            ->when(! $isTrash, fn($builder) => $builder->orderBy('urutan', 'asc')->orderByDesc('id'))
            ->paginate(10)
            ->withQueryString();

        $totalAktif = Testimoni::where('urip', true)->count();
        $avgRating  = Testimoni::where('urip', true)->avg('rating');

        $counts = [
            'all'      => Testimoni::count(),
            'aktif'    => $totalAktif,
            'nonaktif' => Testimoni::where('urip', false)->count(),
            'trash'    => Testimoni::onlyTrashed()->count(),
        ];

        $stats = [
            'avg_rating' => $avgRating !== null ? number_format((float) $avgRating, 1) : '5.0',
            'kategori'   => [
                'alumni'    => Testimoni::where('kategori', 'alumni')->count(),
                'mahasiswa' => Testimoni::where('kategori', 'mahasiswa')->count(),
                'beasiswa'  => Testimoni::where('kategori', 'beasiswa')->count(),
            ],
        ];

        return view('superadmin.konten.landing.testi.index', [
            'items'    => $items,
            'counts'   => $counts,
            'stats'    => $stats,
            'q'        => $q,
            'kategori' => $kategori,
            'status'   => $status,
            'isTrash'  => $isTrash,
        ]);
    }

    public function create(): View
    {
        $nextUrutan = ((int) Testimoni::max('urutan')) + 1;

        return view('superadmin.konten.landing.testi.form', [
            'testimoni'  => null,
            'nextUrutan' => $nextUrutan,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['kategori'] = $data['kategori'] ?: 'alumni';
        $data['rating']   = (int) ($data['rating'] ?? 5);
        $data['urutan']   = (int) ($data['urutan'] ?? 0);
        $data['urip']     = $request->boolean('urip');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('landing/testimoni', 'public');
            $data['foto'] = 'storage/' . $path;
        }

        Testimoni::create($data);

        return redirect()
            ->route('superadmin.testimoni.index')
            ->with('success', 'Testimoni baru berhasil ditambahkan.');
    }

    public function edit(Testimoni $testimoni): View
    {
        return view('superadmin.konten.landing.testi.form', [
            'testimoni'  => $testimoni,
            'nextUrutan' => $testimoni->urutan,
        ]);
    }

    public function update(Request $request, Testimoni $testimoni): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['kategori'] = $data['kategori'] ?: 'alumni';
        $data['rating']   = (int) ($data['rating'] ?? 5);
        $data['urutan']   = (int) ($data['urutan'] ?? 0);
        $data['urip']     = $request->boolean('urip');

        if ($request->boolean('hapus_foto') && ! $request->hasFile('foto')) {
            $this->deleteStoredFoto($testimoni->foto);
            $data['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            $this->deleteStoredFoto($testimoni->foto);
            $path = $request->file('foto')->store('landing/testimoni', 'public');
            $data['foto'] = 'storage/' . $path;
        }

        $testimoni->update($data);

        return redirect()
            ->route('superadmin.testimoni.index')
            ->with('success', 'Perubahan testimoni berhasil disimpan.');
    }

    public function toggle(Testimoni $testimoni): RedirectResponse
    {
        $testimoni->urip = ! $testimoni->urip;
        $testimoni->save();

        $nama  = $testimoni->nama ?: 'Testimoni #' . $testimoni->id;
        $label = $testimoni->urip ? 'diaktifkan dan tayang di beranda' : 'dinonaktifkan dari beranda';

        return back()->with('success', "{$nama} berhasil {$label}.");
    }

    public function destroy(Testimoni $testimoni): RedirectResponse
    {
        $nama = $testimoni->nama ?: 'Testimoni #' . $testimoni->id;
        $testimoni->delete();

        return redirect()
            ->route('superadmin.testimoni.index')
            ->with('success', "{$nama} dipindahkan ke tong sampah.");
    }

    public function restore(int $id): RedirectResponse
    {
        $testimoni = Testimoni::onlyTrashed()->findOrFail($id);
        $testimoni->restore();

        $nama = $testimoni->nama ?: 'Testimoni #' . $testimoni->id;

        return redirect()
            ->route('superadmin.testimoni.index', ['status' => 'trash'])
            ->with('success', "{$nama} berhasil dipulihkan.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $testimoni = Testimoni::onlyTrashed()->findOrFail($id);
        $nama      = $testimoni->nama ?: 'Testimoni #' . $testimoni->id;

        $this->deleteStoredFoto($testimoni->foto);
        $testimoni->forceDelete();

        return redirect()
            ->route('superadmin.testimoni.index', ['status' => 'trash'])
            ->with('success', "{$nama} dihapus permanen.");
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama'     => ['nullable', 'string', 'max:150'],
            'peran'    => ['nullable', 'string', 'max:180'],
            'angkatan' => ['nullable', 'string', 'max:80'],
            'kategori' => ['nullable', Rule::in(array_keys(Testimoni::KATEGORI))],
            'instansi' => ['nullable', 'string', 'max:180'],
            'sorotan'  => ['nullable', 'string', 'max:200'],
            'kutipan'  => ['required', 'string', 'max:2000'],
            'rating'   => ['nullable', 'integer', 'min:1', 'max:5'],
            'urutan'   => ['nullable', 'integer', 'min:0'],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'kutipan.required' => 'Isi kutipan testimoni wajib diisi.',
            'rating.min'       => 'Rating minimal 1 bintang.',
            'rating.max'       => 'Rating maksimal 5 bintang.',
        ]);
    }

    private function deleteStoredFoto(?string $foto): void
    {
        $clean = ltrim((string) $foto, '/');
        if (str_starts_with($clean, 'storage/')) {
            $rel = substr($clean, 8);
            if ($rel !== '' && Storage::disk('public')->exists($rel)) {
                Storage::disk('public')->delete($rel);
            }
        }
    }
}
