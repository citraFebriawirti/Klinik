<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;
    protected $table = 'tb_pemeriksaan';
    protected $primaryKey = 'id_pemeriksaan';
    protected $fillable = [
        'id_pendaftaran',
        'id_dokter',
        'diagnosa_pemeriksaan',
        'catatan_pemeriksaan',
        'tanggal_periksa_pemeriksaan',
    ];
}
