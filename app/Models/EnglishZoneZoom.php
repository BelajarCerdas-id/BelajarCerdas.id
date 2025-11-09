<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneZoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'mentor_id',
        'link_zoom',
        'meeting_id',
        'zoom_passcode',
    ];

    public function Administrator()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }
}