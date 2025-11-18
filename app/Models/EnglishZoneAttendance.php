<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subscription_history_id',
        'attendance_time_in',
    ];

    public function UserAccount() {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function FeatureSubscriptionHistory() {
        return $this->belongsTo(FeatureSubscriptionHistory::class, 'subscription_history_id');
    }
}
