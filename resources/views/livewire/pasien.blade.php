<div>
    @push('scripts')
    <script>
        // Event listener untuk menampilkan alert
        window.addEventListener('alert', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        // Event listener untuk konfirmasi hapus
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
            <h1 class="h3 mb-0 text-gray-800">Data Pasien</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Data Pasien</li>
            </ol>
        </div>

        <!-- Row -->
        <div class="row">
            <!-- Data Pasien -->
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pasien</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex mb-3 mr-5">
                                <input type="text" class="form-control me-2" placeholder="Cari..." wire:model.debounce.500ms="searchTerm">
                                <button wire:click="resetFilter" class="btn btn-warning ml-1">Reset</button>
                            </div>
                            <!-- Tombol Tambah Pasien -->
                            <button wire:click="create()" class="btn btn-primary mb-3">Tambah Pasien +</button>
                        </div>

                        
                    </div>

                    <div class="p-3">
                    
                        <!-- Tabel Data -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 10px">No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>Nomor HP</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pasien as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->nik_pasien }}</td>
                                        <td>{{ $p->nama_pasien }}</td>
                                    
                                        <td>{{ $p->tanggallahir_pasien }}</td>
                                        <td>{{ $p->jenis_kelamin_pasien }}</td>
                                        <td>{{ $p->alamat_pasien }}</td>
                                        <td>{{ $p->no_hp_pasien }}</td>
                                        <td class="d-flex">
                                            <button wire:click.prevent="edit({{ $p->id_pasien }})" class="btn btn-warning mr-1">Edit</button>
                                            <button wire:click="delete({{ $p->id_pasien }})" class="btn btn-danger">Hapus</button>

                                        </td>
                                        
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        @if($isOpen)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $id_pasien ? 'Edit Pasien' : 'Tambah Pasien' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" wire:model="nik_pasien" 
                                               pattern="\d{16}" inputmode="numeric" maxlength="16" required 
                                               title="NIK harus terdiri dari 16 angka">
                                        @error('nik_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" wire:model="nama_pasien">
                                        @error('nama_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                  
                                    <div class="mb-3">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" wire:model="tanggallahir_pasien">
                                        @error('tanggallahir_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!-- Kolom 2 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control" wire:model="jenis_kelamin_pasien">
                                            <option value="">Pilih</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Alamat</label>
                                        <textarea class="form-control" wire:model="alamat_pasien"></textarea>
                                        @error('alamat_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Nomor HP</label>
                                        <input type="text" class="form-control" wire:model="no_hp_pasien" 
                                               pattern="08\d{8,11}" inputmode="numeric" maxlength="13" required 
                                               title="Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 digit angka">
                                        @error('no_hp_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
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
