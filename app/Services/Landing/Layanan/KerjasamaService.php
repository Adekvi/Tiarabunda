<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Kerjasama;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KerjasamaService
{
    private const TTL = 600;

    public function forPage(): Collection
    {
        $rows = Cache::remember(Kerjasama::CACHE_KEY, self::TTL, function () {
            return Kerjasama::query()
                ->select([
                    'id',
                    'jenis',
                    'tandha',
                    'kategori',
                    'nomor_dokumen',
                    'periode',
                    'katrangan',
                    'logo',
                    'path_file',
                    'tautan',
                    'kontak_pic',
                    'urutan',
                    'urip',
                ])
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderByDesc('id')
                ->get()
                ->map(function ($item) {
                    $fileUrl = null;
                    if (! blank($item->path_file)) {
                        $relative = ltrim($item->path_file, '/');
                        if (is_file(public_path($relative))) {
                            $fileUrl = asset($relative);
                        } elseif (is_file(storage_path('app/public/' . $relative))) {
                            $fileUrl = asset('storage/' . $relative);
                        }
                    }

                    $judul = Str::headline(str_replace('-', ' ', (string) $item->tandha));

                    return [
                        'id'            => $item->id,
                        'jenis'         => $item->jenis ?: Kerjasama::JENIS_MITRA,
                        'jenis_label'   => $item->jenis_label,
                        'nama'          => $judul !== '' ? $judul : 'Kerjasama #' . $item->id,
                        'tandha'        => $item->tandha,
                        'kategori'      => $item->kategori ?: 'Industri & Perusahaan',
                        'nomor_dokumen' => $item->nomor_dokumen,
                        'periode'       => $item->periode,
                        'keterangan'    => $item->katrangan,
                        'logo_url'      => $item->logo_url,
                        'has_file'      => $fileUrl !== null,
                        'file_url'      => $fileUrl,
                        'file_ext'      => $item->file_extension,
                        'file_size'     => $item->file_size,
                        'tautan'        => $item->tautan,
                        'kontak_pic'    => $item->kontak_pic,
                        'detail_url'    => url('/layanan/kerjasama/' . $item->tandha),
                    ];
                })
                ->values()
                ->all();
        });

        if (! is_array($rows)) {
            Cache::forget(Kerjasama::CACHE_KEY);
            $rows = [];
        }

        return collect($rows)->map(fn(array $row) => (object) $row);
    }

    public function forgetCache(): void
    {
        Cache::forget(Kerjasama::CACHE_KEY);
    }
}
