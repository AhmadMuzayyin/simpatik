<?php

namespace App\Livewire\Prediksi;

use App\Models\Prediksi;
use App\Models\Preprocessing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Prediksi Naive Bayes')]
class Index extends Component
{
    use WithPagination;

    public function prediksiSekarang()
    {
        // 1. Ambil data preprocessing yang BELUM diprediksi
        $unpredicted = Preprocessing::whereNotIn('siswa_id', Prediksi::pluck('siswa_id'))->get();

        if ($unpredicted->isEmpty()) {
            session()->flash('message', 'Tidak ada data baru untuk diprediksi.');

            return;
        }

        // 2. Ambil data latih (Data Prediksi yang sudah ada)
        $trainingData = Prediksi::join('preprocessings', 'prediksis.siswa_id', '=', 'preprocessings.siswa_id')->get();

        $countTauladan = $trainingData->where('hasil_prediksi', 'Tauladan')->count();
        $totalData = $trainingData->count();

        if ($totalData == 0) {
            $priorTauladan = 0.5;
            $priorBukan = 0.5;
        } else {
            $priorTauladan = $countTauladan / $totalData;
            $priorBukan = ($totalData - $countTauladan) / $totalData;
        }

        $predictedCount = 0;

        foreach ($unpredicted as $data) {
            // Hitung Likelihood Mapel & Harian Gabungan
            $pMapelT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_mapel', $data->kategori_mapel);
            $pHarianT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_harian', $data->kategori_harian);

            $pMapelB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_mapel', $data->kategori_mapel);
            $pHarianB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_harian', $data->kategori_harian);

            $probTauladan = $priorTauladan * $pMapelT * $pHarianT;
            $probBukan = $priorBukan * $pMapelB * $pHarianB;

            // Fallback score calculation jika cold start
            if ($totalData == 0 || ($probTauladan == 0 && $probBukan == 0)) {
                $avgAll = ($data->rata_rata_mapel + $data->rata_rata_harian) / 2;
                $scoreTauladan = $avgAll / 100;
            } else {
                $totalProb = $probTauladan + $probBukan;
                $scoreTauladan = $totalProb > 0 ? ($probTauladan / $totalProb) : 0;
            }

            Prediksi::create([
                'siswa_id' => $data->siswa_id,
                'hasil_prediksi' => 'Bukan Tauladan',
                'skor_probabilitas' => $scoreTauladan,
            ]);

            $predictedCount++;
        }

        // Penetapan 1 Tauladan Utama Sekolah dan Ranking (Siswa Tauladan tidak merangkap Ranking 1)
        $this->updateRankingAndTauladan();

        session()->flash('message', "Berhasil memprediksi $predictedCount siswa. 1 Siswa Tauladan & Ranking telah diperbarui!");
    }

    private function avgNilai(Prediksi $pred): float
    {
        $p = optional($pred->siswa)->preprocessing;

        return $p ? ($p->rata_rata_mapel + $p->rata_rata_harian) / 2 : 0;
    }

    private function calculateLikelihood($trainingData, $kelasLabel, $atribut, $nilaiAtribut)
    {
        if ($trainingData->count() == 0) {
            return 0.5;
        } // Laplace smoothing fallback

        $subset = $trainingData->where('hasil_prediksi', $kelasLabel);
        $totalSubset = $subset->count();

        if ($totalSubset == 0) {
            return 0.01;
        } // Avoid divide by zero

        $countMatch = $subset->where($atribut, $nilaiAtribut)->count();

        // Laplace Smoothing (Add-1)
        return ($countMatch + 1) / ($totalSubset + 3); // 3 Kategori: Tinggi, Sedang, Rendah
    }

    public function updateRankingAndTauladan()
    {
        $prediksis = Prediksi::with('siswa.kelas', 'siswa.preprocessing')->get();

        // Urutkan seluruh siswa dari skor_probabilitas tertinggi ke terendah.
        // Jika skor_probabilitas sama (kategori Naive Bayes-nya identik), pakai
        // rata-rata nilai (mapel & harian) sebagai tie-breaker agar siswa dengan
        // nilai lebih tinggi tidak kalah ranking oleh siswa bernilai lebih rendah.
        $sorted = $prediksis->sort(function ($a, $b) {
            return [$b->skor_probabilitas, $this->avgNilai($b)] <=> [$a->skor_probabilitas, $this->avgNilai($a)];
        })->values();

        foreach ($sorted as $index => $pred) {
            if ($index === 0) {
                // Siswa Tertinggi #1 = SISWA TAULADAN UTAMA
                $pred->update([
                    'hasil_prediksi' => 'Tauladan',
                    'ranking' => 0, // 0 menandakan Tauladan Utama (tidak merangkap Rank 1)
                ]);
            } else {
                // Siswa berikutnya (#2 -> Rank 1, #3 -> Rank 2, #4 -> Rank 3, dst)
                $pred->update([
                    'hasil_prediksi' => 'Bukan Tauladan',
                    'ranking' => $index, // index 1 = Rank 1, index 2 = Rank 2, dst
                ]);
            }
        }
    }

    public function render()
    {
        $query = Prediksi::with('siswa.kelas', 'siswa.preprocessing');

        return view('livewire.prediksi.index', [
            // Urutkan berdasarkan kolom 'ranking' (sudah memperhitungkan tie-breaker
            // rata-rata nilai di updateRankingAndTauladan), bukan skor_probabilitas
            // mentah yang bisa seri antar siswa.
            'prediksiList' => $query->orderBy('ranking', 'asc')->paginate(10),
        ]);
    }
}
