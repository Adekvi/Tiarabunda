<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'image_path',
        'sort_order',
        'is_active',
    ];
}
