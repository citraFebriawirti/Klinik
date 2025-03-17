<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Obat as ModelsObat;
use Illuminate\Support\Facades\DB;

class Obat extends Component
{
    public $obat, $id_obat, $nama_obat, $jenis_obat, $satuan_obat, $stok_obat, $harga_obat, $searchTerm;
    public $isOpen = 0;
    protected $listeners = ['destroy', 'kurangiStokObat'];

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
        return view('livewire.obat', ['obat' => $this->obat]);
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
            'stok_obat' => 'required|integer|min:0',
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
        $obat = ModelsObat::findOrFail($id_obat);
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
        ModelsObat::where('id_obat', $id_obat)->delete();
        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil dihapus',
            'icon' => 'success'
        ]);
    }

    public function kurangiStokObat($resepDetails)
    {
        try {
            if (empty($resepDetails) || !is_array($resepDetails)) {
                throw new \Exception("Detail resep tidak valid!");
            }

            $stokErrors = [];
            foreach ($resepDetails as $detail) {
                $obat = ModelsObat::find($detail['id_obat']);
                if (!$obat) {
                    throw new \Exception("Obat dengan ID {$detail['id_obat']} tidak ditemukan!");
                }
                $jumlahDiminta = (int) $detail['jumlah_resep_detail'];
                if ($obat->stok_obat < $jumlahDiminta) {
                    $stokErrors[] = "Stok {$obat->nama_obat} tidak cukup. Tersedia: {$obat->stok_obat}, diminta: {$jumlahDiminta}.";
                }
            }

            if (!empty($stokErrors)) {
                $this->emitTo('farmasi-resep', 'stokTidakCukup', [
                    'message' => implode(' ', $stokErrors),
                    'resep_id' => $resepDetails[0]['id_resep'] ?? null
                ]);
                return;
            }

            DB::transaction(function () use ($resepDetails) {
                foreach ($resepDetails as $detail) {
                    $obat = ModelsObat::findOrFail($detail['id_obat']);
                    $jumlahDiminta = (int) $detail['jumlah_resep_detail'];
                    $obat->stok_obat -= $jumlahDiminta;
                    $obat->save();
                }
            });

            $this->emitTo('farmasi-resep', 'stokUpdated', true);
        } catch (\Exception $e) {
            $this->emitTo('farmasi-resep', 'stokUpdated', false);
            $this->dispatchBrowserEvent('alert', [
                'title' => 'Gagal!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
}
