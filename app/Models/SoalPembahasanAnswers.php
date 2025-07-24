<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPembahasanAnswers extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subscription_id',
        'question_id',
        'user_answer_option',
        'question_score',
        'status_answer',
        'exam_answer_duration',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function SoalPembahasanQuestions()
    {
        return $this->belongsTo(SoalPembahasanQuestions::class, 'question_id');
    }

    public function FeatureSubscriptionHistory()
    {
        return $this->belongsTo(FeatureSubscriptionHistory::class, 'subscription_id');
    }
}
