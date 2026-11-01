<?php

namespace App\Http\Controllers\Superadmin\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Informasi\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $items = Faq::query()
            ->select(['id', 'pitakon', 'wangsulan', 'urutan', 'urip', 'updated_at'])
            ->when(
                $request->status === 'active',
                fn($q) => $q->active()
            )
            ->when(
                $request->status === 'inactive',
                fn($q) => $q->inactive()
            )
            ->when(
                $request->filled('q'),
                function ($q) use ($request) {
                    $term = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('q')->trim()) . '%';

                    $q->where(function ($inner) use ($term) {
                        $inner->where('pitakon', 'like', $term)
                            ->orWhere('wangsulan', 'like', $term);
                    });
                }
            )
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.konten.informasi.faq.index', compact('items'));
    }

    public function create(): View
    {
        return view('superadmin.konten.informasi.faq.form', [
            'item' => new Faq([
                'urutan' => 0,
                'urip' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['urip'] = $request->boolean('urip', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        Faq::query()->create($data);

        return redirect()
            ->route('superadmin.faq.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq): View
    {
        return view('superadmin.konten.informasi.faq.form', [
            'item' => $faq,
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $this->validated($request);
        $data['urip'] = $request->boolean('urip');
        $data['urutan'] = $data['urutan'] ?? 0;

        $faq->update($data);

        return redirect()
            ->route('superadmin.faq.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    public function toggle(Faq $faq): RedirectResponse
    {
        $faq->toggleUrip();

        return back()->with(
            'success',
            $faq->urip ? 'FAQ diaktifkan.' : 'FAQ dinonaktifkan.'
        );
    }

    /**
     * Validasi terpusat — store & update sama (FAQ tidak punya unique slug).
     *
     * @return array{pitakon: string, wangsulan: string, urutan: int|null}
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'pitakon' => ['required', 'string', 'max:255'],
            'wangsulan' => ['required', 'string', 'max:5000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            // urip di-handle lewat boolean(), tidak wajib di rules
        ], [
            'pitakon.required' => 'Pertanyaan wajib diisi.',
            'wangsulan.required' => 'Jawaban wajib diisi.',
        ]);
    }
}
