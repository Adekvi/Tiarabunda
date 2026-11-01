<?php

namespace App\Models\Pendidikan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pmb extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'syarat',
        'langkah',
        'wektu_bukak',
        'wektu_tutup',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'syarat' => 'array',
            'langkah' => 'array',
            'wektu_bukak' => 'date',
            'wektu_tutup' => 'date',
        ];
    }
}
