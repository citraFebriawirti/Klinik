<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rMedis extends Model
{
    use HasFactory;
    protected $table = 'tb_r_medis';
    protected $primaryKey = 'id_rmed';
    protected $fillable = [
        'norm',
        'id_pasien',
        'id_pendaftaran',
        'id_pemeriksaan',
    ];
}
