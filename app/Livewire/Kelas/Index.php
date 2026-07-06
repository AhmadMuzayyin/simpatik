<?php

namespace App\Livewire\Kelas;

use App\Models\Kelas;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Master Kelas')]
class Index extends Component
{
    use WithPagination;

    public $nama_kelas = '';
    public $edit_id = null;
    public $showModal = false;

    public function rules()
    {
        return [
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $this->edit_id,
        ];
    }

    public function save()
    {
        $this->validate();

        Kelas::updateOrCreate(
            ['id' => $this->edit_id],
            ['nama_kelas' => $this->nama_kelas]
        );

        $this->reset(['nama_kelas', 'edit_id', 'showModal']);
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $this->edit_id = $kelas->id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Kelas::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function create()
    {
        $this->reset(['nama_kelas', 'edit_id']);
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.kelas.index', [
            'kelases' => Kelas::paginate(10)
        ]);
    }
}
