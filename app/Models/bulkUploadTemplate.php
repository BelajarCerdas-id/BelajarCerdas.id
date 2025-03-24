<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bulkUploadTemplate extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'status',
        'nama_file',
        'jenis_file',
        'status_template',
    ];
}