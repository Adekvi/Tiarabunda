<?php

namespace App\Models\Pendidikan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class Akreditasi extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'status_akred',
        'bagean',
        'katrangan',
        'path_gambar',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function prodi(): HasOne
    {
        return $this->hasOne(Prodi::class);
    }

    /** URL publik gambar (aman jika null) */
    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->path_gambar) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // Hapus awalan 'public/' jika tidak sengaja tersimpan di database
        $path = str_replace('public/', '', $this->path_gambar);

        return $disk->url($path);
    }
}
