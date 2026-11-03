<?php

namespace App\Http\Controllers\Superadmin\Konten\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Informasi\Berita;
use App\Services\Media\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function __construct(
        private readonly ImageOptimizer $images,
    ) {}

    public function index(Request $request): View
    {

        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $items = Berita::query()
            ->select([
                'id',
                'digawe_dening',
                'judhul',
                'tandha',
                'golongan',
                'ringkesan',
                'path_sampul',
                'path_thumb',
                'wektu_terbit',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->with(['penulis:id,name'])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('judhul', 'like', $t)
                        ->orWhere('ringkesan', 'like', $t);
                });
            })
            ->orderByDesc('wektu_terbit')
            ->orderBy('urutan')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Berita::query()->count(),
            'aktif' => Berita::query()->active()->count(),
            'nonaktif' => Berita::query()->inactive()->count(),
            'terbit' => Berita::query()->terbit()->count(),
            'draf' => Berita::query()
                ->where(function ($q) {
                    $q->whereNull('wektu_terbit')
                        ->orWhere('wektu_terbit', '>', now());
                })
                ->count(),
        ];

        // stats tetap
        return view('superadmin.konten.informasi.berita.index', compact('items', 'stats', 'perPage'));
    }

    public function create(): View
    {
        return view('superadmin.konten.informasi.berita.form', [
            'item' => new Berita([
                'urutan' => 0,
                'urip' => true,
                'wektu_terbit' => now(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['judhul']);
        $data['digawe_dening'] = $request->user()->id; // tracking pembuat

        if ($request->hasFile('path_sampul')) {
            $stored = $this->images->store($request->file('path_sampul'), 'berita');
            $data['path_sampul'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        Berita::query()->create($data);

        return redirect()
            ->route('superadmin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $beritum): View
    {
        return view('superadmin.konten.informasi.berita.form', [
            'item' => $beritum,
        ]);
    }

    public function update(Request $request, Berita $beritum): RedirectResponse
    {
        $data = $this->validated($request, $beritum->id);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['judhul']);
        // digawe_dening tidak diubah saat edit (tetap penulis asli)
        // Jika ingin catat editor terakhir, buat kolom terpisah — jangan timpa pembuat.

        if ($request->hasFile('path_sampul')) {
            $this->images->delete($beritum->path_sampul, $beritum->path_thumb);
            $stored = $this->images->store($request->file('path_sampul'), 'berita');
            $data['path_sampul'] = $stored['path'];
            $data['path_thumb'] = $stored['thumb'];
        }

        $beritum->update($data);

        return redirect()
            ->route('superadmin.berita.index')
            ->with('success', 'Berita diperbarui.');
    }

    public function destroy(Berita $beritum): RedirectResponse
    {
        $beritum->delete();

        return back()->with('success', 'Berita dihapus.');
    }

    public function toggle(Berita $beritum): RedirectResponse
    {
        $beritum->toggleUrip();

        return back()->with(
            'success',
            $beritum->urip ? 'Berita diaktifkan.' : 'Berita dinonaktifkan.'
        );
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $golonganKeys = array_keys(Berita::GOLONGAN);

        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:255'],
            'tandha' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('beritas', 'tandha')->ignore($id),
            ],
            'golongan' => ['nullable', 'string', 'max:50', Rule::in($golonganKeys)],
            'ringkesan' => ['nullable', 'string', 'max:300'], // = DB
            'isi' => ['nullable', 'string'],
            'wektu_terbit' => ['nullable', 'date'],
            'path_sampul' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'judhul.required' => 'Judul berita wajib diisi.',
            'ringkesan.max' => 'Ringkasan maksimal 300 karakter.',
            'golongan.in' => 'Kategori tidak valid.',
            'path_sampul.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['path_sampul']);

        return $data;
    }
}
