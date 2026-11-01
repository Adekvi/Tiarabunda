<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'id',
        'title',
        'category',
        'file_path',
        'file_type',
        'sort_order',
        'is_active',
    ];
}
