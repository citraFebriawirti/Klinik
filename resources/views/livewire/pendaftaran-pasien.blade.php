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
        <div class="d-sm-flex align-items-center justify-content-between mb-5">
          
          
        </div>

        <!-- Row -->
        <div class="row">
            <!-- Data Pasien -->
            <div class="col-lg-12">
                <form wire:submit.prevent="simpanPendaftaran" class="p-4 shadow rounded bg-white border">
                    @csrf
                    <h4 class="text-center mb-4 text-success fw-bold">Formulir Pendaftaran Pasien</h4>
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label fw-bold text-dark">NIK Pasien</label>
                                <input type="text" class="form-control border-success" wire:model.lazy="nik" maxlength="16" placeholder="Masukkan NIK">
                                @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label fw-bold text-dark">Tanggal Lahir</label>
                                <input type="date" class="form-control border-success" wire:model.lazy="tanggal_lahir">
                                @error('tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold text-dark">Nama Pasien</label>
                                <input type="text" class="form-control border-success" wire:model.lazy="nama" placeholder="Masukkan Nama">
                                @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label fw-bold text-dark">Jenis Kelamin</label>
                                <select class="form-control border-success" wire:model.lazy="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
            
                        <!-- Kolom Kanan -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="no_hp" class="form-label fw-bold text-dark">No. HP</label>
                                <input type="text" class="form-control border-success" wire:model.lazy="no_hp" placeholder="Masukkan No. HP">
                                @error('no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="id_poli" class="form-label fw-bold text-dark">Poli Tujuan</label>
                                <select class="form-control border-success" wire:model.lazy="id_poli">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach ($poli_list as $poli)
                                        <option value="{{ $poli->id_poli }}">{{ $poli->nama_poli }}</option>
                                    @endforeach
                                </select>
                                @error('id_poli') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-bold text-dark">Alamat</label>
                                <textarea class="form-control border-success" wire:model.lazy="alamat" placeholder="Masukkan Alamat" rows="3"></textarea>
                                @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
            
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-success btn-lg shadow w-100">Daftar</button>
                    </div>
                </form>
            </div>
            
            
            
        </div>
    </div>
</div>