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
            <h1 class="h3 mb-0 text-gray-800">Daftar Resep Farmasi</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Farmasi</li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Resep</li>
            </ol>
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
                                        <th>ID Resep</th>
                                        <th>Nama Pasien</th>
                                        <th>Total Harga</th>
                                        <th>Detail Obat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resepList as $resep)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $resep->id_resep }}</td>
                                        <td>{{ $resep->pemeriksaan->pendaftaran->pasien->nama_pasien }}</td>
                                        <td>Rp {{ number_format($resep->total_harga_resep, 2) }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($resep->details as $detail)
                                                <li>{{ $detail->obat->nama_obat }} ({{ $detail->jumlah_resep_detail }} {{ $detail->obat->satuan_obat }}) - {{ $detail->dosis_resep_detail }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <button wire:click="prosesResep({{ $resep->id_resep }})" class="btn btn-primary">Proses</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada resep yang menunggu</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>