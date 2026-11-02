<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\Pmb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PmbController extends Controller
{
    public function index(Request $request): View
    {
        $items = Pmb::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'wektu_bukak',
                'wektu_tutup',
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
                        ->orWhere('tandha', 'like', $t);
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $today = now()->startOfDay();

        // Statistik global (bukan dari halaman paginate saja)
        $stats = [
            'total' => Pmb::query()->count(),
            'aktif' => Pmb::query()->active()->count(),
            'nonaktif' => Pmb::query()->inactive()->count(),
            'dibuka' => Pmb::query()
                ->active()
                ->where(function ($q) use ($today) {
                    $q->whereNull('wektu_bukak')->orWhereDate('wektu_bukak', '<=', $today);
                })
                ->where(function ($q) use ($today) {
                    $q->whereNull('wektu_tutup')->orWhereDate('wektu_tutup', '>=', $today);
                })
                ->count(),
            'segera_tutup' => Pmb::query()
                ->active()
                ->whereNotNull('wektu_tutup')
                ->whereDate('wektu_tutup', '>=', $today)
                ->whereDate('wektu_tutup', '<=', $today->copy()->addDays(14))
                ->count(),
            'belum_buka' => Pmb::query()
                ->active()
                ->whereNotNull('wektu_bukak')
                ->whereDate('wektu_bukak', '>', $today)
                ->count(),
        ];

        return view('superadmin.konten.pendidikan.pmb.index', compact('items', 'stats'));
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.pmb.form', [
            'item' => new Pmb(['urutan' => 0, 'urip' => true, 'syarat' => [], 'langkah' => []]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        $data['syarat'] = $this->linesToArray($request->input('syarat_text'));
        $data['langkah'] = $this->linesToArray($request->input('langkah_text'));

        Pmb::query()->create($data);

        return redirect()
            ->route('superadmin.pmb.index')
            ->with('success', 'Jalur PMB berhasil ditambahkan.');
    }

    public function edit(Pmb $pmb): View
    {
        return view('superadmin.konten.pendidikan.pmb.form', ['item' => $pmb]);
    }

    public function update(Request $request, Pmb $pmb): RedirectResponse
    {
        $data = $this->validated($request, $pmb->id);
        $data['tandha'] = $data['tandha'] ?: Str::slug($data['jeneng']);
        $data['syarat'] = $this->linesToArray($request->input('syarat_text'));
        $data['langkah'] = $this->linesToArray($request->input('langkah_text'));

        $pmb->update($data);

        return redirect()
            ->route('superadmin.pmb.index')
            ->with('success', 'Jalur PMB diperbarui.');
    }

    public function destroy(Pmb $pmb): RedirectResponse
    {
        $pmb->delete();

        return back()->with('success', 'Jalur PMB dihapus.');
    }

    public function toggle(Pmb $pmb): RedirectResponse
    {
        $pmb->toggleUrip();

        return back()->with(
            'success',
            $pmb->urip ? 'Jalur PMB diaktifkan.' : 'Jalur PMB dinonaktifkan.'
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
                Rule::unique('pmbs', 'tandha')->ignore($id),
            ],
            'katrangan' => ['nullable', 'string'],
            'syarat_text' => ['nullable', 'string'],
            'langkah_text' => ['nullable', 'string'],
            'wektu_bukak' => ['nullable', 'date'],
            'wektu_tutup' => ['nullable', 'date', 'after_or_equal:wektu_bukak'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'jeneng.required' => 'Nama jalur wajib diisi.',
            'wektu_tutup.after_or_equal' => 'Tanggal tutup tidak boleh sebelum tanggal buka.',
        ]);

        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['syarat_text'], $data['langkah_text']);

        return $data;
    }

    /** @return list<string> */
    private function linesToArray(?string $text): array
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
