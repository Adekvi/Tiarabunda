<?php

namespace App\Services\Superadmin\Layanan;

use App\Models\Layanan\Kerjasama;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KerjasamaService
{
    private const LOGO_DIR = 'images/kerjasama';
    private const FILE_DIR = 'dokumen/kerjasama';

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search   = trim((string) ($filters['q'] ?? ''));
        $jenis    = trim((string) ($filters['jenis'] ?? ''));
        $kategori = trim((string) ($filters['kategori'] ?? ''));
        $status   = $filters['status'] ?? '';

        return Kerjasama::query()
            ->select([
                'id',
                'jenis',
                'jeneng',
                'tandha',
                'kategori',
                'nomor_dokumen',
                'periode',
                'katrangan',
                'logo',
                'path_file',
                'nama_file_asli',
                'tautan',
                'kontak_pic',
                'urutan',
                'urip',
                'updated_at',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('jeneng', 'like', "%{$search}%")
                        ->orWhere('tandha', 'like', "%{$search}%")
                        ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('katrangan', 'like', "%{$search}%");
                });
            })
            ->when(in_array($jenis, [Kerjasama::JENIS_MITRA, Kerjasama::JENIS_PENAWARAN], true), fn($q) => $q->where('jenis', $jenis))
            ->when($kategori !== '', fn($q) => $q->where('kategori', $kategori))
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
        $row = Kerjasama::withTrashed()
            ->selectRaw("
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) AS total,
                COUNT(CASE WHEN deleted_at IS NULL AND jenis = 'mitra' THEN 1 END) AS mitra,
                COUNT(CASE WHEN deleted_at IS NULL AND jenis = 'penawaran' THEN 1 END) AS penawaran,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 1 THEN 1 END) AS aktif,
                COUNT(CASE WHEN deleted_at IS NULL AND urip = 0 THEN 1 END) AS nonaktif,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) AS terhapus
            ")
            ->first();

        return [
            'total'     => (int) ($row->total ?? 0),
            'mitra'     => (int) ($row->mitra ?? 0),
            'penawaran' => (int) ($row->penawaran ?? 0),
            'aktif'     => (int) ($row->aktif ?? 0),
            'nonaktif'  => (int) ($row->nonaktif ?? 0),
            'terhapus'  => (int) ($row->terhapus ?? 0),
        ];
    }

    /**
     * Distribusi jumlah item per kategori untuk sidebar (1 query GROUP BY).
     */
    public function getCategoryCounts(): array
    {
        return Kerjasama::query()
            ->selectRaw('COALESCE(NULLIF(kategori, ""), "Industri & Perusahaan") AS nama_kategori, COUNT(*) AS jumlah')
            ->groupBy('nama_kategori')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'nama_kategori')
            ->all();
    }

    public function getCategories(): array
    {
        $categories = Kerjasama::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values()
            ->all();

        return array_values(array_unique(array_merge([
            'Industri & Perusahaan',
            'Rumah Sakit & Kesehatan',
            'Pemerintah & Instansi',
            'Perguruan Tinggi',
            'Magang & Rekrutmen',
            'Riset & Pengabdian',
        ], $categories)));
    }

    public function resolveUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug) ?: 'kerjasama-' . Str::lower(Str::random(6));

        $existing = Kerjasama::withTrashed()
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

    public function create(array $data, ?UploadedFile $logo = null, ?UploadedFile $file = null): Kerjasama
    {
        $storedLogo = $logo ? $logo->store(self::LOGO_DIR, 'public') : null;
        $storedFile = $file ? $file->store(self::FILE_DIR, 'public') : null;

        try {
            return DB::transaction(function () use ($data, $file, $storedLogo, $storedFile) {
                return Kerjasama::create([
                    'jenis'          => $data['jenis'],
                    'jeneng'         => $data['jeneng'],
                    'tandha'         => $this->resolveUniqueSlug($data['tandha']),
                    'kategori'       => $data['kategori'],
                    'nomor_dokumen'  => $data['nomor_dokumen'] ?? null,
                    'periode'        => $data['periode'] ?? null,
                    'katrangan'      => $data['katrangan'] ?? null,
                    'logo'           => $storedLogo,
                    'path_file'      => $storedFile,
                    'nama_file_asli' => $file?->getClientOriginalName(),
                    'tautan'         => $data['tautan'] ?? null,
                    'kontak_pic'     => $data['kontak_pic'] ?? null,
                    'urutan'         => $data['urutan'],
                    'urip'           => $data['urip'],
                ]);
            });
        } catch (\Throwable $e) {
            if ($storedLogo) {
                Storage::disk('public')->delete($storedLogo);
            }
            if ($storedFile) {
                Storage::disk('public')->delete($storedFile);
            }
            throw $e;
        }
    }

    public function update(Kerjasama $kerjasama, array $data, ?UploadedFile $logo = null, ?UploadedFile $file = null): Kerjasama
    {
        $oldLogo = $kerjasama->logo;
        $oldFile = $kerjasama->path_file;

        $newLogo = $logo ? $logo->store(self::LOGO_DIR, 'public') : null;
        $newFile = $file ? $file->store(self::FILE_DIR, 'public') : null;

        try {
            DB::transaction(function () use ($kerjasama, $data, $file, $newLogo, $newFile) {
                $payload = [
                    'jenis'         => $data['jenis'],
                    'jeneng'        => $data['jeneng'],
                    'tandha'        => $this->resolveUniqueSlug($data['tandha'], $kerjasama->id),
                    'kategori'      => $data['kategori'],
                    'nomor_dokumen' => $data['nomor_dokumen'] ?? null,
                    'periode'       => $data['periode'] ?? null,
                    'katrangan'     => $data['katrangan'] ?? null,
                    'tautan'        => $data['tautan'] ?? null,
                    'kontak_pic'    => $data['kontak_pic'] ?? null,
                    'urutan'        => $data['urutan'],
                    'urip'          => $data['urip'],
                ];

                if ($newLogo) {
                    $payload['logo'] = $newLogo;
                }

                if ($newFile && $file) {
                    $payload['path_file']      = $newFile;
                    $payload['nama_file_asli'] = $file->getClientOriginalName();
                }

                $kerjasama->update($payload);
            });
        } catch (\Throwable $e) {
            if ($newLogo) {
                Storage::disk('public')->delete($newLogo);
            }
            if ($newFile) {
                Storage::disk('public')->delete($newFile);
            }
            throw $e;
        }

        if ($newLogo && $oldLogo && $oldLogo !== $newLogo) {
            $this->deleteStoredPath($oldLogo);
        }

        if ($newFile && $oldFile && $oldFile !== $newFile) {
            $this->deleteStoredPath($oldFile);
        }

        return $kerjasama;
    }

    public function getTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Kerjasama::onlyTrashed()
            ->select([
                'id',
                'jenis',
                'jeneng',
                'tandha',
                'kategori',
                'nomor_dokumen',
                'logo',
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
        $item = Kerjasama::onlyTrashed()->findOrFail($id);
        $logo = $item->logo;
        $file = $item->path_file;

        $item->forceDelete();

        if ($logo) {
            $this->deleteStoredPath($logo);
        }
        if ($file) {
            $this->deleteStoredPath($file);
        }
    }

    public function emptyTrash(): int
    {
        $items = Kerjasama::onlyTrashed()
            ->select(['id', 'logo', 'path_file'])
            ->get();

        if ($items->isEmpty()) {
            return 0;
        }

        $count = $items->count();

        DB::transaction(function () {
            Kerjasama::onlyTrashed()->forceDelete();
        });

        foreach ($items as $item) {
            if ($item->logo) {
                $this->deleteStoredPath($item->logo);
            }
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
