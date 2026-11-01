<?php

namespace App\Models\Informasi;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = [
        'id',
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'cover_path',
        'published_at',
        'sort_order',
        'is_active',
        'created_by',
    ];
}
