<div>
    @push('scripts')
    <script>
        // Event untuk menampilkan alert
        window.addEventListener('alert', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            }).then(() => {
                // Jika alert sukses, cetak struk
                if (event.detail.icon === 'success') {
                    window.dispatchEvent(new CustomEvent('cetakStruk', {
                        detail: {
                            nik: event.detail.nik,
                            nama: event.detail.nama,
                            poli: event.detail.poli
                        }
                    }));
                }
            });
        });

        // Event untuk mencetak struk
        window.addEventListener('cetakStruk', event => {
            let strukWindow = window.open('', '_blank');
            strukWindow.document.write(`
            <html>
            <head>
                <title>Struk Pendaftaran</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h2 { text-align: center; }
                    .content { margin-top: 20px; }
                    .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #888; }
                </style>
            </head>
            <body>
                <h2>Struk Pendaftaran Pasien</h2>
                <div class="content">
                    <p><strong>NIK:</strong> ${event.detail.nik}</p>
                    <p><strong>Nama:</strong> ${event.detail.nama}</p>
                    <p><strong>Poli Tujuan:</strong> ${event.detail.poli}</p>
                </div>
                <div class="footer">
                    <p>Terima kasih telah mendaftar di klinik kami!</p>
                </div>
                <script>
                    window.print();
                <\/script>
            </body>
            </html>
        `);
            strukWindow.document.close();
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
                <!-- <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"> -->
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
                        <input type="text" class="form-control" wire:model.lazy="nama"
                            {{ $this->nik && $this->tanggal_lahir ? 'readonly' : '' }}>
                        @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" wire:model.lazy="jenis_kelamin"
                            {{ $this->nik && $this->tanggal_lahir ? 'readonly' : '' }}>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" wire:model.lazy="alamat"
                            {{ $this->nik && $this->tanggal_lahir ? 'readonly' : '' }}></textarea>
                        @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" wire:model.lazy="no_hp"
                            {{ $this->nik && $this->tanggal_lahir ? 'readonly' : '' }}>
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
                <!-- </div>
                </div> -->
            </div>
        </div>
    </div>
</div>