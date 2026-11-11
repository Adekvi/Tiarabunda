<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Info extends Model
{
    use HasFactory, HasUrutan, HasUripState;

    protected $fillable = [
        'k',
        't',
        'd',
        'tgl',
        'img',
        'urip',
        'urutan'
    ];

    protected $casts = [
        'urip' => 'boolean',
        'urutan' => 'integer',
    ];
}
