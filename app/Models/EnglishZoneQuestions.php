<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneQuestions extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'administrator_id',
        'level_id',  
        'session_id',
        'passage_id',
        'questions', 
        'tipe_soal',
        'question_format',
        'options_key', 
        'options_value', 
        'answer_key', 
        'answer_text', 
        'difficulty', 
        'status_bank_soal',
        'explanation', 
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZoneSession()
    {
        return $this->belongsTo(EnglishZoneSession::class, 'session_id');
    }

    public function EnglishZoneAnswers() {
        return $this->hasMany(EnglishZoneAnswers::class, 'question_id');
    }

    public function EnglishZonePassage() {
        return $this->belongsTo(EnglishZonePassage::class, 'passage_id');
    }
}