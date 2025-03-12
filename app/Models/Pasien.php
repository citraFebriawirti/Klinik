<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';



    protected $fillable = [
        'nik_pasien',
        'nama_pasien',
        'tempatlahir_pasien',
        'tanggallahir_pasien',
        'jeniskelamin_pasien',
        'alamat_pasien',
        'nomorhp_pasien',

    ];
}
