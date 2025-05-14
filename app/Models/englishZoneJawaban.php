<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneJawaban extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'jenjang_soal_user',
        'soal_id',
        'jawaban',
        'pilihan_ganda',
        'nilai_jawaban',
        'no_soal',
        'modul',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}
