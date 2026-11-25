<?php

namespace App\Services\Superadmin\Lembaga;

use App\Models\Lembaga\Lppm;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LppmService
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search   = trim((string) ($filters['q'] ?? ''));
        $kategori = trim((string) ($filters['kategori'] ?? ''));
        $status   = $filters['status'] ?? '';

        return Lppm::query()
            ->select([
                'id',
                'judhul',
                'tandha',
                'kategori',
                'data_tambahan',
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
            ->when($kategori !== '' && array_key_exists($kategori, Lppm::KATEGORI), fn($q) => $q->where('kategori', $kategori))
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
        $row = Lppm::withTrashed()
            ->selectRaw("
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) AS total,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 1 THEN 1 END) AS aktif,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 0 THEN 1 END) AS nonaktif,
                COUNT(CASE WHEN deleted_at IS NULL AND kategori = 'penelitian' THEN 1 END) AS penelitian,
                COUNT(CASE WHEN deleted_at IS NULL AND kategori = 'pengabdian' THEN 1 END) AS pengabdian,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) AS terhapus
            ")
            ->first();

        return [
            'total'      => (int) ($row->total ?? 0),
            'aktif'      => (int) ($row->aktif ?? 0),
            'nonaktif'   => (int) ($row->nonaktif ?? 0),
            'penelitian' => (int) ($row->penelitian ?? 0),
            'pengabdian' => (int) ($row->pengabdian ?? 0),
            'terhapus'   => (int) ($row->terhapus ?? 0),
        ];
    }

    /**
     * Distribusi jumlah item per kategori untuk sidebar (1 query GROUP BY).
     */
    public function getCategoryCounts(): array
    {
        $counts = Lppm::query()
            ->selectRaw('kategori, COUNT(*) AS jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->all();

        $result = [];
        foreach (Lppm::KATEGORI as $key => $label) {
            $result[$key] = [
                'label'  => $label,
                'jumlah' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $result;
    }

    public function resolveUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug) ?: 'lppm-' . Str::lower(Str::random(6));

        $existing = Lppm::withTrashed()
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

    public function create(array $data): Lppm
    {
        return DB::transaction(function () use ($data) {
            return Lppm::create([
                'judhul'        => $data['judhul'],
                'tandha'        => $this->resolveUniqueSlug($data['tandha']),
                'kategori'      => $data['kategori'],
                'isi'           => $data['isi'] ?? null,
                'data_tambahan' => $data['data_tambahan'] ?? null,
                'urutan'        => $data['urutan'],
                'urip'          => $data['urip'],
            ]);
        });
    }

    public function update(Lppm $lppm, array $data): Lppm
    {
        DB::transaction(function () use ($lppm, $data) {
            $lppm->update([
                'judhul'        => $data['judhul'],
                'tandha'        => $this->resolveUniqueSlug($data['tandha'], $lppm->id),
                'kategori'      => $data['kategori'],
                'isi'           => $data['isi'] ?? null,
                'data_tambahan' => $data['data_tambahan'] ?? null,
                'urutan'        => $data['urutan'],
                'urip'          => $data['urip'],
            ]);
        });

        return $lppm;
    }

    public function getTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Lppm::onlyTrashed()
            ->select([
                'id',
                'judhul',
                'tandha',
                'kategori',
                'data_tambahan',
                'deleted_at',
            ])
            ->orderByDesc('deleted_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function forceDelete(int $id): void
    {
        $item = Lppm::onlyTrashed()->findOrFail($id);
        $item->forceDelete();
    }

    public function emptyTrash(): int
    {
        $count = Lppm::onlyTrashed()->count();
        if ($count === 0) {
            return 0;
        }

        DB::transaction(function () {
            Lppm::onlyTrashed()->forceDelete();
        });

        return $count;
    }
}
