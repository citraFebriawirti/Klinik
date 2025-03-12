<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserComponent extends Component
{
    public $nama_user, $jenis_kelamin_user, $alamat_user, $nomor_hp_user, $email, $password, $status_user, $userId;
    public $isUpdate = false;

    protected $rules = [
        'nama_user' => 'required',
        'jenis_kelamin_user' => 'required|in:Laki-laki,Perempuan',
        'alamat_user' => 'required',
        'nomor_hp_user' => 'required|digits_between:10,15',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'status_user' => 'required|in:Aktif,Tidak Aktif'
    ];

    public function store()
    {
        $this->validate();

        User::create([
            'nama_user' => $this->nama_user,
            'jenis_kelamin_user' => $this->jenis_kelamin_user,
            'alamat_user' => $this->alamat_user,
            'nomor_hp_user' => $this->nomor_hp_user,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status_user' => $this->status_user
        ]);

        $this->dispatchBrowserEvent('swal', [
            'icon' => 'success',
            'title' => 'User berhasil ditambahkan!'
        ]);

        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->nama_user = $user->nama_user;
        $this->jenis_kelamin_user = $user->jenis_kelamin_user;
        $this->alamat_user = $user->alamat_user;
        $this->nomor_hp_user = $user->nomor_hp_user;
        $this->email = $user->email;
        $this->status_user = $user->status_user;
        $this->isUpdate = true;
    }

    public function update()
    {
        $this->validate([
            'nama_user' => 'required',
            'jenis_kelamin_user' => 'required|in:Laki-laki,Perempuan',
            'alamat_user' => 'required',
            'nomor_hp_user' => 'required|digits_between:10,15',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'status_user' => 'required|in:Aktif,Tidak Aktif'
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'nama_user' => $this->nama_user,
            'jenis_kelamin_user' => $this->jenis_kelamin_user,
            'alamat_user' => $this->alamat_user,
            'nomor_hp_user' => $this->nomor_hp_user,
            'email' => $this->email,
            'status_user' => $this->status_user
        ]);

        $this->dispatchBrowserEvent('swal', [
            'icon' => 'success',
            'title' => 'User berhasil diperbarui!'
        ]);

        $this->resetForm();
    }

    public function delete($id)
    {
        User::destroy($id);
        $this->dispatchBrowserEvent('swal', [
            'icon' => 'success',
            'title' => 'User berhasil dihapus!'
        ]);
    }

    public function resetForm()
    {
        $this->nama_user = '';
        $this->jenis_kelamin_user = '';
        $this->alamat_user = '';
        $this->nomor_hp_user = '';
        $this->email = '';
        $this->password = '';
        $this->status_user = '';
        $this->isUpdate = false;
    }

    public function render()
    {
        return view('livewire.user-component', [
            'users' => User::all()
        ]);
    }
}