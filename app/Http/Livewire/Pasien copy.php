<?php

namespace App\Http\Livewire;

use App\Models\Pasien as ModelsPasien;
use Livewire\Component;
use Livewire\WithPagination;

class Pasien extends Component

{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selectedId;
    public $pasien;
    public $nama_pasien, $tempatlahir_pasien, $tanggallahir_pasien, $jeniskelamin_pasien, $alamat_pasien, $nomorhp_pasien;
    public $isModalOpen = false;

    public $updateData = false;



    public function mount()
    {
        $this->pasien = ModelsPasien::all();
    }

    public function render()
    {


        // $pasien = ModelsPasien::orderBy('nama_pasien', 'asc')->paginate(10); // Ambil 10 data per halaman
        // return view('livewire.pasien', ['pasien' => $pasien]);

        return view('livewire.pasien', ['pasien' => $this->pasien]);
    }


    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'nama_pasien' => 'required|string|max:255',
            'tempatlahir_pasien' => 'required|string|max:255',
            'tanggallahir_pasien' => 'required|date',
            'jeniskelamin_pasien' => 'required|in:Laki-laki,Perempuan',
            'alamat_pasien' => 'required|string',
            'nomorhp_pasien' => 'required|numeric|digits_between:10,15',
        ]);

        ModelsPasien::create([
            'id_pasien' => ModelsPasien::GenerateID(),
            'nama_pasien' => $this->nama_pasien,
            'tempatlahir_pasien' => $this->tempatlahir_pasien,
            'tanggallahir_pasien' => $this->tanggallahir_pasien,
            'jeniskelamin_pasien' => $this->jeniskelamin_pasien,
            'alamat_pasien' => $this->alamat_pasien,
            'nomorhp_pasien' => $this->nomorhp_pasien,
        ]);

        $this->emit('swalSuccess', 'Data pasien berhasil ditambahkan!');
        $this->closeModal();
        // $this->resetPage(); // **Refresh Pagination agar data terbaru muncul**
    }


    public function edit($id)
    {
        $pasien = ModelsPasien::find($id);

        if ($pasien) {
            // Simpan ID yang sedang diedit
            $this->selectedId = $id;

            // Isi data ke dalam form
            $this->nama_pasien = $pasien->nama_pasien;
            $this->tempatlahir_pasien = $pasien->tempatlahir_pasien;
            $this->tanggallahir_pasien = $pasien->tanggallahir_pasien;
            $this->jeniskelamin_pasien = $pasien->jeniskelamin_pasien;
            $this->alamat_pasien = $pasien->alamat_pasien;
            $this->nomorhp_pasien = $pasien->nomorhp_pasien;

            $this->updateData = true;
            $this->openModal();
        } else {
            session()->flash('error', 'Data pasien tidak ditemukan.');
        }
    }

    public function update()
    {
        // Debugging: Cek apakah ID tersedia sebelum update
        if (!$this->selectedId) {
            session()->flash('error', 'ID pasien tidak ditemukan.');
            return;
        }

        $this->validate([
            'nama_pasien' => 'required|string|max:255',
            'tempatlahir_pasien' => 'required|string|max:255',
            'tanggallahir_pasien' => 'required|date',
            'jeniskelamin_pasien' => 'required|in:Laki-laki,Perempuan',
            'alamat_pasien' => 'required|string',
            'nomorhp_pasien' => 'required|numeric|digits_between:10,15',
        ]);

        // Mencari pasien berdasarkan ID yang tersimpan di properti
        $pasien = ModelsPasien::find($this->selectedId);

        if (!$pasien) {
            session()->flash('error', 'Data pasien tidak ditemukan.');
            return;
        }

        // Melakukan update data pasien
        $pasien->update([
            'nama_pasien' => $this->nama_pasien,
            'tempatlahir_pasien' => $this->tempatlahir_pasien,
            'tanggallahir_pasien' => $this->tanggallahir_pasien,
            'jeniskelamin_pasien' => $this->jeniskelamin_pasien,
            'alamat_pasien' => $this->alamat_pasien,
            'nomorhp_pasien' => $this->nomorhp_pasien,
        ]);

        // Reset setelah update
        $this->reset(['selectedId', 'nama_pasien', 'tempatlahir_pasien', 'tanggallahir_pasien', 'jeniskelamin_pasien', 'alamat_pasien', 'nomorhp_pasien']);

        $this->emit('swalSuccess', 'Data pasien berhasil diperbarui!');
        $this->closeModal();
    }





    private function resetInputFields()
    {
        $this->nama_pasien = '';
        $this->tempatlahir_pasien = '';
        $this->tanggallahir_pasien = '';
        $this->jeniskelamin_pasien = '';
        $this->alamat_pasien = '';
        $this->nomorhp_pasien = '';
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}
