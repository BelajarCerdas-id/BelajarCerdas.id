<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class englishZoneJawaban extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_lengkap',
        'email',
        'sekolah',
        'kelas',
        'status',
        'jenjang_murid',
        'id_soal',
        'jawaban',
        'pilihan_ganda',
        'nilai_jawaban',
        'no_soal',
        'modul',
    ];
}