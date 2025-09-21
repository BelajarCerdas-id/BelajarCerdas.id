<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'unit_name',
        'level_id',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneLevel()
    {
        return $this->belongsTo(EnglishZoneLevel::class, 'level_id');
    }

    public function EnglishZoneMateri()
    {
        return $this->hasOne(EnglishZoneMateri::class, 'unit_id');
    }
}