<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Model;

class Kemahasiswaan extends Model
{
    protected $fillable = [
        'id',
        'title',
        'body',
        'meta',
        'sort_order',
        'is_active',
    ];
}
