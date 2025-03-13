<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'tb_resep';
    protected $primaryKey = 'id_resep';
    protected $fillable = ['id_pemeriksaan', 'status_resep', 'total_harga_resep'];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }

    public function details()
    {
        return $this->hasMany(ResepDetail::class, 'id_resep');
    }
}
