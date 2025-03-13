<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Obat as ModelsObat;
use Illuminate\Support\Facades\Schema;

class Obat extends Component
{
    public $obat, $id_obat, $nama_obat, $jenis_obat, $satuan_obat, $stok_obat, $harga_obat, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function mount($id_obat = null)
    {
        $this->id_obat = $id_obat;
    }

    public function render()
    {
        $query = ModelsObat::query();

        if ($this->searchTerm) {
            $query->where('nama_obat', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('jenis_obat', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('satuan_obat', 'like', '%' . $this->searchTerm . '%');
        }

        $this->obat = $query->get();

        return view('livewire.obat', [
            'obat' => $this->obat
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
        $this->id_obat = null;
        $this->nama_obat = '';
        $this->jenis_obat = '';
        $this->satuan_obat = '';
        $this->stok_obat = '';
        $this->harga_obat = '';
    }

    public function store()
    {
        $this->validate([
            'nama_obat' => 'required|string|max:50',
            'jenis_obat' => 'required|string|max:50',
            'satuan_obat' => 'required|string|max:50',
            'stok_obat' => 'required|integer',
            'harga_obat' => 'required|integer',
        ]);

        ModelsObat::updateOrCreate(['id_obat' => $this->id_obat], [
            'nama_obat' => $this->nama_obat,
            'jenis_obat' => $this->jenis_obat,
            'satuan_obat' => $this->satuan_obat,
            'stok_obat' => $this->stok_obat,
            'harga_obat' => $this->harga_obat,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_obat ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id_obat)
    {
        $obat = ModelsObat::where('id_obat', $id_obat)->firstOrFail();
        $this->id_obat = $id_obat;
        $this->nama_obat = $obat->nama_obat;
        $this->jenis_obat = $obat->jenis_obat;
        $this->satuan_obat = $obat->satuan_obat;
        $this->stok_obat = $obat->stok_obat;
        $this->harga_obat = $obat->harga_obat;

        $this->openModal();
    }

    public function delete($id_obat)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id_obat,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id_obat)
    {
        if (Schema::hasTable('obat')) {
            ModelsObat::where('id_obat', $id_obat)->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
