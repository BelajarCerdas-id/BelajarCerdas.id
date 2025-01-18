<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class englishZoneSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'modul_soal',
        'jenjang',
        'status',
        'soal',
        'option_pilihan',
        'jawaban_pilihan',
        'tingkat_kesulitan',
        'jawaban_benar',
        'deskripsi_jawaban',
        'tipe_upload',
        'status_soal'
    ];
}