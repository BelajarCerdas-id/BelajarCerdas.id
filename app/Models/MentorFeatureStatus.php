<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorFeatureStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'feature_id',
        'status_mentor',
    ];

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }
}