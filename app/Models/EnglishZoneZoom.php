<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneZoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'batch_schedule_id',
        'mentor_id',
        'level_id',
        'session',
        'link_zoom',
        'meeting_id',
        'zoom_passcode',
    ];

    public function Administrator()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }

    public function EnglishZoneBatchSchedule()
    {
        return $this->belongsTo(EnglishZoneBatchSchedule::class, 'batch_schedule_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }
}