<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratPks extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'status',
        'surat_pks',
        'tipe_surat_pks',
    ];
}