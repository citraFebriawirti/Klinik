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

    // Relasi dengan Pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    // Relasi dengan Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    // Relasi dengan Resep (one-to-one)
    public function resep()
    {
        return $this->hasOne(Resep::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }
}
