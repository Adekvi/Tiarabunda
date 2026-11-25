<?php

namespace App\Services\Superadmin\Layanan;

use App\Models\Layanan\Akademik;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AkademikService
{
    private const STORAGE_DIR = 'dokumen/akademik';

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search   = trim((string) ($filters['q'] ?? ''));
        $kategori = trim((string) ($filters['kategori'] ?? ''));
        $status   = $filters['status'] ?? '';

        return Akademik::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'kategori',
                'katrangan',
                'path_file',
                'nama_file_asli',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('jeneng', 'like', "\%{$search}%")
                        ->orWhere('tandha', 'like', "%{$search}%")
                        ->orWhere('katrangan', 'like', "%{$search}%");
                });
            })
            ->when($kategori !== '', fn($q) => $q->where('kategori', $kategori))
            ->when($status !== '' && in_array($status, ['0', '1'], true), fn($q) => $q->where('urip', (bool)$status))
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Seluruh metrik utama dalam 1 kali query agregat.
     */
    public function getStats(): array
    {
        $row = Akademik::withTrashed()
            ->selectRaw('
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) AS total,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 1 THEN 1 END) AS aktif,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 0 THEN 1 END) AS nonaktif,
                COUNT(DISTINCT CASE WHEN deleted_at IS NULL THEN kategori END) AS total_kategori,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) AS terhapus
            ')
            ->first();

        return [
            'total'          => (int) ($row->total ?? 0),
            'aktif'          => (int) ($row->aktif ?? 0),
            'nonaktif'       => (int) ($row->nonaktif ?? 0),
            'total_kategori' => (int) ($row->total_kategori ?? 0),
            'terhapus'       => (int) ($row->terhapus ?? 0),
        ];
    }

    /**
     * Jumlah dokumen aktif per kategori untuk ringkasan sidebar (1 query GROUP BY).
     */
    public function getCategoryCounts(): array
    {
        return Akademik::query()
            ->selectRaw('COALESCE(NULLIF(kategori, ""), "Umum") AS nama_kategori, COUNT(*) AS jumlah')
            ->groupBy('nama_kategori')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'nama_kategori')
            ->all();
    }

    public function getCategories(): array
    {
        $categories = Akademik::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values()
            ->all();

        return array_values(array_unique(array_merge(
            ['Umum', 'Administrasi', 'KRS & Nilai', 'Tugas Akhir', 'Wisuda'],
            $categories
        )));
    }

    public function resolveUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug) ?: 'dokumen-' . Str::lower(Str::random(6));

        $existing = Akademik::withTrashed()
            ->where('tandha', 'like', $slug . '%')
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->pluck('tandha')
            ->flip();

        if (! $existing->has($slug)) {
            return $slug;
        }

        $counter = 2;
        while ($existing->has("{$slug}-{$counter}")) {
            $counter++;
        }

        return "{$slug}-{$counter}";
    }

    public function create(array $data, UploadedFile $file): Akademik
    {
        $storedPath = $file->store(self::STORAGE_DIR, 'public');

        try {
            return DB::transaction(function () use ($data, $file, $storedPath) {
                return Akademik::create([
                    'jeneng'         => $data['jeneng'],
                    'tandha'         => $this->resolveUniqueSlug($data['tandha']),
                    'kategori'       => $data['kategori'],
                    'katrangan'      => $data['katrangan'] ?? null,
                    'path_file'      => $storedPath,
                    'nama_file_asli' => $file->getClientOriginalName(),
                    'urutan'         => $data['urutan'],
                    'urip'           => $data['urip'],
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($storedPath);
            throw $e;
        }
    }

    public function update(Akademik $akademik, array $data, ?UploadedFile $file = null): Akademik
    {
        $oldPath = $akademik->path_file;
        $newPath = $file ? $file->store(self::STORAGE_DIR, 'public') : null;

        try {
            DB::transaction(function () use ($akademik, $data, $file, $newPath) {
                $payload = [
                    'jeneng'    => $data['jeneng'],
                    'tandha'    => $this->resolveUniqueSlug($data['tandha'], $akademik->id),
                    'kategori'  => $data['kategori'],
                    'katrangan' => $data['katrangan'] ?? null,
                    'urutan'    => $data['urutan'],
                    'urip'      => $data['urip'],
                ];

                if ($newPath && $file) {
                    $payload['path_file']      = $newPath;
                    $payload['nama_file_asli'] = $file->getClientOriginalName();
                }

                $akademik->update($payload);
            });
        } catch (\Throwable $e) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $e;
        }

        if ($newPath && $oldPath && $oldPath !== $newPath) {
            $this->deleteStoredFile($oldPath);
        }

        return $akademik;
    }

    public function getTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Akademik::onlyTrashed()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'kategori',
                'katrangan',
                'path_file',
                'nama_file_asli',
                'deleted_at',
            ])
            ->orderByDesc('deleted_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function forceDelete(int $id): void
    {
        $item = Akademik::onlyTrashed()->findOrFail($id);
        $path = $item->path_file;

        $item->forceDelete();

        if ($path) {
            $this->deleteStoredFile($path);
        }
    }

    public function emptyTrash(): int
    {
        $items = Akademik::onlyTrashed()
            ->select(['id', 'path_file'])
            ->get();

        if ($items->isEmpty()) {
            return 0;
        }

        $count = $items->count();

        DB::transaction(function () {
            Akademik::onlyTrashed()->forceDelete();
        });

        foreach ($items as $item) {
            if ($item->path_file) {
                $this->deleteStoredFile($item->path_file);
            }
        }

        return $count;
    }

    private function deleteStoredFile(string $path): void
    {
        $relative = ltrim($path, '/');
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
