<?php

namespace App\Http\Controllers\Superadmin\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Informasi\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $items = Faq::query()
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($request->q, fn($q, $s) => $q->where('question', 'like', "%{$request->q}%"))
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.faq.index', compact('items'));
    }

    public function create()
    {
        return view('superadmin.faq.form', ['item' => new Faq()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => ['required', 'string', 'max:255'],
            'answer'     => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Faq::create($data);

        return redirect()
            ->route('superadmin.faq.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('superadmin.faq.form', ['item' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => ['required', 'string', 'max:255'],
            'answer'     => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $faq->update($data);

        return redirect()
            ->route('superadmin.faq.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ dihapus.');
    }

    public function toggle(Faq $faq)
    {
        $faq->update(['is_active' => ! $faq->is_active]);

        return back()->with('success', 'Status FAQ diperbarui.');
    }
}
