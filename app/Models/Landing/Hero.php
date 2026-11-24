<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hero extends Model
{
    use HasUripState;

    protected $fillable = [
        'judul',
        'sub_judul',
        'teks_tombol_1',
        'link_tombol_1',
        'teks_tombol_2',
        'link_tombol_2',
        'tipe_media',
        'path_media',
        'path_poster',
        'durasi_slider',
        'urip',
    ];

    protected $casts = [
        'durasi_slider' => 'integer',
        'urip'          => 'boolean',
    ];

    /**
     * @return HasMany<Heromedia, $this>
     */
    public function medias(): HasMany
    {
        return $this->hasMany(Heromedia::class, 'hero_id');
    }

    /**
     * @return HasOne<Heromedia, $this>
     */
    public function videoMedia(): HasOne
    {
        return $this->hasOne(Heromedia::class, 'hero_id')
            ->where('tipe', 'video');
    }

    /**
     * @return HasMany<Heromedia, $this>
     */
    public function fotoMedias(): HasMany
    {
        return $this->hasMany(Heromedia::class, 'hero_id')
            ->where('tipe', 'gambar')
            ->orderBy('urutan')
            ->orderBy('id');
    }

    /**
     * @return HasMany<Heromedia, $this>
     */
    public function activeMedias(): HasMany
    {
        return $this->hasMany(Heromedia::class, 'hero_id')
            ->where('urip', true)
            ->orderByRaw("CASE WHEN tipe = 'video' THEN 0 ELSE 1 END")
            ->orderBy('urutan')
            ->orderBy('id');
    }
}
