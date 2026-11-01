<?php

namespace App\Models\Profil;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Sejarah extends Model
{
    protected $fillable = [
        'id',
        'title',
        'body',
        'timeline',
        'cover_path',
        'sort_order',
        'is_active',
        'updated_by',
    ];

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
