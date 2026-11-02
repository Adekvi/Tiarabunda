<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'judhul',
        'tandha',
        'ringkesan',
        'isi',
        'path_sampul',
        'path_thumb',
        'wektu_terbit',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'wektu_terbit' => 'datetime',
        ];
    }

    public function scopeTerbit(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('wektu_terbit')
            ->where('wektu_terbit', '<=', now());
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
