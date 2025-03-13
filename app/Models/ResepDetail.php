<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepDetail extends Model
{
    protected $table = 'tb_resep_detail';
    protected $primaryKey = 'id_resep_detail';
    protected $fillable = [
        'id_resep',
        'id_obat',
        'is_racik',
        'nama_racik',
        'dosis_resep_detail',
        'jumlah_resep_detail',
        'aturan_pakai_resep_detail',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
