<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'judhul',
        'golongan',
        'path_berkas',
        'jinis_berkas',
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
