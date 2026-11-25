<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Layanan\AkademikRequest;
use App\Models\Layanan\Akademik;
use App\Services\Superadmin\Layanan\AkademikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkademikController extends Controller
{
    public function __construct(
        private readonly AkademikService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'kategori', 'status']);

        return view('superadmin.konten.layanan.akademik.index', [
            'isTrash'        => false,
            'items'          => $this->service->getPaginated($filters),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => $this->service->getCategories(),
            'filters'        => $filters,
        ]);
    }

    public function trashed(): View
    {
        return view('superadmin.konten.layanan.akademik.index', [
            'isTrash'        => true,
            'items'          => $this->service->getTrashed(),
            'stats'          => $this->service->getStats(),
            'categoryCounts' => $this->service->getCategoryCounts(),
            'categories'     => [],
            'filters'        => [],
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.akademik.form', [
            'akademik'   => new Akademik(['urip' => true, 'urutan' => 0, 'kategori' => 'Umum']),
            'categories' => $this->service->getCategories(),
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function store(AkademikRequest $request): RedirectResponse
    {
        $this->service->create($request->validated(), $request->file('file'));

        return redirect()
            ->route('superadmin.akademik.index')
            ->with('success', 'Dokumen layanan akademik berhasil ditambahkan.');
    }

    public function edit(Akademik $akademik): View
    {
        return view('superadmin.konten.layanan.akademik.form', [
            'akademik'   => $akademik,
            'categories' => $this->service->getCategories(),
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function update(AkademikRequest $request, Akademik $akademik): RedirectResponse
    {
        $this->service->update($akademik, $request->validated(), $request->file('file'));

        return redirect()
            ->route('superadmin.akademik.index')
            ->with('success', 'Perubahan dokumen akademik berhasil disimpan.');
    }

    public function toggle(Request $request, Akademik $akademik): RedirectResponse|JsonResponse
    {
        $akademik->update(['urip' => ! $akademik->urip]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok'   => true,
                'urip' => $akademik->urip,
                'msg'  => $akademik->urip ? 'Dokumen diaktifkan.' : 'Dokumen dinonaktifkan.',
            ]);
        }

        return back()->with('success', 'Status publikasi dokumen diperbarui.');
    }

    public function destroy(Akademik $akademik): RedirectResponse
    {
        $akademik->delete();

        return redirect()
            ->route('superadmin.akademik.index')
            ->with('success', 'Dokumen dipindahkan ke tempat sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Akademik::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.akademik.trashed')
            ->with('success', 'Dokumen berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->service->forceDelete($id);

        return redirect()
            ->route('superadmin.akademik.trashed')
            ->with('success', 'Dokumen dan berkas dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $deleted = $this->service->emptyTrash();

        return redirect()
            ->route('superadmin.akademik.trashed')
            ->with('success', "{$deleted} dokumen di tempat sampah dihapus permanen.");
    }
}
