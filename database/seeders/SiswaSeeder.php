<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $kelases = Kelas::all();

        if ($kelases->isEmpty()) return;

        foreach ($kelases as $kelas) {
            for ($i = 1; $i <= 5; $i++) {
                Siswa::create([
                    'kelas_id' => $kelas->id,
                    'nama_siswa' => $faker->name,
                    'nis' => $faker->unique()->numerify('#####'),
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '-13 years'),
                ]);
            }
        }
    }
}
