<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keynote extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'sekolah',
        'fase',
        'kelas',
        'no_hp',
        'fase_catatan',
        'kelas_catatan',
        'mapel',
        'bab',
        'catatan',
        'image_catatan',
    ];
}
