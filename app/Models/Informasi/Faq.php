<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'pitakon',
        'wangsulan',
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
