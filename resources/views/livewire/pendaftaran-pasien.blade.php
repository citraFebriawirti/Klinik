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
                <div style="
    font-family: Arial, sans-serif;
    border: 2px solid #4CAF50;
    padding: 20px;
    width: 350px;
    margin: 0 auto;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background-color: #f9f9f9;
">

    <!-- Header -->
    <div style="
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
    ">
        <h2 style="color: #4CAF50; margin: 0;">Struk Pendaftaran Pasien</h2>
        <p style="font-size: 14px; color: #555;">
            Tanggal Mendaftar: <strong>${event.detail.tanggal_daftar_pendaftaran}</strong>
        </p>
    </div>

    <!-- Isi Struk -->
    <table style="
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    ">
        <tr>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #333;
            "><strong>ID Pendaftaran</strong></td>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #555;
                text-align: right;
            ">${event.detail.id_pendaftaran}</td>
        </tr>
        <tr>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #333;
            "><strong>NIK</strong></td>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #555;
                text-align: right;
            ">${event.detail.nik}</td>
        </tr>
        <tr>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #333;
            "><strong>Nama</strong></td>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #555;
                text-align: right;
            ">${event.detail.nama}</td>
        </tr>
        <tr>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #333;
            "><strong>Poli Tujuan</strong></td>
            <td style="
                padding: 8px;
                border-bottom: 1px solid #ddd;
                color: #555;
                text-align: right;
            ">${event.detail.poli}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div style="
        text-align: center;
        padding-top: 10px;
        border-top: 2px solid #4CAF50;
        color: #555;
        font-size: 14px;
    ">
        <p>Terima kasih telah mendaftar di klinik kami!</p>
    </div>
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