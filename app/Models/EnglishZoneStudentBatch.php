<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneStudentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'level_id',
        'batch_schedule_id',
        'mentor_id',
    ];

    public function Student()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZoneBatchSchedule()
    {
        return $this->belongsTo(EnglishZoneBatchSchedule::class, 'batch_schedule_id');
    }

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }
}