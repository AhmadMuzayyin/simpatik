<?php

namespace App\Livewire\Laporan;

use App\Models\Kelas;
use App\Models\Prediksi;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Laporan Tauladan & Ranking')]
class Index extends Component
{
    public $activeTab = '';

    public function mount()
    {
        $firstKelas = Kelas::first();
        if ($firstKelas) {
            $this->activeTab = 'kelas_'.$firstKelas->id;
        }
    }

    private function avgNilai(Prediksi $pred): float
    {
        $p = optional($pred->siswa)->preprocessing;

        return $p ? ($p->rata_rata_mapel + $p->rata_rata_harian) / 2 : 0;
    }

    public function render()
    {
        $kelases = Kelas::all();

        // 1. Siswa Tauladan Utama Sekolah (Skor Naive Bayes Pertama #1)
        $tauladanUtama = Prediksi::with(['siswa.kelas', 'siswa.preprocessing'])
            ->where('hasil_prediksi', 'Tauladan')
            ->first();

        // 2. Ranking 1, 2, 3 Tingkat Sekolah (Siswa di bawah Tauladan)
        $top3Sekolah = Prediksi::with(['siswa.kelas', 'siswa.preprocessing'])
            ->where('hasil_prediksi', 'Bukan Tauladan')
            ->whereIn('ranking', [1, 2, 3])
            ->orderBy('ranking', 'asc')
            ->get();

        // 3. Rekap Data Ranking per Kelas (Siswa Tauladan tidak merangkap Rank 1 di kelasnya)
        $dataPerKelas = [];
        foreach ($kelases as $kelas) {
            $topRankingsKelas = Prediksi::with(['siswa.kelas', 'siswa.preprocessing'])
                ->whereHas('siswa', function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                })
                ->where('hasil_prediksi', 'Bukan Tauladan') // Exclude Siswa Tauladan
                ->get()
                // skor_probabilitas Naive Bayes bisa seri antar siswa (karena berbasis
                // kategori, bukan nilai mentah). Jika seri, urutkan berdasarkan
                // rata-rata nilai (mapel & harian) tertinggi sebagai tie-breaker.
                ->sort(function ($a, $b) {
                    return [$b->skor_probabilitas, $this->avgNilai($b)] <=> [$a->skor_probabilitas, $this->avgNilai($a)];
                })
                ->take(10)
                ->values();

            $dataPerKelas[$kelas->id] = [
                'kelas' => $kelas,
                'topRankings' => $topRankingsKelas,
            ];
        }

        return view('livewire.laporan.index', [
            'kelases' => $kelases,
            'tauladanUtama' => $tauladanUtama,
            'top3Sekolah' => $top3Sekolah,
            'dataPerKelas' => $dataPerKelas,
        ]);
    }
}
