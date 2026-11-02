<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'judhul',
        'tandha',
        'ringkesan',
        'isi',
        'lokasi',
        'wektu_mulai',
        'wektu_rampung',
        'path_sampul',
        'path_thumb',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'wektu_mulai' => 'datetime',
            'wektu_rampung' => 'datetime',
        ];
    }

    /** Kegiatan yang sudah / sedang relevan untuk publik */
    public function scopeTerjadwal(Builder $query): Builder
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('wektu_mulai')
                    ->orWhere('wektu_mulai', '>=', now()->subDay());
            });
    }

    public function getSampulUrlAttribute(): ?string
    {
        return $this->path_sampul
            ? asset('storage/' . $this->path_sampul)
            : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        if ($this->path_thumb) {
            return asset('storage/' . $this->path_thumb);
        }

        return $this->sampul_url;
    }
}
