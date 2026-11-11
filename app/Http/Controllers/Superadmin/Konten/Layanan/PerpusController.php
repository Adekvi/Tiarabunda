<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan\Perpus;
use App\Services\Landing\LandingHomeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerpusController extends Controller
{
    public function __construct(
        private readonly LandingHomeService $landing,
    ) {}

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
        $items = Perpus::query()
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when(! $trashed, fn($q) => $q->whereNull('deleted_at'))
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
            'total'      => Perpus::query()->count(),
            'aktif'      => Perpus::query()->active()->count(),
            'nonaktif'   => Perpus::query()->inactive()->count(),
            'terhapus'   => Perpus::onlyTrashed()->count(),
            'ada_portal' => Perpus::query()
                ->whereNotNull('data_tambahan->url_portal')
                ->where('data_tambahan->url_portal', '!=', '')
                ->count(),
        ];

        return view('superadmin.konten.layanan.perpus.index', [
            'items'   => $items,
            'stats'   => $stats,
            'trashed' => $trashed,
        ]);
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

        $this->landing->forgetPerpus();

        return redirect()
            ->route('superadmin.perpus.index')
            ->with('success', 'Konten perpustakaan ditambahkan.');
    }

    public function edit(Perpus $perpus): View
    {
        return view('superadmin.konten.layanan.perpus.form', [
            'item' => $perpus,
        ]);
    }

    public function update(Request $request, Perpus $perpus): RedirectResponse
    {
        $data = $this->validated($request);
        $data['data_tambahan'] = $this->metaFromRequest($request);
        $perpus->update($data);
        $this->landing->forgetPerpus();
        return redirect()
            ->route('superadmin.perpus.index')
            ->with('success', 'Konten perpustakaan diperbarui.');
    }

    public function toggle(Perpus $perpus): RedirectResponse
    {
        $perpus->toggleUrip();
        $this->landing->forgetPerpus();
        return back()->with(
            'success',
            $perpus->urip ? 'Konten diaktifkan.' : 'Konten dinonaktifkan.'
        );
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Perpus::onlyTrashed()->findOrFail($id);
        $item->restore();

        $this->landing->forgetPerpus();

        return redirect()->route('superadmin.perpus.trashed')->with('success', 'Konten dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Perpus::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        $this->landing->forgetPerpus();

        return redirect()->route('superadmin.perpus.trashed')->with('success', 'Konten dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        Perpus::onlyTrashed()->forceDelete();

        $this->landing->forgetPerpus();

        return redirect()->route('superadmin.perpus.trashed')->with('success', 'Semua sampah berhasil dikosongkan.');
    }

    public function destroy(Perpus $perpus): RedirectResponse
    {
        $perpus->delete();
        $this->landing->forgetPerpus();
        return back()->with('success', 'Konten perpustakaan dipindahkan ke sampah.');
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
