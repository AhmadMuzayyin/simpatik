<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $fillable = ['nama_mapel'];

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}
