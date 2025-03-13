
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
    

  
    
     <script>
        document.addEventListener("DOMContentLoaded", function () {
            function initTomSelect() {
                document.querySelectorAll("[data-tomselect]").forEach(el => {
                    if (el.tomselect) {
                        el.tomselect.destroy();
                    }
        
                    let tomSelect = new TomSelect(el, {
                        create: false,
                        searchField: "text",
                        sortField: { field: "text", direction: "asc" }
                    });
        
                    el.addEventListener("change", function () {
                        Livewire.emit("statusUpdated", this.value);
                    });
        
                    console.log(`Tom Select initialized on ${el.id}`);
                });
            }
        
            initTomSelect();
        
            Livewire.hook("message.processed", () => {
                setTimeout(() => {
                    console.log("Livewire update detected");
                    initTomSelect();
                }, 100);
            });
        
           
        });
        </script> 
    
{{-- <script>
    new TomSelect("#select-beast",{
	create: true,
	sortField: {
		field: "text",
		direction: "asc"
	}
})
</script> --}}



    
   
   
        @endpush

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Janji Temu</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Data Janji Temu</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary" >Data Janji Temu</h6>
                        <div class="d-flex align-items-center" style="gap: 10px; ">
                            <!-- Input Pencarian -->
                            <input type="text" class="form-control shadow-sm" placeholder="Cari..." wire:model="searchTerm"
                                style="width: 400px; max-width: 100%; padding: 8px; font-size: 14px; border-width: 2px; border-radius: 6px;">
            
                            <!-- Dropdown Filter Status -->
                            {{-- <select data-tomselect class=" shadow-sm border-primary rounded lazyload" wire:model="filterStatus" 
                                style="width: 300px; padding: 8px; font-size: 14px; border-width: 2px; border-radius: 6px;" >
                                <option value="">Status.....</option>
                                @foreach ($janjiTemu as $l)
                                    <option value="{{ $l->status_janji_temu }}">{{ $l->status_janji_temu }}</option>
                                @endforeach
                            </select> --}}

                            
                                <select wire:model="filterStatus"  data-tomselect class=" shadow-sm border-primary rounded lazyload"   style="width: 300px; padding: 8px; font-size: 14px; border-width: 2px; border-radius: 6px;" >>
                                    <option value="">Semua Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Batal">Batal</option>
                                </select>
            
                            <!-- Tombol Reset -->
                            <button wire:click="resetFilter" class="btn btn-warning">Reset</button>
            
                            <!-- Tombol Tambah -->
                            <button wire:click="create()" class="btn btn-primary">Tambah Janji Temu +</button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>NIK Pasien</th>
                                        <th>Nama Pasien</th>
                                       
                                        <th>Nama Dokter</th>
                                        <th>Tanggal </th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($janjiTemu as $janji)
                                    <tr>
                                    <td>{{ $loop->iteration }}</td>
                                        <td>{{ $janji->pasien->nik_pasien ??'-'}}</td>
                                        <td>{{ $janji->pasien->nama_pasien ??'-'}}</td>
                                        <td>{{ $janji->dokter->nama_dokter ??'-'}}</td>
                                 
                                        <td>{{ $janji->tanggalwaktu_janji_temu }}</td>
                                        <td>{{ $janji->keluhan_janji_temu }}</td>
                                        <td>{{ $janji->status_janji_temu }}</td>
                                        <td>
                                            <button wire:click.prevent="edit({{ $janji->id }})" class="btn btn-warning">Edit</button>
                                            <button wire:click="delete({{ $janji->id }})" class="btn btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                             
                            </table>
                            <div class="d-flex justify-content-center mt-5">
                                {{ $janjiTemu->links() }}
                            </div>
                            
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
                            <h5 class="modal-title">{{ $janjiTemu_id ? 'Edit Janji Temu' : 'Tambah Janji Temu' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Pasien</label>
                               <div wire.ignore>
                                <select  @error('nik_pasien') is-invalid @enderror" 
                                data-tomselect 
                                wire:model.defer="nik_pasien" 

                                class="form-select shadow-sm border-primary rounded"
                           
                                required>
                                
                                <option value="">Pilih Pasien</option>
                                @foreach($pasiens as $p) 
                                    <option value="{{ strval($p->id) }}" data-nama="{{ $p->nama_pasien }}">
                                        {{ $p->nama_pasien }} - {{ $p->nik_pasien }}
                                    </option>
                                @endforeach
                            </select>
                               </div>
                            
                                @error('nik_pasien') 
                                    <span class="text-danger">{{ $message }}</span> 
                                @enderror
                            </div>
                            
                            
                            
                            <div class="mb-3">
                                <label>Dokter</label>
                                <select @error('nik_pasien') is-invalid @enderror"  class="form-select shadow-sm border-primary rounded" wire:model.defer="kode_dokter" data-tomselect  required >
                                    <option value="">Pilih Dokter</option>
                                    @foreach($dokters as $d)
                                        
                                        <option value="{{ strval($d->id) }}" data-nama="{{ $d->nama_dokter }}">
                                            {{ $d->nama_dokter }} - {{ $d->kode_dokter }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_dokter') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            <div class="mb-3">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" wire:model.defer="tanggalwaktu_janji_temu" required>
                                @error('tanggalwaktu_janji_temu') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Keluhan</label>
                                <textarea class="form-control" wire:model.defer="keluhan_janji_temu" required></textarea>
                                @error('keluhan_janji_temu') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select class="form-control" wire:model.defer="status_janji_temu" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Batal">Batal</option>
                                </select>
                                @error('status_janji_temu') <span class="text-danger">{{ $message }}</span> @enderror
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