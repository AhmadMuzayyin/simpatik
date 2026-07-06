<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    protected $fillable = [
        'siswa_id',
        'hasil_prediksi',
        'skor_probabilitas',
        'ranking',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
