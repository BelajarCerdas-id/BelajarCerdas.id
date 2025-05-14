<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneSoal extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'modul_soal',
        'jenjang_soal',
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

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}