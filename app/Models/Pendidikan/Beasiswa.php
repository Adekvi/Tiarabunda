<?php

namespace App\Models\Pendidikan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beasiswa extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'syarat',
        'jangkepan',
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
