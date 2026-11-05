<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageOptimizer
{
    /**
     * Simpan gambar utama + thumb. Menggunakan GD (bawaan PHP).
     *
     * @return array{path: string, thumb: string}
     */
    public function store(UploadedFile $file, string $folder = 'fasilitas'): array
    {
        $real = $file->getRealPath();
        if ($real === false) {
            throw new RuntimeException('File upload tidak dapat dibaca.');
        }

        $disk = Storage::disk('public');
        $base = $folder . '/' . Str::uuid()->toString();
        $mainPath  = $base . '.jpg';
        $thumbPath = $base . '_thumb.jpg';

        try {
            $binary = $this->readAndResize($real, 1200, 75);
            if (! $disk->put($mainPath, $binary)) {
                throw new RuntimeException('Gagal menyimpan gambar utama.');
            }

            $thumbBinary = $this->readAndResize($real, 720, 72);
            if (! $disk->put($thumbPath, $thumbBinary)) {
                throw new RuntimeException('Gagal menyimpan thumbnail.');
            }
        } catch (\Throwable $e) {
            // rollback: hapus file yang mungkin sudah terlanjur tersimpan
            $disk->delete([$mainPath, $thumbPath]);
            throw $e;
        }

        return [
            'path'  => $mainPath,
            'thumb' => $thumbPath,
        ];
    }

    public function delete(?string ...$paths): void
    {
        $paths = array_values(array_filter(
            array_unique($paths ?? []),
            fn($p) => is_string($p) && $p !== ''
        ));

        if ($paths === []) {
            return;
        }

        Storage::disk('public')->delete($paths);
    }

    private function readAndResize(string $source, int $maxWidth, int $quality): string
    {
        $info = @getimagesize($source);
        if ($info === false) {
            throw new RuntimeException('File bukan gambar yang valid.');
        }

        [$w, $h, $type] = $info;

        if ($type === IMAGETYPE_WEBP && ! function_exists('imagecreatefromwebp')) {
            throw new RuntimeException('Server tidak mendukung WebP. Gunakan JPG/PNG.');
        }

        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($source),
            IMAGETYPE_PNG  => imagecreatefrompng($source),
            IMAGETYPE_WEBP => imagecreatefromwebp($source),
            default        => false,
        };

        if (! $src) {
            throw new RuntimeException('Format gambar tidak didukung atau file rusak.');
        }

        try {
            if ($w > $maxWidth) {
                $nw = $maxWidth;
                $nh = (int) round($h * ($maxWidth / $w));
            } else {
                $nw = $w;
                $nh = $h;
            }

            $dst = imagecreatetruecolor($nw, $nh);
            if (! $dst) {
                throw new RuntimeException('Gagal membuat kanvas gambar.');
            }

            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

            ob_start();
            $ok = imagejpeg($dst, null, $quality);
            $out = ob_get_clean();

            if (! $ok || ! is_string($out) || $out === '') {
                throw new RuntimeException('Gagal mengompres gambar.');
            }

            return $out;
        } finally {
            if (isset($dst) && $dst instanceof \GdImage) {
                imagedestroy($dst);
            }
            if ($src instanceof \GdImage) {
                imagedestroy($src);
            }
        }
    }
}
