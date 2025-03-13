<div>
    @push('scripts')
    <script>
        window.addEventListener('alert', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        window.addEventListener('confirmDelete', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('destroy', event.detail.id);
                }
            });
        });
    </script>
    @endpush

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Obat</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Data Obat</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Obat</h6>
                        <button wire:click="create()" class="btn btn-primary mb-3">Tambah Obat +</button>
                    </div>

                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>Jenis Obat</th>
                                        <th>Satuan</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($obat as $o)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $o->nama_obat }}</td>
                                        <td>{{ $o->jenis_obat }}</td>
                                        <td>{{ $o->satuan_obat }}</td>
                                        <td>{{ $o->stok_obat }}</td>
                                        <td>{{ number_format($o->harga_obat, 0, ',', '.') }}</td>
                                        <td class="d-flex">
                                            <button wire:click.prevent="edit({{ $o->id_obat }})" class="btn btn-warning mr-1">Edit</button>
                                            <button wire:click="delete({{ $o->id_obat }})" class="btn btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isOpen)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $id_obat ? 'Edit Obat' : 'Tambah Obat' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Obat</label>
                                <input type="text" class="form-control" wire:model="nama_obat" required>
                            </div>
                            <div class="mb-3">
                                <label>Jenis Obat</label>
                                <input type="text" class="form-control" wire:model="jenis_obat" required>
                            </div>
                            <div class="mb-3">
                                <label>Satuan Obat</label>
                                <input type="text" class="form-control" wire:model="satuan_obat" required>
                            </div>
                            <div class="mb-3">
                                <label>Stok</label>
                                <input type="number" class="form-control" wire:model="stok_obat" required>
                            </div>
                            <div class="mb-3">
                                <label>Harga</label>
                                <input type="number" class="form-control" wire:model="harga_obat" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>