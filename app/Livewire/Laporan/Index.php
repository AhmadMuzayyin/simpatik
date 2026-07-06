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
            $this->activeTab = 'kelas_' . $firstKelas->id;
        }
    }

    public function render()
    {
        $kelases = Kelas::all();
        
        $prediksis = Prediksi::with(['siswa.kelas', 'siswa.preprocessing'])
                    ->orderBy('ranking', 'asc')
                    ->get();
        
        $globalTauladan = Prediksi::with(['siswa.kelas', 'siswa.preprocessing'])
                            ->orderBy('skor_probabilitas', 'desc')
                            ->first();
        
        // Group by kelas
        $dataPerKelas = [];
        foreach ($kelases as $kelas) {
            $prediksiKelas = $prediksis->filter(function($p) use ($kelas) {
                return optional($p->siswa)->kelas_id == $kelas->id;
            });
            
            $top10 = $prediksiKelas->take(10);
            
            $dataPerKelas[$kelas->id] = [
                'kelas' => $kelas,
                'top10' => $top10,
            ];
        }

        return view('livewire.laporan.index', [
            'kelases' => $kelases,
            'dataPerKelas' => $dataPerKelas,
            'globalTauladan' => $globalTauladan,
        ]);
    }
}
