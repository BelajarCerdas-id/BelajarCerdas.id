<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPembahasanAnswers extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'question_id',
        'user_answer_option',
        'question_score',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function soalPembahasanQuestions()
    {
        return $this->belongsTo(SoalPembahasanQuestions::class, 'question_id');
    }
}
