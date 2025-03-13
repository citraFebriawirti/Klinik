<?php

namespace App\Http\Livewire;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Resep;
use App\Models\ResepDetail;
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
    public $obatList = [];

    public $resepItems = [];
    public $id_obat;
    public $dosis;
    public $jumlah;
    public $aturan_pakai;
    public $is_racik = false;

    public $isOpen = false;
    public $isDetailOpen = false; // Untuk modal detail pemeriksaan
    public $selectedPemeriksaan = null; // Data pemeriksaan yang dipilih untuk dilihat

    // Properti untuk filter dan pencarian
    public $search = ''; // Untuk pencarian nama, NIK, atau ID pendaftaran
    public $statusFilter = ''; // Untuk filter status (kosong berarti semua)

    public function mount()
    {
        $this->ambilPasien();
        $this->obatList = Obat::all();
    }

    public function ambilPasien()
    {
        $query = Pendaftaran::with('pasien', 'poli', 'pemeriksaan.resep.details.obat');

        // Pencarian berdasarkan nama, NIK, atau ID pendaftaran
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id_pendaftaran', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pasien', function ($q) {
                        $q->where('nama_pasien', 'like', '%' . $this->search . '%')
                            ->orWhere('nik_pasien', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($this->statusFilter) {
            $query->where('status_pendaftaran', $this->statusFilter);
        }

        $this->daftarPasien = $query->get();
    }

    // Update daftar pasien ketika pencarian atau filter berubah
    public function updatedSearch()
    {
        $this->ambilPasien();
    }

    public function updatedStatusFilter()
    {
        $this->ambilPasien();
    }

    public function pilihPasien($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        if ($pendaftaran->status_pendaftaran !== 'Menunggu') {
            $this->emit('showAlert', [
                'title' => 'Tidak Dapat Memeriksa!',
                'text' => 'Pasien ini sudah selesai diperiksa.',
                'icon' => 'warning'
            ]);
            return;
        }

        $this->id_pendaftaran = $pendaftaran->id_pendaftaran;
        $this->dokterList = Dokter::where('id_poli', $pendaftaran->id_poli)->get();

        $this->resetFields();
        $this->openModal();
    }

    public function lihatDetail($id_pendaftaran)
    {
        $pendaftaran = Pendaftaran::with('pemeriksaan.resep.details.obat')->findOrFail($id_pendaftaran);
        $this->selectedPemeriksaan = $pendaftaran->pemeriksaan;
        $this->isDetailOpen = true;
        $this->emit('openDetailModal');
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('openModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->isDetailOpen = false;
        $this->emit('closeModal');
    }

    public function resetFields()
    {
        $this->id_dokter = null;
        $this->diagnosa = '';
        $this->catatan = '';
        $this->resepItems = [];
        $this->id_obat = null;
        $this->dosis = '';
        $this->jumlah = '';
        $this->aturan_pakai = '';
        $this->is_racik = false;
        $this->selectedPemeriksaan = null;
    }

    public function tambahItemResep()
    {
        $this->validate([
            'id_obat' => 'required|exists:tb_obat,id_obat',
            'dosis' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'aturan_pakai' => 'required|string|max:255',
        ]);

        $obat = Obat::find($this->id_obat);
        $this->resepItems[] = [
            'id_obat' => $this->id_obat,
            'nama_obat' => $obat->nama_obat,
            'dosis' => $this->dosis,
            'jumlah' => $this->jumlah,
            'aturan_pakai' => $this->aturan_pakai,
            'is_racik' => $this->is_racik,
        ];

        $this->id_obat = null;
        $this->dosis = '';
        $this->jumlah = '';
        $this->aturan_pakai = '';
    }

    public function hapusItemResep($index)
    {
        unset($this->resepItems[$index]);
        $this->resepItems = array_values($this->resepItems);
    }

    public function simpanPemeriksaan()
    {
        $this->validate([
            'id_dokter' => 'required|exists:tb_dokter,id_dokter',
            'diagnosa' => 'required|max:255',
            'catatan' => 'nullable|max:500',
        ]);

        $pemeriksaan = Pemeriksaan::create([
            'id_pendaftaran' => $this->id_pendaftaran,
            'id_dokter' => $this->id_dokter,
            'diagnosa_pemeriksaan' => $this->diagnosa,
            'catatan_pemeriksaan' => $this->catatan,
            'tanggal_periksa_pemeriksaan' => now(),
        ]);

        Pendaftaran::where('id_pendaftaran', $this->id_pendaftaran)
            ->update(['status_pendaftaran' => 'Selesai']);

        if (!empty($this->resepItems)) {
            $resep = Resep::create([
                'id_pemeriksaan' => $pemeriksaan->id_pemeriksaan,
                'status_resep' => 'Menunggu',
                'total_harga_resep' => 0,
            ]);

            $totalHarga = 0;
            $namaRacik = $this->is_racik ? "RACK-" . str_pad($resep->id_resep, 2, '0', STR_PAD_LEFT) : null;
            foreach ($this->resepItems as $item) {
                $obat = Obat::find($item['id_obat']);
                $subtotal = $obat->harga_obat * $item['jumlah'];
                $totalHarga += $subtotal;

                ResepDetail::create([
                    'id_resep' => $resep->id_resep,
                    'id_obat' => $item['id_obat'],
                    'is_racik' => $item['is_racik'],
                    'nama_racik' => $item['is_racik'] ? $namaRacik : null,
                    'dosis_resep_detail' => $item['dosis'],
                    'jumlah_resep_detail' => $item['jumlah'],
                    'aturan_pakai_resep_detail' => $item['aturan_pakai'],
                ]);
            }

            $resep->update(['total_harga_resep' => $totalHarga]);
        }

        $this->closeModal();
        $this->resetFields();
        $this->ambilPasien();

        $this->emit('showAlert', [
            'title' => 'Pemeriksaan Berhasil!',
            'text' => 'Data pemeriksaan dan resep telah disimpan.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.pemeriksaan-pasien');
    }
}
