<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
<<<<<<< HEAD
=======

>>>>>>> 70989e5771b6efbed425bff1fb9b5409a6af36ef
    protected $table = 'tb_pasien';
    protected $primaryKey = 'id_pasien';
    protected $fillable = [
        'nama_pasien',
        'nik_pasien',
        'tanggallahir_pasien',
        'jenis_kelamin_pasien',
        'alamat_pasien',
<<<<<<< HEAD
        'no_hp_pasien',
=======
        'no_hp_pasien'
>>>>>>> 70989e5771b6efbed425bff1fb9b5409a6af36ef
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pasien');
    }
}
