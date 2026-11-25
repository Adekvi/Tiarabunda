<?php

namespace App\Services\Superadmin\Lembaga;

use App\Models\Lembaga\Lpm;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LpmService
{
    private const FILE_DIR = 'dokumen/lpm';

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search   = trim((string) ($filters['q'] ?? ''));
        $kategori = trim((string) ($filters['kategori'] ?? ''));
        $status   = $filters['status'] ?? '';

        return Lpm::query()
            ->select([
                'id',
                'judhul',
                'tandha',
                'kategori',
                'data_tambahan',
                'path_file',
                'nama_file_asli',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->selectRaw('LEFT(isi, 180) AS ringkasan')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judhul', 'like', "%{$search}%")
                        ->orWhere('tandha', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->when($kategori !== '' && array_key_exists($kategori, Lpm::KATEGORI), fn($q) => $q->where('kategori', $kategori))
            ->when($status !== '' && in_array($status, ['0', '1'], true), fn($q) => $q->where('urip', (bool) $status))
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Seluruh metrik statistik dalam 1 kali query agregat.
     */
    public function getStats(): array
    {
        $row = Lpm::withTrashed()
            ->selectRaw("
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) AS total,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 1 THEN 1 END) AS aktif,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 0 THEN 1 END) AS nonaktif,
                COUNT(CASE WHEN deleted_at IS NULL AND kategori = 'spmi' THEN 1 END) AS spmi,
                COUNT(CASE WHEN deleted_at IS NULL AND kategori = 'akreditasi' THEN 1 END) AS akreditasi,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) AS terhapus
            ")
            ->first();

        return [
            'total'      => (int) ($row->total ?? 0),
            'aktif'      => (int) ($row->aktif ?? 0),
            'nonaktif'   => (int) ($row->nonaktif ?? 0),
            'spmi'       => (int) ($row->spmi ?? 0),
            'akreditasi' => (int) ($row->akreditasi ?? 0),
            'terhapus'   => (int) ($row->terhapus ?? 0),
        ];
    }

    /**
     * Distribusi jumlah item per kategori untuk sidebar (1 query GROUP BY).
     */
    public function getCategoryCounts(): array
    {
        $counts = Lpm::query()
            ->selectRaw('kategori, COUNT(*) AS jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->all();

        $result = [];
        foreach (Lpm::KATEGORI as $key => $label) {
            $result[$key] = [
                'label'  => $label,
                'jumlah' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $result;
    }

    public function resolveUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug) ?: 'lpm-' . Str::lower(Str::random(6));

        $existing = Lpm::withTrashed()
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

    public function create(array $data, ?UploadedFile $file = null): Lpm
    {
        $storedFile = $file ? $file->store(self::FILE_DIR, 'public') : null;

        try {
            return DB::transaction(function () use ($data, $file, $storedFile) {
                return Lpm::create([
                    'judhul'         => $data['judhul'],
                    'tandha'         => $this->resolveUniqueSlug($data['tandha']),
                    'kategori'       => $data['kategori'],
                    'isi'            => $data['isi'] ?? null,
                    'data_tambahan'  => $data['data_tambahan'] ?? null,
                    'path_file'      => $storedFile,
                    'nama_file_asli' => $file?->getClientOriginalName(),
                    'urutan'         => $data['urutan'],
                    'urip'           => $data['urip'],
                ]);
            });
        } catch (\Throwable $e) {
            if ($storedFile) {
                Storage::disk('public')->delete($storedFile);
            }
            throw $e;
        }
    }

    public function update(Lpm $lpm, array $data, ?UploadedFile $file = null): Lpm
    {
        $oldFile = $lpm->path_file;
        $newFile = $file ? $file->store(self::FILE_DIR, 'public') : null;

        try {
            DB::transaction(function () use ($lpm, $data, $file, $newFile) {
                $payload = [
                    'judhul'        => $data['judhul'],
                    'tandha'        => $this->resolveUniqueSlug($data['tandha'], $lpm->id),
                    'kategori'      => $data['kategori'],
                    'isi'           => $data['isi'] ?? null,
                    'data_tambahan' => $data['data_tambahan'] ?? null,
                    'urutan'        => $data['urutan'],
                    'urip'          => $data['urip'],
                ];

                if ($newFile && $file) {
                    $payload['path_file']      = $newFile;
                    $payload['nama_file_asli'] = $file->getClientOriginalName();
                }

                $lpm->update($payload);
            });
        } catch (\Throwable $e) {
            if ($newFile) {
                Storage::disk('public')->delete($newFile);
            }
            throw $e;
        }

        if ($newFile && $oldFile && $oldFile !== $newFile) {
            $this->deleteStoredPath($oldFile);
        }

        return $lpm;
    }

    public function getTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Lpm::onlyTrashed()
            ->select([
                'id',
                'judhul',
                'tandha',
                'kategori',
                'data_tambahan',
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
        $item = Lpm::onlyTrashed()->findOrFail($id);
        $file = $item->path_file;

        $item->forceDelete();

        if ($file) {
            $this->deleteStoredPath($file);
        }
    }

    public function emptyTrash(): int
    {
        $items = Lpm::onlyTrashed()
            ->select(['id', 'path_file'])
            ->get();

        if ($items->isEmpty()) {
            return 0;
        }

        $count = $items->count();

        DB::transaction(function () {
            Lpm::onlyTrashed()->forceDelete();
        });

        foreach ($items as $item) {
            if ($item->path_file) {
                $this->deleteStoredPath($item->path_file);
            }
        }

        return $count;
    }

    private function deleteStoredPath(string $path): void
    {
        $relative = ltrim($path, '/');
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
