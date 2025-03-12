<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'nama_user',
        'jenis_kelamin_user',
        'alamat_user',
        'nomor_hp_user',
        'email',
        'password',
        'status_user'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public static function GenerateID()
    {

        $prefix = "USER" . date('Ymd');
        $lastID = DB::table('users')->where('id_user', 'like', $prefix . '%')->max('id_user');

        if ($lastID == null) {
            return $prefix . "0000001";
        } else {
            $lastID = str_replace($prefix, '', $lastID);
            $lastID = (int) $lastID;
            $lastID += 1;
            $lastID = str_pad($lastID, 7, '0', STR_PAD_LEFT);
            return $prefix . $lastID;
        }
    }
}