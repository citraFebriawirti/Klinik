<?php

namespace App\Http\Livewire;

use App\Models\Resep;
use App\Models\xPengambilanObat;
use Livewire\Component;
use Livewire\WithPagination;

class FarmasiResep extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedResepId = null;
    protected $resepList;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->ambilResep();
    }

    public function ambilResep()
    {
        $query = Resep::with(['pemeriksaan.pendaftaran.pasien', 'details.obat', 'transaksi', 'pengambilanObat']);

        if ($this->statusFilter) {
            $query->where('status_resep', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('pemeriksaan.pendaftaran.pasien', function ($q) {
                $q->where('nama_pasien', 'like', '%' . $this->search . '%');
            });
        }

        $this->resepList = $query->paginate(10);
    }


    public function updatedSearch()
    {
        $this->resetPage();
        $this->ambilResep();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->ambilResep();
    }

    public function prosesResep($id)
    {
        $resep = Resep::findOrFail($id);
        if ($resep->status_resep === 'Menunggu') {
            $resep->update(['status_resep' => 'Diproses']);
            $this->emit('showAlert', [
                'title' => 'Berhasil!',
                'text' => 'Resep telah diproses dan siap untuk pembayaran.',
                'icon' => 'success'
            ]);
        }
        $this->ambilResep();
    }

    public function selesaikanResep($id)
    {
        $resep = Resep::findOrFail($id);
        if ($resep->status_resep === 'Diproses' && $resep->transaksi && $resep->transaksi->status_transaksi === 'Lunas') {
            $resep->update(['status_resep' => 'Selesai']);
            xPengambilanObat::where('id_resep', $id)->update([
                'status_pengambilan_obat' => 'Diambil',
                'tanggal_ambil_pengambilan_obat' => now(),
            ]);
            $this->emit('showAlert', [
                'title' => 'Berhasil!',
                'text' => 'Resep telah selesai dan obat telah diambil pasien.',
                'icon' => 'success'
            ]);
        } else {
            $this->emit('showAlert', [
                'title' => 'Gagal!',
                'text' => 'Pembayaran belum lunas, obat belum bisa diselesaikan.',
                'icon' => 'error'
            ]);
        }
        $this->ambilResep();
    }

    public function showStruk($id)
    {
        $this->selectedResepId = $id; // Set ID resep yang dipilih untuk ditampilkan di modal
    }

    public function render()
    {
        return view('livewire.farmasi-resep', [
            'resepList' => $this->resepList,
            'selectedResep' => $this->selectedResepId ? Resep::with(['details.obat', 'pemeriksaan.pendaftaran.pasien'])->find($this->selectedResepId) : null,
        ]);
    }
}
