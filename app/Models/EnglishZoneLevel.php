<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'level_name',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneQuestions()
    {
        return $this->hasOne(EnglishZoneQuestions::class, 'level_id');
    }
}