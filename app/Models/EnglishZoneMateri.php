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
        'materi_lesson_plan',
        'video_materi',
        'level_id',
        'session_id',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZoneSession()
    {
        return $this->belongsTo(EnglishZoneSession::class, 'session_id');
    }
}