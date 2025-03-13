<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'tb_obat';
    protected $primaryKey = 'id_obat';
    protected $fillable = ['nama_obat', 'jenis_obat', 'satuan_obat', 'stok_obat', 'harga_obat'];
}
