<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class englishZoneSoal extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_lengkap',
        'status',
        'modul',
        'jenjang',
        'soal',
        'pilihan_A',
        'bobot_A',
        'pilihan_B',
        'bobot_B',
        'pilihan_C',
        'bobot_C',
        'pilihan_D',
        'bobot_D',
        'pilihan_E',
        'bobot_E',
        'tingkat_kesulitan',
        'jawaban',
        'deskripsi_jawaban',
        'tipe_upload',
        'status_soal',
    ];
}