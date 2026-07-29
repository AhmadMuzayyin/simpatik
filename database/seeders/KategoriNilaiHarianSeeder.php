<?php

namespace Database\Seeders;

use App\Models\KategoriNilaiHarian;
use Illuminate\Database\Seeder;

class KategoriNilaiHarianSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pengetahuan Harian',
            'Keterampilan',
            'Sikap & Perilaku',
            'Kedisiplinan',
            'Kehadiran & Keaktifan',
            'Tahfidz & Hafalan',
        ];

        foreach ($categories as $catName) {
            KategoriNilaiHarian::updateOrCreate(
                ['nama_kategori' => $catName]
            );
        }
    }
}
