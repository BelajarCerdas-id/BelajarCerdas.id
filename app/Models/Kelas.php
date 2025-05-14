<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas',
        'kode',
        'fase_id',
    ];

    public function Fase()
    {
        return $this->belongsTo(Fase::class);
    }

    Public function Tanya()
    {
        return $this->hasOne(Tanya::class, 'kelas_id');
    }

    public function StudentProfiles()
    {
        return $this->hasOne(StudentProfiles::class, 'kelas_id');
    }
}