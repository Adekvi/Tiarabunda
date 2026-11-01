<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
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
}
