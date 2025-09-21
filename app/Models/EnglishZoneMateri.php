<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneMateri extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'materi_vocabulary',
        'materi_grammar',
        'video_materi',
        'lesson_plan',
        'level_id',
        'unit_id',
        'session',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZoneUnit()
    {
        return $this->belongsTo(EnglishZoneUnit::class, 'unit_id');
    }
}