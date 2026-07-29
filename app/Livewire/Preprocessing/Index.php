<?php

namespace App\Livewire\Preprocessing;

use App\Models\Preprocessing;
use App\Models\Siswa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Preprocessing Data')]
class Index extends Component
{
    use WithPagination;

    public function proses()
    {
        $siswas = Siswa::with(['nilaiMapels', 'nilaiHarians.kategoriNilaiHarian'])->get();
        $processedCount = 0;

        foreach ($siswas as $siswa) {
            if ($siswa->nilaiMapels->count() === 0 || $siswa->nilaiHarians->count() === 0) {
                continue;
            }

            $avgMapel = $siswa->nilaiMapels->avg('nilai');
            $avgHarian = $siswa->nilaiHarians->avg('nilai');

            $detailHarian = [];
            foreach ($siswa->nilaiHarians as $nh) {
                if ($nh->kategoriNilaiHarian) {
                    $namaKategori = $nh->kategoriNilaiHarian->nama_kategori;
                    $detailHarian[$namaKategori] = [
                        'nilai' => $nh->nilai,
                        'kategori' => $this->getKategori($nh->nilai),
                    ];
                }
            }

            Preprocessing::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'rata_rata_mapel' => $avgMapel,
                    'rata_rata_harian' => $avgHarian,
                    'kategori_mapel' => $this->getKategori($avgMapel),
                    'kategori_harian' => $this->getKategori($avgHarian),
                    'detail_harian' => $detailHarian,
                ]
            );
            $processedCount++;
        }

        session()->flash('message', "Berhasil memproses data preprocessing untuk $processedCount siswa.");
    }

    private function getKategori($nilai)
    {
        if ($nilai >= 85) {
            return 'Tinggi';
        }
        if ($nilai >= 70) {
            return 'Sedang';
        }

        return 'Rendah';
    }

    public function render()
    {
        return view('livewire.preprocessing.index', [
            'data' => Preprocessing::with('siswa.kelas')->paginate(10),
        ]);
    }
}
