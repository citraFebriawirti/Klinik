<?php

namespace App\Http\Livewire;

use App\Models\Dokter;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\ResepDetail;
use Livewire\Component;
use Livewire\WithPagination;

class PemeriksaanPasien extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Properti untuk data pasien dan pemeriksaan
    public $id_pendaftaran;
    public $id_dokter;
    public $diagnosa;
    public $catatan;
    protected $daftarPasien;
    public $dokterList = [];
    public $obatList = [];
    public $filteredObatList = []; // Daftar obat yang difilter berdasarkan pilihan

    // Properti untuk resep
    public $resepItems = [];
    public $id_obat;
    public $dosis;
    public $jumlah;
    public $aturan_pakai;
    public $is_racik = false;
    public $nama_racik;
    public $nama_racik_aktif;

    // Properti untuk kontrol UI
    public $isOpen = false;
    public $isDetailOpen = false;
    public $selectedPemeriksaan = null;
    public $search = '';
    public $statusFilter = '';

    public function mount()
    {
        $this->obatList = Obat::all()->toArray();
        $this->filteredObatList = $this->obatList; // Inisialisasi daftar obat yang difilter
        $this->ambilPasien();
    }

    public function ambilPasien()
    {
        $query = Pendaftaran::with('pasien', 'poli', 'pemeriksaan.resep.details.obat');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id_pendaftaran', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pasien', function ($q) {
                        $q->where('nama_pasien', 'like', '%' . $this->search . '%')
                            ->orWhere('nik_pasien', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('status_pendaftaran', $this->statusFilter);
        }

        $this->daftarPasien = $query->orderBy('created_at', 'desc')->paginate(5);
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->ambilPasien();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
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
        $this->nama_racik = '';
        $this->nama_racik_aktif = null;
        $this->selectedPemeriksaan = null;
        $this->filteredObatList = $this->obatList; // Reset filteredObatList saat reset
    }

    public function updatedIsRacik()
    {
        $this->filterObatList();
    }

    public function updatedNamaRacikAktif()
    {
        $this->filterObatList();
    }

    public function filterObatList()
    {
        $usedObatIds = [];

        // Ambil ID obat yang sudah digunakan dalam kelompok aktif saat ini
        foreach ($this->resepItems as $item) {
            if ($this->is_racik && $this->nama_racik_aktif && $item['nama_racik'] === $this->nama_racik_aktif) {
                // Hanya masukkan obat dari racikan aktif saat mode racik dan nama_racik_aktif ada
                $usedObatIds[] = $item['id_obat'];
            } elseif (!$this->is_racik && !$item['nama_racik']) {
                // Hanya masukkan obat non-racik saat mode non-racik
                $usedObatIds[] = $item['id_obat'];
            }
        }

        // Filter daftar obat
        $this->filteredObatList = array_filter($this->obatList, function ($obat) use ($usedObatIds) {
            return !in_array($obat['id_obat'], $usedObatIds);
        });

        // Reset array keys
        $this->filteredObatList = array_values($this->filteredObatList);
    }

    public function tambahItemResep()
    {
        $this->validate([
            'id_obat' => 'required|exists:tb_obat,id_obat',
            'dosis' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'aturan_pakai' => 'required|string|max:255',
            'nama_racik' => $this->is_racik && !$this->nama_racik_aktif ? 'required|string|max:255' : 'nullable',
        ]);

        $obat = Obat::find($this->id_obat);

        // Jika racikan dan belum ada nama aktif, set nama_racik_aktif
        if ($this->is_racik && !$this->nama_racik_aktif) {
            $this->nama_racik_aktif = $this->nama_racik;
        }

        $newItem = [
            'id_obat' => $this->id_obat,
            'nama_obat' => $obat->nama_obat,
            'dosis' => $this->dosis,
            'jumlah' => $this->jumlah,
            'aturan_pakai' => $this->aturan_pakai,
            'nama_racik' => $this->is_racik ? $this->nama_racik_aktif : null,
        ];

        // Cari apakah ada item dengan id_obat yang sama dalam kelompok yang sama
        $existingIndex = null;
        foreach ($this->resepItems as $index => $item) {
            if ($item['id_obat'] === $this->id_obat && $item['nama_racik'] === $newItem['nama_racik']) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Jika ada duplikat, jumlahkan jumlah
            $this->resepItems[$existingIndex]['jumlah'] += $this->jumlah;
        } else {
            // Jika tidak ada duplikat, tambahkan item baru
            $this->resepItems[] = $newItem;
        }

        // Reset input kecuali nama_racik_aktif jika racikan
        $this->reset(['id_obat', 'dosis', 'jumlah', 'aturan_pakai']);
        if (!$this->is_racik) {
            $this->nama_racik = '';
        }

        // Update daftar obat yang tersedia
        $this->filterObatList();
    }

    public function tambahLagiRacikan($namaRacik)
    {
        $this->nama_racik_aktif = $namaRacik;
        $this->is_racik = true;
        $this->filterObatList();
    }

    public function resetNamaRacik()
    {
        $this->nama_racik_aktif = null;
        $this->nama_racik = '';
        $this->filterObatList();
    }

    public function hapusItemResep($index)
    {
        unset($this->resepItems[$index]);
        $this->resepItems = array_values($this->resepItems);

        // Jika tidak ada item dengan nama_racik_aktif lagi, reset nama_racik_aktif
        $racikExists = collect($this->resepItems)->contains('nama_racik', $this->nama_racik_aktif);
        if (!$racikExists) {
            $this->resetNamaRacik();
        }

        // Update daftar obat yang tersedia setelah penghapusan
        $this->filterObatList();
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
            foreach ($this->resepItems as $item) {
                $obat = Obat::find($item['id_obat']);
                $subtotal = $obat->harga_obat * $item['jumlah'];
                $totalHarga += $subtotal;

                ResepDetail::create([
                    'id_resep' => $resep->id_resep,
                    'id_obat' => $item['id_obat'],
                    'is_racik' => $item['nama_racik'] ? 1 : 0,
                    'nama_racik' => $item['nama_racik'],
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
        $this->ambilPasien();
        return view('livewire.pemeriksaan-pasien', [
            'daftarPasien' => $this->daftarPasien,
        ]);
    }
}
