<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'sekolah',
        'fase',
        'kelas',
        'email',
        'password',
        'no_hp',
        'status',
        'kode_jenjang_murid',
    ];

}