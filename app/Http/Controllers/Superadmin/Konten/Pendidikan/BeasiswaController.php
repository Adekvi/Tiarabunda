<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Beasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $items = Beasiswa::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'katrangan',
                'jangkepan',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('jeneng', 'like', $t)
                        ->orWhere('jangkepan', 'like', $t)
                        ->orWhere('katrangan', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Beasiswa::query()->count(),
            'aktif' => Beasiswa::query()->active()->count(),
            'nonaktif' => Beasiswa::query()->inactive()->count(),
            'dengan_syarat' => Beasiswa::query()
                ->whereNotNull('syarat')
                ->where('syarat', '!=', '')
                ->count(),
            'dengan_cakupan' => Beasiswa::query()
                ->whereNotNull('jangkepan')
                ->where('jangkepan', '!=', '')
                ->count(),
        ];

        return view('superadmin.konten.pendidikan.beasiswa.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.beasiswa.form', [
            'item' => new Beasiswa(['urutan' => 0, 'urip' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);

        Beasiswa::query()->create($data);

        return redirect()
            ->route('superadmin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil ditambahkan.');
    }

    public function edit(Beasiswa $beasiswa): View
    {
        return view('superadmin.konten.pendidikan.beasiswa.form', [
            'item' => $beasiswa,
        ]);
    }

    public function update(Request $request, Beasiswa $beasiswa): RedirectResponse
    {
        $data = $this->validated($request, $beasiswa->id);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        $beasiswa->update($data);

        return redirect()
            ->route('superadmin.beasiswa.index')
            ->with('success', 'Beasiswa diperbarui.');
    }

    public function destroy(Beasiswa $beasiswa): RedirectResponse
    {
        $beasiswa->delete();

        return back()->with('success', 'Beasiswa dihapus.');
    }

    public function toggle(Beasiswa $beasiswa): RedirectResponse
    {
        $beasiswa->toggleUrip();

        return back()->with(
            'success',
            $beasiswa->urip ? 'Beasiswa diaktifkan.' : 'Beasiswa dinonaktifkan.'
        );
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'jeneng' => ['required', 'string', 'max:150'],
            'tandha' => [
                'nullable',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('beasiswas', 'tandha')->ignore($id),
            ],
            'katrangan' => ['nullable', 'string'],
            'syarat' => ['nullable', 'string'],
            'jangkepan' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'jeneng.required' => 'Nama beasiswa wajib diisi.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        return $data;
    }
}
