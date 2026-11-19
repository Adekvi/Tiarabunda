<?php

namespace App\Services\Landing\Content;

use App\Models\Profil\Struktur;
use App\Services\Landing\Contracts\PageContentInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class StrukturPageContent implements PageContentInterface
{
    public const CACHE_KEY = 'landing.profil.struktur';
    private const CACHE_TTL = 600;

    public function getData(?string $prodiSlug = null): array
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            if (! class_exists(Struktur::class) || ! Schema::hasTable('strukturs')) {
                return [];
            }

            return Struktur::query()
                ->where('urip', true)
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'parent_id', 'judhul', 'jeneng', 'kunci_induk', 'path_foto', 'urutan'])
                ->map(function ($item) {
                    $cleanPath = $item->path_foto
                        ? preg_replace('/^(storage|public)\//', '', $item->path_foto)
                        : null;

                    return [
                        'id'          => $item->id,
                        'parent_id'   => $item->parent_id,
                        'judhul'      => $item->judhul,
                        'jeneng'      => $item->jeneng,
                        'kunci_induk' => $item->kunci_induk,
                        'foto_url'    => $cleanPath ? asset('storage/' . $cleanPath) : null,
                        'urutan'      => $item->urutan,
                    ];
                })
                ->values()
                ->all();
        });

        if (! is_array($rows)) {
            Cache::forget(self::CACHE_KEY);
            $rows = [];
        }

        $isDummy = false;

        // Gunakan data dummy jika tabel strukturs belum diisi
        if ($rows === []) {
            $rows = $this->dummyData();
            $isDummy = true;
        }

        return [
            'struktur'        => $this->buildTree($rows),
            'isDummyStruktur' => $isDummy,
        ];
    }

    /**
     * Susun array flat (parent_id) menjadi pohon hierarki bersarang.
     */
    private function buildTree(array $items, ?int $parentId = null): array
    {
        $branch = [];

        foreach ($items as $item) {
            if (($item['parent_id'] ?? null) === $parentId) {
                $item['children'] = $this->buildTree($items, $item['id']);
                $branch[] = $item;
            }
        }

        return $branch;
    }

    /**
     * Data dummy yang strukturnya identik dengan kolom tabel `strukturs`.
     */
    private function dummyData(): array
    {
        return [
            // Level 1: Pimpinan Tertinggi (Root)
            [
                'id'          => 1,
                'parent_id'   => null,
                'judhul'      => 'Rektor',
                'jeneng'      => 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.',
                'kunci_induk' => 'rektor',
                'foto_url'    => null,
                'urutan'      => 1,
            ],

            // Level 2: Wakil Rektor
            [
                'id'          => 2,
                'parent_id'   => 1,
                'judhul'      => 'Wakil Rektor I Bidang Akademik',
                'jeneng'      => 'Dr. Ahmad Fauzi, M.Pd.',
                'kunci_induk' => 'warek-1',
                'foto_url'    => null,
                'urutan'      => 1,
            ],
            [
                'id'          => 3,
                'parent_id'   => 1,
                'judhul'      => 'Wakil Rektor II Bidang Administrasi & Keuangan',
                'jeneng'      => 'Rina Marlina, S.E., M.M.',
                'kunci_induk' => 'warek-2',
                'foto_url'    => null,
                'urutan'      => 2,
            ],
            [
                'id'          => 4,
                'parent_id'   => 1,
                'judhul'      => 'Wakil Rektor III Bidang Kemahasiswaan & Kerja Sama',
                'jeneng'      => 'Hendra Wijaya, S.Kom., M.T.I.',
                'kunci_induk' => 'warek-3',
                'foto_url'    => null,
                'urutan'      => 3,
            ],

            // Level 3: Pimpinan Fakultas & Lembaga (di bawah Wakil Rektor I)
            [
                'id'          => 5,
                'parent_id'   => 2,
                'judhul'      => 'Dekan Fakultas Ilmu Kesehatan',
                'jeneng'      => 'Ns. Siti Rahmawati, S.Kep., M.Kep.',
                'kunci_induk' => 'dekan-fik',
                'foto_url'    => null,
                'urutan'      => 1,
            ],
            [
                'id'          => 6,
                'parent_id'   => 2,
                'judhul'      => 'Dekan Fakultas Keguruan dan Ilmu Pendidikan',
                'jeneng'      => 'Dr. Bambang Sutrisno, M.Pd.',
                'kunci_induk' => 'dekan-fkip',
                'foto_url'    => null,
                'urutan'      => 2,
            ],
            [
                'id'          => 7,
                'parent_id'   => 2,
                'judhul'      => 'Dekan Fakultas Ilmu Komputer',
                'jeneng'      => 'Dimas Pratama, S.Kom., M.Kom.',
                'kunci_induk' => 'dekan-fikom',
                'foto_url'    => null,
                'urutan'      => 3,
            ],
            [
                'id'          => 8,
                'parent_id'   => 2,
                'judhul'      => 'Dekan Fakultas Ekonomi Bisnis',
                'jeneng'      => 'Nadia Kusuma, S.E., M.Ak.',
                'kunci_induk' => 'dekan-feb',
                'foto_url'    => null,
                'urutan'      => 4,
            ],
            [
                'id'          => 9,
                'parent_id'   => 2,
                'judhul'      => 'Kepala LPPM',
                'jeneng'      => 'Dr. Ratna Dewi, M.Si.',
                'kunci_induk' => 'kepala-lppm',
                'foto_url'    => null,
                'urutan'      => 5,
            ],
            [
                'id'          => 10,
                'parent_id'   => 2,
                'judhul'      => 'Kepala LPMI (Penjaminan Mutu)',
                'jeneng'      => 'apt. Reza Mahendra, M.Farm.',
                'kunci_induk' => 'kepala-lpmi',
                'foto_url'    => null,
                'urutan'      => 6,
            ],
        ];
    }
}
