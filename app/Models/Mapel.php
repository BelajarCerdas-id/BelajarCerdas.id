<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mata_pelajaran',
        'kode',
        'harga_koin',
        'kelas_id',
        'fase_id',
        'kurikulum_id',
        'status_mata_pelajaran',
    ];

    public function SubBab()
    {
        return $this->hasOne(SubBab::class, 'mapel_id');
    }

    public function Bab()
    {
        return $this->hasOne(Bab::class, 'mapel_id');
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function Kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    Public function Tanya()
    {
        return $this->hasOne(Tanya::class, 'mapel_id');
    }

    // SOAL PEMBAHASAN QUESTIONS
    public function SoalPembahasanQuestions() {
        return $this->hasOne(SoalPembahasanQuestions::class, 'administrator_id');
    }
}
