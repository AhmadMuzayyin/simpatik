<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $fillable = [
        'kelas_id',
        'nama_siswa',
        'nis',
        'tempat_lahir',
        'tanggal_lahir',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilaiMapels(): HasMany
    {
        return $this->hasMany(NilaiMapel::class);
    }

    public function nilaiHarian(): HasOne
    {
        return $this->hasOne(NilaiHarian::class);
    }

    public function preprocessing()
    {
        return $this->hasOne(Preprocessing::class);
    }

    public function prediksi()
    {
        return $this->hasOne(Prediksi::class);
    }
}
