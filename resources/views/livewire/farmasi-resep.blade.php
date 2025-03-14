<div>
    @push('scripts')
    <script>
        window.addEventListener('showAlert', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        window.addEventListener('showStrukModal', event => {
            $('#strukModal').modal('show');
        });
    </script>
    @endpush

    <!-- Modal untuk Struk Obat -->
    <div class="modal-body">
        @if($selectedResep)
        <h6>Pasien: {{ $selectedResep->pemeriksaan->pendaftaran->pasien->nama_pasien }}</h6>
        <h6>ID Pendaftaran: {{ $selectedResep->pemeriksaan->id_pendaftaran }}</h6>
        <h6>Tanggal: {{ $selectedResep->created_at->format('d-m-Y H:i') }}</h6>
        <hr>
        <h6>Detail Obat:</h6>
        @php
        $details = $selectedResep->details ?? collect();
        $groupedDetails = $details->groupBy('nama_racik');
        $totalHarga = 0;
        @endphp
        @if($groupedDetails->isNotEmpty())
        @foreach ($groupedDetails as $namaRacik => $details)
        @if ($namaRacik)
        <h6><strong>Racikan: {{ $namaRacik }}</strong></h6>
        <ul class="list-group mb-3">
            @foreach ($details as $detail)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }})
                <span>Rp {{ number_format($detail->obat->harga_obat * $detail->jumlah_resep_detail, 2) }}</span>
            </li>
            @php
            $totalHarga += $detail->obat->harga_obat * $detail->jumlah_resep_detail;
            @endphp
            @endforeach
        </ul>
        @else
        <h6><strong>Obat Non-Racik</strong></h6>
        <ul class="list-group mb-3">
            @foreach ($details as $detail)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }})
                <span>Rp {{ number_format($detail->obat->harga_obat * $detail->jumlah_resep_detail, 2) }}</span>
            </li>
            @php
            $totalHarga += $detail->obat->harga_obat * $detail->jumlah_resep_detail;
            @endphp
            @endforeach
        </ul>
        @endif
        @endforeach
        <h6 class="text-right"><strong>Total Harga: Rp {{ number_format($totalHarga, 2) }}</strong></h6>
        @else
        <p>Tidak ada detail obat untuk resep ini.</p>
        @endif
        @else
        <p>Tidak ada data struk untuk ditampilkan.</p>
        @endif
    </div>

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Resep Farmasi</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Farmasi</li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Resep</li>
            </ol>
        </div>

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
                                    <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Cari Nama Pasien..." aria-label="Cari Nama Pasien">
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
                                        <td>Rp {{ number_format($resep->total_harga_resep, 2) }}</td>
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
                                            @if($resep->status_resep === 'Menunggu')
                                            <span class="badge badge-warning">Menunggu</span>
                                            @elseif($resep->status_resep === 'Diproses' && $resep->transaksi && $resep->transaksi->status_transaksi === 'Lunas' && $resep->pengambilanObat && $resep->pengambilanObat->status_pengambilan_obat === 'Belum Diambil')
                                            <span class="badge badge-info">Diproses</span><br>
                                            <span class="badge badge-success mt-1">Lunas - Obat Siap Diambil</span>
                                            @elseif($resep->status_resep === 'Diproses')
                                            <span class="badge badge-info">Diproses</span>
                                            @elseif($resep->status_resep === 'Selesai')
                                            <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($resep->status_resep === 'Menunggu')
                                            <button wire:click="prosesResep({{ $resep->id_resep }})" class="btn btn-primary btn-sm mb-1">Proses Obat</button>
                                            @elseif($resep->status_resep === 'Diproses')
                                            @if($resep->transaksi && $resep->transaksi->status_transaksi === 'Lunas')
                                            <button wire:click="selesaikanResep({{ $resep->id_resep }})" class="btn btn-success btn-sm mb-1">Selesai</button>
                                            @else
                                            <span class="text-muted">Menunggu Pembayaran</span>
                                            @endif
                                            <button wire:click="showStruk({{ $resep->id_resep }})" class="btn btn-info btn-sm mb-1" data-toggle="modal" data-target="#strukModal">Struk Obat</button>
                                            @else
                                            <!-- <span class="text-muted">Selesai</span> -->
                                            <button wire:click="showStruk({{ $resep->id_resep }})" class="btn btn-info btn-sm mb-1" data-toggle="modal" data-target="#strukModal">Struk Obat</button>
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

                        <div class="d-flex justify-content-center">
                            {{ $resepList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>