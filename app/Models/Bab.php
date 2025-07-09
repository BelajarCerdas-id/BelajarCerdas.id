<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bab extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_bab',
        'semester',
        'kode',
        'kelas_id',
        'mapel_id',
        'fase_id',
        'kurikulum_id',
    ];

    // buat nentuin bab publish atau unpublish di fitur yang di aktifkan
    public function BabFeatureStatuses()
    {
        return $this->hasMany(BabFeatureStatus::class, 'bab_id');
    }

    public function SubBab()
    {
        return $this->hasOne(SubBab::class, 'bab_id');
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function Mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function Kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    Public function Tanya()
    {
        return $this->hasOne(Tanya::class, 'bab_id');
    }

    // SOAL PEMBAHASAN QUESTIONS
    public function SoalPembahasanQuestions() {
        return $this->hasOne(SoalPembahasanQuestions::class, 'administrator_id');
    }
}
