<?php

namespace App\Livewire\Siswa;

use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
#[Title('Master Siswa')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $kelas_id = '';

    public $nama_siswa = '';

    public $nis = '';

    public $tempat_lahir = '';

    public $tanggal_lahir = '';

    public $edit_id = null;

    public $showModal = false;

    public $excel_file = null;

    public $showImportModal = false;

    public function rules()
    {
        return [
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string|max:255',
            'nis' => ['required', 'numeric', 'regex:/^[0-9]+$/', 'unique:siswas,nis,'.$this->edit_id],
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ];
    }

    protected $messages = [
        'nis.required' => 'NIS wajib diisi.',
        'nis.numeric' => 'NIS harus berupa angka dan tidak boleh mengandung huruf.',
        'nis.regex' => 'NIS harus berupa angka dan tidak boleh mengandung huruf.',
        'nis.unique' => 'NIS sudah terdaftar.',
    ];

    public function save()
    {
        $this->validate();

        Siswa::updateOrCreate(
            ['id' => $this->edit_id],
            [
                'kelas_id' => $this->kelas_id,
                'nama_siswa' => $this->nama_siswa,
                'nis' => $this->nis,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
            ]
        );

        $this->reset(['kelas_id', 'nama_siswa', 'nis', 'tempat_lahir', 'tanggal_lahir', 'edit_id', 'showModal']);
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $this->edit_id = $siswa->id;
        $this->kelas_id = $siswa->kelas_id;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->nis = $siswa->nis;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Siswa::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function create()
    {
        $this->reset(['kelas_id', 'nama_siswa', 'nis', 'tempat_lahir', 'tanggal_lahir', 'edit_id']);
        $this->showModal = true;
    }

    public $template_kelas_id = '';

    public $showExportModal = false;

    public function downloadTemplate()
    {
        $this->validate([
            'template_kelas_id' => 'required|exists:kelas,id',
        ], [
            'template_kelas_id.required' => 'Silakan pilih kelas terlebih dahulu.',
        ]);

        $kelas = Kelas::find($this->template_kelas_id);

        $this->showExportModal = false;

        return Excel::download(
            new SiswaExport($this->template_kelas_id),
            'template_siswa_'.str_replace(' ', '_', strtolower($kelas->nama_kelas)).'.xlsx'
        );
    }

    public function importExcel()
    {
        $this->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'excel_file.required' => 'File Excel wajib diunggah.',
            'excel_file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
        ]);

        try {
            Excel::import(new SiswaImport, $this->excel_file);
            $this->reset(['excel_file', 'showImportModal']);
            session()->flash('message', 'Data siswa berhasil di-import dari Excel.');
        } catch (\Exception $e) {
            $this->addError('excel_file', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.siswa.index', [
            'siswas' => Siswa::with('kelas')->paginate(10),
            'kelases' => Kelas::all(),
        ]);
    }
}
