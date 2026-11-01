<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasTandha;
use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submenu extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'menu_id',
        'judhul',
        'tandha',
        'sub_judhul',
        'urutan',
        'urip',
        'blok_isi',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'blok_isi' => 'array',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
