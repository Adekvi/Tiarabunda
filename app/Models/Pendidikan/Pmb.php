<?php

namespace App\Models\Pendidikan;

use Illuminate\Database\Eloquent\Model;

class Pmb extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'requirements',
        'steps',
        'open_at',
        'close_at',
        'sort_order',
        'is_active',
    ];
}
