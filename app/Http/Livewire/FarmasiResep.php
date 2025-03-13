<?php

namespace App\Http\Livewire;

use App\Models\Obat;
use App\Models\Resep;
use Livewire\Component;

class FarmasiResep extends Component
{
    public $resepList = [];

    public function mount()
    {
        $this->ambilResep();
    }

    public function ambilResep()
    {
        $this->resepList = Resep::where('status_resep', 'Menunggu')
            ->with('pemeriksaan.pendaftaran.pasien', 'details.obat')
            ->get();
    }

    public function prosesResep($id_resep)
    {
        $resep = Resep::findOrFail($id_resep);
        foreach ($resep->details as $detail) {
            $obat = Obat::find($detail->id_obat);
            if ($obat->stok_obat >= $detail->jumlah_resep_detail) {
                $obat->stok_obat -= $detail->jumlah_resep_detail;
                $obat->save();
            } else {
                $this->emit('showAlert', [
                    'title' => 'Stok Tidak Cukup!',
                    'text' => "Stok obat {$obat->nama_obat} tidak mencukupi.",
                    'icon' => 'error'
                ]);
                return;
            }
        }

        $resep->status_resep = 'Diproses';
        $resep->save();

        $this->ambilResep();
        $this->emit('showAlert', [
            'title' => 'Resep Diproses!',
            'text' => 'Resep telah diproses dan siap untuk pembayaran.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.farmasi-resep');
    }
}
