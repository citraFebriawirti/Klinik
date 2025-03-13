<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;
    protected $table = 'tb_poli';
    protected $primaryKey = 'id_poli';
    protected $fillable = ['nama_poli'];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_poli');
    }

    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'id_poli');
    }
}
