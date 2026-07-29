<?php

namespace App\Livewire\Nilai;

use App\Models\KategoriNilaiHarian;
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

    // State for Nilai Harian (Dynamic Global)
    public $harian_siswa_id = '';

    public $harian_scores = []; // [kategori_id => nilai]

    public $available_categories = [];

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

        if (! $this->mapel_edit_id) {
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

        if ($this->showDetailModal && $this->detailSiswaId) {
            $this->mapel_siswa_id = $this->detailSiswaId;
        }

        $this->showMapelModal = true;
    }

    // Nilai Harian Methods
    public function updatedHarianSiswaId($siswaId)
    {
        $this->loadHarianCategoriesForSiswa($siswaId);
    }

    public function loadHarianCategoriesForSiswa($siswaId)
    {
        $this->harian_scores = [];
        $this->available_categories = KategoriNilaiHarian::all();

        if (! $siswaId) {
            return;
        }

        $existingScores = NilaiHarian::where('siswa_id', $siswaId)->pluck('nilai', 'kategori_nilai_harian_id')->toArray();

        foreach ($this->available_categories as $cat) {
            $this->harian_scores[$cat->id] = $existingScores[$cat->id] ?? 0;
        }
    }

    public function createHarian()
    {
        $this->reset(['harian_siswa_id', 'harian_scores']);
        $this->available_categories = KategoriNilaiHarian::all();
        $this->showHarianModal = true;
    }

    public function editHarianForSiswa($siswaId)
    {
        $this->harian_siswa_id = $siswaId;
        $this->loadHarianCategoriesForSiswa($siswaId);
        $this->showHarianModal = true;
    }

    public function saveHarian()
    {
        $this->validate([
            'harian_siswa_id' => 'required|exists:siswas,id',
            'harian_scores' => 'required|array',
            'harian_scores.*' => 'required|numeric|min:0|max:100',
        ], [
            'harian_siswa_id.required' => 'Pilih siswa terlebih dahulu.',
            'harian_scores.*.required' => 'Setiap nilai kategori harian wajib diisi.',
            'harian_scores.*.numeric' => 'Nilai harian harus berupa angka.',
        ]);

        foreach ($this->harian_scores as $kategoriId => $nilaiVal) {
            NilaiHarian::updateOrCreate(
                [
                    'siswa_id' => $this->harian_siswa_id,
                    'kategori_nilai_harian_id' => $kategoriId,
                ],
                [
                    'nilai' => $nilaiVal,
                ]
            );
        }

        $this->reset(['harian_siswa_id', 'harian_scores', 'available_categories', 'showHarianModal']);
        session()->flash('message', 'Data Nilai Harian berhasil disimpan.');
    }

    public function deleteHarianForSiswa($siswaId)
    {
        NilaiHarian::where('siswa_id', $siswaId)->delete();
        session()->flash('message', 'Data Nilai Harian siswa berhasil dihapus.');
    }

    public function render()
    {
        $qMapelSiswa = Siswa::with(['kelas'])
            ->has('nilaiMapels')
            ->withCount('nilaiMapels')
            ->withSum('nilaiMapels', 'nilai')
            ->withAvg('nilaiMapels', 'nilai');

        $qHarianSiswa = Siswa::with(['kelas', 'nilaiHarians.kategoriNilaiHarian'])
            ->has('nilaiHarians');

        if ($this->filter_kelas) {
            $qMapelSiswa->where('kelas_id', $this->filter_kelas);
            $qHarianSiswa->where('kelas_id', $this->filter_kelas);
        }

        return view('livewire.nilai.index', [
            'mapelSiswas' => $qMapelSiswa->paginate(10, ['*'], 'mapelPage'),
            'harianSiswas' => $qHarianSiswa->paginate(10, ['*'], 'harianPage'),
            'kelases' => Kelas::all(),
            'mapels' => MataPelajaran::all(),
            'siswas' => Siswa::when($this->filter_kelas, function ($q) {
                $q->where('kelas_id', $this->filter_kelas);
            })->get(),
        ]);
    }
}
