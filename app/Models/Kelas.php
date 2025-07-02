<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas',
        'kode',
        'fase_id',
        'kurikulum_id',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }
    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function Mapel()
    {
        return $this->hasOne(Mapel::class, 'kelas_id');
    }

    public function Bab()
    {
        return $this->hasOne(Bab::class, 'kelas_id');
    }

    public function SubBab()
    {
        return $this->hasOne(SubBab::class, 'kelas_id');
    }

    Public function Tanya()
    {
        return $this->hasOne(Tanya::class, 'kelas_id');
    }

    public function StudentProfiles()
    {
        return $this->hasOne(StudentProfiles::class, 'kelas_id');
    }

    // SOAL PEMBAHASAN QUESTIONS
    public function SoalPembahasanQuestions() {
        return $this->hasOne(SoalPembahasanQuestions::class, 'administrator_id');
    }
}
