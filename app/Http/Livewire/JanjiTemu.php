<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JanjiTemu as ModelsJanjiTemu;
use App\Models\Dokter as ModelsDokter;
use App\Models\Pasien as ModelsPasien;

class JanjiTemu extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Untuk menggunakan Bootstrap pagination

    public $search = '';

    public $nik_pasien, $kode_dokter, $tanggalwaktu_janji_temu, $keluhan_janji_temu, $status_janji_temu, $janjiTemu_id;
    public $searchTerm, $filterDokter, $filterStatus, $filterNamaPasien;

    public $isOpen = false;
    protected $listeners = ['destroy'];

    public function render()
    {
        $query = ModelsJanjiTemu::with(['pasien', 'dokter']);

        // Filter pencarian umum
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->whereHas('pasien', function ($q) {
                    $q->where('nik_pasien', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('nama_pasien', 'like', '%' . $this->searchTerm . '%');
                })->orWhereHas('dokter', function ($q) {
                    $q->where('kode_dokter', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('nama_dokter', 'like', '%' . $this->searchTerm . '%');
                })->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('keluhan_janji_temu', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('tanggalwaktu_janji_temu', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('status_janji_temu', 'like', '%' . $this->searchTerm . '%')
                    ->paginate(5);
            });
        }

        // Filter berdasarkan dokter (harus melalui relasi dokter)
        if (!empty($this->filterDokter)) {

            $query->whereHas('dokter', function ($q) {
                $q->where('kode_dokter', $this->filterDokter);
            });
        }

        // Filter berdasarkan status janji temu

        $query->when(!empty($this->filterStatus), function ($q) {
            $q->whereIn('status_janji_temu', (array) $this->filterStatus);
        });

        // $query->when(!empty($this->filterNamaPasien), function ($q) {
        //     $q->whereHas('pasien', function ($subQuery) {
        //         $subQuery->whereIn('nama_pasien', (array) $this->filterNamaPasien);
        //     });
        // });


        // Mengambil data dengan pagination agar lebih efisien
        return view('livewire.janji-temu', [
            'janjiTemu' => $query->paginate(5), // Hindari mengambil semua data dengan all()
            'pasiens' => ModelsPasien::all(),
            'dokters' => ModelsDokter::all(),

        ]);
    }

    public function updatingSearchTerm()
    {
        $this->resetPage(); // Reset halaman ke awal saat pencarian diubah
    }

    public function updatingFilterDokter()
    {
        $this->resetPage(); // Reset halaman ke awal saat filter dokter diubah
    }

    public function updatingFilterStatus()
    {
        $this->resetPage(); // Reset halaman ke awal saat filter status diubah
    }


    public function resetFilter()
    {
        $this->searchTerm = '';
        $this->filterDokter = '';
        // $this->filterNamaPasien = '';
        $this->filterStatus = '';
        $this->resetPage();
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
        $this->nik_pasien = '';
        $this->kode_dokter = '';
        $this->tanggalwaktu_janji_temu = '';
        $this->keluhan_janji_temu = '';
        $this->status_janji_temu = 'Pending';
        $this->janjiTemu_id = null;
    }

    public function store()
    {
        $this->validate([
            'nik_pasien' => 'required|exists:pasien,id',
            'kode_dokter' => 'required|exists:dokter,id',
            'tanggalwaktu_janji_temu' => 'required|date',
            'keluhan_janji_temu' => 'required',
            'status_janji_temu' => 'required|in:Pending,Dikonfirmasi,Selesai,Batal',
        ]);

        ModelsJanjiTemu::updateOrCreate(['id' => $this->janjiTemu_id], [
            'nik_pasien' => $this->nik_pasien,
            'kode_dokter' => $this->kode_dokter,
            'tanggalwaktu_janji_temu' => $this->tanggalwaktu_janji_temu,
            'keluhan_janji_temu' => $this->keluhan_janji_temu,
            'status_janji_temu' => $this->status_janji_temu
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->janjiTemu_id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $janjiTemu = ModelsJanjiTemu::with(['pasien', 'dokter'])->findOrFail($id);
        $this->janjiTemu_id = $id;
        $this->nik_pasien = $janjiTemu->nik_pasien;
        $this->kode_dokter = $janjiTemu->kode_dokter;
        $this->tanggalwaktu_janji_temu = $janjiTemu->tanggalwaktu_janji_temu;
        $this->keluhan_janji_temu = $janjiTemu->keluhan_janji_temu;
        $this->status_janji_temu = $janjiTemu->status_janji_temu;

        $this->openModal();
    }

    public function delete($id)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id)
    {
        ModelsJanjiTemu::find($id)->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil dihapus',
            'icon' => 'success'
        ]);
    }
}
