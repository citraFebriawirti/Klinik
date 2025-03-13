<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pasien as ModelsPasien;
use Illuminate\Support\Facades\Schema;

class Pasien extends Component
{
    public $pasien, $nik_pasien, $nama_pasien, $tanggallahir_pasien,
        $jenis_kelamin_pasien, $alamat_pasien, $no_hp_pasien, $id_pasien, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy']; // Menangani event hapus

    public function mount($id_pasien = null) // Mengubah parameter menjadi id_pasien
    {
        $this->id = $id_pasien;
    }

    public function render()
    {
        $query = ModelsPasien::query(); // Query hanya untuk tabel tb_pasien

        // Pencarian berdasarkan semua field pasien
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nik_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nama_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('tanggallahir_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('jenis_kelamin_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('alamat_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('no_hp_pasien', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->pasien = $query->get(); // Mengambil data pasien sesuai filter

        return view('livewire.pasien', [
            'pasien' => $this->pasien
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
        $this->nik_pasien = '';
        $this->nama_pasien = '';
        $this->tanggallahir_pasien = '';
        $this->jenis_kelamin_pasien = '';
        $this->alamat_pasien = '';
        $this->no_hp_pasien = '';
        $this->id_pasien = null;
    }

    public function store()
    {
        $this->validate([
            'nik_pasien' => 'required|unique:tb_pasien,nik_pasien,' . ($this->id_pasien ?? 'NULL') . ',id_pasien',
            'nama_pasien' => 'required',
            'tanggallahir_pasien' => 'required|date',
            'jenis_kelamin_pasien' => 'required|in:L,P',
            'alamat_pasien' => 'required',
            'no_hp_pasien' => 'required|max:15',
        ]);

        ModelsPasien::updateOrCreate(['id_pasien' => $this->id_pasien], [
            'nik_pasien' => $this->nik_pasien,
            'nama_pasien' => $this->nama_pasien,
            'tanggallahir_pasien' => $this->tanggallahir_pasien,
            'jenis_kelamin_pasien' => $this->jenis_kelamin_pasien,
            'alamat_pasien' => $this->alamat_pasien,
            'no_hp_pasien' => $this->no_hp_pasien,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_pasien ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id_pasien) // Mengubah parameter menjadi id_pasien
    {
        $pasien = ModelsPasien::where('id_pasien', $id_pasien)->firstOrFail();
        $this->id_pasien = $id_pasien;
        $this->nik_pasien = $pasien->nik_pasien;
        $this->nama_pasien = $pasien->nama_pasien;
        $this->tanggallahir_pasien = $pasien->tanggallahir_pasien;
        $this->jenis_kelamin_pasien = $pasien->jenis_kelamin_pasien;
        $this->alamat_pasien = $pasien->alamat_pasien;
        $this->no_hp_pasien = $pasien->no_hp_pasien;

        $this->openModal();
    }

    public function delete($id) // Mengubah parameter menjadi id_pasien
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id) // Mengubah parameter menjadi id_pasien
    {
        if (Schema::hasTable('tb_pasien')) {
            ModelsPasien::where('id_pasien', $id)->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
