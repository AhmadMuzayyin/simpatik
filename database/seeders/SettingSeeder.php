<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'SIMPATIK',
                'lembaga' => 'Lembaga Pendidikan ABC',
                'admin_email' => 'admin@admin.com',
                'admin_password' => 'password',
            ]
        );
    }
}
