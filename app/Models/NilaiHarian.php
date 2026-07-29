<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiHarian extends Model
{
    protected $fillable = [
        'siswa_id',
        'kategori_nilai_harian_id',
        'nilai',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kategoriNilaiHarian()
    {
        return $this->belongsTo(KategoriNilaiHarian::class, 'kategori_nilai_harian_id');
    }
}
