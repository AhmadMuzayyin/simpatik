<?php

namespace Database\Seeders;

use App\Models\KategoriNilaiHarian;
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
        $kategoriList = KategoriNilaiHarian::all();

        foreach ($siswas as $siswa) {
            // Seed Nilai Harian untuk setiap kategori harian
            foreach ($kategoriList as $cat) {
                NilaiHarian::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'kategori_nilai_harian_id' => $cat->id,
                    ],
                    [
                        'nilai' => rand(70, 98),
                    ]
                );
            }

            // Seed Nilai Mapel (per mapel per siswa)
            foreach ($mapels as $mapel) {
                NilaiMapel::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'mapel_id' => $mapel->id,
                    ],
                    [
                        'nilai' => rand(70, 100),
                    ]
                );
            }
        }
    }
}
