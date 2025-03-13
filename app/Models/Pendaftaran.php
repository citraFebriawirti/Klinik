<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    protected $table = 'tb_pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    protected $fillable = [
        'id_pasien',
        'id_poli',
        'status_pendaftaran',
        'tanggal_daftar_pendaftaran',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function pemeriksaan()
    {
        return $this->hasOne(Pemeriksaan::class, 'id_pendaftaran');
    }
}
