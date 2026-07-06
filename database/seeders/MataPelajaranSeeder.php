<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            'Pendidikan Agama Islam',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Matematika',
            'Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial',
            'Bahasa Inggris',
            'Seni Budaya',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'Prakarya',
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::updateOrCreate(['nama_mapel' => $mapel]);
        }
    }
}
