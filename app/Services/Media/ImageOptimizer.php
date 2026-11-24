<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageOptimizer
{
    /**
     * Simpan gambar dari upload form (default: 1 file WebP).
     *
     * @return array{path: string, thumb: ?string}
     */
    public function store(
        UploadedFile $file,
        string $folder = 'fasilitas',
        bool $withThumb = false,
    ): array {
        $real = $file->getRealPath();
        if ($real === false) {
            throw new RuntimeException('File upload tidak dapat dibaca.');
        }

        return $this->storeFromPath($real, $folder, null, $withThumb);
    }

    /**
     * Simpan gambar dari path lokal (berguna untuk Seeder / import).
     * Jika $fixedName diisi (tanpa ekstensi), file lama dengan nama sama akan ditimpa.
     *
     * @return array{path: string, thumb: ?string}
     */
    public function storeFromPath(
        string $sourcePath,
        string $folder = 'fasilitas',
        ?string $fixedName = null,
        bool $withThumb = false,
    ): array {
        $disk = Storage::disk('public');
        $useWebp = function_exists('imagewebp');
        $ext = $useWebp ? 'webp' : 'jpg';

        $name = $fixedName ?: Str::uuid()->toString();
        $base = trim($folder, '/') . '/' . $name;

        $mainPath  = $base . '.' . $ext;
        $thumbPath = $withThumb ? ($base . '_thumb.' . $ext) : null;

        try {
            $binary = $this->readAndResize($sourcePath, 1200, 78, $useWebp);
            if (! $disk->put($mainPath, $binary)) {
                throw new RuntimeException('Gagal menyimpan gambar utama.');
            }

            if ($withThumb && $thumbPath !== null) {
                $thumbBinary = $this->readAndResize($sourcePath, 360, 72, $useWebp);
                if (! $disk->put($thumbPath, $thumbBinary)) {
                    throw new RuntimeException('Gagal menyimpan thumbnail.');
                }
            }
        } catch (\Throwable $e) {
            $this->delete($mainPath, $thumbPath);
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
            array_unique($paths),
            fn($p) => is_string($p) && $p !== ''
        ));

        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }
    }

    private function readAndResize(string $source, int $maxWidth, int $quality, bool $useWebp): string
    {
        $info = @getimagesize($source);
        if ($info === false) {
            throw new RuntimeException('File bukan gambar yang valid.');
        }

        [$w, $h, $type] = $info;

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG  => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            default        => false,
        };

        if (! $src) {
            throw new RuntimeException('Format gambar tidak didukung atau file rusak.');
        }

        try {
            if (function_exists('imagepalettetotruecolor')) {
                imagepalettetotruecolor($src);
            }

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

            if ($useWebp) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
            } else {
                $white = imagecolorallocate($dst, 255, 255, 255);
                imagefill($dst, 0, 0, $white);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

            ob_start();
            $ok = $useWebp
                ? imagewebp($dst, null, $quality)
                : imagejpeg($dst, null, $quality);
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
