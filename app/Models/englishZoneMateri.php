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
    'materi_pdf',
    'judul_video',
    'link_video',
    'modul_download',
    'jenjang_murid',
    ];

    public function modulLock()
    {

    return $this->hasMany(modulLock::class);

    }
}