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
    public $isOpen = false;
    public $isEditOpen = false;
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
        'stokUpdated' => 'handleStokUpdated',
        'stokTidakCukup' => 'handleStokTidakCukup'
    ];

    public function mount()
    {
        $this->resetPage();
        $this->obatList = Obat::all()->toArray();
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
                ResepDetail::where('id_resep', $this->editResepId)->delete();

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

                $resep->update(['total_harga_resep' => $totalHarga]);
            });

            $this->emit('showAlert', ['title' => 'Berhasil!', 'text' => 'Resep telah diperbarui.', 'icon' => 'success']);
            $this->closeEditModal();
            $this->emit('refreshComponent');
        } catch (\Exception $e) {
            $this->emit('showAlert', ['title' => 'Gagal!', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
    }

    public function prosesResep($id)
    {
        try {
            \Log::info("Memulai prosesResep untuk ID: {$id}");
            $resep = Resep::with('details')->findOrFail($id);

            if ($resep->status_resep !== 'Menunggu') {
                throw new \Exception('Status resep tidak valid untuk diproses.');
            }

            $resepDetails = $resep->details->map(function ($detail) use ($id) {
                return [
                    'id_resep' => $id,
                    'id_obat' => $detail->id_obat,
                    'jumlah_resep_detail' => $detail->jumlah_resep_detail,
                ];
            })->toArray();

            if (empty($resepDetails)) {
                throw new \Exception('Tidak ada detail resep untuk diproses.');
            }

            \Log::info("Mengirim event kurangiStokObat", ['resepDetails' => $resepDetails]);
            $this->emitTo('obat', 'kurangiStokObat', $resepDetails);
            $this->selectedResepId = $id;
        } catch (\Exception $e) {
            \Log::error("Error di prosesResep: {$e->getMessage()}");
            $this->emit('showAlert', [
                'title' => 'Gagal!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
            $this->emit('refreshComponent');
        }
    }

    public function handleStokUpdated($success)
    {
        \Log::info("handleStokUpdated dipanggil", ['success' => $success, 'selectedResepId' => $this->selectedResepId]);
        if ($success && $this->selectedResepId) {
            $resep = Resep::find($this->selectedResepId);
            if ($resep && $resep->status_resep === 'Menunggu') {
                $resep->update(['status_resep' => 'Diproses']);
                $this->emit('showAlert', [
                    'title' => 'Berhasil!',
                    'text' => 'Resep telah diproses dan stok obat diperbarui.',
                    'icon' => 'success'
                ]);
                $this->emit('refreshComponent');
                $this->selectedResepId = null; // Reset setelah sukses
            }
        } else {
            $this->emit('showAlert', [
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memperbarui stok obat.',
                'icon' => 'error'
            ]);
        }
    }

    public function handleStokTidakCukup($data)
    {
        \Log::info("handleStokTidakCukup dipanggil", $data);
        $resepId = $data['resep_id'];
        $message = $data['message'];
        $this->dispatchBrowserEvent('confirmEdit', [
            'title' => 'Stok Tidak Cukup!',
            'text' => $message . ' Apakah Anda ingin mengubah data resep?',
            'icon' => 'warning',
            'resep_id' => $resepId
        ]);
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
                XPengambilanObat::updateOrCreate(
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
            $this->emit('refreshComponent');
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
            'resepList' => $this->getResepQuery()->paginate(5),
            'selectedResep' => $this->selectedResepId
                ? Resep::with(['details.obat', 'pemeriksaan.pendaftaran.pasien'])->findOrFail($this->selectedResepId)
                : null,
        ]);
    }
}
