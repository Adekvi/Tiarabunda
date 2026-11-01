<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasTandha;
use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'digawe_dening',
        'judhul',
        'tandha',
        'golongan',
        'ringkesan',
        'isi',
        'path_sampul',
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

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'digawe_dening');
    }

    /** Landing: hanya yang aktif + sudah dijadwalkan terbit */
    public function scopeTerbit(Builder $query): Builder
    {
        return $query
            ->active()
            ->whereNotNull('wektu_terbit')
            ->where('wektu_terbit', '<=', now());
    }
}
