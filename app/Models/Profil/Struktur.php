<?php

namespace App\Models\Profil;

use Illuminate\Database\Eloquent\Model;

class Struktur extends Model
{
    protected $fillable = [
        'id',
        'parent_id',
        'title',
        'name',
        'parent_key',
        'photo_path',
        'sort_order',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(Struktur::class, 'parent_id');
    }
}
