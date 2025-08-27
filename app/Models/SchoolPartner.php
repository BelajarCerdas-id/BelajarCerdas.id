<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPartner extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'nama_kepsek',
        'nik_kepsek',
    ];
}