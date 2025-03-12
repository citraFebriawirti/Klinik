<div class="container mt-4">
    <h2 class="mb-4">Data Buku</h2>

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

    {{-- Pencarian & Filter --}}
    <div class="d-flex mb-3">
        <input type="text" class="form-control me-2" placeholder="Cari..." wire:model.debounce.500ms="searchTerm">

        <select class="form-control me-2" wire:model="filterGenre">
            <option value="">Semua Genre</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->nama_genre }}</option>
            @endforeach
        </select>

        <button wire:click="resetFilter" class="btn btn-secondary">Reset</button>
    </div>

    {{-- Tombol Tambah --}}
    <button wire:click="create()" class="btn btn-primary mb-3">Tambah Buku</button>

    {{-- Tabel Data --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Genre Buku</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $b)
            <tr>
                <td>{{ $b->kode_buku }}</td>
                <td>{{ $b->judul_buku }}</td>
                <td>{{ $b->penulis }}</td>
                <td>{{ $b->genre->nama_genre ?? '-' }}</td>
                <td>
                    <button wire:click="edit({{ $b->id }})" class="btn btn-warning">Edit</button>
                    <button wire:click="delete({{ $b->id }})" class="btn btn-danger">Hapus</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>


    </table>

    {{-- Modal --}}
    @if($isOpen)
    <div class="modal" style="display:block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $buku_id ? 'Edit Buku' : 'Tambah Buku' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kode Buku</label>
                            <input type="text" class="form-control" wire:model="kode_buku">
                            @error('kode_buku') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Judul Buku</label>
                            <input type="text" class="form-control" wire:model="judul_buku">
                            @error('judul_buku') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Penulis</label>
                            <input type="text" class="form-control" wire:model="penulis">
                            @error('penulis') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="genre_buku">Genre</label>
                            <select wire:model="genre_buku" id="genre_buku" class="form-control">
                                <option value="" {{ is_null($genre_buku) ? 'selected' : '' }}>Pilih Genre</option>
                                @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ $genre_buku == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->nama_genre }}
                                </option>
                                @endforeach
                            </select>
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