<?php

namespace App\Models\Profil;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Visimisi extends Model
{
    protected $fillable = [
        'id',
        'section',
        'title',
        'body',
        'items',
        'sort_order',
        'is_active',
        'updated_by',
    ];

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
