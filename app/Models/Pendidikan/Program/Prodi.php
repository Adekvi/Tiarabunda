<?php

namespace App\Models\Pendidikan\Program;

use App\Models\Concerns\HasTandha;
use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'fakultas_id',
        'jeneng',
        'tandha',
        'undhak',
        'katrangan',
        'akred',
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

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }
}
