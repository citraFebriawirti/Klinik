<div>
    @push('scripts')
    <script>
        window.addEventListener('showAlert', event => {
            console.log('Event showAlert diterima:', event.detail);
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('openModal', function() {
                console.log('Event openModal diterima');
                const modalElement = document.getElementById('modalPemeriksaan');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal element tidak ditemukan');
                }
            });

            window.addEventListener('openDetailModal', function() {
                console.log('Event openDetailModal diterima');
                const modalElement = document.getElementById('modalDetailPemeriksaan');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal detail element tidak ditemukan');
                }
            });

            window.addEventListener('closeModal', function() {
                console.log('Event closeModal diterima');
                const modalElement = document.getElementById('modalPemeriksaan');
                const modalDetailElement = document.getElementById('modalDetailPemeriksaan');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                if (modalDetailElement) {
                    const modalDetail = bootstrap.Modal.getInstance(modalDetailElement);
                    if (modalDetail) modalDetail.hide();
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

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pasien untuk Pemeriksaan</h6>
                    </div>

                    <div class="p-3">
                        <!-- Filter dan Pencarian -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="statusFilter">Filter Status</label>
                                <select wire:model="statusFilter" class="form-control" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search">Cari Pasien</label>
                                <input type="text" wire:model.debounce.500ms="search" class="form-control" id="search" placeholder="Nama, NIK, atau ID Pendaftaran">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">ID Pendaftaran</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Poli</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($daftarPasien as $pasien)
                                        <tr>
                                            <td>{{ $loop->iteration + $daftarPasien->firstItem() - 1 }}</td>
                                            <td>{{ $pasien->id_pendaftaran }}</td>
                                            <td>{{ $pasien->pasien->nik_pasien ?? 'N/A' }}</td>
                                            <td>{{ $pasien->pasien->nama_pasien ?? 'N/A' }}</td>
                                            <td>{{ $pasien->poli->nama_poli ?? 'N/A' }}</td>
                                            <td>
                                                @if($pasien->status_pendaftaran === 'Menunggu')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @else
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pasien->status_pendaftaran === 'Menunggu')
                                                    <button wire:click="pilihPasien({{ $pasien->id_pendaftaran }})" class="btn btn-info btn-sm">Periksa</button>
                                                @else
                                                    <button wire:click="lihatDetail({{ $pasien->id_pendaftaran }})" class="btn btn-secondary btn-sm">Lihat Detail</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-5">
                                {{ $daftarPasien->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pemeriksaan -->
        @if($isOpen)
        <div class="modal fade show d-block" id="modalPemeriksaan" tabindex="-1" aria-labelledby="modalPemeriksaanLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form wire:submit.prevent="simpanPemeriksaan">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPemeriksaanLabel">Pemeriksaan Pasien</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <h6 class="mb-3">Resep Obat</h6>
                                    <div class="mb-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" wire:model="is_racik"> Racik
                                        </label>
                                    </div>
                                    @if($is_racik && !$nama_racik_aktif)
                                    <div class="mb-3">
                                        <label class="form-label">Nama Racikan</label>
                                        <input type="text" class="form-control" wire:model="nama_racik" placeholder="Masukkan nama racikan (contoh: Obat Batuk)">
                                        @error('nama_racik') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    @elseif($nama_racik_aktif)
                                    <div class="mb-3">
                                        <label class="form-label">Nama Racikan Aktif</label>
                                        <input type="text" class="form-control" value="{{ $nama_racik_aktif }}" disabled>
                                        <button type="button" wire:click="resetNamaRacik" class="btn btn-warning btn-sm mt-2">Ganti Nama Racikan</button>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="form-label">Obat</label>
                                        <select wire:model="id_obat" class="form-control">
                                            <option value="">Pilih Obat</option>
                                            @foreach ($obatList as $obat)
                                            <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }} ({{ $obat->jenis_obat }})</option>
                                            @endforeach
                                        </select>
                                        @error('id_obat') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Dosis</label>
                                        <input type="text" class="form-control" wire:model="dosis" placeholder="Contoh: 1x sehari">
                                        @error('dosis') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Obat</label>
                                        <input type="number" class="form-control" wire:model="jumlah" min="1">
                                        @error('jumlah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Aturan Pakai</label>
                                        <input type="text" class="form-control" wire:model="aturan_pakai" placeholder="Contoh: Setelah makan">
                                        @error('aturan_pakai') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="button" wire:click="tambahItemResep" class="btn btn-primary btn-sm">Tambah ke Resep</button>
                                </div>
                                <div class="col-md-4">
                                    <div class="mt-3">
                                        @php
                                        $groupedResepItems = collect($resepItems)->groupBy('nama_racik');
                                        @endphp
                                        @foreach ($groupedResepItems as $namaRacik => $items)
                                        @if ($namaRacik)
                                        <h6 class="mb-2">Racikan: {{ $namaRacik }}</h6>
                                        @else
                                        <h6 class="mb-2">Obat Non-Racik</h6>
                                        @endif

                                      
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Obat</th>
                                                    <th>Dosis</th>
                                                    <th>Jumlah</th>
                                                    <th>Aturan Pakai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $index => $item)
                                                <tr>
                                                    <td>{{ $item['nama_obat'] }}</td>
                                                    <td>{{ $item['dosis'] }}</td>
                                                    <td>{{ $item['jumlah'] }}</td>
                                                    <td>{{ $item['aturan_pakai'] }}</td>
                                                    <td>
                                                        <button type="button" wire:click="hapusItemResep({{ $groupedResepItems->keys()->search($namaRacik) * 1000 + $index }})" class="btn btn-danger btn-sm">Hapus</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($namaRacik && $is_racik && $nama_racik_aktif === $namaRacik)
                                        <button type="button" wire:click="tambahLagiRacikan('{{ $namaRacik }}')" class="btn btn-primary btn-sm mt-2">Sedang menambah Item ke {{ $namaRacik }}</button>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
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

        <!-- Modal Detail Pemeriksaan -->
        @if($isDetailOpen && $selectedPemeriksaan)
        <div class="modal fade show d-block" id="modalDetailPemeriksaan" tabindex="-1" aria-labelledby="modalDetailPemeriksaanLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modalDetailPemeriksaanLabel">Detail Pemeriksaan</h6>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div> 
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Pendaftaran</label>
                            <input type="text" class="form-control" value="{{ $selectedPemeriksaan->id_pendaftaran }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diagnosa</label>
                            <input type="text" class="form-control" value="{{ $selectedPemeriksaan->diagnosa_pemeriksaan }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" disabled>{{ $selectedPemeriksaan->catatan_pemeriksaan }}</textarea>
                        </div>
                        @if($selectedPemeriksaan->resep)
                        <h6>Resep Obat</h6>
                        @php
                        $groupedDetails = $selectedPemeriksaan->resep->details->groupBy('nama_racik');
                        @endphp
                        @foreach ($groupedDetails as $namaRacik => $details)
                        @if ($namaRacik)
                        <h6 class="mb-2">Racikan: {{ $namaRacik }}</h6>
                        @else
                        <h6 class="mb-2">Obat Non-Racik</h6>
                        @endif
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Dosis</th>
                                    <th>Jumlah Obat</th>
                                    <th>Satuan</th>
                                    <th>Cara Pakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail->obat->id_obat }}</td>
                                    <td>{{ $detail->obat->nama_obat }}</td>
                                    <td>{{ $detail->dosis_resep_detail }}</td>
                                    {{-- <td>{{ $detail->jumlah_resep_detail }}</td> --}}
                                    <td>{{ $detail->obat->stok_obat }}</td>
                                    <td>{{ $detail->obat->satuan_obat }}</td>
                                    <td>{{ $detail->aturan_pakai_resep_detail }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                        @else
                        <p>Tidak ada resep untuk pemeriksaan ini.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>