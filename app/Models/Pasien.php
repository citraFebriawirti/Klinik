<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'tb_pasien';
    protected $primaryKey = 'id_pasien';
    protected $fillable = [
        'nama_pasien',
        'nik_pasien',
        'tanggallahir_pasien',
        'jenis_kelamin_pasien',
        'alamat_pasien',
        'no_hp_pasien',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pasien');
    }
}
