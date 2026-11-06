<?php

namespace App\Models\Pendidikan\Program;

use App\Models\Concerns\HasTandha;
use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\Pendidikan\Akreditasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use HasUripState, HasUrutan, HasTandha, SoftDeletes;

    protected $fillable = [
        'fakultas_id',
        'akreditasi_id',
        'jeneng',
        'tandha',
        'undhak',
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

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function akreditasi(): BelongsTo
    {
        return $this->belongsTo(Akreditasi::class);
    }

    // Opsional: hanya mengambil akreditasi yang aktif
    public function activeAkreditasis(): HasMany
    {
        return $this->hasMany(Akreditasi::class)
            ->where('urip', true)
            ->ordered();
    }
}
