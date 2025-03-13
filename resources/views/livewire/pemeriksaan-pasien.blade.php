<div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('openModal', () => {
                const modalElement = document.getElementById('modalPemeriksaan');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });

            window.addEventListener('closeModal', () => {
                const modalElement = document.getElementById('modalPemeriksaan');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
            });

            window.addEventListener('showAlert', (event) => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon,
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
    @endpush

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pasien untuk Pemeriksaan</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Pasien untuk Pemeriksaan</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- Daftar Pasien -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Pendaftaran</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Poli</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarPasien as $pasien)
                            <tr>
                                <td>{{ $pasien->id_pendaftaran }}</td>
                                <td>{{ $pasien->pasien->nik_pasien }}</td>
                                <td>{{ $pasien->pasien->nama_pasien }}</td>
                                <td>{{ $pasien->poli->nama_poli }}</td>
                                <td>
                                    <button wire:click="pilihPasien({{ $pasien->id_pendaftaran }})" class="btn btn-info">Periksa</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Pemeriksaan -->
                <div class="modal fade" id="modalPemeriksaan" tabindex="-1" aria-labelledby="modalPemeriksaanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPemeriksaanLabel">Pemeriksaan Pasien</h5>
                                <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label class="form-label">ID Pendaftaran: <b>{{ $id_pendaftaran }}</b></label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dokter:</label>
                                    <select wire:model="id_dokter" class="form-control">
                                        <option value="">Pilih Dokter</option>
                                        @foreach ($dokterList as $dokter)
                                        <option value="{{ $dokter->id_dokter }}">{{ $dokter->nama_dokter }} - {{ $dokter->spesialisasi_dokter }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_dokter') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Diagnosa:</label>
                                    <input type="text" wire:model="diagnosa" class="form-control">
                                    @error('diagnosa') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan:</label>
                                    <textarea wire:model="catatan" class="form-control"></textarea>
                                    @error('catatan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                                <button wire:click="simpanPemeriksaan" class="btn btn-success">Simpan Pemeriksaan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>