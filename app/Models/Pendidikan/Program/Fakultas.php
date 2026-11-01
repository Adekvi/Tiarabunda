<?php

namespace App\Models\Pendidikan\Program;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'code',
        'description',
        'sort_order',
        'is_active',
    ];

    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }
}
