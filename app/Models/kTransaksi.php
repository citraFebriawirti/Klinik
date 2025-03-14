<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kTransaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transkasi'; // or null
    protected $fillable = [
        'id_resep',
        'total_bayar_transaksi',
        'status_transaksi',
        'tanggal_bayar_transaksi',
    ];
}
