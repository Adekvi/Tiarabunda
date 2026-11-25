<?php

namespace App\Http\Controllers\Superadmin\Konten\Lembaga\LPM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Lembaga\LpmRequest;
use App\Models\Lembaga\Lpm;
use App\Services\Superadmin\Lembaga\LpmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LpmController extends Controller
{
    public function __construct(
        private readonly LpmService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'kategori', 'status']);

        return view('superadmin.konten.lembaga.lpm.index', [
            'isTrash'        => false,
            'items'          => $this->service->getPaginated($filters),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => Lpm::KATEGORI,
            'filters'        => $filters,
        ]);
    }

    public function trashed(): View
    {
        return view('superadmin.konten.lembaga.lpm.index', [
            'isTrash'        => true,
            'items'          => $this->service->getTrashed(),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => Lpm::KATEGORI,
            'filters'        => [],
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.lembaga.lpm.form', [
            'lpm'        => new Lpm(['kategori' => 'spmi', 'urip' => true, 'urutan' => 0]),
            'categories' => Lpm::KATEGORI,
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function store(LpmRequest $request): RedirectResponse
    {
        $this->service->create(
            $request->validated(),
            $request->file('file')
        );

        return redirect()
            ->route('superadmin.lpm.index')
            ->with('success', 'Konten LPM berhasil ditambahkan.');
    }

    public function edit(Lpm $lpm): View
    {
        return view('superadmin.konten.lembaga.lpm.form', [
            'lpm'        => $lpm,
            'categories' => Lpm::KATEGORI,
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function update(LpmRequest $request, Lpm $lpm): RedirectResponse
    {
        $this->service->update(
            $lpm,
            $request->validated(),
            $request->file('file')
        );

        return redirect()
            ->route('superadmin.lpm.index')
            ->with('success', 'Perubahan konten LPM berhasil disimpan.');
    }

    public function toggle(Request $request, Lpm $lpm): RedirectResponse|JsonResponse
    {
        $lpm->update(['urip' => ! $lpm->urip]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok'   => true,
                'urip' => $lpm->urip,
                'msg'  => $lpm->urip ? 'Konten LPM diaktifkan.' : 'Konten LPM dinonaktifkan.',
            ]);
        }

        return back()->with('success', 'Status publikasi konten LPM diperbarui.');
    }

    public function destroy(Lpm $lpm): RedirectResponse
    {
        $lpm->delete();

        return redirect()
            ->route('superadmin.lpm.index')
            ->with('success', 'Konten LPM dipindahkan ke tempat sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Lpm::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.lpm.trashed')
            ->with('success', 'Konten LPM berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->service->forceDelete($id);

        return redirect()
            ->route('superadmin.lpm.trashed')
            ->with('success', 'Konten LPM beserta berkas dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $deleted = $this->service->emptyTrash();

        return redirect()
            ->route('superadmin.lpm.trashed')
            ->with('success', "{$deleted} konten LPM di tempat sampah dihapus permanen.");
    }
}
