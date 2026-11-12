<?php

namespace App\Http\Controllers\Superadmin\Konten\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
{
    public function index(Request $request)
    {
        $trashed = $request->boolean('trashed');
        $query = $trashed ? Info::onlyTrashed() : Info::query();

        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->input('search');
            $q->where(function ($sub) use ($search) {
                $sub->where('t', 'like', "%{$search}%")
                    ->orWhere('k', 'like', "%{$search}%");
            });
        });

        $query->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
            $q->where('urip', $request->status === 'active');
        });

        $items = $query->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Info::count(),
            'active' => Info::where('urip', true)->count(),
            'draft' => Info::where('urip', false)->count(),
            'terhapus' => Info::onlyTrashed()->count(), // Untuk Banner
            'categories' => Info::select('k')->distinct()->whereNotNull('k')->count(),
        ];

        $categories = Info::select('k')
            ->selectRaw('count(*) as total')
            ->whereNotNull('k')
            ->groupBy('k')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $maxCat = $categories->max('total') ?: 1;

        return view('superadmin.konten.landing.info.index', compact('items', 'stats', 'categories', 'maxCat', 'trashed'));
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

        Cache::forget('landing.home.info');

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
            if ($info->img && Storage::disk('public')->exists($info->img)) {
                Storage::disk('public')->delete($info->img);
            }
            $validated['img'] = $request->file('img')->store('info', 'public');
        }

        $info->update($validated);

        Cache::forget('landing.home.info');

        return redirect()->route('superadmin.info.index')->with('success', 'Info berhasil diperbarui.');
    }

    public function destroy(Info $info)
    {
        $info->delete();

        Cache::forget('landing.home.info');

        return redirect()->route('superadmin.info.index')->with('success', 'Info dipindahkan ke Sampah.');
    }

    public function toggle(Info $info)
    {
        $info->toggleUrip();
        Cache::forget('landing.home.info');
        return back()->with('success', 'Status publikasi berhasil diubah.');
    }

    public function emptyTrash()
    {
        $trashed = Info::onlyTrashed()->get();
        foreach ($trashed as $item) {
            if ($item->img && Storage::disk('public')->exists($item->img)) {
                Storage::disk('public')->delete($item->img);
            }
            $item->forceDelete();
        }
        Cache::forget('landing.home.info');
        return redirect()->route('superadmin.info.index')->with('success', 'Sampah berhasil dikosongkan permanen.');
    }

    public function trashed(Request $request)
    {
        $items = Info::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('superadmin.konten.landing.info.trashed', compact('items'));
    }

    public function restore($id)
    {
        Info::onlyTrashed()->findOrFail($id)->restore();
        Cache::forget('landing.home.info');
        return back()->with('success', 'Info berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $info = Info::onlyTrashed()->findOrFail($id);
        if ($info->img && Storage::disk('public')->exists($info->img)) {
            Storage::disk('public')->delete($info->img);
        }
        $info->forceDelete();
        Cache::forget('landing.home.info');
        return back()->with('success', 'Info dihapus permanen.');
    }
}
