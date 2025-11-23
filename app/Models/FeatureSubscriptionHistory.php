<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureSubscriptionHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'student_id',
        'transaction_id',
        'fase_id',
        'start_date',
        'end_date',
        'subscription_status',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function Transactions()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    public function SoalPembahasanAnswers()
    {
        return $this->hasOne(SoalPembahasanAnswers::class, 'subscription_id');
    }

    public function Fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function EnglishZoneStudentBatch()
    {
        return $this->hasMany(EnglishZoneStudentBatch::class, 'subscription_history_id');
    }

    public function EnglishZoneAttendance() {
        return $this->hasMany(EnglishZoneAttendance::class, 'subscription_history_id');
    }

    public function EnglishZoneAnswers() {
        return $this->hasMany(EnglishZoneAnswers::class, 'subscription_history_id');
    }
}