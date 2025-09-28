<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneBatchSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'batch_id',
        'batch_schedule_group',
        'schedule_time_group',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneBatch()
    {
        return $this->belongsTo(EnglishZoneBatch::class, 'batch_id');
    }

    public function EnglishZoneMentorSchedule()
    {
        return $this->hasOne(EnglishZoneMentorSchedule::class, 'batch_schedule_id');
    }

    public function EnglishZoneStudentBatch()
    {
        return $this->hasMany(EnglishZoneStudentBatch::class, 'batch_schedule_id');
    }
}