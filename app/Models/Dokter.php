<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'tb_dokter';
    protected $primaryKey = 'id_dokter';
    protected $fillable = [
        'nama_dokter',
        'id_poli',
        'spesialisasi_dokter',
        'no_hp_dokter',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_dokter');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli', 'id_poli');
    }
}
