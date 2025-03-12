<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';



    protected $fillable = [
        'kode_dokter',
        'nama_dokter',
        'spesialis_dokter',
        'nomorhp_dokter',

    ];
}
