<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function submenus()
    {
        return $this->hasMany(Submenu::class)->orderBy('sort_order');
    }

    public function activeSubmenus()
    {
        return $this->submenus()->where('is_active', true);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
