<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pasien as ModelsPasien;
use Illuminate\Support\Facades\Schema;

class Pasien extends Component
{
    public $pasien, $nik_pasien, $nama_pasien, $tempatlahir_pasien, $tanggallahir_pasien,
        $jeniskelamin_pasien, $alamat_pasien, $nomorhp_pasien, $pasien_id, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function render()

    {
        $query = ModelsPasien::query(); // Query hanya untuk tabel pasien

        // Pencarian berdasarkan semua field pasien
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nik_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nama_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('tempatlahir_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('tanggallahir_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('jeniskelamin_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('alamat_pasien', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nomorhp_pasien', 'like', '%' . $this->searchTerm . '%');
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
        $this->tempatlahir_pasien = '';
        $this->tanggallahir_pasien = '';
        $this->jeniskelamin_pasien = '';
        $this->alamat_pasien = '';
        $this->nomorhp_pasien = '';
        $this->pasien_id = null;
    }

    public function store()
    {
        $this->validate([
            'nik_pasien' => 'required|unique:pasien,nik_pasien,' . ($this->pasien_id ?? 'NULL'),
            'nama_pasien' => 'required',
            'tempatlahir_pasien' => 'required',
            'tanggallahir_pasien' => 'required|date',
            'jeniskelamin_pasien' => 'required|in:Laki-laki,Perempuan',
            'alamat_pasien' => 'required',
            'nomorhp_pasien' => 'required|max:15',
        ]);

        ModelsPasien::updateOrCreate(['id' => $this->pasien_id], [
            'nik_pasien' => $this->nik_pasien,
            'nama_pasien' => $this->nama_pasien,
            'tempatlahir_pasien' => $this->tempatlahir_pasien,
            'tanggallahir_pasien' => $this->tanggallahir_pasien,
            'jeniskelamin_pasien' => $this->jeniskelamin_pasien,
            'alamat_pasien' => $this->alamat_pasien,
            'nomorhp_pasien' => $this->nomorhp_pasien,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->pasien_id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    /**
     * Melakukan pengeditan data pasien berdasarkan ID yang diberikan
     *
     * @param int $id ID pasien yang akan diedit
     * @return void
     */
    public function edit($id)
    {
        $pasien = ModelsPasien::findOrFail($id);
        $this->pasien_id = $id;
        $this->nik_pasien = $pasien->nik_pasien;
        $this->nama_pasien = $pasien->nama_pasien;
        $this->tempatlahir_pasien = $pasien->tempatlahir_pasien;
        $this->tanggallahir_pasien = $pasien->tanggallahir_pasien;
        $this->jeniskelamin_pasien = $pasien->jeniskelamin_pasien;
        $this->alamat_pasien = $pasien->alamat_pasien;
        $this->nomorhp_pasien = $pasien->nomorhp_pasien;

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
        if (Schema::hasTable('pasien')) {
            ModelsPasien::find($id)?->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }
}
