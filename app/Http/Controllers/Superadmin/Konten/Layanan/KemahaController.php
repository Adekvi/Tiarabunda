<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan\Kemahasiswaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KemahaController extends Controller
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
        $items = Kemahasiswaan::query()
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('judhul', 'like', $t)
                        ->orWhere('isi', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'      => Kemahasiswaan::count(),
            'aktif'      => Kemahasiswaan::active()->count(),
            'nonaktif'   => Kemahasiswaan::inactive()->count(),
            'ada_kontak' => Kemahasiswaan::whereNotNull('data_tambahan->kontak')->count(),
            'terhapus'   => Kemahasiswaan::onlyTrashed()->count(),
        ];

        return view('superadmin.konten.layanan.kemaha.index', compact('items', 'stats', 'trashed'));
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.kemaha.form', [
            'item' => new Kemahasiswaan([
                'urutan' => 0,
                'urip' => true,
                'data_tambahan' => [],
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['data_tambahan'] = $this->metaFromRequest($request);

        Kemahasiswaan::query()->create($data);

        return redirect()
            ->route('superadmin.kemaha.index')
            ->with('success', 'Konten kemahasiswaan ditambahkan.');
    }

    public function edit(Kemahasiswaan $kemaha): View
    {
        // resource "kemaha" → parameter {kemaha}
        return view('superadmin.konten.layanan.kemaha.form', [
            'item' => $kemaha,
        ]);
    }

    public function update(Request $request, Kemahasiswaan $kemaha): RedirectResponse
    {
        $data = $this->validated($request);
        $data['data_tambahan'] = $this->metaFromRequest($request);
        $kemaha->update($data);

        return redirect()
            ->route('superadmin.kemaha.index')
            ->with('success', 'Konten kemahasiswaan diperbarui.');
    }

    public function destroy(Kemahasiswaan $kemaha): RedirectResponse
    {
        $kemaha->delete();

        return back()->with('success', 'Konten kemahasiswaan dihapus.');
    }

    public function toggle(Kemahasiswaan $kemaha): RedirectResponse
    {
        $kemaha->toggleUrip();

        return back()->with(
            'success',
            $kemaha->urip ? 'Konten diaktifkan.' : 'Konten dinonaktifkan.'
        );
    }

    public function restore(int $id): RedirectResponse
    {
        Kemahasiswaan::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Konten dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        Kemahasiswaan::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Konten dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        Kemahasiswaan::onlyTrashed()->forceDelete();
        return redirect()
            ->route('superadmin.kemaha.trashed')
            ->with('success', 'Sampah dikosongkan.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:150'],
            'isi' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'url_portal' => ['nullable', 'url', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'judhul.required' => 'Judul wajib diisi.',
            'url_portal.url' => 'URL harus valid (sertakan https://).',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        unset($data['kontak'], $data['email'], $data['lokasi'], $data['url_portal']);

        return $data;
    }

    /** @return array<string, string> */
    private function metaFromRequest(Request $request): array
    {
        return array_filter([
            'kontak' => $request->input('kontak'),
            'email' => $request->input('email'),
            'lokasi' => $request->input('lokasi'),
            'url_portal' => $request->input('url_portal'),
        ], fn($v) => filled($v));
    }
}
