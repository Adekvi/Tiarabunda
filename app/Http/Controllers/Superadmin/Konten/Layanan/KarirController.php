<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan\Karier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KarirController extends Controller
{
    public function index(Request $request): View
    {
        $items = Karier::query()
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
            'total' => Karier::query()->count(),
            'aktif' => Karier::query()->active()->count(),
            'nonaktif' => Karier::query()->inactive()->count(),
            'ada_tautan' => Karier::query()
                ->whereNotNull('data_tambahan')
                ->get(['data_tambahan'])
                ->filter(fn($r) => filled(data_get($r->data_tambahan, 'url_portal')))
                ->count(),
        ];

        return view('superadmin.konten.layanan.karier.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.karier.form', [
            'item' => new Karier([
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

        Karier::query()->create($data);

        return redirect()
            ->route('superadmin.karir.index')
            ->with('success', 'Konten pusat karier ditambahkan.');
    }

    public function edit(Karier $karir): View
    {
        // resource "karir" → {karir}
        return view('superadmin.konten.layanan.karier.form', [
            'item' => $karir,
        ]);
    }

    public function update(Request $request, Karier $karir): RedirectResponse
    {
        $data = $this->validated($request);
        $data['data_tambahan'] = $this->metaFromRequest($request);
        $karir->update($data);

        return redirect()
            ->route('superadmin.karir.index')
            ->with('success', 'Konten pusat karier diperbarui.');
    }

    public function destroy(Karier $karir): RedirectResponse
    {
        $karir->delete();

        return back()->with('success', 'Konten pusat karier dihapus.');
    }

    public function toggle(Karier $karir): RedirectResponse
    {
        $karir->toggleUrip();

        return back()->with(
            'success',
            $karir->urip ? 'Konten diaktifkan.' : 'Konten dinonaktifkan.'
        );
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
