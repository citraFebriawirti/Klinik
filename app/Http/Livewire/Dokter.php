<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dokter as ModelsDokter;
use Illuminate\Support\Facades\Schema;

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Render the component.
 *
 * @return \Illuminate\View\View
 */
/******  a43230aa-57d8-4a79-b0f0-188d1b96e20e  *******/ class Dokter extends Component
{
    public $dokter, $kode_dokter, $nama_dokter, $spesialis_dokter, $nomorhp_dokter, $dokter_id, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function render()
    {
        $query = ModelsDokter::query();

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('kode_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nama_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('spesialis_dokter', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nomorhp_dokter', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->dokter = $query->get();

        return view('livewire.dokter', [
            'dokter' => $this->dokter
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
        $this->kode_dokter = '';
        $this->nama_dokter = '';
        $this->spesialis_dokter = '';
        $this->nomorhp_dokter = '';
        $this->dokter_id = null;
    }

    public function store()
    {
        $this->validate([
            'kode_dokter' => 'required|unique:dokter,kode_dokter,' . ($this->dokter_id ?? 'NULL'),
            'nama_dokter' => 'required',
            'spesialis_dokter' => 'required',
            'nomorhp_dokter' => 'required|max:15',
        ]);

        ModelsDokter::updateOrCreate(['id' => $this->dokter_id], [
            'kode_dokter' => $this->kode_dokter,
            'nama_dokter' => $this->nama_dokter,
            'spesialis_dokter' => $this->spesialis_dokter,
            'nomorhp_dokter' => $this->nomorhp_dokter,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->dokter_id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $dokter = ModelsDokter::findOrFail($id);
        $this->dokter_id = $id;
        $this->kode_dokter = $dokter->kode_dokter;
        $this->nama_dokter = $dokter->nama_dokter;
        $this->spesialis_dokter = $dokter->spesialis_dokter;
        $this->nomorhp_dokter = $dokter->nomorhp_dokter;

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
        if (Schema::hasTable('dokter')) {
            ModelsDokter::find($id)?->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
