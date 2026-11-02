<?php

namespace App\Http\Controllers\Superadmin\Konten\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Informasi\Kegiatan;
use App\Services\Media\ImageOptimizer;
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
        $items = Kegiatan::query()
            ->select([
                'id',
                'judhul',
                'ringkesan',
                'lokasi',
                'wektu_mulai',
                'wektu_selesai',
                'path_sampul',
                'path_thumb',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('judhul', 'like', $t)
                        ->orWhere('lokasi', 'like', $t)
                        ->orWhere('ringkesan', 'like', $t);
                });
            })
            ->orderByDesc('wektu_mulai')
            ->orderBy('urutan')
            ->paginate(12)
            ->withQueryString();

        $now = now();
        $stats = [
            'total' => Kegiatan::query()->count(),
            'aktif' => Kegiatan::query()->active()->count(),
            'nonaktif' => Kegiatan::query()->inactive()->count(),
            'mendatang' => Kegiatan::query()
                ->active()
                ->whereNotNull('wektu_mulai')
                ->where('wektu_mulai', '>=', $now)
                ->count(),
            'berlangsung' => Kegiatan::query()
                ->active()
                ->whereNotNull('wektu_mulai')
                ->where('wektu_mulai', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->whereNull('wektu_rampung')
                        ->orWhere('wektu_rampung', '>=', $now);
                })
                ->count(),
        ];

        return view('superadmin.konten.informasi.kegiatan.index', compact('items', 'stats'));
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

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:200'],
            'tandha' => [
                'nullable',
                'string',
                'max:200',
                'alpha_dash',
                Rule::unique('kegiatans', 'tandha')->ignore($id),
            ],
            'ringkesan' => ['nullable', 'string', 'max:500'],
            'isi' => ['nullable', 'string'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'wektu_mulai' => ['nullable', 'date'],
            'wektu_rampung' => ['nullable', 'date', 'after_or_equal:wektu_mulai'],
            'path_sampul' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'judhul.required' => 'Nama kegiatan wajib diisi.',
            'wektu_rampung.after_or_equal' => 'Waktu selesai tidak boleh sebelum mulai.',
            'path_sampul.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['path_sampul']);

        return $data;
    }
}
