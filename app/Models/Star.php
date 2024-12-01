<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mentor',
        'email',
        'sekolah',
        'status',
        'id_tanya',
        'payment_status',
        'kode_payment'
    ];
}
