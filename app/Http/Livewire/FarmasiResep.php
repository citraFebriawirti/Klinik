<?php

namespace App\Http\Livewire;

use App\Models\Resep;
use App\Models\ResepDetail;
use App\Models\Obat;
use App\Models\XPengambilanObat;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FarmasiResep extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false; // Untuk modal struk
    public $isEditOpen = false; // Untuk modal edit
    public $statusFilter = '';
    public $selectedResepId = null;
    public $editResepId = null;
    public $editDetails = [];
    public $obatList = [];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'closeModal' => 'closeModal',
        'closeEditModal' => 'closeEditModal',
    ];

    public function mount()
    {
        $this->resetPage();
        $this->obatList = Obat::all()->toArray(); // Ambil daftar obat untuk dropdown
    }

    private function getResepQuery() // Bisa diubah menjadi getResepQuery() untuk camelCase
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
        $this->emit('openModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->emit('closeModal');
    }

    public function openEditModal($id)
    {
        $this->editResepId = $id;
        $resep = Resep::with('details.obat')->findOrFail($id);
        $this->editDetails = $resep->details->map(function ($detail) {
            return [
                'id' => $detail->id_resep_detail,
                'id_obat' => $detail->id_obat,
                'nama_obat' => $detail->obat->nama_obat,
                'jumlah' => $detail->jumlah_resep_detail,
                'dosis' => $detail->dosis_resep_detail,
                'aturan_pakai' => $detail->aturan_pakai_resep_detail,
                'is_racik' => $detail->is_racik ? true : false,
                'nama_racik' => $detail->nama_racik,
            ];
        })->toArray();
        $this->isEditOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditOpen = false;
        $this->editResepId = null;
        $this->editDetails = [];
    }

    public function updateDetail($index, $field, $value)
    {
        $this->editDetails[$index][$field] = $value;

        // Jika is_racik diubah menjadi false, hapus nama_racik
        if ($field === 'is_racik' && !$value) {
            $this->editDetails[$index]['nama_racik'] = null;
        }
    }

    public function addDetail()
    {
        $this->editDetails[] = [
            'id' => null,
            'id_obat' => '',
            'nama_obat' => '',
            'jumlah' => 1,
            'dosis' => '',
            'aturan_pakai' => '',
            'is_racik' => false,
            'nama_racik' => '',
        ];
    }

    public function removeDetail($index)
    {
        unset($this->editDetails[$index]);
        $this->editDetails = array_values($this->editDetails);
    }

    public function simpanEditResep()
    {
        // Validasi dinamis berdasarkan is_racik
        $rules = [];
        foreach ($this->editDetails as $index => $detail) {
            $rules["editDetails.{$index}.id_obat"] = 'required|exists:tb_obat,id_obat';
            $rules["editDetails.{$index}.jumlah"] = 'required|integer|min:1';
            $rules["editDetails.{$index}.dosis"] = 'required|string|max:50';
            $rules["editDetails.{$index}.aturan_pakai"] = 'required|string|max:255';
            $rules["editDetails.{$index}.is_racik"] = 'required|boolean';
            $rules["editDetails.{$index}.nama_racik"] = $detail['is_racik'] ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        $this->validate($rules);

        try {
            DB::transaction(function () {
                $resep = Resep::findOrFail($this->editResepId);

                // Hapus detail resep yang ada
                ResepDetail::where('id_resep', $this->editResepId)->delete();

                // Kelompokkan dan jumlahkan obat yang sama dalam kelompok yang sama
                $groupedDetails = [];
                foreach ($this->editDetails as $detail) {
                    $key = $detail['is_racik'] ? ($detail['nama_racik'] ?? 'noname') : 'non-racik';
                    $obatKey = $detail['id_obat'] . '|' . $key;

                    if (!isset($groupedDetails[$obatKey])) {
                        $groupedDetails[$obatKey] = [
                            'id_obat' => $detail['id_obat'],
                            'jumlah' => 0,
                            'dosis' => $detail['dosis'],
                            'aturan_pakai' => $detail['aturan_pakai'],
                            'is_racik' => $detail['is_racik'],
                            'nama_racik' => $detail['is_racik'] ? $detail['nama_racik'] : null,
                        ];
                    }
                    $groupedDetails[$obatKey]['jumlah'] += $detail['jumlah'];
                }

                // Simpan detail resep yang telah dikelompokkan
                $totalHarga = 0;
                foreach ($groupedDetails as $detail) {
                    $obat = Obat::find($detail['id_obat']);
                    $subtotal = $obat->harga_obat * $detail['jumlah'];
                    $totalHarga += $subtotal;

                    ResepDetail::create([
                        'id_resep' => $this->editResepId,
                        'id_obat' => $detail['id_obat'],
                        'is_racik' => $detail['is_racik'] ? 1 : 0,
                        'nama_racik' => $detail['nama_racik'],
                        'dosis_resep_detail' => $detail['dosis'],
                        'jumlah_resep_detail' => $detail['jumlah'],
                        'aturan_pakai_resep_detail' => $detail['aturan_pakai'],
                    ]);
                }

                // Update total harga resep
                $resep->update(['total_harga_resep' => $totalHarga]);
            });

            $this->emit('showAlert', ['title' => 'Berhasil!', 'text' => 'Resep telah diperbarui.', 'icon' => 'success']);
            $this->closeEditModal();
        } catch (\Exception $e) {
            $this->emit('showAlert', ['title' => 'Gagal!', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function prosesResep($id)
    {
        try {
            $resep = Resep::findOrFail($id);

            if ($resep->status_resep !== 'Menunggu') {
                throw new \Exception('Status resep tidak valid untuk diproses.');
            }

            $resep->update(['status_resep' => 'Diproses']);

            $this->emit('showAlert', [
                'title' => 'Berhasil!',
                'text' => 'Resep telah diproses dan siap untuk pembayaran.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->emit('showAlert', [
                'title' => 'Gagal!',
                'text' => $e->getMessage() ?: 'Terjadi kesalahan saat memproses resep.',
                'icon' => 'error'
            ]);
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
                throw new \Exception('Pembayaran belum lunas, obat belum bisa diselesaikan.');
            }

            DB::transaction(function () use ($id, $resep) {
                $resep->update(['status_resep' => 'Selesai']);

                XPengambilanObat::updateOrCreate( // Perbaiki 'xPengambilanObat'
                    ['id_resep' => $id],
                    [
                        'status_pengambilan_obat' => 'Diambil',
                        'tanggal_ambil_pengambilan_obat' => now(),
                    ]
                );
            });

            $this->emit('showAlert', [
                'title' => 'Berhasil!',
                'text' => 'Resep telah selesai dan obat telah diambil pasien.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->emit('showAlert', [
                'title' => 'Gagal!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function showStruk($id)
    {
        $this->selectedResepId = $id;
        $this->openModal();
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
