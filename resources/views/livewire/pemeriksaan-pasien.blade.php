<div>
    @push('scripts')
    <script>
        // Event listener untuk menampilkan alert
        window.addEventListener('showAlert', event => {
            console.log('Event showAlert diterima:', event.detail); // Debug
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        // Event listener untuk membuka modal
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('openModal', function() {
                console.log('Event openModal diterima'); // Debug
                const modalElement = document.getElementById('modalPemeriksaan');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal element tidak ditemukan');
                }
            });

            window.addEventListener('closeModal', function() {
                console.log('Event closeModal diterima'); // Debug
                const modalElement = document.getElementById('modalPemeriksaan');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
            });
        });
    </script>
    @endpush

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pasien untuk Pemeriksaan</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Pasien untuk Pemeriksaan</li>
            </ol>
        </div>

        <!-- Row -->
        <div class="row">
            <!-- Data Pasien untuk Pemeriksaan -->
            <div class="col-lg-12">
                <div class="card mb-4">


                    <div class="p-3">
                        <!-- Tabel Data -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Pendaftaran</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Poli</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($daftarPasien as $pasien)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pasien->id_pendaftaran }}</td>
                                        <td>{{ $pasien->pasien->nik_pasien }}</td>
                                        <td>{{ $pasien->pasien->nama_pasien }}</td>
                                        <td>{{ $pasien->poli->nama_poli }}</td>
                                        <td>
                                            <button wire:click="pilihPasien({{ $pasien->id_pendaftaran }})" class="btn btn-info">Periksa</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data tidak ditemukan</td>
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
        <div class="modal fade show d-block" id="modalPemeriksaan" tabindex="-1" aria-labelledby="modalPemeriksaanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="simpanPemeriksaan">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPemeriksaanLabel">Pemeriksaan Pasien</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ID Pendaftaran</label>
                                <input type="text" class="form-control" value="{{ $id_pendaftaran }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dokter</label>
                                <select wire:model="id_dokter" class="form-control">
                                    <option value="">Pilih Dokter</option>
                                    @foreach ($dokterList as $dokter)
                                    <option value="{{ $dokter->id_dokter }}">{{ $dokter->nama_dokter }} - {{ $dokter->spesialisasi_dokter }}</option>
                                    @endforeach
                                </select>
                                @error('id_dokter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Diagnosa</label>
                                <input type="text" class="form-control" wire:model="diagnosa">
                                @error('diagnosa') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" wire:model="catatan"></textarea>
                                @error('catatan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                            <button type="submit" class="btn btn-success">Simpan Pemeriksaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>