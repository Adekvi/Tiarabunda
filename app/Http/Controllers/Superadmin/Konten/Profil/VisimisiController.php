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
        $items = Visimisi::query()
            ->select(['id', 'bagean', 'judhul', 'isi', 'dhaptar', 'urutan', 'urip', 'updated_at'])
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
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.konten.profil.visimisi.visimisi', compact('items'));
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

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function toggle(Visimisi $visimisi): RedirectResponse
    {
        $visimisi->toggleUrip();

        return back()->with(
            'success',
            $visimisi->urip ? 'Data diaktifkan.' : 'Data dinonaktifkan.'
        );
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
