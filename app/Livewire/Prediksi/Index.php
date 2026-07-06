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
        // Jika kosong, kita bootstrap data latih dengan rule sederhana
        $trainingData = Prediksi::join('preprocessings', 'prediksis.siswa_id', '=', 'preprocessings.siswa_id')->get();
        
        $countTauladan = $trainingData->where('hasil_prediksi', 'Tauladan')->count();
        $countBukan = $trainingData->where('hasil_prediksi', 'Bukan Tauladan')->count();
        $totalData = $trainingData->count();

        // Fallback jika tidak ada data latih historis (Cold Start)
        if ($totalData == 0) {
            $priorTauladan = 0.5;
            $priorBukan = 0.5;
        } else {
            $priorTauladan = $countTauladan / $totalData;
            $priorBukan = $countBukan / $totalData;
        }

        $predictedCount = 0;

        foreach ($unpredicted as $data) {
            // Hitung Likelihood
            // P(X | Tauladan)
            $pMapelT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_mapel', $data->kategori_mapel);
            $pPengetahuanT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_pengetahuan', $data->kategori_pengetahuan);
            $pKeterampilanT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_keterampilan', $data->kategori_keterampilan);
            $pSikapT = $this->calculateLikelihood($trainingData, 'Tauladan', 'kategori_sikap', $data->kategori_sikap);

            $probTauladan = $priorTauladan * $pMapelT * $pPengetahuanT * $pKeterampilanT * $pSikapT;

            // P(X | Bukan Tauladan)
            $pMapelB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_mapel', $data->kategori_mapel);
            $pPengetahuanB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_pengetahuan', $data->kategori_pengetahuan);
            $pKeterampilanB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_keterampilan', $data->kategori_keterampilan);
            $pSikapB = $this->calculateLikelihood($trainingData, 'Bukan Tauladan', 'kategori_sikap', $data->kategori_sikap);

            $probBukan = $priorBukan * $pMapelB * $pPengetahuanB * $pKeterampilanB * $pSikapB;

            // Cold start fallback rules jika probabilitas 0 karena data latih belum representatif
            if ($totalData == 0 || ($probTauladan == 0 && $probBukan == 0)) {
                $avgAll = ($data->rata_rata_mapel + $data->rata_rata_pengetahuan + $data->rata_rata_keterampilan + $data->rata_rata_sikap) / 4;
                if ($avgAll >= 85) {
                    $probTauladan = $avgAll / 100;
                    $probBukan = 1 - $probTauladan;
                } else {
                    $probBukan = (100 - $avgAll) / 100;
                    $probTauladan = 1 - $probBukan;
                }
            }

            // Normalisasi
            $totalProb = $probTauladan + $probBukan;
            if ($totalProb > 0) {
                $scoreTauladan = $probTauladan / $totalProb;
            } else {
                $scoreTauladan = 0;
            }

            $hasil = $probTauladan > $probBukan ? 'Tauladan' : 'Bukan Tauladan';

            Prediksi::create([
                'siswa_id' => $data->siswa_id,
                'hasil_prediksi' => $hasil,
                'skor_probabilitas' => $scoreTauladan,
            ]);

            $predictedCount++;
        }

        // Kalkulasi ulang ranking per kelas berdasarkan skor_probabilitas
        $this->updateRanking();

        session()->flash('message', "Berhasil memprediksi $predictedCount siswa.");
    }

    private function calculateLikelihood($trainingData, $kelasLabel, $atribut, $nilaiAtribut)
    {
        if ($trainingData->count() == 0) return 0.5; // Laplace smoothing fallback
        
        $subset = $trainingData->where('hasil_prediksi', $kelasLabel);
        $totalSubset = $subset->count();
        
        if ($totalSubset == 0) return 0.01; // Avoid divide by zero
        
        $countMatch = $subset->where($atribut, $nilaiAtribut)->count();
        
        // Laplace Smoothing (Add-1)
        return ($countMatch + 1) / ($totalSubset + 3); // Asumsi 3 kategori: Tinggi, Sedang, Rendah
    }

    private function updateRanking()
    {
        $prediksis = Prediksi::with('siswa')->get();
        $groupedByKelas = $prediksis->groupBy('siswa.kelas_id');

        foreach ($groupedByKelas as $kelasId => $siswaPrediksi) {
            $sorted = $siswaPrediksi->sortByDesc('skor_probabilitas')->values();
            foreach ($sorted as $index => $pred) {
                $pred->update(['ranking' => $index + 1]);
            }
        }
    }

    public function render()
    {
        return view('livewire.prediksi.index', [
            'data' => Prediksi::with('siswa.kelas', 'siswa.preprocessing')
                        ->orderBy('skor_probabilitas', 'desc')
                        ->paginate(10)
        ]);
    }
}
