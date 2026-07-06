<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    protected $kelas_id;

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function collection()
    {
        $siswas = Siswa::where('kelas_id', $this->kelas_id)->get(['kelas_id', 'nis', 'nama_siswa', 'tempat_lahir', 'tanggal_lahir']);
        
        // If there are no students, we just return an empty collection
        if ($siswas->isEmpty()) {
            // Provide at least one empty row with the class_id filled for convenience
            return collect([
                [
                    'kelas_id' => $this->kelas_id,
                    'nis' => '',
                    'nama_siswa' => '',
                    'tempat_lahir' => '',
                    'tanggal_lahir' => '',
                ]
            ]);
        }

        return $siswas;
    }

    public function headings(): array
    {
        return [
            'Kelas ID (Jangan Diubah)',
            'NIS',
            'Nama Siswa',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
        ];
    }
}
