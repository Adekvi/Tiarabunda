<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan\Program;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FakultasController extends Controller
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

        $items = Fakultas::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'kodhe',
                'katrangan',
                'urutan',
                'urip',
                'updated_at',
                'deleted_at',
            ])
            ->withCount(['prodis', 'activeProdis'])
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when(! $trashed, fn($q) => $q->whereNull('deleted_at'))
            ->when($filters['status'] === 'active',   fn($q) => $q->active())
            ->when($filters['status'] === 'inactive', fn($q) => $q->inactive())
            ->when(filled($filters['q']), function ($q) use ($filters) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
                $q->where(fn($i) => $i->where('jeneng', 'like', $t)->orWhere('kodhe', 'like', $t));
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'    => Fakultas::query()->count(),
            'aktif'    => Fakultas::query()->active()->count(),
            'nonaktif' => Fakultas::query()->inactive()->count(),
            'prodi'    => \App\Models\Pendidikan\Program\Prodi::query()->count(),
            'terhapus' => Fakultas::onlyTrashed()->count(),
        ];

        return view('superadmin.konten.pendidikan.program.fakultas.index', [
            'items'    => $items,
            'stats'    => $stats,
            'trashed'  => $trashed,
            'filtered' => filled($filters['q']) || filled($filters['status']),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.program.fakultas.form', [
            'item' => new Fakultas(['urutan' => 0, 'urip' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $this->uniqueTandha($data['tandha'] ?? null, $data['jeneng']);

        Fakultas::query()->create($data);

        return redirect()
            ->route('superadmin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas): View
    {
        return view('superadmin.konten.pendidikan.program.fakultas.form', [
            'item' => $fakultas->loadCount(['prodis', 'activeProdis']),
        ]);
    }

    public function update(Request $request, Fakultas $fakultas): RedirectResponse
    {
        $data = $this->validated($request, $fakultas->id);
        $data['tandha'] = $this->uniqueTandha($data['tandha'] ?? null, $data['jeneng'], $fakultas->id);

        $fakultas->update($data);

        return redirect()
            ->route('superadmin.fakultas.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        if ($fakultas->trashed()) {
            return back()->with('error', 'Fakultas sudah berada di sampah.');
        }

        $fakultas->delete();

        return back()->with('success', 'Fakultas dipindahkan ke sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Fakultas::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.fakultas.trashed')
            ->with('success', 'Fakultas dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Fakultas::onlyTrashed()->findOrFail($id);

        // Guard: cegah hapus permanen jika masih ada prodi aktif
        if ($item->prodis()->exists()) {
            return back()->with('error', 'Tidak bisa hapus permanen: masih ada prodi terkait. Hapus prodi dulu.');
        }

        try {
            DB::transaction(fn() => $item->forceDelete());
        } catch (\Throwable $e) {
            Log::error('Gagal force delete fakultas', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal hapus permanen. Coba lagi.');
        }

        return redirect()
            ->route('superadmin.fakultas.trashed')
            ->with('success', 'Fakultas dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $count = 0;
        $skipped = 0;

        DB::transaction(function () use (&$count, &$skipped) {
            Fakultas::onlyTrashed()->get()->each(function ($item) use (&$count, &$skipped) {
                if ($item->prodis()->exists()) {
                    $skipped++;
                    return;
                }
                $item->forceDelete();
                $count++;
            });
        });

        $msg = "{$count} fakultas dihapus permanen.";
        if ($skipped > 0) {
            $msg .= " {$skipped} dilewati karena masih punya prodi.";
        }

        return redirect()
            ->route('superadmin.fakultas.trashed')
            ->with($skipped > 0 ? 'error' : 'success', $msg);
    }

    public function toggle(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->toggleUrip();

        return back()->with(
            'success',
            $fakultas->urip ? 'Fakultas diaktifkan.' : 'Fakultas dinonaktifkan.'
        );
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'jeneng'    => ['required', 'string', 'max:150'],
            'tandha'    => ['nullable', 'string', 'max:150', 'alpha_dash', 'unique:fakultas,tandha' . ($id ? ",$id" : '')],
            'kodhe'     => ['nullable', 'string', 'max:20'],
            'katrangan' => ['nullable', 'string', 'max:5000'],
            'urutan'    => ['nullable', 'integer', 'min:0', 'max:9999'],
            'urip'      => ['nullable', 'boolean'],
        ]);

        $data['urip']   = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        return $data;
    }

    /**
     * Buat slug unik: kalau slug sudah dipakai, tambah suffix -1, -2, dst.
     */
    private function uniqueTandha(?string $tandha, string $jeneng, ?int $ignoreId = null): string
    {
        $base = Str::slug($tandha ?: $jeneng) ?: 'fakultas';
        $slug = $base;
        $i    = 1;

        while (
            Fakultas::withTrashed()
            ->where('tandha', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
