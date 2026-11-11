<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use Illuminate\Database\Eloquent\Model;

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
        'urip'
    ];
}
