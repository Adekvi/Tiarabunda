<?php

namespace App\Models\Profil;

use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    protected $fillable = [
        'id',
        'name',
        'jabatan',
        'photo_path',
        'quote',
        'bio',
        'sort_order',
        'is_active',
    ];
}
