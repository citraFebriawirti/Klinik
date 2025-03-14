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
    </script>
    @endpush

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi Kasir</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Kasir</li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Transaksi</li>
            </ol>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pencarian</h6>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Menunggu Pembayaran</h6>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiList as $resep)
                                    <tr>
                                        <td>{{ $loop->iteration + ($transaksiList->currentPage() - 1) * $transaksiList->perPage() }}</td>
                                        <td>{{ $resep->pemeriksaan->id_pendaftaran }}</td>
                                        <td>{{ $resep->id_resep }}</td>
                                        <td>{{ $resep->pemeriksaan->pendaftaran->pasien->nama_pasien }}</td>
                                        <td>Rp {{ number_format($resep->total_harga_resep, 2) }}</td>
                                        <td>
                                            <button wire:click="bayar({{ $resep->id_resep }})" class="btn btn-success btn-sm">Bayar</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada transaksi menunggu pembayaran</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $transaksiList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>