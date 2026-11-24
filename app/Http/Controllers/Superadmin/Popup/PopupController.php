<?php

namespace App\Http\Controllers\Superadmin\Popup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\PopupRequest;
use App\Models\Landing\Popup;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupController extends Controller
{
    public const KATEGORI_LABELS = [
        'pmb'      => 'PMB & Brosur',
        'jurusan'  => 'Jurusan / Prodi',
        'beasiswa' => 'Beasiswa',
        'info'     => 'Pusat Informasi',
        'custom'   => 'Lainnya',
    ];

    public function __construct(
        private readonly ImageOptimizer $imageOptimizer,
    ) {}

    public function index(Request $request): View
    {
        $viewMode = $request->string('view')->toString();
        $kategori = $request->string('kategori')->toString();
        $search   = trim($request->string('q')->toString());

        $query = $viewMode === 'trashed'
            ? Popup::onlyTrashed()->latest('deleted_at')
            : Popup::query()->orderBy('urutan')->orderBy('id');

        if ($kategori !== '' && array_key_exists($kategori, self::KATEGORI_LABELS)) {
            $query->where('kategori', $kategori);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        return view('superadmin.konten.landing.popup.index', [
            'popups'     => $query->paginate(10)->withQueryString(),
            'kategoris'  => self::KATEGORI_LABELS,
            'viewMode'   => $viewMode,
            'kategori'   => $kategori,
            'search'     => $search,
            'aktifCount' => Popup::count(),
            'arsipCount' => Popup::onlyTrashed()->count(),
        ]);
    }

    public function create(): View
    {
        $nextUrutan = ((int) Popup::withTrashed()->max('urutan')) + 1;

        return view('superadmin.konten.landing.popup.form', [
            'popup' => new Popup([
                'kategori'      => 'pmb',
                'urutan'        => $nextUrutan,
                'is_active'     => true,
                'buka_tab_baru' => false,
            ]),
            'kategoris' => self::KATEGORI_LABELS,
            'isEdit'    => false,
        ]);
    }

    public function store(PopupRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['hapus_gambar']);

        if ($request->hasFile('gambar')) {
            $stored = $this->imageOptimizer->store($request->file('gambar'), 'popups', false);
            $data['gambar'] = $stored['path'];
        }

        Popup::create($data);

        return redirect()
            ->route('superadmin.popup.index')
            ->with('success', 'Slide pop-up berhasil ditambahkan.');
    }

    public function edit(Popup $popup): View
    {
        return view('superadmin.konten.landing.popup.form', [
            'popup'     => $popup,
            'kategoris' => self::KATEGORI_LABELS,
            'isEdit'    => true,
        ]);
    }

    public function update(PopupRequest $request, Popup $popup): RedirectResponse
    {
        $data = $request->validated();
        $hapusGambar = (bool) ($data['hapus_gambar'] ?? false);
        unset($data['hapus_gambar']);

        if ($request->hasFile('gambar')) {
            $oldGambar = $popup->gambar;
            $stored = $this->imageOptimizer->store($request->file('gambar'), 'popups', false);
            $data['gambar'] = $stored['path'];
            $this->imageOptimizer->delete($oldGambar);
        } elseif ($hapusGambar) {
            $this->imageOptimizer->delete($popup->gambar);
            $data['gambar'] = null;
        } else {
            unset($data['gambar']);
        }

        $popup->update($data);

        return redirect()
            ->route('superadmin.popup.index')
            ->with('success', 'Slide pop-up berhasil diperbarui.');
    }

    public function toggleStatus(Popup $popup): RedirectResponse
    {
        $popup->update(['is_active' => ! $popup->is_active]);

        return back()->with('success', 'Status tampil pop-up berhasil diperbarui.');
    }

    public function destroy(Popup $popup): RedirectResponse
    {
        $popup->delete();

        return back()->with('success', 'Slide pop-up dipindahkan ke arsip.');
    }

    public function restore(int $id): RedirectResponse
    {
        $popup = Popup::onlyTrashed()->findOrFail($id);
        $popup->restore();

        return redirect()
            ->route('superadmin.popup.index', ['view' => 'trashed'])
            ->with('success', 'Slide pop-up berhasil dipulihkan.');
    }

    public function forceDestroy(int $id): RedirectResponse
    {
        $popup = Popup::onlyTrashed()->findOrFail($id);
        $this->imageOptimizer->delete($popup->gambar);
        $popup->forceDelete();

        return redirect()
            ->route('superadmin.popup.index', ['view' => 'trashed'])
            ->with('success', 'Slide pop-up dihapus permanen.');
    }
}
