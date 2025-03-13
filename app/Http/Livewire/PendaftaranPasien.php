<?php

namespace App\Http\Livewire;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PendaftaranPasien extends Component
{
    use LivewireAlert;

    public $nik;
    public $nama;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $alamat;
    public $no_hp;
    public $id_poli;
    public $poli_list = [];

    public function mount()
    {
        // Ambil data poli dari database
        $this->poli_list = Poli::all();
    }

    public function updatedNik()
    {
        $this->cariPasienLama();
    }

    public function updatedTanggalLahir()
    {
        $this->cariPasienLama();
    }

    public function cariPasienLama()
    {
        if ($this->nik && $this->tanggal_lahir) {
            $pasien = Pasien::where('nik_pasien', $this->nik)
                ->where('tanggallahir_pasien', $this->tanggal_lahir)
                ->first();

            if ($pasien) {
                // Jika pasien lama ditemukan, isi data otomatis
                $this->nama = $pasien->nama_pasien;
                $this->jenis_kelamin = $pasien->jenis_kelamin_pasien;
                $this->alamat = $pasien->alamat_pasien;
                $this->no_hp = $pasien->no_hp_pasien;

                // Tampilkan alert pasien lama ditemukan
                $this->dispatchBrowserEvent('alert', [
                    'title' => 'Pasien Lama Ditemukan',
                    'text' => 'Data pasien telah diisi otomatis.',
                    'icon' => 'info'
                ]);
            }
        }
    }

    public function simpanPendaftaran()
    {
        $this->validate([
            'nik' => 'required|min:16|max:16',
            'nama' => 'required|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|max:500',
            'no_hp' => 'nullable|max:15',
            'id_poli' => 'required|exists:tb_poli,id_poli',
        ]);

        // Cari apakah pasien sudah terdaftar
        $pasien = Pasien::firstOrCreate(
            [
                'nik_pasien' => $this->nik,
                'tanggallahir_pasien' => $this->tanggal_lahir,
            ],
            [
                'nama_pasien' => $this->nama,
                'jenis_kelamin_pasien' => $this->jenis_kelamin,
                'alamat_pasien' => $this->alamat,
                'no_hp_pasien' => $this->no_hp,
            ]
        );

        // Simpan data pendaftaran
        Pendaftaran::create([
            'id_pasien' => $pasien->id_pasien,
            'id_poli' => $this->id_poli,
            'status_pendaftaran' => 'Menunggu',
        ]);

        // Ambil nama poli berdasarkan id_poli
        $namaPoli = Poli::find($this->id_poli)->nama_poli;

        // Reset form
        $this->reset();

        // Tampilkan alert sukses dan cetak struk
        $this->dispatchBrowserEvent('alert', [
            'title' => 'Pendaftaran Berhasil!',
            'text' => 'Pendaftaran pasien berhasil!',
            'icon' => 'success'
        ]);

        // Tunggu sejenak lalu cetak struk
        $this->dispatchBrowserEvent('cetakStruk', [
            'id_pendaftaran' => Pendaftaran::latest()->first()->id_pendaftaran,
            'nik' => $pasien->nik_pasien,
            'nama' => $pasien->nama_pasien,
            'poli' => $namaPoli,
            'tanggal_daftar_pendaftaran' => now()->format('d/m/Y H:i:s'),

        ]);
    }

    public function render()
    {
        return view('livewire.pendaftaran-pasien');
    }
}
