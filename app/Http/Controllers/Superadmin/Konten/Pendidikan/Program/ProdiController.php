<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan\Program;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdiController extends Controller
{
    public function index(Request $request): View
    {
        $items = Prodi::query()
            ->select(['id', 'fakultas_id', 'jeneng', 'tandha', 'undhak', 'akred', 'urutan', 'urip', 'updated_at'])
            ->with(['fakultas:id,jeneng'])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('fakultas_id'), fn($q) => $q->where('fakultas_id', $request->fakultas_id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where('jeneng', 'like', $t);
            })
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        $fakultasList = Fakultas::query()->active()->ordered()->get(['id', 'jeneng']);

        return view('superadmin.konten.pendidikan.program.prodi.index', compact('items', 'fakultasList'));
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
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        Prodi::query()->create($data);

        return redirect()->route('superadmin.prodi.index')
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
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        $prodi->update($data);

        return redirect()->route('superadmin.prodi.index')
            ->with('success', 'Program studi diperbarui.');
    }

    public function destroy(Prodi $prodi): RedirectResponse
    {
        $prodi->delete();

        return back()->with('success', 'Program studi dihapus.');
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
            'jeneng' => ['required', 'string', 'max:150'],
            'tandha' => [
                'nullable',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('prodis', 'tandha')->ignore($id),
            ],
            'undhak' => ['required', Rule::in(['D3', 'D4', 'S1'])],
            'katrangan' => ['nullable', 'string'],
            'akred' => ['nullable', 'string', 'max:20'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        return $data;
    }
}
