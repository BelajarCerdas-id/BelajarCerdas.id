<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'personal_email',
        'sekolah',
        'fase_id',
        'kelas_id',
        'mentor_referral_code',
        'kode_jenjang_murid',
    ];

    public function userAccount() {
        return $this->belongsTo(UserAccount::class);
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}