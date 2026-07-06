<?php

namespace App\Livewire\Preprocessing;

use App\Models\Nilai;
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
        $siswas = Siswa::with(['nilaiMapels', 'nilaiHarian'])->get();
        $processedCount = 0;

        foreach ($siswas as $siswa) {
            if ($siswa->nilaiMapels->count() === 0 || !$siswa->nilaiHarian) continue;

            $avgMapel = $siswa->nilaiMapels->avg('nilai');
            $avgPengetahuan = $siswa->nilaiHarian->pengetahuan;
            $avgKeterampilan = $siswa->nilaiHarian->keterampilan;
            $avgSikap = $siswa->nilaiHarian->sikap;

            Preprocessing::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'rata_rata_mapel' => $avgMapel,
                    'rata_rata_pengetahuan' => $avgPengetahuan,
                    'rata_rata_keterampilan' => $avgKeterampilan,
                    'rata_rata_sikap' => $avgSikap,
                    'kategori_mapel' => $this->getKategori($avgMapel),
                    'kategori_pengetahuan' => $this->getKategori($avgPengetahuan),
                    'kategori_keterampilan' => $this->getKategori($avgKeterampilan),
                    'kategori_sikap' => $this->getKategori($avgSikap),
                ]
            );
            $processedCount++;
        }

        session()->flash('message', "Berhasil memproses data untuk $processedCount siswa.");
    }

    private function getKategori($nilai)
    {
        if ($nilai >= 85) return 'Tinggi';
        if ($nilai >= 70) return 'Sedang';
        return 'Rendah';
    }

    public function render()
    {
        return view('livewire.preprocessing.index', [
            'data' => Preprocessing::with('siswa.kelas')->paginate(10)
        ]);
    }
}
