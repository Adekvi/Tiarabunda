<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan\Program;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FakultasController extends Controller
{
    public function index(Request $request): View
    {
        $items = Fakultas::query()
            ->select(['id', 'jeneng', 'tandha', 'kodhe', 'katrangan', 'urutan', 'urip', 'updated_at'])
            ->withCount(['prodis', 'activeProdis'])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(fn($i) => $i->where('jeneng', 'like', $t)->orWhere('kodhe', 'like', $t));
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('superadmin.konten.pendidikan.program.fakultas.index', compact('items'));
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
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        Fakultas::query()->create($data);

        return redirect()->route('superadmin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas): View
    {
        return view('superadmin.konten.pendidikan.fakultas.form', ['item' => $fakultas]);
    }

    public function update(Request $request, Fakultas $fakultas): RedirectResponse
    {
        $data = $this->validated($request, $fakultas->id);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        $fakultas->update($data);

        return redirect()->route('superadmin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->delete();
        return back()->with('success', 'Fakultas dihapus.');
    }

    public function toggle(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->toggleUrip();
        return back()->with('success', $fakultas->urip ? 'Fakultas diaktifkan.' : 'Fakultas dinonaktifkan.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'jeneng' => ['required', 'string', 'max:150'],
            'tandha' => ['nullable', 'string', 'max:150', 'alpha_dash', 'unique:fakultas,tandha' . ($id ? ",$id" : '')],
            'kodhe' => ['nullable', 'string', 'max:20'],
            'katrangan' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }
}
