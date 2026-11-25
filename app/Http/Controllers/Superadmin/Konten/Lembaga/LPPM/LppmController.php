<?php

namespace App\Http\Controllers\Superadmin\Konten\Lembaga\LPPM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Lembaga\LppmRequest;
use App\Models\Lembaga\Lppm;
use App\Services\Superadmin\Lembaga\LppmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LppmController extends Controller
{
    public function __construct(
        private readonly LppmService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'kategori', 'status']);

        return view('superadmin.konten.lembaga.lppm.index', [
            'isTrash'        => false,
            'items'          => $this->service->getPaginated($filters),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => Lppm::KATEGORI,
            'filters'        => $filters,
        ]);
    }

    public function trashed(): View
    {
        return view('superadmin.konten.lembaga.lppm.index', [
            'isTrash'        => true,
            'items'          => $this->service->getTrashed(),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => Lppm::KATEGORI,
            'filters'        => [],
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.lembaga.lppm.form', [
            'lppm'       => new Lppm(['kategori' => 'penelitian', 'urip' => true, 'urutan' => 0]),
            'categories' => Lppm::KATEGORI,
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function store(LppmRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('superadmin.lppm.index')
            ->with('success', 'Konten LPPM berhasil ditambahkan.');
    }

    public function edit(Lppm $lppm): View
    {
        return view('superadmin.konten.lembaga.lppm.form', [
            'lppm'       => $lppm,
            'categories' => Lppm::KATEGORI,
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function update(LppmRequest $request, Lppm $lppm): RedirectResponse
    {
        $this->service->update($lppm, $request->validated());

        return redirect()
            ->route('superadmin.lppm.index')
            ->with('success', 'Perubahan konten LPPM berhasil disimpan.');
    }

    public function toggle(Request $request, Lppm $lppm): RedirectResponse|JsonResponse
    {
        $lppm->update(['urip' => ! $lppm->urip]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok'   => true,
                'urip' => $lppm->urip,
                'msg'  => $lppm->urip ? 'Konten LPPM diaktifkan.' : 'Konten LPPM dinonaktifkan.',
            ]);
        }

        return back()->with('success', 'Status publikasi konten LPPM diperbarui.');
    }

    public function destroy(Lppm $lppm): RedirectResponse
    {
        $lppm->delete();

        return redirect()
            ->route('superadmin.lppm.index')
            ->with('success', 'Konten LPPM dipindahkan ke tempat sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Lppm::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.lppm.trashed')
            ->with('success', 'Konten LPPM berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->service->forceDelete($id);

        return redirect()
            ->route('superadmin.lppm.trashed')
            ->with('success', 'Konten LPPM dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $deleted = $this->service->emptyTrash();

        return redirect()
            ->route('superadmin.lppm.trashed')
            ->with('success', "{$deleted} konten LPPM di tempat sampah dihapus permanen.");
    }
}
