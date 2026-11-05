<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan\Program;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdiController extends Controller
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
            'q'           => $request->string('q')->trim()->toString(),
            'status'      => $request->input('status'),
            'fakultas_id' => $request->input('fakultas_id'),
        ];

        $items = Prodi::query()
            ->select([
                'id',
                'fakultas_id',
                'jeneng',
                'tandha',
                'undhak',
                'akred',
                'urutan',
                'urip',
                'updated_at',
                'deleted_at',
            ])
            ->with(['fakultas:id,jeneng,kodhe'])
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->when(! $trashed, fn($q) => $q->whereNull('deleted_at'))
            ->when($filters['status'] === 'active', fn($q) => $q->active())
            ->when($filters['status'] === 'inactive', fn($q) => $q->inactive())
            ->when(filled($filters['fakultas_id']), fn($q) => $q->where('fakultas_id', $filters['fakultas_id']))
            ->when(filled($filters['q']), function ($q) use ($filters) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
                $q->where(fn($i) => $i
                    ->where('jeneng', 'like', $t)
                    ->orWhere('tandha', 'like', $t)
                    ->orWhere('undhak', 'like', $t));
            })
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $fakultasList = Fakultas::query()
            ->when(! $trashed, fn($q) => $q->active())
            ->ordered()
            ->get(['id', 'jeneng']);

        $stats = [
            'total'    => Prodi::query()->count(),
            'aktif'    => Prodi::query()->active()->count(),
            'nonaktif' => Prodi::query()->inactive()->count(),
            'fakultas' => Fakultas::query()->active()->count(),
            'terhapus' => Prodi::onlyTrashed()->count(),
        ];

        return view('superadmin.konten.pendidikan.program.prodi.index', [
            'items'         => $items,
            'fakultasList'  => $fakultasList,
            'stats'         => $stats,
            'trashed'       => $trashed,
            'filtered'      => filled($filters['q'])
                || filled($filters['status'])
                || filled($filters['fakultas_id']),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.program.prodi.form', [
            'item' => new Prodi(['urutan' => 0, 'urip' => true, 'undhak' => 'D3']),
            'fakultasList' => Fakultas::query()->active()->ordered()->get(['id', 'jeneng']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $this->uniqueTandha($data['tandha'] ?? null, $data['jeneng']);

        Prodi::query()->create($data);

        return redirect()
            ->route('superadmin.prodi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi): View
    {
        return view('superadmin.konten.pendidikan.program.prodi.form', [
            'item' => $prodi,
            'fakultasList' => Fakultas::query()->ordered()->get(['id', 'jeneng']),
        ]);
    }

    public function update(Request $request, Prodi $prodi): RedirectResponse
    {
        $data = $this->validated($request, $prodi->id);
        $data['tandha'] = $this->uniqueTandha($data['tandha'] ?? null, $data['jeneng'], $prodi->id);

        $prodi->update($data);

        return redirect()
            ->route('superadmin.prodi.index')
            ->with('success', 'Program studi diperbarui.');
    }

    public function destroy(Prodi $prodi): RedirectResponse
    {
        if ($prodi->trashed()) {
            return back()->with('error', 'Program studi sudah berada di sampah.');
        }

        $prodi->delete();

        return back()->with('success', 'Program studi dipindahkan ke sampah.');
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Prodi::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('superadmin.prodi.trashed')
            ->with('success', 'Program studi dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Prodi::onlyTrashed()->findOrFail($id);

        try {
            DB::transaction(fn() => $item->forceDelete());
        } catch (\Throwable $e) {
            Log::error('Gagal force delete prodi', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Gagal hapus permanen. Coba lagi.');
        }

        return redirect()
            ->route('superadmin.prodi.trashed')
            ->with('success', 'Program studi dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $count = 0;

        DB::transaction(function () use (&$count) {
            Prodi::onlyTrashed()->get()->each(function (Prodi $item) use (&$count) {
                $item->forceDelete();
                $count++;
            });
        });

        return redirect()
            ->route('superadmin.prodi.trashed')
            ->with('success', "{$count} program studi dihapus permanen.");
    }

    public function toggle(Prodi $prodi): RedirectResponse
    {
        $prodi->toggleUrip();

        return back()->with(
            'success',
            $prodi->urip ? 'Program studi diaktifkan.' : 'Program studi dinonaktifkan.'
        );
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'jeneng'      => ['required', 'string', 'max:150'],
            'tandha'      => [
                'nullable',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('prodis', 'tandha')->ignore($id),
            ],
            'undhak'      => ['required', Rule::in(['D3', 'D4', 'S1'])],
            'katrangan'   => ['nullable', 'string', 'max:5000'],
            'akred'       => ['nullable', 'string', 'max:20'],
            'urutan'      => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['urip']   = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        return $data;
    }

    private function uniqueTandha(?string $tandha, string $jeneng, ?int $ignoreId = null): string
    {
        $base = Str::slug($tandha ?: $jeneng) ?: 'prodi';
        $slug = $base;
        $i = 1;

        while (
            Prodi::withTrashed()
            ->where('tandha', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
