<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Services\Landing\Layanan\AkademikService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Akademik extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $table = 'akademiks';

    protected $fillable = [
        'jeneng',
        'tandha',
        'kategori',
        'katrangan',
        'path_file',
        'nama_file_asli',
        'urutan',
        'urip',
    ];

    protected static function booted(): void
    {
        $flush = fn() => Cache::forget(AkademikService::CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    protected function casts(): array
    {
        return [
            'urip'   => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function getResolvedFilePathAttribute(): ?string
    {
        if (blank($this->path_file)) {
            return null;
        }

        $relative = ltrim($this->path_file, '/');

        foreach ([public_path($relative), storage_path('app/public/' . $relative)] as $candidate) {
            if (is_file($candidate) && $this->isInsideAllowedDir($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /** Cegah path traversal ('../') walau path_file berasal dari database. */
    private function isInsideAllowedDir(string $path): bool
    {
        $real = realpath($path);

        foreach ([public_path(), storage_path('app/public')] as $base) {
            $baseReal = realpath($base);
            if ($real !== false && $baseReal !== false && str_starts_with($real, $baseReal . DIRECTORY_SEPARATOR)) {
                return true;
            }
        }

        return false;
    }

    public function getFileExistsAttribute(): bool
    {
        return $this->resolved_file_path !== null;
    }

    public function getFileExtensionAttribute(): string
    {
        $file = $this->path_file ?: $this->nama_file_asli ?: 'docx';
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        return strtoupper($ext ?: 'DOCX');
    }

    public function getFileSizeAttribute(): string
    {
        $fullPath = $this->resolved_file_path;
        if (! $fullPath) {
            return $this->file_extension;
        }

        $bytes = filesize($fullPath) ?: 0;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return max(1, (int) round($bytes / 1024)) . ' KB';
    }
}
