<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_fase',
        'kode',
        'kurikulum_id',
    ];

    public function SubBab()
    {
        return $this->hasOne(SubBab::class, 'fase_id');
    }

    public function Bab()
    {
        return $this->hasOne(Bab::class, 'fase_id');
    }
    public function Mapel()
    {
        return $this->hasOne(Mapel::class, 'fase_id');
    }
    public function Kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function Kelas()
    {
        return $this->hasOne(Kelas::class, 'fase_id');
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    Public function Tanya()
    {
        return $this->hasOne(Tanya::class, 'fase_id');
    }

    public function StudentProfiles()
    {
        return $this->hasOne(StudentProfiles::class, 'fase_id');
    }


}