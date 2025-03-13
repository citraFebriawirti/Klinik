<?php

namespace App\Http\Livewire;

use App\Models\Dokter;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PemeriksaanPasien extends Component
{
    public $id_pendaftaran;
    public $id_dokter;
    public $diagnosa;
    public $catatan;
    public $daftarPasien = [];
    public $dokterList = [];

    public $isOpen = false;

    public function mount()
    {
        $this->ambilPasien();
    }

    public function ambilPasien()
    {
        $this->daftarPasien = Pendaftaran::where('status_pendaftaran', 'Menunggu')
            ->with('pasien', 'poli')
            ->get();
    }

    public function pilihPasien($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $this->id_pendaftaran = $pendaftaran->id_pendaftaran;
        $this->dokterList = Dokter::where('id_poli', $pendaftaran->id_poli)->get();

        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('openModal'); // Menggunakan emit untuk Livewire 2.x
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->emit('closeModal');
    }

    public function resetFields()
    {
        $this->id_dokter = null;
        $this->diagnosa = '';
        $this->catatan = '';
    }

    public function simpanPemeriksaan()
    {
        $this->validate([
            'id_dokter' => 'required|exists:dokters,id_dokter',
            'diagnosa' => 'required|max:255',
            'catatan' => 'nullable|max:500',
        ]);

        Pemeriksaan::create([
            'id_pendaftaran' => $this->id_pendaftaran,
            'id_dokter' => $this->id_dokter,
            'diagnosa_pemeriksaan' => $this->diagnosa,
            'catatan_pemeriksaan' => $this->catatan,
            'tanggal_periksa_pemeriksaan' => now(),
        ]);

        Pendaftaran::where('id_pendaftaran', $this->id_pendaftaran)
            ->update(['status_pendaftaran' => 'Selesai']);

        $this->closeModal();
        $this->resetFields();
        $this->ambilPasien();

        $this->emit('showAlert', [
            'title' => 'Pemeriksaan Berhasil!',
            'text' => 'Data pemeriksaan telah disimpan.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.pemeriksaan-pasien');
    }
}
