<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Heromedia extends Model
{
    use HasUripState;

    protected $table = 'heromedia';

    protected $fillable = [
        'hero_id',
        'tipe',
        'path_media',
        'path_poster',
        'urutan',
        'urip',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'urip'   => 'boolean',
    ];

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class, 'hero_id');
    }
}
