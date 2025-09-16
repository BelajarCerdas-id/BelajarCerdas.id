<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneMentorSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'mentor_id',
        'batch_schedule_id',
        'status_schedule'
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }

    public function EnglishZoneBatchSchedule()
    {
        return $this->belongsTo(EnglishZoneBatchSchedule::class, 'batch_schedule_id');
    }

}