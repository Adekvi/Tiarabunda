<?php

namespace App\Models\Pendidikan\Program;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'id',
        'fakultas_id',
        'name',
        'slug',
        'jenjang',
        'description',
        'akreditasi',
        'sort_order',
        'is_active',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
}
