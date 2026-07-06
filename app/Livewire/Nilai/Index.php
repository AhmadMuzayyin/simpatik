<?php

namespace App\Livewire\Nilai;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiHarian;
use App\Models\NilaiMapel;
use App\Models\Siswa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Master Nilai')]
class Index extends Component
{
    use WithPagination;

    public $activeTab = 'mapel';
    public $filter_kelas = '';

    // State for Nilai Mapel
    public $mapel_siswa_id = '';
    public $mapel_id = '';
    public $nilai = 0;
    public $mapel_edit_id = null;
    public $showMapelModal = false;

    // State for Nilai Harian
    public $harian_siswa_id = '';
    public $pengetahuan = 0;
    public $keterampilan = 0;
    public $sikap = 0;
    public $harian_edit_id = null;
    public $showHarianModal = false;

    public $showDetailModal = false;
    public $detailSiswaId = null;
    public $detailSiswa = null;

    public function showDetail($id)
    {
        $this->detailSiswaId = $id;
        $this->loadDetailSiswa();
        $this->showDetailModal = true;
    }

    public function loadDetailSiswa()
    {
        if ($this->detailSiswaId) {
            $this->detailSiswa = Siswa::with(['kelas', 'nilaiMapels.mataPelajaran'])->find($this->detailSiswaId);
        }
    }

    // Nilai Mapel Methods
    public function saveMapel()
    {
        $this->validate([
            'mapel_siswa_id' => 'required|exists:siswas,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        if (!$this->mapel_edit_id) {
            $exists = NilaiMapel::where('siswa_id', $this->mapel_siswa_id)->where('mapel_id', $this->mapel_id)->exists();
            if ($exists) {
                $this->addError('mapel_id', 'Nilai untuk mata pelajaran ini sudah ada.');
                return;
            }
        }

        NilaiMapel::updateOrCreate(
            ['id' => $this->mapel_edit_id],
            [
                'siswa_id' => $this->mapel_siswa_id,
                'mapel_id' => $this->mapel_id,
                'nilai' => $this->nilai,
            ]
        );

        $this->reset(['mapel_siswa_id', 'mapel_id', 'nilai', 'mapel_edit_id', 'showMapelModal']);
        $this->loadDetailSiswa();
        session()->flash('message', 'Data Nilai Mapel berhasil disimpan.');
    }

    public function editMapel($id)
    {
        $n = NilaiMapel::findOrFail($id);
        $this->mapel_edit_id = $n->id;
        $this->mapel_siswa_id = $n->siswa_id;
        $this->mapel_id = $n->mapel_id;
        $this->nilai = $n->nilai;
        $this->showMapelModal = true;
    }

    public function deleteMapel($id)
    {
        NilaiMapel::findOrFail($id)->delete();
        $this->loadDetailSiswa();
        session()->flash('message', 'Data Nilai Mapel berhasil dihapus.');
    }

    public function createMapel()
    {
        $this->reset(['mapel_siswa_id', 'mapel_id', 'nilai', 'mapel_edit_id']);
        
        // If opened from detail modal, pre-fill siswa
        if ($this->showDetailModal && $this->detailSiswaId) {
            $this->mapel_siswa_id = $this->detailSiswaId;
        }
        
        $this->showMapelModal = true;
    }

    // Nilai Harian Methods
    public function saveHarian()
    {
        $this->validate([
            'harian_siswa_id' => 'required|exists:siswas,id',
            'pengetahuan' => 'required|numeric|min:0|max:100',
            'keterampilan' => 'required|numeric|min:0|max:100',
            'sikap' => 'required|numeric|min:0|max:100',
        ]);

        if (!$this->harian_edit_id) {
            $exists = NilaiHarian::where('siswa_id', $this->harian_siswa_id)->exists();
            if ($exists) {
                $this->addError('harian_siswa_id', 'Nilai harian untuk siswa ini sudah ada.');
                return;
            }
        }

        NilaiHarian::updateOrCreate(
            ['id' => $this->harian_edit_id],
            [
                'siswa_id' => $this->harian_siswa_id,
                'pengetahuan' => $this->pengetahuan,
                'keterampilan' => $this->keterampilan,
                'sikap' => $this->sikap,
            ]
        );

        $this->reset(['harian_siswa_id', 'pengetahuan', 'keterampilan', 'sikap', 'harian_edit_id', 'showHarianModal']);
        session()->flash('message', 'Data Nilai Harian berhasil disimpan.');
    }

    public function editHarian($id)
    {
        $n = NilaiHarian::findOrFail($id);
        $this->harian_edit_id = $n->id;
        $this->harian_siswa_id = $n->siswa_id;
        $this->pengetahuan = $n->pengetahuan;
        $this->keterampilan = $n->keterampilan;
        $this->sikap = $n->sikap;
        $this->showHarianModal = true;
    }

    public function deleteHarian($id)
    {
        NilaiHarian::findOrFail($id)->delete();
        session()->flash('message', 'Data Nilai Harian berhasil dihapus.');
    }

    public function createHarian()
    {
        $this->reset(['harian_siswa_id', 'pengetahuan', 'keterampilan', 'sikap', 'harian_edit_id']);
        $this->showHarianModal = true;
    }

    public function render()
    {
        $qMapelSiswa = Siswa::with(['kelas'])
            ->has('nilaiMapels')
            ->withCount('nilaiMapels')
            ->withSum('nilaiMapels', 'nilai')
            ->withAvg('nilaiMapels', 'nilai');

        $qHarian = NilaiHarian::with('siswa.kelas');
        
        if ($this->filter_kelas) {
            $qMapelSiswa->where('kelas_id', $this->filter_kelas);
            $qHarian->whereHas('siswa', function($q) {
                $q->where('kelas_id', $this->filter_kelas);
            });
        }

        return view('livewire.nilai.index', [
            'mapelSiswas' => $qMapelSiswa->paginate(10, ['*'], 'mapelPage'),
            'nilaiHarians' => $qHarian->paginate(10, ['*'], 'harianPage'),
            'kelases' => Kelas::all(),
            'mapels' => MataPelajaran::all(),
            'siswas' => Siswa::when($this->filter_kelas, function($q) {
                $q->where('kelas_id', $this->filter_kelas);
            })->get(),
        ]);
    }
}
