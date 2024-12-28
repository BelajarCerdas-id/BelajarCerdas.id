<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class englishZoneSoal extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_lengkap',
        'status',
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
        'tipe_upload'
    ];
}
