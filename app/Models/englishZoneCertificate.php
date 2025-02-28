<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class englishZoneCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'status',
        'certificate',
    ];
}