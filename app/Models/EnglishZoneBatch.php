<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'batch_name',
        'start_day',
        'start_month',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function EnglishZoneBatchSchedule() {
        return $this->hasOne(EnglishZoneBatchSchedule::class, 'batch_id');
    }
}