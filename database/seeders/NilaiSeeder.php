<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use App\Models\NilaiHarian;
use App\Models\NilaiMapel;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = Siswa::all();
        $mapels = MataPelajaran::all();

        foreach ($siswas as $siswa) {
            // Seed Nilai Harian (1 per siswa)
            NilaiHarian::create([
                'siswa_id' => $siswa->id,
                'pengetahuan' => rand(70, 95),
                'keterampilan' => rand(70, 95),
                'sikap' => rand(70, 95),
            ]);

            // Seed Nilai Mapel (per mapel per siswa)
            foreach ($mapels as $mapel) {
                NilaiMapel::create([
                    'siswa_id' => $siswa->id,
                    'mapel_id' => $mapel->id,
                    'nilai' => rand(70, 100),
                ]);
            }
        }
    }
}
