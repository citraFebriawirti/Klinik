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
                        <div class="d-flex align-items-center">
                            <div class="d-flex mr-3">
                                <input type="text" class="form-control" placeholder="Cari..." wire:model.debounce.500ms="searchTerm">
                                <button wire:click="resetFilter" class="btn btn-warning ml-2">Reset</button>
                            </div>
                            <button wire:click="create()" class="btn btn-primary">Tambah Obat +</button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
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
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $o->nama_obat }}</td>
                                        <td>{{ $o->jenis_obat }}</td>
                                        <td>{{ $o->satuan_obat }}</td>
                                        <td class="text-center">
                                            @if($o->stok_obat <= 10)
                                                <span class="badge badge-danger">{{ $o->stok_obat }}</span>
                                            @elseif($o->stok_obat <= 50)
                                                <span class="badge badge-warning">{{ $o->stok_obat }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $o->stok_obat }}</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($o->harga_obat, 0, ',', '.') }}</td>
                                        <td class="d-flex justify-content-center">
                                            <button wire:click="edit({{ $o->id_obat }})" class="btn btn-warning btn-sm mr-1">Edit</button>
                                            <button wire:click="delete({{ $o->id_obat }})" class="btn btn-danger btn-sm">Hapus</button>
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
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $id_obat ? 'Edit Obat' : 'Tambah Obat' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Obat</label>
                                <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" wire:model="nama_obat" required>
                                @error('nama_obat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Obat</label>
                                <input type="text" class="form-control @error('jenis_obat') is-invalid @enderror" wire:model="jenis_obat" required>
                                @error('jenis_obat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Satuan Obat</label>
                                <input type="text" class="form-control @error('satuan_obat') is-invalid @enderror" wire:model="satuan_obat" required>
                                @error('satuan_obat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control @error('stok_obat') is-invalid @enderror" wire:model="stok_obat" min="0" required>
                                @error('stok_obat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control @error('harga_obat') is-invalid @enderror" wire:model="harga_obat" min="0" required>
                                @error('harga_obat') <span class="invalid-feedback">{{ $message }}</span> @enderror
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