<?php

namespace App\Http\Controllers\Superadmin\Konten\Profil;

use App\Http\Controllers\Controller;
use App\Models\Profil\Sejarah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SejarahController extends Controller
{
    public function index(Request $request): View
    {
        $items = Sejarah::query()
            ->select(['id', 'judhul', 'isi', 'path_sampul', 'urutan', 'urip', 'updated_at'])
            ->when($request->status === 'active', fn($q) => $q->active())
            ->when($request->status === 'inactive', fn($q) => $q->inactive())
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('judhul', 'like', $term)
                        ->orWhere('isi', 'like', $term);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('superadmin.konten.profil.sejarah.sejarah', compact('items'));
    }

    public function create(): View
    {
        return view('superadmin.konten.profil.sejarah.form', [
            'item' => new Sejarah(['urutan' => 0, 'urip' => true, 'kronologi' => []]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['dianyari_dening'] = $request->user()->id;
        $data['kronologi'] = $this->parseKronologi($request->input('kronologi_text'));
        $data['path_sampul'] = $this->storeCover($request) ?? null;

        Sejarah::query()->create($data);

        return redirect()
            ->route('superadmin.sejarah.index')
            ->with('success', 'Sejarah berhasil ditambahkan.');
    }

    public function edit(Sejarah $sejarah): View
    {
        return view('superadmin.konten.profil.sejarah.form', [
            'item' => $sejarah,
        ]);
    }

    public function update(Request $request, Sejarah $sejarah): RedirectResponse
    {
        $data = $this->validated($request);
        $data['dianyari_dening'] = $request->user()->id;
        $data['kronologi'] = $this->parseKronologi($request->input('kronologi_text'));

        if ($path = $this->storeCover($request)) {
            if ($sejarah->path_sampul) {
                Storage::disk('public')->delete($sejarah->path_sampul);
            }
            $data['path_sampul'] = $path;
        }

        $sejarah->update($data);

        return redirect()
            ->route('superadmin.sejarah.index')
            ->with('success', 'Sejarah berhasil diperbarui.');
    }

    public function destroy(Sejarah $sejarah): RedirectResponse
    {
        $sejarah->delete();

        return back()->with('success', 'Sejarah berhasil dihapus.');
    }

    public function toggle(Sejarah $sejarah): RedirectResponse
    {
        $sejarah->toggleUrip();

        return back()->with(
            'success',
            $sejarah->urip ? 'Sejarah diaktifkan.' : 'Sejarah dinonaktifkan.'
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'judhul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'path_sampul' => ['nullable', 'image', 'max:2048'],
            'kronologi_text' => ['nullable', 'string'],
        ], [
            'judhul.required' => 'Judul wajib diisi.',
            'isi.required' => 'Isi sejarah wajib diisi.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['path_sampul'], $data['kronologi_text']);

        return $data;
    }

    /** Format baris: Tahun | Judul | Deskripsi */
    private function parseKronologi(?string $text): array
    {
        if (! filled($text)) {
            return [];
        }

        return collect(preg_split("/\r\n|\n|\r/", $text))
            ->map(fn($line) => trim($line))
            ->filter()
            ->map(function (string $line) {
                $parts = array_map('trim', explode('|', $line, 3));

                return [
                    'tahun' => $parts[0] ?? '',
                    'judul' => $parts[1] ?? '',
                    'deskripsi' => $parts[2] ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function storeCover(Request $request): ?string
    {
        if (! $request->hasFile('path_sampul')) {
            return null;
        }

        return $request->file('path_sampul')->store('sejarah', 'public');
    }
}
