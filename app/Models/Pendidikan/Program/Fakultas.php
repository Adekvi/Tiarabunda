<?php

namespace App\Models\Pendidikan\Program;

use App\Models\Concerns\HasTandha;
use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fakultas extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'kodhe',
        'katrangan',
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

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class)->ordered();
    }

    public function activeProdis(): HasMany
    {
        return $this->hasMany(Prodi::class)
            ->where((new Prodi)->getTable() . '.urip', true)
            ->ordered();
    }
}
