<?php

namespace App\Models\Informasi;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'location',
        'starts_at',
        'ends_at',
        'cover_path',
        'sort_order',
        'is_active',
    ];
}
