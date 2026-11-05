<?php

namespace App\Http\Controllers\Superadmin\Konten\Layanan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Layanan\FasilitasRequest;
use App\Models\Layanan\Fasilitas;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
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
        $filters = [
            'q'      => $request->string('q')->trim()->toString(),
            'status' => $request->input('status'),
        ];

        $items = Fasilitas::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'path_gambar',
                'path_thumb',
                'urutan',
                'urip',
                'updated_at',
                'deleted_at'
            ])
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->filtered($filters)
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'       => Fasilitas::query()->count(),
            'aktif'       => Fasilitas::query()->active()->count(),
            'nonaktif'    => Fasilitas::query()->inactive()->count(),
            'ada_gambar'  => Fasilitas::query()->whereNotNull('path_gambar')->count(),
            'terhapus'    => Fasilitas::onlyTrashed()->count(),
        ];

        return view('superadmin.konten.layanan.fasilitas.index', [
            'items'    => $items,
            'stats'    => $stats,
            'trashed'  => $trashed,
            'filtered' => filled($filters['q']) || filled($filters['status']),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.layanan.fasilitas.form', [
            'item' => new Fasilitas(['urutan' => 0, 'urip' => true]),
        ]);
    }

    public function store(FasilitasRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('path_gambar')) {
            $file = $request->file('path_gambar');

            if (! $file->isValid()) {
                return back()->withInput()
                    ->withErrors(['path_gambar' => 'File gagal diunggah (kemungkinan melebihi upload_max_filesize).']);
            }

            try {
                $stored = $this->images->store($file, 'fasilitas');
            } catch (\Throwable $e) {
                Log::error('Gagal optimasi gambar fasilitas', [
                    'error' => $e->getMessage(),
                    'file'  => $file->getClientOriginalName(),
                ]);
                return back()->withInput()
                    ->withErrors(['path_gambar' => 'Gagal memproses gambar: ' . $e->getMessage()]);
            }

            $data['path_gambar'] = $stored['path'];
            $data['path_thumb']  = $stored['thumb'];
        }

        try {
            Fasilitas::query()->create($data);
        } catch (\Throwable $e) {
            // rollback file yang baru disimpan
            $this->images->delete($data['path_gambar'] ?? null, $data['path_thumb'] ?? null);

            Log::error('Gagal menyimpan fasilitas', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->withErrors(['jeneng' => 'Gagal menyimpan data. Coba lagi.']);
        }

        return redirect()
            ->route('superadmin.fasilitas.index')
            ->with('success', 'Fasilitas ditambahkan.');
    }

    public function edit(Fasilitas $fasilita): View
    {
        return view('superadmin.konten.layanan.fasilitas.form', [
            'item' => $fasilita,
        ]);
    }

    public function update(FasilitasRequest $request, Fasilitas $fasilita): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('path_gambar')) {
            $file = $request->file('path_gambar');

            if (! $file->isValid()) {
                return back()->withInput()
                    ->withErrors(['path_gambar' => 'File gagal diunggah (kemungkinan melebihi upload_max_filesize).']);
            }

            try {
                $stored = $this->images->store($file, 'fasilitas');
            } catch (\Throwable $e) {
                Log::error('Gagal optimasi gambar fasilitas', [
                    'error' => $e->getMessage(),
                    'file'  => $file->getClientOriginalName(),
                ]);
                return back()->withInput()
                    ->withErrors(['path_gambar' => 'Gagal memproses gambar: ' . $e->getMessage()]);
            }

            // Simpan yang baru dulu, baru hapus yang lama
            $this->images->delete($fasilita->path_gambar, $fasilita->path_thumb);

            $data['path_gambar'] = $stored['path'];
            $data['path_thumb']  = $stored['thumb'];
        } elseif ($request->boolean('hapus_gambar')) {
            $this->images->delete($fasilita->path_gambar, $fasilita->path_thumb);
            $data['path_gambar'] = null;
            $data['path_thumb']  = null;
        }

        try {
            $fasilita->update($data);
        } catch (\Throwable $e) {
            Log::error('Gagal update fasilitas', ['id' => $fasilita->id, 'error' => $e->getMessage()]);
            return back()->withInput()
                ->withErrors(['jeneng' => 'Gagal menyimpan perubahan. Coba lagi.']);
        }

        return redirect()
            ->route('superadmin.fasilitas.index')
            ->with('success', 'Fasilitas diperbarui.');
    }

    public function destroy(Fasilitas $fasilita): RedirectResponse
    {
        if ($fasilita->trashed()) {
            return back()->with('error', 'Fasilitas sudah berada di sampah.');
        }

        $fasilita->delete();

        return back()->with('success', 'Fasilitas dipindahkan ke sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Fasilitas::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.fasilitas.trashed')
            ->with('success', 'Fasilitas dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Fasilitas::onlyTrashed()->findOrFail($id);

        $this->images->delete($item->path_gambar, $item->path_thumb);
        $item->forceDelete();

        return redirect()
            ->route('superadmin.fasilitas.trashed')
            ->with('success', 'Fasilitas dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        DB::transaction(function () {
            Fasilitas::onlyTrashed()->get()->each(function ($item) {
                $this->images->delete($item->path_gambar, $item->path_thumb);
                $item->forceDelete();
            });
        });

        return redirect()
            ->route('superadmin.fasilitas.trashed')
            ->with('success', 'Semua sampah fasilitas dibersihkan.');
    }

    public function toggle(Fasilitas $fasilita): RedirectResponse
    {
        $fasilita->toggleUrip();

        return back()->with(
            'success',
            $fasilita->urip ? 'Fasilitas diaktifkan.' : 'Fasilitas dinonaktifkan.'
        );
    }
}
