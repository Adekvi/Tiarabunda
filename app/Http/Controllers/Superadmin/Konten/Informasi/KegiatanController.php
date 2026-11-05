<?php

namespace App\Http\Controllers\Superadmin\Konten\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Informasi\Kegiatan;
use App\Services\Media\ImageOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
    ) {}

    public function index(Request $request): View
    {
        // Validasi parameter URL agar query tetap terkendali.
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['all', 'active', 'inactive', 'trashed']),
            ],
            'per_page' => [
                'nullable',
                Rule::in([10, 25, 50, 100]),
            ],
        ]);

        $keyword = trim($filters['q'] ?? '');
        $status = $filters['status'] ?? 'all';
        $perPage = (int) ($filters['per_page'] ?? 10);

        // Query utama.
        $query = Kegiatan::query()
            ->select([
                'id',
                'judhul',
                'tandha',
                'katrangan',
                'papan',
                'wektu_mulai',
                'wektu_rampung',
                'path_sampul',
                'path_thumb',
                'urutan',
                'urip',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter status dan soft delete.
        if ($status === 'trashed') {
            $query->onlyTrashed();
        } else {
            $query
                ->when(
                    $status === 'active',
                    fn(Builder $q) => $q->active()
                )
                ->when(
                    $status === 'inactive',
                    fn(Builder $q) => $q->inactive()
                );
        }

        // Pencarian.
        if ($keyword !== '') {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('judhul', 'like', "%{$keyword}%")
                    ->orWhere('tandha', 'like', "%{$keyword}%")
                    ->orWhere('papan', 'like', "%{$keyword}%")
                    ->orWhere('katrangan', 'like', "%{$keyword}%");
            });
        }

        // Pengurutan dan pagination.
        $items = $query
            ->orderByDesc('wektu_mulai')
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // Statistik hanya menghitung data yang belum dihapus.
        $now = now();

        $stats = [
            'total' => Kegiatan::query()->count(),

            'aktif' => Kegiatan::query()
                ->active()
                ->count(),

            'nonaktif' => Kegiatan::query()
                ->inactive()
                ->count(),

            'mendatang' => Kegiatan::query()
                ->active()
                ->whereNotNull('wektu_mulai')
                ->where('wektu_mulai', '>', $now)
                ->count(),

            'berlangsung' => Kegiatan::query()
                ->active()
                ->whereNotNull('wektu_mulai')
                ->where('wektu_mulai', '<=', $now)
                ->where(function (Builder $q) use ($now) {
                    $q->whereNull('wektu_rampung')
                        ->orWhere('wektu_rampung', '>=', $now);
                })
                ->count(),

            'terhapus' => Kegiatan::onlyTrashed()->count(),
        ];

        // Informasi untuk partial.
        $filtered = $keyword !== '' || $status !== 'all';
        $trashed = $status === 'trashed';

        return view(
            'superadmin.konten.informasi.kegiatan.index',
            compact(
                'items',
                'stats',
                'filtered',
                'trashed',
                'status',
                'keyword',
                'perPage'
            )
        );
    }

    public function create(): View
    {
        return view('superadmin.konten.informasi.kegiatan.form', [
            'item' => new Kegiatan([
                'urutan' => 0,
                'urip' => true,
                'wektu_mulai' => now()->addDay()->setTime(8, 0),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['judhul']);

        if ($request->hasFile('path_sampul')) {
            $stored = $this->images->store($request->file('path_sampul'), 'kegiatan');
            $data['path_sampul'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        Kegiatan::query()->create($data);

        return redirect()
            ->route('superadmin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('superadmin.konten.informasi.kegiatan.form', [
            'item' => $kegiatan,
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $data = $this->validated($request, $kegiatan->id);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['judhul']);

        if ($request->hasFile('path_sampul')) {
            $this->images->delete($kegiatan->path_sampul, $kegiatan->path_thumb);
            $stored = $this->images->store($request->file('path_sampul'), 'kegiatan');
            $data['path_sampul'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        $kegiatan->update($data);

        return redirect()
            ->route('superadmin.kegiatan.index')
            ->with('success', 'Kegiatan diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return back()->with('success', 'Kegiatan dihapus.');
    }

    public function toggle(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->toggleUrip();

        return back()->with(
            'success',
            $kegiatan->urip ? 'Kegiatan diaktifkan.' : 'Kegiatan dinonaktifkan.'
        );
    }


    public function restore(int $id): RedirectResponse
    {
        $kegiatan = Kegiatan::onlyTrashed()->findOrFail($id);

        $kegiatan->restore();

        return redirect()
            ->route('superadmin.kegiatan.index', [
                'status' => 'trashed',
            ])
            ->with('success', 'Kegiatan berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $kegiatan = Kegiatan::onlyTrashed()->findOrFail($id);

        // Hapus permanen file jika memang tidak digunakan oleh data lain.
        $this->images->delete(
            $kegiatan->path_sampul,
            $kegiatan->path_thumb
        );

        $kegiatan->forceDelete();

        return redirect()
            ->route('superadmin.kegiatan.index', [
                'status' => 'trashed',
            ])
            ->with('success', 'Kegiatan dihapus secara permanen.');
    }


    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:255'],
            'tandha' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('kegiatans', 'tandha')->ignore($id),
            ],
            // 'ringkesan' dan 'isi' dihapus, diganti 'katrangan' sesuai nama kolom tabel
            'katrangan' => ['nullable', 'string'],

            'papan' => ['nullable', 'string', 'max:255'],
            'wektu_mulai' => ['nullable', 'date'],
            'wektu_rampung' => ['nullable', 'date', 'after_or_equal:wektu_mulai'],

            // Asumsi path_sampul menerima file (ditangani di luar fungsi ini)
            'path_sampul' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            // urutan adalah unsignedInteger, jadi min: 0
            'urutan' => ['nullable', 'integer', 'min:0'],
        ], [
            'judhul.required' => 'Nama kegiatan wajib diisi.',
            'wektu_rampung.after_or_equal' => 'Waktu selesai tidak boleh sebelum mulai.',
            'path_sampul.max' => 'Ukuran gambar maksimal 2 MB.',
            'path_sampul.image' => 'File harus berupa gambar.',
            'path_sampul.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
        ]);

        // Set nilai default sesuai tabel
        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        unset($data['path_sampul']);

        return $data;
    }
}
