<?php

namespace App\Models\Informasi;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'id',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];
}
