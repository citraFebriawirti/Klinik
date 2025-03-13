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
            <h1 class="h3 mb-0 text-gray-800">Data Dokter</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Data Dokter</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Dokter</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex mb-3 mr-5">
                                <input type="text" class="form-control me-2" placeholder="Cari..." wire:model.debounce.500ms="searchTerm">
                                <button wire:click="resetFilter" class="btn btn-warning ml-1">Reset</button>
                            </div>
                            <!-- Tombol Tambah Pasien -->
                            <button wire:click="create()" class="btn btn-primary mb-3">Tambah Dokter +</button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        <th>Poli</th>
                                        <th>Spesialis</th>
                                        <th>Nomor HP</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tbody>
                                        @forelse($dokter as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->nama_dokter }}</td>
                                            <td>{{ $d->poli->nama_poli ?? '-' }}</td> <!-- Menampilkan nama poli -->
                                            <td>{{ $d->spesialisasi_dokter }}</td>
                                            <td>{{ $d->no_hp_dokter ?? '-' }}</td>
                                            <td>
                                                <button wire:click.prevent="edit({{ $d->id_dokter }})" class="btn btn-warning">Edit</button>
                                                <button wire:click="delete({{ $d->id_dokter }})" class="btn btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Data tidak ditemukan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    
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
                            <h5 class="modal-title">{{ $id_dokter ? 'Edit Dokter' : 'Tambah Dokter' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Dokter</label>
                                <input type="text" class="form-control" wire:model="nama_dokter" required>
                                @error('nama_dokter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label>Poli</label>
                                <select class="form-control" wire:model="id_poli" required>
                                    <option value="">Pilih Poli</option>
                                    @foreach($poli as $p) 
                                    <option value="{{ strval($p->id_poli) }}" data-nama="{{ $p->nama_poli }}">
                                        {{ $p->nama_poli }}
                                    </option>
                                @endforeach
                                </select>


                              
                                @error('id_poli') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label>Spesialisasi</label>
                                <input type="text" class="form-control" wire:model="spesialisasi_dokter" required>
                                @error('spesialisasi_dokter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label>Nomor HP</label>
                                <input type="text" class="form-control" wire:model="no_hp_dokter" pattern="08\d{8,11}" inputmode="numeric" maxlength="15" required>
                                @error('no_hp_dokter') <span class="text-danger">{{ $message }}</span> @enderror
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