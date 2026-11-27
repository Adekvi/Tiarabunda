<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Lokasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LokasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lokasi::query();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('urip', $request->input('status') === '1');
        }

        $items = $query->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $totalAktif = Lokasi::where('urip', true)->count();
        $totalNonaktif = Lokasi::where('urip', false)->count();
        $trashedCount = Lokasi::onlyTrashed()->count();
        $isTrashed = false;

        return view('superadmin.konten.landing.lokasi.index', compact(
            'items',
            'totalAktif',
            'totalNonaktif',
            'trashedCount',
            'isTrashed'
        ));
    }

    public function trashed(Request $request): View
    {
        $query = Lokasi::onlyTrashed();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $items = $query->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $totalAktif = Lokasi::where('urip', true)->count();
        $totalNonaktif = Lokasi::where('urip', false)->count();
        $trashedCount = Lokasi::onlyTrashed()->count();
        $isTrashed = true;

        return view('superadmin.konten.landing.lokasi.index', compact(
            'items',
            'totalAktif',
            'totalNonaktif',
            'trashedCount',
            'isTrashed'
        ));
    }

    public function create(): View
    {
        $nextUrutan = ((int) Lokasi::max('urutan')) + 1;

        return view('superadmin.konten.landing.lokasi.form', [
            'lokasi'     => null,
            'nextUrutan' => $nextUrutan,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('landing/lokasi', 'public');
            $data['foto'] = 'storage/' . $path;
        }

        Lokasi::create($data);

        return redirect()
            ->route('superadmin.lokasi.index')
            ->with('success', 'Data lokasi kampus berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi): View
    {
        return view('superadmin.konten.landing.lokasi.form', [
            'lokasi'     => $lokasi,
            'nextUrutan' => $lokasi->urutan,
        ]);
    }

    public function update(Request $request, Lokasi $lokasi): RedirectResponse
    {
        $data = $this->validatePayload($request);

        if ($request->hasFile('foto')) {
            $this->deleteStoredFoto($lokasi->foto);
            $path = $request->file('foto')->store('landing/lokasi', 'public');
            $data['foto'] = 'storage/' . $path;
        } elseif ($request->boolean('hapus_foto')) {
            $this->deleteStoredFoto($lokasi->foto);
            $data['foto'] = null;
        }

        $lokasi->update($data);

        return redirect()
            ->route('superadmin.lokasi.index')
            ->with('success', 'Perubahan lokasi kampus berhasil disimpan.');
    }

    public function toggle(Lokasi $lokasi): RedirectResponse
    {
        $lokasi->update([
            'urip' => ! $lokasi->urip,
        ]);

        $label = $lokasi->urip ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "{$lokasi->nama} berhasil {$label}.");
    }

    public function destroy(Lokasi $lokasi): RedirectResponse
    {
        $nama = $lokasi->nama;
        $lokasi->delete();

        return back()->with('success', "{$nama} dipindahkan ke tong sampah.");
    }

    public function restore(int $id): RedirectResponse
    {
        $lokasi = Lokasi::onlyTrashed()->findOrFail($id);
        $lokasi->restore();

        return back()->with('success', "{$lokasi->nama} berhasil dipulihkan.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $lokasi = Lokasi::onlyTrashed()->findOrFail($id);
        $nama = $lokasi->nama;

        $this->deleteStoredFoto($lokasi->foto);
        $lokasi->forceDelete();

        return back()->with('success', "{$nama} dihapus secara permanen.");
    }

    public function emptyTrash(): RedirectResponse
    {
        $trashed = Lokasi::onlyTrashed()->get();

        foreach ($trashed as $item) {
            $this->deleteStoredFoto($item->foto);
            $item->forceDelete();
        }

        return redirect()
            ->route('superadmin.lokasi.trashed')
            ->with('success', 'Tong sampah lokasi kampus berhasil dikosongkan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:10'],
            'badge'     => ['required', 'string', 'max:150'],
            'nama'      => ['required', 'string', 'max:180'],
            'deskripsi' => ['required', 'string'],
            'alamat'    => ['required', 'string'],
            'jam'       => ['required', 'string', 'max:180'],
            'telepon'   => ['required', 'string', 'max:50'],
            'koordinat' => ['nullable', 'string', 'max:100'],
            'akses'     => ['nullable', 'string', 'max:255'],
            'unggulan'  => ['nullable', 'string'],
            'foto'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'map_embed' => ['nullable', 'string'],
            'map_url'   => ['nullable', 'url', 'max:500'],
            'urutan'    => ['nullable', 'integer', 'min:0'],
            'urip'      => ['nullable', 'boolean'],
        ]);

        $chips = [];
        if (! empty($validated['unggulan'])) {
            $chips = array_values(array_filter(array_map('trim', explode(',', $validated['unggulan']))));
        }

        return [
            'kode'      => strtoupper(trim($validated['kode'])),
            'badge'     => trim($validated['badge']),
            'nama'      => trim($validated['nama']),
            'deskripsi' => trim($validated['deskripsi']),
            'alamat'    => trim($validated['alamat']),
            'jam'       => trim($validated['jam']),
            'telepon'   => trim($validated['telepon']),
            'koordinat' => isset($validated['koordinat']) ? trim($validated['koordinat']) : null,
            'akses'     => isset($validated['akses']) ? trim($validated['akses']) : null,
            'unggulan'  => $chips,
            'map_embed' => $this->extractEmbedUrl($validated['map_embed'] ?? null),
            'map_url'   => $validated['map_url'] ?? null,
            'urutan'    => (int) ($validated['urutan'] ?? 0),
            'urip'      => $request->boolean('urip'),
        ];
    }

    private function extractEmbedUrl(?string $raw): ?string
    {
        $val = trim((string) $raw);
        if ($val === '') {
            return null;
        }

        if (preg_match('/src=["\']([^"\']+)["\']/i', $val, $matches)) {
            return $matches[1];
        }

        return $val;
    }

    private function deleteStoredFoto(?string $fotoPath): void
    {
        if ($fotoPath && str_starts_with($fotoPath, 'storage/')) {
            $relative = substr($fotoPath, strlen('storage/'));
            Storage::disk('public')->delete($relative);
        }
    }
}
