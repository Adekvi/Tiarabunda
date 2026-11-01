<?php

namespace App\Models\Profil;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sejarah extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'dianyari_dening',
        'judhul',
        'isi',
        'kronologi',
        'path_sampul',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'kronologi' => 'array',
        ];
    }

    public function penganyar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dianyari_dening');
    }
}
