<div>
    @push('styles')
    <style>
        /* Styling untuk Modal */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 15px 20px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .modal-body {
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .modal-body h6 {
            color: #333;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .modal-body .list-group-item {
            border: none;
            padding: 10px 15px;
            background: #f1f3f5;
            margin-bottom: 5px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .modal-body .list-group-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .modal-footer {
            padding: 15px 20px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            background: #f8f9fa;
        }

        .modal-footer .btn-primary {
            background: #28a745;
            border: none;
            padding: 8px 20px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .modal-footer .btn-primary:hover {
            background: #218838;
        }

        .modal-footer .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 8px 20px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .modal-footer .btn-secondary:hover {
            background: #5a6268;
        }

        hr {
            border-top: 2px solid #007bff;
            margin: 15px 0;
        }

        .table-editable input,
        .table-editable select {
            width: 100%;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .table-editable .btn-sm {
            padding: 3px 8px;
        }

        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            vertical-align: middle;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('showAlert', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon,
                    confirmButtonText: 'OK'
                });
            });

            window.addEventListener('confirmEdit', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ubah Resep',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('openEditModal', event.detail.resep_id);
                    }
                });
            });

            window.addEventListener('confirmProses', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('confirmProsesResep', event.detail.resep_id);
                    }
                });
            });
        });

        function printStruk() {
            const modalContent = document.querySelector('.modal-content');
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Struk Obat</title>');
            printWindow.document.write(`
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        margin: 20px;
                        padding: 0;
                        color: #333;
                    }
                    .modal-content {
                        border: 4px double #28a745;
                        border-radius: 10px;
                        width: 100%;
                        max-width: 700px;
                        margin: 0 auto;
                        padding: 20px;
                        background: #fff;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        position: relative;
                    }
                    .modal-content::before {
                        content: '';
                        position: absolute;
                        top: -8px;
                        left: -8px;
                        right: -8px;
                        bottom: -8px;
                        border: 2px solid #28a745;
                        border-radius: 14px;
                        z-index: -1;
                    }
                    .modal-header, .modal-footer {
                        display: none;
                    }
                    .modal-body {
                        padding: 0;
                    }
                    h6 {
                        font-size: 14px;
                        font-weight: bold;
                        margin: 10px 0 5px;
                        color: #28a745;
                    }
                    ul.list-group {
                        list-style-type: none;
                        padding: 0;
                        margin: 0;
                    }
                    .list-group-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 8px 12px;
                        margin-bottom: 5px;
                        border-bottom: 1px dashed #28a745;
                        font-size: 12px;
                    }
                    .list-group-item:last-child {
                        border-bottom: none;
                    }
                    hr {
                        border-top: 1px dashed #28a745;
                        margin: 15px 0;
                    }
                    .text-right {
                        text-align: right;
                        font-size: 14px;
                        font-weight: bold;
                        color: #dc3545;
                    }
                    .print-header {
                        text-align: center;
                        font-size: 16px;
                        font-weight: bold;
                        margin-bottom: 20px;
                        color: #28a745;
                        border-bottom: 2px solid #28a745;
                        padding-bottom: 5px;
                    }
                </style>
            `);
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="print-header">STRUK PEMBELIAN OBAT</div>');
            printWindow.document.write(modalContent.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        document.addEventListener("DOMContentLoaded", function () {
            function initTomSelect() {
                document.querySelectorAll("[data-tomselect]").forEach(el => {
                    if (el.tomselect) {
                        el.tomselect.destroy();
                    }
        
                    let tomSelect = new TomSelect(el, {
                        create: false,
                        searchField: "text",
                        sortField: { field: "text", direction: "asc" }
                    });
        
                    el.addEventListener("change", function () {
                        Livewire.emit("statusUpdated", this.value);
                    });
        
                    console.log(`Tom Select initialized on ${el.id}`);
                });
            }
        
            initTomSelect();
        
            Livewire.hook("message.processed", () => {
                setTimeout(() => {
                    console.log("Livewire update detected");
                    initTomSelect();
                }, 100);
            });
        
           
        });
    </script>
    @endpush

    @livewire('obat') <!-- Pastikan komponen Obat dimuat -->

    <div class="container-fluid" id="container-wrapper">
        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Resep Farmasi</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Farmasi</li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Resep</li>
            </ol>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Filter dan Pencarian</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <input type="text" wire:model.debounce.500ms="search" class="form-control"
                                        placeholder="Cari Nama Pasien..." aria-label="Cari Nama Pasien">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <select wire:model="statusFilter" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Diproses">Diproses</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Resep -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Resep</h6>
                    </div>
                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Pendaftaran</th>
                                        <th>ID Resep</th>
                                        <th>Nama Pasien</th>
                                        <th>Total Harga</th>
                                        <th>Detail Obat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resepList as $resep)
                                    <tr>
                                        <td>{{ $loop->iteration + ($resepList->currentPage() - 1) * $resepList->perPage() }}</td>
                                        <td>{{ $resep->pemeriksaan->id_pendaftaran }}</td>
                                        <td>{{ $resep->id_resep }}</td>
                                        <td>{{ $resep->pemeriksaan->pendaftaran->pasien->nama_pasien }}</td>
                                        <td>Rp {{ number_format($resep->total_harga_resep, 2, ',', '.') }}</td>
                                        <td>
                                            @php
                                            $groupedDetails = $resep->details->groupBy('nama_racik');
                                            @endphp
                                            @foreach ($groupedDetails as $namaRacik => $details)
                                            @if ($namaRacik)
                                            <strong>Racikan: {{ $namaRacik }}</strong>
                                            <ul class="mb-2">
                                                @foreach ($details as $detail)
                                                <li>{{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }}) - {{ $detail->dosis_resep_detail }} - {{ $detail->aturan_pakai_resep_detail }}</li>
                                                @endforeach
                                            </ul>
                                            @else
                                            <strong>Obat Non-Racik</strong>
                                            <ul class="mb-2">
                                                @foreach ($details as $detail)
                                                <li>{{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }}) - {{ $detail->dosis_resep_detail }} - {{ $detail->aturan_pakai_resep_detail }}</li>
                                                @endforeach
                                            </ul>
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @switch($resep->status_resep)
                                            @case('Menunggu')
                                            <span class="badge badge-warning">Menunggu</span>
                                            @break
                                            @case('Diproses')
                                            @if($resep->transaksi && $resep->transaksi->status_transaksi === 'Lunas' && $resep->pengambilanObat && $resep->pengambilanObat->status_pengambilan_obat === 'Belum Diambil')
                                            <span class="badge badge-info">Diproses</span><br>
                                            <span class="badge badge-success mt-1">Lunas - Obat Siap Diambil</span>
                                            @else
                                            <span class="badge badge-info">Diproses</span>
                                            @endif
                                            @break
                                            @case('Selesai')
                                            <span class="badge badge-success">Selesai</span>
                                            @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($resep->status_resep === 'Menunggu')
                                            <button wire:click="prosesResep({{ $resep->id_resep }})" 
                                                    wire:loading.attr="disabled" 
                                                    class="btn btn-primary btn-sm mb-1">
                                                <span wire:loading wire:target="prosesResep({{ $resep->id_resep }})">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...
                                                </span>
                                                <span wire:loading.remove wire:target="prosesResep({{ $resep->id_resep }})">
                                                    Proses Obat
                                                </span>
                                            </button>
                                            <button wire:click="openEditModal({{ $resep->id_resep }})" class="btn btn-warning btn-sm mb-1">Edit Resep</button>
                                            @elseif($resep->status_resep === 'Diproses')
                                            @if($resep->transaksi && $resep->transaksi->status_transaksi === 'Lunas')
                                            <button wire:click="selesaikanResep({{ $resep->id_resep }})" class="btn btn-success btn-sm mb-1">Selesai</button>
                                            @else
                                            <span class="text-muted">Menunggu Pembayaran</span>
                                            @endif
                                            <button wire:click="showStruk({{ $resep->id_resep }})" class="btn btn-info btn-sm mb-1">Struk Obat</button>
                                            @else
                                            <button wire:click="showStruk({{ $resep->id_resep }})" class="btn btn-info btn-sm mb-1">Struk Obat</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada resep</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $resepList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Struk -->
        @if($isOpen)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-labelledby="strukModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="strukModalLabel">Struk Obat</h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($selectedResep)
                        <div class="mb-3">
                            <h6><i class="fas fa-user mr-2"></i>Pasien: {{ $selectedResep->pemeriksaan->pendaftaran->pasien->nama_pasien }}</h6>
                            <h6><i class="fas fa-id-card mr-2"></i>ID Pendaftaran: {{ $selectedResep->pemeriksaan->id_pendaftaran }}</h6>
                            <h6><i class="fas fa-calendar-alt mr-2"></i>Tanggal: {{ $selectedResep->created_at->format('d-m-Y H:i') }}</h6>
                        </div>
                        <hr>
                        <h6><i class="fas fa-pills mr-2"></i>Detail Obat:</h6>
                        @php
                        $details = $selectedResep->details ?? collect();
                        $groupedDetails = $details->groupBy('nama_racik');
                        $totalHarga = 0;
                        @endphp
                        @if($details->isNotEmpty())
                        @foreach ($groupedDetails as $namaRacik => $group)
                        @if ($namaRacik)
                        <h6><strong>Racikan: {{ $namaRacik }}</strong></h6>
                        <ul class="list-group mb-3">
                            @foreach ($group as $detail)
                            <li class="list-group-item">
                                {{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }})
                                <span>Rp {{ number_format($detail->obat->harga_obat * $detail->jumlah_resep_detail, 2, ',', '.') }}</span>
                            </li>
                            @php
                            $totalHarga += $detail->obat->harga_obat * $detail->jumlah_resep_detail;
                            @endphp
                            @endforeach
                        </ul>
                        @else
                        <h6><strong>Obat Non-Racik</strong></h6>
                        <ul class="list-group mb-3">
                            @foreach ($group as $detail)
                            <li class="list-group-item">
                                {{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }})
                                <span>Rp {{ number_format($detail->obat->harga_obat * $detail->jumlah_resep_detail, 2, ',', '.') }}</span>
                            </li>
                            @php
                            $totalHarga += $detail->obat->harga_obat * $detail->jumlah_resep_detail;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                        @endforeach
                        <h6 class="text-right"><strong><i class="fas fa-money-bill-wave mr-2"></i>Total Harga: Rp {{ number_format($totalHarga, 2, ',', '.') }}</strong></h6>
                        @else
                        <p class="text-muted"><i class="fas fa-info-circle mr-2"></i>Tidak ada detail obat untuk resep ini.</p>
                        @endif
                        @else
                        <p class="text-muted"><i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada data struk untuk ditampilkan.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="printStruk()"><i class="fas fa-print mr-2"></i>Print Struk</button>
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"><i class="fas fa-times mr-2"></i>Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeModal"></div>
        @endif

        <!-- Modal Edit Resep -->
        @if($isEditOpen)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-labelledby="editResepModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editResepModalLabel">Edit Resep - ID Resep: {{ $editResepId }}</h5>
                        <button type="button" class="close" wire:click="closeEditModal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-warning"><i class="fas fa-exclamation-triangle mr-2"></i>Catatan: Obat yang sama dalam kelompok yang sama (racik dengan nama sama atau non-racik) akan dijadikan satu dan quantity-nya dijumlahkan saat disimpan.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-editable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Obat</th>
                                        <th>Jumlah</th>
                                        <th style="width: 10px">Dosis</th>
                                        <th>Aturan Pakai</th>
                                        <th>Racik</th>
                                        <th>Nama Racikan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($editDetails as $index => $detail)
                                    <tr>
                                        <td>
                                            <select wire:model="editDetails.{{ $index }}.id_obat" class="f"   data-tomselect>
                                                <option value="">Pilih Obat</option>
                                                @foreach ($obatList as $obat)
                                              
                                                <option value="{{ $obat['id_obat'] }}">{{ $obat['nama_obat'] }} ({{ $obat['jenis_obat'] }}) - Stok: {{ $obat['stok_obat'] }}</option>
                                                @endforeach
                                            </select>
                                            @error("editDetails.{$index}.id_obat") <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" wire:model="editDetails.{{ $index }}.jumlah" min="1" class="form-control">
                                            @error("editDetails.{$index}.jumlah") <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model="editDetails.{{ $index }}.dosis" class="form-control" placeholder="Contoh: 1x sehari">
                                            @error("editDetails.{$index}.dosis") <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model="editDetails.{{ $index }}.aturan_pakai" class="form-control" placeholder="Contoh: Setelah makan">
                                            @error("editDetails.{$index}.aturan_pakai") <span class="text-danger">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="checkbox" wire:model="editDetails.{{ $index }}.is_racik">
                                        </td>
                                        <td>
                                            @if($editDetails[$index]['is_racik'])
                                            <input type="text" wire:model="editDetails.{{ $index }}.nama_racik" class="form-control" placeholder="Contoh: Obat Batuk">
                                            @error("editDetails.{$index}.nama_racik") <span class="text-danger">{{ $message }}</span> @enderror
                                            @else
                                            <span class="text-muted">Non-Racik</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" wire:click="removeDetail({{ $index }})" class="btn btn-danger btn-sm">Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" wire:click="addDetail" class="btn btn-primary btn-sm mb-3">Tambah Obat</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="simpanEditResep" class="btn btn-success"><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
                        <button type="button" wire:click="closeEditModal" class="btn btn-secondary"><i class="fas fa-times mr-2"></i>Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeEditModal"></div>
        @endif
    </div>
</div>