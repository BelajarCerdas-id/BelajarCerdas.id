<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneAnswers extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subscription_history_id',
        'level_id',
        'passage_id',
        'question_id',
        'user_answer_option',
        'user_answer_text',
        'user_answer_audio',
        'question_score',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function EnglishZoneQuestions()
    {
        return $this->belongsTo(EnglishZoneQuestions::class, 'question_id');
    }

    public function FeatureSubscriptionHistory()
    {
        return $this->belongsTo(FeatureSubscriptionHistory::class, 'subscription_history_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZonePassage()
    {
        return $this->belongsTo(EnglishZonePassage::class, 'passage_id');
    }
}