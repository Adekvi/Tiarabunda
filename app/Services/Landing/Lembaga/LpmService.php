<?php

namespace App\Services\Landing\Lembaga;

use App\Models\Lembaga\Lpm;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LpmService
{
    private const CACHE_TTL = 600;

    public function forPage(): Collection
    {
        $rows = Cache::remember(Lpm::CACHE_KEY, self::CACHE_TTL, function (): array {
            /** @var \Illuminate\Database\Eloquent\Collection<int, Lpm> $items */
            $items = Lpm::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get();

            $result = [];
            foreach ($items as $item) {
                $result[] = $this->transform($item);
            }

            return $result;
        });

        return collect($rows)->map(fn(array $row) => (object) $row);
    }

    public function findBySlug(string $slug): ?object
    {
        $item = Lpm::query()
            ->where('urip', true)
            ->where('tandha', $slug)
            ->first();

        return $item ? (object) $this->transform($item) : null;
    }

    public function kategoriOptions(): array
    {
        return Lpm::KATEGORI;
    }

    public function clearCache(): void
    {
        Cache::forget(Lpm::CACHE_KEY);
    }

    private function transform(Lpm $item): array
    {
        $fileUrl = $this->resolveFileUrl($item);

        return [
            'id'             => $item->id,
            'judhul'         => $item->judhul,
            'tandha'         => $item->tandha ?: Str::slug($item->judhul ?? 'lpm-' . $item->id),
            'kategori'       => $item->kategori ?: 'spmi',
            'kategori_label' => $item->kategori_label,
            'isi'            => $item->isi,
            'data_tambahan'  => $item->data_tambahan ?? [],
            'has_file'       => $fileUrl !== null,
            'file_url'       => $fileUrl,
            'file_ext'       => $item->file_extension,
            'file_size'      => $item->file_size,
            'urutan'         => $item->urutan,
        ];
    }

    private function resolveFileUrl(Lpm $item): ?string
    {
        if (blank($item->path_file)) {
            return null;
        }

        if (Str::startsWith($item->path_file, ['http://', 'https://'])) {
            return $item->path_file;
        }

        $relative = ltrim($item->path_file, '/');

        if (is_file(public_path($relative))) {
            return asset($relative);
        }

        if (is_file(storage_path('app/public/' . $relative))) {
            return asset('storage/' . $relative);
        }

        return null;
    }
}
