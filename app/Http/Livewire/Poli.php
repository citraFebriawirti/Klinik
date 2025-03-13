<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Poli as ModelsPoli;
use Illuminate\Support\Facades\Schema;

class Poli extends Component
{
    public $poli, $id_poli, $nama_poli, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy']; // Menangani event hapus

    public function mount($id_poli = null)
    {
        $this->id_poli = $id_poli;
    }

    public function render()
    {
        $query = ModelsPoli::query(); // Query hanya untuk tabel poli


        // Pencarian berdasarkan nama poli
        if ($this->searchTerm) {
            $query->where('nama_poli', 'like', '%' . $this->searchTerm . '%');
        }

        $this->poli = $query->get(); // Mengambil data poli sesuai filter

        return view('livewire.poli', [
            'poli' => $this->poli
        ]);
    }

    public function resetFilter()
    {
        $this->searchTerm = '';
    }

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('openModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->emit('closeModal');
    }

    public function resetFields()
    {
        $this->id_poli = null;
        $this->nama_poli = '';
    }

    public function store()
    {
        $this->validate([
            'nama_poli' => 'required|unique:tb_poli,nama_poli,' . ($this->id_poli ?? 'NULL') . ',id_poli',
        ]);

        ModelsPoli::updateOrCreate(['id_poli' => $this->id_poli], [
            'nama_poli' => $this->nama_poli,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_poli ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id_poli)
    {
        $poli = ModelsPoli::where('id_poli', $id_poli)->firstOrFail();
        $this->id_poli = $id_poli;
        $this->nama_poli = $poli->nama_poli;

        $this->openModal();
    }

    public function delete($id_poli)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id_poli,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id_poli)
    {
        if (Schema::hasTable('tb_poli')) {
            ModelsPoli::where('id_poli', $id_poli)->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
