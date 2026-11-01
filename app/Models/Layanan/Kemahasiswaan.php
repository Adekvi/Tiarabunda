<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kemahasiswaan extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'judhul',
        'isi',
        'data_tambahan',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'data_tambahan' => 'array',
        ];
    }
}
