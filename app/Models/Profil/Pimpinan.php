<?php

namespace App\Models\Profil;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pimpinan extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'kalungguhan',
        'path_foto',
        'unen',
        'riwayat',
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
}
