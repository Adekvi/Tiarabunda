<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan\Perpus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerpusController extends Controller
{
    public function index(Request $request): View
    {
        $items = Perpus::query()
            ->select(['id', 'judhul', 'isi', 'data_tambahan', 'urutan', 'urip', 'updated_at'])
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
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Perpus::query()->count(),
            'aktif' => Perpus::query()->active()->count(),
            'nonaktif' => Perpus::query()->inactive()->count(),
            'ada_portal' => Perpus::query()
                ->whereNotNull('data_tambahan')
                ->get(['data_tambahan'])
                ->filter(fn($p) => filled(data_get($p->data_tambahan, 'url_portal')))
                ->count(),
        ];

        // Catatan latency: untuk tabel kecil OK.
        // Jika data besar, ganti "ada_portal" dengan whereJsonContains / JSON path index.

        return view('superadmin.konten.layanan.perpus.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.perpus.form', [
            'item' => new Perpus([
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

        Perpus::query()->create($data);

        return redirect()
            ->route('superadmin.perpus.index')
            ->with('success', 'Konten perpustakaan ditambahkan.');
    }

    public function edit(Perpus $perpu): View
    {
        // Route model binding: {perpu} dari resource "perpus"
        return view('superadmin.konten.layanan.perpus.form', [
            'item' => $perpu,
        ]);
    }

    public function update(Request $request, Perpus $perpu): RedirectResponse
    {
        $data = $this->validated($request);
        $data['data_tambahan'] = $this->metaFromRequest($request);
        $perpu->update($data);

        return redirect()
            ->route('superadmin.perpus.index')
            ->with('success', 'Konten perpustakaan diperbarui.');
    }

    public function destroy(Perpus $perpu): RedirectResponse
    {
        $perpu->delete();

        return back()->with('success', 'Konten perpustakaan dihapus.');
    }

    public function toggle(Perpus $perpu): RedirectResponse
    {
        $perpu->toggleUrip();

        return back()->with(
            'success',
            $perpu->urip ? 'Konten diaktifkan.' : 'Konten dinonaktifkan.'
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:150'],
            'isi' => ['nullable', 'string'],
            'url_portal' => ['nullable', 'url', 'max:255'],
            'jam_layanan' => ['nullable', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'judhul.required' => 'Judul wajib diisi.',
            'url_portal.url' => 'URL portal harus valid (sertakan https://).',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        unset($data['url_portal'], $data['jam_layanan'], $data['kontak'], $data['email']);

        return $data;
    }

    /** @return array<string, string> */
    private function metaFromRequest(Request $request): array
    {
        return array_filter([
            'url_portal' => $request->input('url_portal'),
            'jam_layanan' => $request->input('jam_layanan'),
            'kontak' => $request->input('kontak'),
            'email' => $request->input('email'),
        ], fn($v) => filled($v));
    }
}
