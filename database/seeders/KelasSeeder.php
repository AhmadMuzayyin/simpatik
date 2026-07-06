<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelases = [
            'Kelas 7A',
            'Kelas 7B',
            'Kelas 8A',
            'Kelas 8B',
            'Kelas 9A',
            'Kelas 9B',
        ];

        foreach ($kelases as $kelas) {
            Kelas::updateOrCreate(['nama_kelas' => $kelas]);
        }
    }
}
