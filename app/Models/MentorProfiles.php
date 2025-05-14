<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorProfiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'personal_email',
        'status_pendidikan',
        'bidang',
        'jurusan',
        'tahun_lulus',
        'sekolah_mengajar',
        'kode_referral',
        'status_mentor'
    ];

    public function UserAccount() {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function MentorFeatureStatus() {
        return $this->hasMany(MentorFeatureStatus::class, 'mentor_id');
    }
}