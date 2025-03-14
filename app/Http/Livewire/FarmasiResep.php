<?php

namespace App\Http\Livewire;

use App\Models\Resep;
use App\Models\XPengambilanObat;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FarmasiResep extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false; // Ubah ke boolean untuk kontrol modal
    public $statusFilter = '';
    public $selectedResepId = null;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'closeModal' => 'closeModal' // Pastikan listener ini ada
    ];

    public function mount()
    {
        $this->resetPage();
    }

    private function getResepQuery()
    {
        $query = Resep::with(['pemeriksaan.pendaftaran.pasien', 'details.obat', 'transaksi', 'pengambilanObat'])
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter) {
            $query->where('status_resep', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('pemeriksaan.pendaftaran.pasien', function ($q) {
                $q->where('nama_pasien', 'like', '%' . $this->search . '%');
            });
        }

        return $query;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->selectedResepId = null;
    }

    public function prosesResep($id)
    {
        try {
            $resep = Resep::findOrFail($id);
            if ($resep->status_resep !== 'Menunggu') {
                throw new \Exception('Status resep tidak valid untuk diproses.');
            }
            $resep->update(['status_resep' => 'Diproses']);
            $this->emit('showAlert', ['title' => 'Berhasil!', 'text' => 'Resep telah diproses.', 'icon' => 'success']);
        } catch (\Exception $e) {
            $this->emit('showAlert', ['title' => 'Gagal!', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function selesaikanResep($id)
    {
        try {
            $resep = Resep::with('transaksi')->findOrFail($id);
            if ($resep->status_resep !== 'Diproses') {
                throw new \Exception('Resep belum diproses.');
            }
            if (!$resep->transaksi || $resep->transaksi->status_transaksi !== 'Lunas') {
                throw new \Exception('Pembayaran belum lunas.');
            }
            DB::transaction(function () use ($id, $resep) {
                $resep->update(['status_resep' => 'Selesai']);
                XPengambilanObat::updateOrCreate(
                    ['id_resep' => $id],
                    ['status_pengambilan_obat' => 'Diambil', 'tanggal_ambil_pengambilan_obat' => now()]
                );
            });
            $this->emit('showAlert', ['title' => 'Berhasil!', 'text' => 'Resep telah selesai.', 'icon' => 'success']);
        } catch (\Exception $e) {
            $this->emit('showAlert', ['title' => 'Gagal!', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function showStruk($id)
    {
        $this->selectedResepId = $id;
        $this->openModal(); // Buka modal saat tombol diklik
    }

    public function render()
    {
        return view('livewire.farmasi-resep', [
            'resepList' => $this->getResepQuery()->paginate(10),
            'selectedResep' => $this->selectedResepId
                ? Resep::with(['details.obat', 'pemeriksaan.pendaftaran.pasien'])->findOrFail($this->selectedResepId)
                : null,
        ]);
    }
}
