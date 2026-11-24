<?php

namespace App\Models\Pendidikan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Services\Landing\Pendidikan\BeasiswaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Beasiswa extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'syarat',
        'jangkepan',
        'urutan',
        'urip',
    ];

    protected static function booted(): void
    {
        $flush = function () {
            Cache::forget('landing.home.beasiswa');
            Cache::forget(BeasiswaService::CACHE_KEY);
        };

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function getSyaratListAttribute(): array
    {
        if (blank($this->syarat)) {
            return [];
        }

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $this->syarat, $matches)) {
            return collect($matches[1])
                ->map(fn($item) => trim(strip_tags($item)))
                ->filter()
                ->values()
                ->toArray();
        }

        return collect(preg_split('/\r\n|\r|\n/', strip_tags($this->syarat)))
            ->map(fn($item) => trim($item))
            ->filter()
            ->values()
            ->toArray();
    }
}
