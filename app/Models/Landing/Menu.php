<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\Concerns\HasTandha;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'judhul',
        'tandha',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function submenus(): HasMany
    {
        return $this->hasMany(Submenu::class)->ordered();
    }

    /** Dipakai eager load: with(['activeSubmenus']) — hindari N+1 di navbar */
    public function activeSubmenus(): HasMany
    {
        return $this->hasMany(Submenu::class)
            ->where($this->submenus()->getRelated()->getTable() . '.urip', true)
            ->ordered();
    }
}
