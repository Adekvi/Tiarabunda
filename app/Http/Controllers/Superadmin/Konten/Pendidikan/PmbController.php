<?php

namespace App\Http\Controllers\Superadmin\Konten\Pendidikan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\Landing\Pendidikan\PmbRequest;
use App\Models\Pendidikan\Pmb;
use App\Services\Landing\LandingHomeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $stats = [
            'total'        => Pmb::query()->count(),
            'aktif'        => Pmb::query()->active()->count(),
            'nonaktif'     => Pmb::query()->inactive()->count(),
            'terhapus'     => Pmb::onlyTrashed()->count(),
            'dibuka'       => Pmb::query()
                ->active()
                ->where(fn($q) => $q->whereNull('wektu_bukak')->orWhereDate('wektu_bukak', '<=', $today))
                ->where(fn($q) => $q->whereNull('wektu_tutup')->orWhereDate('wektu_tutup', '>=', $today))
                ->count(),
            'segera_tutup' => Pmb::query()
                ->active()
                ->whereNotNull('wektu_tutup')
                ->whereDate('wektu_tutup', '>=', $today)
                ->whereDate('wektu_tutup', '<=', $today->copy()->addDays(14))
                ->count(),
            'belum_buka'   => Pmb::query()
                ->active()
                ->whereNotNull('wektu_bukak')
                ->whereDate('wektu_bukak', '>', $today)
                ->count(),
        ];

        return view('superadmin.konten.pendidikan.pmb.index', [
            'items' => $items,
            'stats' => $stats,
            'trashed' => false,
        ]);
    }

    public function trashed(Request $request): View
    {
        $items = Pmb::onlyTrashed()
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';
                $q->where(function ($inner) use ($t) {
                    $inner->where('jeneng', 'like', $t)
                        ->orWhere('tandha', 'like', $t);
                });
            })
            ->orderByDesc('deleted_at')
            ->paginate(12)
            ->withQueryString();

        return view('superadmin.konten.pendidikan.pmb.index', [
            'items' => $items,
            'stats' => null,
            'trashed' => true,
        ]);
    }

    public function create(): View
    {
        return view('superadmin.konten.pendidikan.pmb.form', [
            'item' => new Pmb([
                'urutan'  => 0,
                'urip'    => true,
                'syarat'  => [],
                'langkah' => [],
            ]),
        ]);
    }

    public function store(PmbRequest $request, LandingHomeService $homeService): RedirectResponse
    {
        $data = $this->prepareData($request->validated());
        Pmb::query()->create($data);

        $homeService->forgetJalurPmb();

        return redirect()->route('superadmin.pmb.index')->with('success', 'Jalur PMB berhasil ditambahkan.');
    }

    public function edit(Pmb $pmb): View
    {
        return view('superadmin.konten.pendidikan.pmb.form', ['item' => $pmb]);
    }

    public function update(PmbRequest $request, Pmb $pmb, LandingHomeService $homeService): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), $pmb->id);
        $pmb->update($data);

        $homeService->forgetJalurPmb();

        return redirect()->route('superadmin.pmb.index')->with('success', 'Jalur PMB diperbarui.');
    }

    public function destroy(Pmb $pmb, LandingHomeService $homeService): RedirectResponse
    {
        $pmb->delete();
        $homeService->forgetJalurPmb();

        return back()->with('success', 'Jalur PMB dipindahkan ke sampah.');
    }

    public function restore(int $id, LandingHomeService $homeService): RedirectResponse
    {
        $pmb = Pmb::onlyTrashed()->findOrFail($id);
        $pmb->restore();
        $homeService->forgetJalurPmb();

        return back()->with('success', 'Jalur PMB berhasil dipulihkan.');
    }

    public function forceDelete(int $id, LandingHomeService $homeService): RedirectResponse
    {
        $pmb = Pmb::onlyTrashed()->findOrFail($id);
        $pmb->forceDelete();
        $homeService->forgetJalurPmb();

        return back()->with('success', 'Jalur PMB dihapus permanen.');
    }

    public function emptyTrash(LandingHomeService $homeService): RedirectResponse
    {
        Pmb::onlyTrashed()->forceDelete();
        $homeService->forgetJalurPmb();

        return back()->with('success', 'Semua sampah berhasil dikosongkan.');
    }

    public function toggle(Pmb $pmb, LandingHomeService $homeService): RedirectResponse
    {
        $pmb->toggleUrip();
        $homeService->forgetJalurPmb();

        return back()->with(
            'success',
            $pmb->urip ? 'Jalur PMB diaktifkan.' : 'Jalur PMB dinonaktifkan.'
        );
    }

    /**
     * Normalisasi data dari FormRequest menjadi atribut model.
     */
    private function prepareData(array $data, ?int $id = null): array
    {
        $data['tandha']    = filled($data['tandha'] ?? null)
            ? $data['tandha']
            : Str::slug($data['jeneng']);

        // Konversi HTML editor → array baris untuk kolom JSON.
        $data['syarat']    = $this->htmlToList($data['syarat_text']  ?? '');
        $data['langkah']   = $this->htmlToList($data['langkah_text'] ?? '');

        // katrangan disimpan sebagai HTML utuh (sudah tersanitasi di FormRequest).
        $data['katrangan'] = $this->cleanRichText($data['katrangan'] ?? '');

        $data['urip']   = (bool) ($data['urip'] ?? true);
        $data['urutan'] = (int) ($data['urutan'] ?? 0);

        unset($data['syarat_text'], $data['langkah_text']);

        return $data;
    }

    /**
     * Ambil teks poin-poin dari HTML editor.
     * Prioritas: <li>…</li> → kalau tidak ada, pecah per <p>/<br>.
     *
     * @return list<string>
     */
    private function htmlToList(?string $html): array
    {
        $html = trim((string) $html);
        if ($html === '') {
            return [];
        }

        // Jika ada <li>, ambil isinya
        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $m)) {
            return collect($m[1])
                ->map(fn($s) => $this->plainText($s))
                ->filter()
                ->values()
                ->all();
        }

        // Fallback: pecah per </p> atau <br>
        $text = preg_split('/<\/p>|<br\s*\/?>/i', $html);
        return collect($text)
            ->map(fn($s) => $this->plainText($s))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * HTML → teks biasa 1 baris (decode entity + rapikan spasi).
     */
    private function plainText(string $html): string
    {
        $t = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $t = preg_replace('/\s+/u', ' ', $t) ?? '';
        return trim($t);
    }

    /**
     * Bersihkan HTML `katrangan` sebelum disimpan (defense-in-depth).
     */
    private function cleanRichText(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $allowed = '<p><br><strong><b><em><i><u><s><strike><ul><ol><li><a><h2><h3><blockquote>';
        $html = strip_tags($html, $allowed);

        // hapus atribut on* & style
        $prev = null;
        while ($prev !== $html) {
            $prev = $html;
            $html = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/\s+(href|src)\s*=\s*(["\']?)\s*javascript:[^"\'>\s]*\2/i', '', $html);
        }

        return $html;
    }
}
