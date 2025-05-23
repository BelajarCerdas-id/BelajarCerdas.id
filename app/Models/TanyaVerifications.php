<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaVerifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'tanya_id',
        'harga_soal',
        'administrator_id',
        'status_verifikasi',
        'alasan_verifikasi_ditolak',
    ];

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }

    public function Tanya()
    {
        return $this->belongsTo(Tanya::class, 'tanya_id');
    }

    public function Administrator()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

}