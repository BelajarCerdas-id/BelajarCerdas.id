<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZonePassage extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'level_id',
        'passage_content',
        'audio_file',
        'audio_script',
        'passage_type',
        'passage_status',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }
}