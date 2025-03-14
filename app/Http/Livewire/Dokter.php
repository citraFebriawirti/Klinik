<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dokter as ModelsDokter;
use App\Models\Poli as ModelsPoli;
use Illuminate\Support\Facades\Schema;
use Livewire\WithPagination;

class Dokter extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Ubah menjadi protected
    protected $dokter;

    public $id_dokter, $nama_dokter, $id_poli, $spesialisasi_dokter, $no_hp_dokter, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function render()
    {
        $query = ModelsDokter::with('poli');

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('id_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nama_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('spesialisasi_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('no_hp_dokter', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Simpan hasil paginate ke properti protected
        $this->dokter = $query->paginate(5);

        $poli = ModelsPoli::all();

        // Kembalikan data ke view
        return view('livewire.dokter', [
            'dokter' => $this->dokter, // Kirim data paginasi
            'poli' => $poli,
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
        $this->id_dokter = null;
        $this->nama_dokter = '';
        $this->id_poli = '';
        $this->spesialisasi_dokter = '';
        $this->no_hp_dokter = '';
    }

    public function store()
    {
        $this->validate([
            'nama_dokter' => 'required',
            'id_poli' => 'required|exists:tb_poli,id_poli',
            'spesialisasi_dokter' => 'required',
            'no_hp_dokter' => 'nullable|max:15',
        ]);

        ModelsDokter::updateOrCreate(['id_dokter' => $this->id_dokter], [
            'nama_dokter' => $this->nama_dokter,
            'id_poli' => $this->id_poli,
            'spesialisasi_dokter' => $this->spesialisasi_dokter,
            'no_hp_dokter' => $this->no_hp_dokter,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_dokter ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id_dokter)
    {
        $dokter = ModelsDokter::findOrFail($id_dokter);
        $this->id_dokter = $id_dokter;
        $this->nama_dokter = $dokter->nama_dokter;
        $this->id_poli = $dokter->id_poli;
        $this->spesialisasi_dokter = $dokter->spesialisasi_dokter;
        $this->no_hp_dokter = $dokter->no_hp_dokter;

        $this->openModal();
    }

    public function delete($id)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id)
    {
        if (Schema::hasTable('tb_dokter')) {
            ModelsDokter::where('id_dokter', $id)->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
