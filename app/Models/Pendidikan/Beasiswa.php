<?php

namespace App\Models\Pendidikan;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'requirements',
        'coverage',
        'sort_order',
        'is_active',
    ];
}
