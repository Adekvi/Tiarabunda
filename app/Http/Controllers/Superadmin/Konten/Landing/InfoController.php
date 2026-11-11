<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
{
    public function index()
    {
        $items = Info::ordered()->get();
        return view('superadmin.konten.landing.info.index', compact('items'));
    }

    public function create()
    {
        return view('superadmin.konten.landing.info.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'k' => 'required|string|max:50',
            't' => 'required|string|max:255',
            'd' => 'nullable|string',
            'tgl' => 'required|string|max:50',
            'img' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urip' => 'boolean',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('info', 'public');
        }

        Info::create($validated);

        return redirect()->route('superadmin.info.index')->with('success', 'Info berhasil ditambahkan.');
    }

    public function edit(Info $info)
    {
        return view('superadmin.konten.landing.info.form', ['item' => $info]);
    }

    public function update(Request $request, Info $info)
    {
        $validated = $request->validate([
            'k' => 'required|string|max:50',
            't' => 'required|string|max:255',
            'd' => 'nullable|string',
            'tgl' => 'required|string|max:50',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urip' => 'boolean',
        ]);

        if ($request->hasFile('img')) {
            // Hapus gambar lama
            if ($info->img && Storage::disk('public')->exists($info->img)) {
                Storage::disk('public')->delete($info->img);
            }
            $validated['img'] = $request->file('img')->store('info', 'public');
        }

        $info->update($validated);

        return redirect()->route('superadmin.info.index')->with('success', 'Info berhasil diperbarui.');
    }

    public function destroy(Info $info)
    {
        if ($info->img && Storage::disk('public')->exists($info->img)) {
            Storage::disk('public')->delete($info->img);
        }
        $info->delete();

        return redirect()->route('superadmin.info.index')->with('success', 'Info berhasil dihapus.');
    }

    public function toggle(Info $info)
    {
        $info->toggleUrip();
        return back()->with('success', 'Status publikasi berhasil diubah.');
    }
}
