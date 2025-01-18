<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class englishZoneMateri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'status',
        'modul',
        'judul_modul',
        'pdf_file',
        'judul_video',
        'link_video',
        'jenjang_murid'
    ];
}