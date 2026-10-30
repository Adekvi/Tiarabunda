<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submenu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_id',
        'title',
        'slug',
        'subtitle',
        'sort_order',
        'is_active',
        'blocks',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'blocks'    => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
