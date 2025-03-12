<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Buku as BukuModel;
use App\Models\Genre;



class Buku extends Component
{

    public $buku, $kode_buku, $judul_buku, $penulis, $buku_id, $genre_buku, $searchTerm, $filterGenre;
    public $isOpen = 0;
    protected $listeners = ['destroy'];

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_buku');
    }

    public function render()
    {
        $query = BukuModel::with('genre');

        // Pencarian berdasarkan semua field
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('kode_buku', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('judul_buku', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('penulis', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('genre', function ($q) {
                        $q->where('nama_genre', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }

        if ($this->filterGenre) {
            $query->where('genre_buku', $this->filterGenre);
        }

        $this->buku = $query->get();
        $genres = Genre::all();

        return view('livewire.buku', [
            'buku' => $this->buku,
            'genres' => $genres
        ]);
    }

    public function resetFilter()
    {
        $this->searchTerm = '';
        $this->filterGenre = '';
    }

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('openModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->emit('closeModal');
    }

    public function resetFields()
    {
        $this->kode_buku = '';
        $this->judul_buku = '';
        $this->penulis = '';
        $this->buku_id = null;
        $this->genre_buku = null;
    }

    public function store()
    {
        $this->validate([
            'genre_buku' => 'nullable|exists:genres,id',
            'kode_buku' => 'required|unique:buku,kode_buku,' . ($this->buku_id ?? 'NULL'),
            'judul_buku' => 'required',
            'penulis' => 'required',
        ]);

        BukuModel::updateOrCreate(['id' => $this->buku_id], [
            'kode_buku' => $this->kode_buku,
            'judul_buku' => $this->judul_buku,
            'penulis' => $this->penulis,
            'genre_buku' => $this->genre_buku
        ]);

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => $this->buku_id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $buku = BukuModel::with('genre')->findOrFail($id);
        $this->buku_id = $id;
        $this->kode_buku = $buku->kode_buku;
        $this->judul_buku = $buku->judul_buku;
        $this->penulis = $buku->penulis;
        $this->genre_buku = $buku->genre?->id; // Ambil ID genre dari relasi genre

        $this->openModal();
    }

    public function delete($id)
    {
        $this->dispatchBrowserEvent('confirmDelete', [
            'id' => $id,
            'title' => 'Yakin ingin menghapus?',
            'text' => 'Data akan dihapus secara permanen.',
            'icon' => 'warning'
        ]);
    }

    public function destroy($id)
    {
        BukuModel::find($id)->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil dihapus',
            'icon' => 'success'
        ]);
    }
}
