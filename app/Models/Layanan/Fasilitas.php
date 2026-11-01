<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
   use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'path_gambar',
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
