<?php

namespace App\Livewire\Mapel;

use App\Models\MataPelajaran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Master Mata Pelajaran')]
class Index extends Component
{
    use WithPagination;

    public $nama_mapel = '';
    public $edit_id = null;
    public $showModal = false;

    public function rules()
    {
        return [
            'nama_mapel' => 'required|string|max:255|unique:mata_pelajarans,nama_mapel,' . $this->edit_id,
        ];
    }

    public function save()
    {
        $this->validate();

        MataPelajaran::updateOrCreate(
            ['id' => $this->edit_id],
            ['nama_mapel' => $this->nama_mapel]
        );

        $this->reset(['nama_mapel', 'edit_id', 'showModal']);
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $this->edit_id = $mapel->id;
        $this->nama_mapel = $mapel->nama_mapel;
        $this->showModal = true;
    }

    public function delete($id)
    {
        MataPelajaran::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function create()
    {
        $this->reset(['nama_mapel', 'edit_id']);
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.mapel.index', [
            'mapels' => MataPelajaran::paginate(10)
        ]);
    }
}
