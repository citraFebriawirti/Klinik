<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JanjiTemu extends Model
{
    use HasFactory;

    protected $table = 'janji_temu';

    protected $fillable = [
        'tanggalwaktu_janji_temu',
        'keluhan_janji_temu',
        'status_janji_temu',
        'nik_pasien',  // Tambahkan foreign key
        'kode_dokter'  // Tambahkan foreign key
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'nik_pasien', 'id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kode_dokter', 'id');
    }
}
