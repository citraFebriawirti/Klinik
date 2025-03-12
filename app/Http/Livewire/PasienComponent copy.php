<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pasien;


class PasienComponent extends Component
{
    public $id_pasien;
    public $nama_pasien, $tempatlahir_pasien, $tanggallahir_pasien, $jeniskelamin_pasien, $alamat_pasien, $nomorhp_pasien;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function render()
    {
        return view('livewire.pasien-component', [
            'pasien' => Pasien::all()
        ]);
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
        $this->id_pasien = null;
        $this->nama_pasien = '';
        $this->tempatlahir_pasien = '';
        $this->tanggallahir_pasien = '';
        $this->jeniskelamin_pasien = '';
        $this->alamat_pasien = '';
        $this->nomorhp_pasien = '';
    }

    public function store()
    {
        $this->validate([
            'nama_pasien' => 'required|string|max:255',
            'tempatlahir_pasien' => 'required|string|max:255',
            'tanggallahir_pasien' => 'required|date',
            'jeniskelamin_pasien' => 'required|in:Laki-laki,Perempuan',
            'alamat_pasien' => 'required|string',
            'nomorhp_pasien' => 'required|numeric|digits_between:10,15',
        ]);

        if ($this->id_pasien) {
            // Update data jika id_pasien ada
            $pasien = Pasien::where('id_pasien', $this->id_pasien)->first();
            if ($pasien) {
                $pasien->update([
                    'nama_pasien' => $this->nama_pasien,
                    'tempatlahir_pasien' => $this->tempatlahir_pasien,
                    'tanggallahir_pasien' => $this->tanggallahir_pasien,
                    'jeniskelamin_pasien' => $this->jeniskelamin_pasien,
                    'alamat_pasien' => $this->alamat_pasien,
                    'nomorhp_pasien' => $this->nomorhp_pasien,
                ]);
            }
        } else {
            // Insert data jika id_pasien null
            Pasien::create([
                'id_pasien' => Pasien::GenerateID(),
                'nama_pasien' => $this->nama_pasien,
                'tempatlahir_pasien' => $this->tempatlahir_pasien,
                'tanggallahir_pasien' => $this->tanggallahir_pasien,
                'jeniskelamin_pasien' => $this->jeniskelamin_pasien,
                'alamat_pasien' => $this->alamat_pasien,
                'nomorhp_pasien' => $this->nomorhp_pasien,
            ]);
        }

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_pasien ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $pasien = Pasien::where('id_pasien', $id)->first();

        if (!$pasien) {
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Gagal!',
                'text' => 'Data pasien tidak ditemukan',
                'icon' => 'error'
            ]);
            return;
        }

        $this->id_pasien = $pasien->id_pasien;
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
        // Cari data berdasarkan id_pasien
        $pasien = Pasien::where('id_pasien', $id)->first();

        $pasien = Pasien::where('id_pasien', $id)->first();

        if (!$pasien) {
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Gagal!',
                'text' => 'Data pasien tidak ditemukan',
                'icon' => 'error'
            ]);
            return;
        }

        // Hapus data
        $pasien->delete();

        // Kirim notifikasi sukses
        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil dihapus',
            'icon' => 'success'
        ]);
    }
}