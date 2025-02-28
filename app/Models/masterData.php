<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterData extends Model
{
    use HasFactory;

    protected $fillable = [
        'provinsi',
        'kab_kota',
        'kecamatan',
        'jenjang_sekolah',
        'sekolah',
        'status_sekolah',
        'paket_kerjasama',
    ];
}