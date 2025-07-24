<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserAccount extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'email',
        'password',
        'no_hp',
        'role',
        'status_akun',
    ];

    // PROFILE USER
    public function StudentProfiles() {
        return $this->hasOne(StudentProfiles::class, 'user_id');
    }

    public function MentorProfiles() {
        return $this->hasOne(MentorProfiles::class, 'user_id');
    }

    public function OfficeProfiles() {
        return $this->hasOne(OfficeProfiles::class, 'user_id');
    }

    // TRANSAKSI
    public function Transactions() {
        return $this->hasOne(Transactions::class, 'user_id');
    }

    // ENGLISH ZONE
    public function EnglishZoneUser() {
        return $this->hasOne(EnglishZoneUser::class, 'user_id');
    }
    public function EnglishZoneMateri() {
        return $this->hasOne(englishZoneMateri::class, 'user_id');
    }

    public function EnglishZoneSoal() {
        return $this->hasOne(englishZoneMateri::class, 'user_id');
    }

    public function EnglishZoneJawaban() {
        return $this->hasOne(englishZoneMateri::class, 'user_id');
    }

    // TANYA COIN
    public function TanyaUserCoin() {
        return $this->hasOne(TanyaUserCoin::class, 'user_id');
    }

    // SYLLABUS
    public function Kurikulum() {
        return $this->hasOne(Kurikulum::class, 'user_id');
    }

    public function Fase() {
        return $this->hasOne(Fase::class, 'user_id');
    }

    public function Kelas() {
        return $this->hasOne(Kelas::class, 'user_id');
    }

    public function Mapel() {
        return $this->hasOne(Mapel::class, 'user_id');
    }

    // TANYA ACCESS
    public function TanyaAccess() {
        return $this->hasOne(tanyaAccess::class, 'user_id');
    }

    // TANYA
    public function Tanya() {
        return $this->hasOne(Tanya::class, 'viewed_by');
    }

    // TANYA VERIFICATION
    public function TanyaVerificationMentor()
    {
        return $this->hasMany(TanyaVerifications::class, 'mentor_id', 'id');
    }

    public function TanyaVerificationAdministrator()
    {
        return $this->hasOne(TanyaVerifications::class, 'administrator_id');
    }

    // TANYA RANK PROGRESS
    public function TanyaRankProgress() {
        return $this->hasOne(TanyaRankMentorProgress::class, 'mentor_id');
    }

    // MENTOR PAYMENT DETAIL
    public function MentorPaymentDetail()
    {
        return $this->hasOne(MentorPaymentDetail::class, 'mentor_payment_id');
    }

    // SOAL PEMBAHASAN QUESTIONS
    public function SoalPembahasanQuestions() {
        return $this->hasOne(SoalPembahasanQuestions::class, 'administrator_id');
    }

    public function getProfileAttribute() {
        return match ($this->role) {
            'Siswa' => $this->StudentProfiles,
            'Murid' => $this->StudentProfiles,
            'Mentor' => $this->MentorProfiles,
            'Administrator' => $this->OfficeProfiles,
            'Admin Sales' => $this->OfficeProfiles,
            'Sales' => $this->OfficeProfiles,
            // default => null, // Atau bisa disesuaikan dengan nilai lain yang sesuai
        };
    }
}
