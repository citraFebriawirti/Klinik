<?php

namespace App\Http\Livewire;

use App\Models\Resep;
use App\Models\xPengambilanObat;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PengambilanObat extends Component
{
    use WithPagination;

    public $pengambilanList;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap untuk pagination

    public function mount()
    {
        $this->ambilPengambilan();
    }

    public function ambilPengambilan()
    {
        $this->pengambilanList = Resep::select('tb_resep.*')
            ->where('status_resep', 'Selesai')
            ->leftJoin('pengambilan_obat', 'tb_resep.id_resep', '=', 'pengambilan_obat.id_resep')
            ->whereNull('pengambilan_obat.id_resep')
            ->paginate(10);

        // Muat relasi secara manual jika diperlukan
        $this->pengambilanList->load('pemeriksaan.pendaftaran.pasien');
    }

    public function serahkanObat($id_resep)
    {
        xPengambilanObat::create([
            'id_resep' => $id_resep,
            'status_pengambilan_obat' => 'Diambil',
            'tanggal_ambil_pengambilan_obat' => now(),
        ]);

        $this->ambilPengambilan();
        $this->emit('showAlert', [
            'title' => 'Obat Diserahkan!',
            'text' => 'Obat telah diberikan kepada pasien.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('pengambilan-obat.index');
    }
}
