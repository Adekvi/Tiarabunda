<?php

namespace App\Http\Controllers\Superadmin\Konten\Profil;

use App\Http\Controllers\Controller;
use App\Models\Profil\Pimpinan;
use App\Services\Landing\LandingHomeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PimpinanController extends Controller
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
            'q'      => $request->string('q')->trim()->toString(),
            'status' => $request->input('status'),
        ];

        $items = Pimpinan::query()
            ->select([
                'id',
                'jeneng',
                'kalungguhan',
                'path_foto',
                'unen',
                'riwayat',
                'urutan',
                'urip',
                'updated_at',
                'deleted_at',
            ])
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when(! $trashed, fn($q) => $q->whereNull('deleted_at'))
            ->when($filters['status'] === 'active', fn($q) => $q->active())
            ->when($filters['status'] === 'inactive', fn($q) => $q->inactive())
            ->when(filled($filters['q']), function ($q) use ($filters) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
                $q->where(function ($i) use ($t) {
                    $i->where('jeneng', 'like', $t)
                        ->orWhere('kalungguhan', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'    => Pimpinan::query()->count(),
            'aktif'    => Pimpinan::query()->active()->count(),
            'nonaktif' => Pimpinan::query()->inactive()->count(),
            'terhapus' => Pimpinan::onlyTrashed()->count(),

            'dengan_foto' => Pimpinan::query()
                ->whereNotNull('path_foto')
                ->where('path_foto', '!=', '')
                ->count(),
        ];

        return view('superadmin.konten.profil.pimpinan.index', [
            'items'    => $items,
            'stats'    => $stats,
            'trashed'  => $trashed,
            'filtered' => filled($filters['q']) || filled($filters['status']),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.profil.pimpinan.form', [
            'item' => new Pimpinan(['urutan' => 0, 'urip' => true]),
        ]);
    }

    public function store(Request $request, LandingHomeService $homeService): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['path_foto'] = $request->file('foto')->store('pimpinan', 'public');
        }

        Pimpinan::query()->create($data);
        $homeService->forgetPimpinan();

        return redirect()
            ->route('superadmin.pimpinan.index')
            ->with('success', 'Pimpinan berhasil ditambahkan.');
    }

    public function edit(Pimpinan $pimpinan): View
    {
        return view('superadmin.konten.profil.pimpinan.form', [
            'item' => $pimpinan,
        ]);
    }

    public function update(Request $request, Pimpinan $pimpinan, LandingHomeService $homeService): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            if ($pimpinan->path_foto) {
                Storage::disk('public')->delete($pimpinan->path_foto);
            }
            $data['path_foto'] = $request->file('foto')->store('pimpinan', 'public');
        }

        $pimpinan->update($data);
        $homeService->forgetPimpinan();

        return redirect()
            ->route('superadmin.pimpinan.index')
            ->with('success', 'Pimpinan berhasil diperbarui.');
    }

    public function destroy(Pimpinan $pimpinan, LandingHomeService $homeService): RedirectResponse
    {
        if ($pimpinan->trashed()) {
            return back()->with('error', 'Pimpinan sudah berada di sampah.');
        }

        $pimpinan->delete();
        $homeService->forgetPimpinan();

        return back()->with('success', 'Pimpinan dipindahkan ke sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Pimpinan::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.pimpinan.trashed')
            ->with('success', 'Pimpinan dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Pimpinan::onlyTrashed()->findOrFail($id);

        try {
            DB::transaction(function () use ($item) {
                if ($item->path_foto) {
                    Storage::disk('public')->delete($item->path_foto);
                }
                $item->forceDelete();
            });
        } catch (\Throwable $e) {
            Log::error('Gagal force delete pimpinan', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal hapus permanen. Coba lagi.');
        }

        return redirect()
            ->route('superadmin.pimpinan.trashed')
            ->with('success', 'Pimpinan dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $count = 0;

        DB::transaction(function () use (&$count) {
            Pimpinan::onlyTrashed()->get()->each(function (Pimpinan $item) use (&$count) {
                if ($item->path_foto) {
                    Storage::disk('public')->delete($item->path_foto);
                }
                $item->forceDelete();
                $count++;
            });
        });

        return redirect()
            ->route('superadmin.pimpinan.trashed')
            ->with('success', "{$count} pimpinan dihapus permanen.");
    }

    public function toggle(Pimpinan $pimpinan, LandingHomeService $homeService): RedirectResponse
    {
        $pimpinan->toggleUrip();
        $homeService->forgetPimpinan();

        return back()->with(
            'success',
            $pimpinan->urip ? 'Pimpinan diaktifkan.' : 'Pimpinan dinonaktifkan.'
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'jeneng'      => ['required', 'string', 'max:150'],
            'kalungguhan' => ['required', 'string', 'max:150'],
            'unen'        => ['nullable', 'string', 'max:2000'],
            'riwayat'     => ['nullable', 'string', 'max:10000'],
            'urutan'      => ['nullable', 'integer', 'min:0', 'max:9999'],
            'foto'        => ['nullable', 'image', 'max:2048'],
        ]);

        $data['urip']   = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        unset($data['foto']);

        return $data;
    }
}
