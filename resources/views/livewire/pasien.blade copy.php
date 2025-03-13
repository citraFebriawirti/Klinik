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
    </script>
    @endpush

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pendaftaran Pasien</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
            </ol>
        </div>

        <!-- Row -->
        <div class="row">
            <!-- Data Pasien -->
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Pendaftaran</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex mb-3 mr-5">
                                <form wire:submit.prevent="simpanPendaftaran">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="nik" class="form-label">NIK Pasien</label>
                                        <input type="text" class="form-control" wire:model.lazy="nik" maxlength="16">
                                        @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" wire:model.lazy="tanggal_lahir">
                                        @error('tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Pasien</label>
                                        <input type="text" class="form-control" wire:model.lazy="nama">
                                        @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-control" wire:model.lazy="jenis_kelamin">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" wire:model.lazy="alamat"></textarea>
                                        @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" wire:model.lazy="no_hp">
                                        @error('no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_poli" class="form-label">Poli Tujuan</label>
                                        <select class="form-control" wire:model.lazy="id_poli">
                                            <option value="">-- Pilih Poli --</option>
                                            @foreach ($poli_list as $poli)
                                            <option value="{{ $poli->id_poli }}">{{ $poli->nama_poli }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_poli') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Daftar</button>
                                    </div>
                                </form>
                            </div>

                        </div>


                    </div>


                </div>
            </div>
        </div>


    </div>
</div>