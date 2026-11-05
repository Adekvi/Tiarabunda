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
        'katrangan',
        'papan',
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
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Kegiatan yang masih relevan untuk ditampilkan ke publik.
     */
    public function scopeTerjadwal(Builder $query): Builder
    {
        return $query->active()
            ->where(function (Builder $query) {
                $query->whereNull('wektu_mulai')
                    ->orWhere('wektu_mulai', '>=', now()->subDay());
            });
    }

    /**
     * URL gambar sampul.
     */
    public function getSampulUrlAttribute(): ?string
    {
        return $this->path_sampul
            ? asset('storage/' . $this->path_sampul)
            : null;
    }

    /**
     * URL thumbnail dengan fallback ke gambar sampul.
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->path_thumb
            ? asset('storage/' . $this->path_thumb)
            : $this->sampul_url;
    }
}
