<?php

namespace App\Livewire\KategoriNilaiHarian;

use App\Models\KategoriNilaiHarian;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kategori Nilai Harian')]
class Index extends Component
{
    use WithPagination;

    public $nama_kategori = '';

    public $edit_id = null;

    public $showModal = false;

    public function rules()
    {
        return [
            'nama_kategori' => 'required|string|max:255|unique:kategori_nilai_harians,nama_kategori,'.$this->edit_id,
        ];
    }

    public function save()
    {
        $this->validate();

        KategoriNilaiHarian::updateOrCreate(
            ['id' => $this->edit_id],
            [
                'nama_kategori' => $this->nama_kategori,
            ]
        );

        $this->reset(['nama_kategori', 'edit_id', 'showModal']);
        session()->flash('message', 'Kategori nilai harian berhasil disimpan.');
    }

    public function edit($id)
    {
        $k = KategoriNilaiHarian::findOrFail($id);
        $this->edit_id = $k->id;
        $this->nama_kategori = $k->nama_kategori;
        $this->showModal = true;
    }

    public function delete($id)
    {
        KategoriNilaiHarian::findOrFail($id)->delete();
        session()->flash('message', 'Kategori nilai harian berhasil dihapus.');
    }

    public function create()
    {
        $this->reset(['nama_kategori', 'edit_id']);
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.kategori-nilai-harian.index', [
            'kategoriList' => KategoriNilaiHarian::orderBy('nama_kategori')->paginate(10),
        ]);
    }
}
