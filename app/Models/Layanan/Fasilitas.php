<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'katrangan',
        'path_gambar',
        'path_thumb',
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

    public function getGambarUrlAttribute(): ?string
    {
        return $this->path_gambar
            ? asset('storage/' . $this->path_gambar)
            : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        if ($this->path_thumb) {
            return asset('storage/' . $this->path_thumb);
        }

        return $this->gambar_url;
    }
}
