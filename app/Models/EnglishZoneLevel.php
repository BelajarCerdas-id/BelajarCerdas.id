<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'level_name',
        'lesson_plan',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneQuestions()
    {
        return $this->hasOne(EnglishZoneQuestions::class, 'level_id');
    }

    public function EnglishZoneMateri()
    {
        return $this->hasOne(EnglishZoneMateri::class, 'level_id');
    }

    public function EnglishZoneBatch()
    {
        return $this->hasMany(EnglishZoneBatch::class, 'level_id');
    }

    public function EnglishZoneStudentBatch()
    {
        return $this->hasMany(EnglishZoneStudentBatch::class, 'level_id');
    }
}