<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Layanan\KerjasamaRequest;
use App\Models\Layanan\Kerjasama;
use App\Services\Superadmin\Layanan\KerjasamaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KerjasamaController extends Controller
{
    public function __construct(
        private readonly KerjasamaService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'jenis', 'kategori', 'status']);

        return view('superadmin.konten.layanan.kerjasama.index', [
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
        return view('superadmin.konten.layanan.kerjasama.index', [
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
        return view('superadmin.konten.layanan.kerjasama.form', [
            'kerjasama'  => new Kerjasama([
                'jenis'    => Kerjasama::JENIS_MITRA,
                'urip'     => true,
                'urutan'   => 0,
                'kategori' => 'Industri & Perusahaan',
            ]),
            'categories' => $this->service->getCategories(),
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function store(KerjasamaRequest $request): RedirectResponse
    {
        $this->service->create(
            $request->validated(),
            $request->file('logo'),
            $request->file('file')
        );

        return redirect()
            ->route('superadmin.kerjasama.index')
            ->with('success', 'Data kerjasama berhasil ditambahkan.');
    }

    public function edit(Kerjasama $kerjasama): View
    {
        return view('superadmin.konten.layanan.kerjasama.form', [
            'kerjasama'  => $kerjasama,
            'categories' => $this->service->getCategories(),
            'stats'      => $this->service->getStats(),
        ]);
    }

    public function update(KerjasamaRequest $request, Kerjasama $kerjasama): RedirectResponse
    {
        $this->service->update(
            $kerjasama,
            $request->validated(),
            $request->file('logo'),
            $request->file('file')
        );

        return redirect()
            ->route('superadmin.kerjasama.index')
            ->with('success', 'Perubahan data kerjasama berhasil disimpan.');
    }

    public function toggle(Request $request, Kerjasama $kerjasama): RedirectResponse|JsonResponse
    {
        $kerjasama->update(['urip' => ! $kerjasama->urip]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok'   => true,
                'urip' => $kerjasama->urip,
                'msg'  => $kerjasama->urip ? 'Kerjasama diaktifkan.' : 'Kerjasama dinonaktifkan.',
            ]);
        }

        return back()->with('success', 'Status publikasi kerjasama diperbarui.');
    }

    public function destroy(Kerjasama $kerjasama): RedirectResponse
    {
        $kerjasama->delete();

        return redirect()
            ->route('superadmin.kerjasama.index')
            ->with('success', 'Data kerjasama dipindahkan ke tempat sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Kerjasama::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.kerjasama.trashed')
            ->with('success', 'Data kerjasama berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->service->forceDelete($id);

        return redirect()
            ->route('superadmin.kerjasama.trashed')
            ->with('success', 'Data kerjasama beserta berkas dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $deleted = $this->service->emptyTrash();

        return redirect()
            ->route('superadmin.kerjasama.trashed')
            ->with('success', "{$deleted} data di tempat sampah dihapus permanen.");
    }
}
