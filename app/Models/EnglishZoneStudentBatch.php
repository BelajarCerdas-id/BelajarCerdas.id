<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneStudentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'subscription_history_id', 
        'level_id', 
        'level_start_date', 
        'level_end_date', 
        'batch_schedule_id',
        'mentor_id'
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

    public function FeatureSubscriptionHistory()
    {
        return $this->belongsTo(FeatureSubscriptionHistory::class, 'subscription_history_id');
    }

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }
}