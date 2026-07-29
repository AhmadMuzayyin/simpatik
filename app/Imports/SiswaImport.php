<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Headers: kelas_id_jangan_diubah, nis, nama_siswa, tempat_lahir, tanggal_lahir_yyyy_mm_dd
        $kelasId = $row['kelas_id_jangan_diubah'] ?? $row['kelas_id'] ?? null;
        $nis = trim($row['nis'] ?? '');
        $namaSiswa = trim($row['nama_siswa'] ?? '');
        $tempatLahir = trim($row['tempat_lahir'] ?? '');
        $tanggalLahir = trim($row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? '');

        // Skip row if NIS or Nama is empty
        if (empty($nis) || empty($namaSiswa)) {
            return null;
        }

        // Strict NIS check: MUST be numeric only
        if (! preg_match('/^[0-9]+$/', $nis)) {
            throw new \Exception("Gagal Import: NIS '$nis' milik $namaSiswa mengandung karakter non-angka. NIS wajib angka!");
        }

        return Siswa::updateOrCreate(
            ['nis' => $nis],
            [
                'kelas_id' => $kelasId,
                'nama_siswa' => $namaSiswa,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalLahir,
            ]
        );
    }
}
