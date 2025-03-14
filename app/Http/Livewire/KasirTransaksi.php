<?php

namespace App\Http\Livewire;

use App\Models\kTransaksi;
use App\Models\Resep;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\xPengambilanObat;

class KasirTransaksi extends Component
{
    use WithPagination;

    public $search = ''; // Tetap public karena digunakan di view untuk binding
    protected $transaksiList; // Ubah ke protected
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->ambilTransaksi();
    }

    public function ambilTransaksi()
    {
        $query = Resep::where('status_resep', 'Diproses')
            ->with('pemeriksaan.pendaftaran.pasien')
            ->whereDoesntHave('transaksi', function ($q) {
                $q->where('status_transaksi', 'Lunas');
            });

        if ($this->search) {
            $query->whereHas('pemeriksaan.pendaftaran.pasien', function ($q) {
                $q->where('nama_pasien', 'like', '%' . $this->search . '%');
            });
        }

        $this->transaksiList = $query->paginate(10); // Simpan sebagai paginator di properti protected
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->ambilTransaksi();
    }

    public function bayar($id_resep)
    {
        $resep = Resep::findOrFail($id_resep);
        kTransaksi::create([
            'id_resep' => $id_resep,
            'total_bayar_transaksi' => $resep->total_harga_resep,
            'status_transaksi' => 'Lunas',
            'tanggal_bayar_transaksi' => now(),
        ]);

        // Status tetap 'Diproses' karena obat belum diambil pasien di farmasi
        $this->ambilTransaksi();
        $this->emit('showAlert', [
            'title' => 'Pembayaran Berhasil!',
            'text' => 'Transaksi telah lunas, obat siap diambil di farmasi.',
            'icon' => 'success'
        ]);

        xPengambilanObat::create([
            'id_resep' => $id_resep,
            'status_pengambilan_obat' => 'Belum Diambil',
            'tanggal_ambil_pengambilan_obat' => null,
        ]);
    }

    public function render()
    {
        return view('livewire.kasir-transaksi', ['transaksiList' => $this->transaksiList]); // Pass ke view sebagai variabel
    }
}
