<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class englishZoneMateri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'status',
        'modul',
        'judul',
        'pdf_file',
        'video_materi',
        'jenjang_murid'
    ];
}
