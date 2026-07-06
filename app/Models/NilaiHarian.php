<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'pengetahuan',
        'keterampilan',
        'sikap',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
