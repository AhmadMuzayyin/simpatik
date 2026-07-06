<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preprocessing extends Model
{
    protected $fillable = [
        'siswa_id',
        'rata_rata_mapel',
        'rata_rata_pengetahuan',
        'rata_rata_keterampilan',
        'rata_rata_sikap',
        'kategori_mapel',
        'kategori_pengetahuan',
        'kategori_keterampilan',
        'kategori_sikap',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
