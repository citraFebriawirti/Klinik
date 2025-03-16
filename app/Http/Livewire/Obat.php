<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Obat as ModelsObat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Obat extends Component
{
    public $obat, $id_obat, $nama_obat, $jenis_obat, $satuan_obat, $stok_obat, $harga_obat, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy', 'kurangiStokObat']; // Tambahkan listener untuk pengurangan stok

    public function mount($id_obat = null)
    {
        $this->id_obat = $id_obat;
    }

    public function render()
    {
        $query = ModelsObat::query();

        if ($this->searchTerm) {
            $query->where('nama_obat', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('jenis_obat', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('satuan_obat', 'like', '%' . $this->searchTerm . '%');
        }

        $this->obat = $query->get();

        return view('livewire.obat', [
            'obat' => $this->obat
        ]);
    }

    public function resetFilter()
    {
        $this->searchTerm = '';
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
        $this->id_obat = null;
        $this->nama_obat = '';
        $this->jenis_obat = '';
        $this->satuan_obat = '';
        $this->stok_obat = '';
        $this->harga_obat = '';
    }

    public function store()
    {
        $this->validate([
            'nama_obat' => 'required|string|max:50',
            'jenis_obat' => 'required|string|max:50',
            'satuan_obat' => 'required|string|max:50',
            'stok_obat' => 'required|integer|min:0', // Tambahkan validasi min:0 agar stok tidak negatif
            'harga_obat' => 'required|integer|min:0',
        ]);

        ModelsObat::updateOrCreate(['id_obat' => $this->id_obat], [
            'nama_obat' => $this->nama_obat,
            'jenis_obat' => $this->jenis_obat,
            'satuan_obat' => $this->satuan_obat,
            'stok_obat' => $this->stok_obat,
            'harga_obat' => $this->harga_obat,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->id_obat ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id_obat)
    {
        $obat = ModelsObat::findOrFail($id_obat); // Gunakan findOrFail untuk keamanan
        $this->id_obat = $id_obat;
        $this->nama_obat = $obat->nama_obat;
        $this->jenis_obat = $obat->jenis_obat;
        $this->satuan_obat = $obat->satuan_obat;
        $this->stok_obat = $obat->stok_obat;
        $this->harga_obat = $obat->harga_obat;

        $this->openModal();
    }

    public function delete($id_obat)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id_obat,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id_obat)
    {
        if (Schema::hasTable('obat')) {
            ModelsObat::where('id_obat', $id_obat)->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Data berhasil dihapus',
                'icon' => 'success'
            ]);
        }
    }

    // Method baru untuk mengurangi stok obat berdasarkan detail resep
    public function kurangiStokObat($resepDetails)
    {
        try {
            $stokErrors = [];
            foreach ($resepDetails as $detail) {
                $obat = ModelsObat::findOrFail($detail['id_obat']);
                $jumlahDiminta = (int) $detail['jumlah'];

                if ($obat->stok_obat < $jumlahDiminta) {
                    $stokErrors[] = "Stok {$obat->nama_obat} tidak cukup. Tersedia: {$obat->stok_obat}, diminta: {$jumlahDiminta}.";
                }
            }

            if (!empty($stokErrors)) {
                throw new \Exception(implode(' ', $stokErrors));
            }

            DB::transaction(function () use ($resepDetails) {
                foreach ($resepDetails as $detail) {
                    $obat = ModelsObat::findOrFail($detail['id_obat']);
                    $obat->stok_obat -= (int) $detail['jumlah'];
                    $obat->save();
                }
            });

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Berhasil!',
                'text' => 'Stok obat telah diperbarui.',
                'icon' => 'success'
            ]);

            // Emit event ke FarmasiResep untuk melanjutkan
            $this->emitTo('farmasi-resep', 'stokUpdated', true);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Gagal!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);

            // Emit event ke FarmasiResep untuk menghentikan
            $this->emitTo('farmasi-resep', 'stokUpdated', false);
        }
    }
}
