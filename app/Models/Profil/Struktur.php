<?php

namespace App\Models\Profil;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Struktur extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'judhul',
        'jeneng',
        'kunci_induk',
        'path_foto',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Struktur::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Struktur::class, 'parent_id')->orderBy('urutan');
    }
}
