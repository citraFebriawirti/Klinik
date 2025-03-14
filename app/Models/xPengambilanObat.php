<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xPengambilanObat extends Model
{
    protected $table = 'pengambilan_obat';
    protected $primaryKey = 'id_pengambilan';
    protected $fillable = ['id_resep', 'status_pengambilan_obat', 'tanggal_ambil_pengambilan_obat'];
}
