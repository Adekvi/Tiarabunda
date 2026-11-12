<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Info extends Model
{
    use HasFactory, HasUrutan, HasUripState, SoftDeletes;

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
