<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Simpan gambar utama + thumb. Menggunakan GD (bawaan PHP).
     *
     * @return array{path: string, thumb: string|null}
     */
    public function store(UploadedFile $file, string $folder = 'fasilitas'): array
    {
        $disk = Storage::disk('public');
        $base = $folder . '/' . Str::uuid()->toString();
        $mainPath = $base . '.jpg';
        $thumbPath = $base . '_thumb.jpg';

        $binary = $this->readAndResize($file->getRealPath(), 1600, 82);
        $disk->put($mainPath, $binary);

        $thumbBinary = $this->readAndResize($file->getRealPath(), 480, 78);
        $disk->put($thumbPath, $thumbBinary);

        return [
            'path' => $mainPath,
            'thumb' => $thumbPath,
        ];
    }

    public function delete(?string $path, ?string $thumb = null): void
    {
        $disk = Storage::disk('public');
        if ($path) {
            $disk->delete($path);
        }
        if ($thumb) {
            $disk->delete($thumb);
        }
    }

    private function readAndResize(string $source, int $maxWidth, int $quality): string
    {
        [$w, $h, $type] = getimagesize($source);
        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($source),
            IMAGETYPE_PNG => imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp')
                ? imagecreatefromwebp($source)
                : imagecreatefromjpeg($source),
            default => imagecreatefromjpeg($source),
        };

        if ($w > $maxWidth) {
            $nw = $maxWidth;
            $nh = (int) round($h * ($maxWidth / $w));
        } else {
            $nw = $w;
            $nh = $h;
        }

        $dst = imagecreatetruecolor($nw, $nh);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        ob_start();
        imagejpeg($dst, null, $quality);
        $out = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $out;
    }
}
