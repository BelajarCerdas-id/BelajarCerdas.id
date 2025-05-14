<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tanya extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'fase_id',
        'kelas_id',
        'mapel_id',
        'bab_id',
        'harga_koin',
        'pertanyaan',
        'image_tanya',
        'status_soal_student',
        'mentor_id',
        'jawaban',
        'image_jawab',
        'status_soal',
        'alasan_ditolak',
    ];

    protected $dates = ['created_at'];

    public function Student()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function Mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function Bab()
    {
        return $this->belongsTo(Bab::class, 'bab_id');
    }

}