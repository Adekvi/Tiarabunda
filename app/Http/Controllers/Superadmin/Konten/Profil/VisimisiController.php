<?php

namespace App\Http\Controllers\Superadmin\Konten\Profil;

use App\Http\Controllers\Controller;
use App\Models\Profil\Visimisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisimisiController extends Controller
{
    public function index(Request $request): View
    {
        $trashed = false;

        $items = Visimisi::query()
            ->select([
                'id',
                'bagean',
                'judhul',
                'isi',
                'dhaptar',
                'urutan',
                'urip',
                'updated_at'
            ])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->bagean, fn($q) => $q->where('bagean', $request->bagean))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('judhul', 'like', $term)
                        ->orWhere('isi', 'like', $term);
                });
            })
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $stats = $this->buildStats();

        return view('superadmin.konten.profil.visimisi.visimisi', compact('items', 'stats', 'trashed'));
    }

    public function trashed(Request $request): View
    {
        $trashed = true;

        $items = Visimisi::onlyTrashed()
            ->select([
                'id',
                'bagean',
                'judhul',
                'isi',
                'dhaptar',
                'urutan',
                'urip',
                'deleted_at',
                'updated_at'
            ])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('judhul', 'like', $term)
                        ->orWhere('isi', 'like', $term);
                });
            })
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $stats = $this->buildStats();

        return view('superadmin.konten.profil.visimisi.visimisi', compact('items', 'stats', 'trashed'));
    }

    public function create(): View
    {
        return view('superadmin.konten.profil.visimisi.form', [
            'item' => new Visimisi([
                'bagean' => 'visi',
                'urutan' => 0,
                'urip' => true,
                'dhaptar' => [],
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['dianyari_dening'] = $request->user()->id;
        $data['dhaptar'] = $this->parseDhaptar($request->input('dhaptar_text'));

        Visimisi::query()->create($data);

        return redirect()
            ->route('superadmin.visimisi.index')
            ->with('success', 'Visi/Misi berhasil ditambahkan.');
    }

    public function edit(Visimisi $visimisi): View
    {
        $visimisi->load('penganyar');

        return view('superadmin.konten.profil.visimisi.form', [
            'item' => $visimisi,
        ]);
    }

    public function update(Request $request, Visimisi $visimisi): RedirectResponse
    {
        $data = $this->validated($request);
        $data['dianyari_dening'] = $request->user()->id;
        $data['dhaptar'] = $this->parseDhaptar($request->input('dhaptar_text'));

        $visimisi->update($data);

        return redirect()
            ->route('superadmin.visimisi.index')
            ->with('success', 'Visi/Misi berhasil diperbarui.');
    }

    public function destroy(Visimisi $visimisi): RedirectResponse
    {
        $visimisi->delete();

        return back()->with('success', 'Data berhasil dipindahkan ke sampah.');
    }

    public function toggle(Visimisi $visimisi): RedirectResponse
    {
        $visimisi->toggleUrip();

        return back()->with(
            'success',
            $visimisi->urip ? 'Data diaktifkan.' : 'Data dinonaktifkan.'
        );
    }

    public function restore(int $id): RedirectResponse
    {
        $item = Visimisi::onlyTrashed()->findOrFail($id);
        $item->restore();

        return back()->with('success', 'Data berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $item = Visimisi::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return back()->with('success', 'Data berhasil dihapus permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        Visimisi::onlyTrashed()->forceDelete();

        return back()->with('success', 'Sampah berhasil dikosongkan.');
    }

    private function buildStats(): array
    {
        $total = Visimisi::count();
        $aktif = Visimisi::where('urip', true)->count();
        $visi  = Visimisi::where('bagean', 'visi')->count();
        $misi  = Visimisi::where('bagean', 'misi')->count();

        return [
            'total'    => $total,
            'aktif'    => $aktif,
            'nonaktif' => $total - $aktif,
            'visi'     => $visi,
            'misi'     => $misi,
            'terhapus' => Visimisi::onlyTrashed()->count(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'bagean' => ['required', Rule::in(['visi', 'misi'])],
            'judhul' => ['required', 'string', 'max:255'],
            'isi' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'dhaptar_text' => ['nullable', 'string'],
        ], [
            'bagean.required' => 'Pilih bagian Visi atau Misi.',
            'judhul.required' => 'Judul wajib diisi.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['dhaptar_text']);

        return $data;
    }

    /** Satu baris = satu poin misi */
    private function parseDhaptar(?string $text): array
    {
        if (! filled($text)) {
            return [];
        }

        return collect(preg_split("/\r\n|\n|\r/", $text))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
