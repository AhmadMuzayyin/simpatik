<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriNilaiHarian extends Model
{
    protected $fillable = [
        'nama_kategori',
    ];

    public function nilaiHarians()
    {
        return $this->hasMany(NilaiHarian::class, 'kategori_nilai_harian_id');
    }
}
