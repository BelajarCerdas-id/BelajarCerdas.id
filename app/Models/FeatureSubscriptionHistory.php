<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureSubscriptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'transaction_id',
        'start_date',
        'end_date',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'student_id');
    }

    public function Transactions()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    public function SoalPembahasanAnswers()
    {
        return $this->hasOne(SoalPembahasanAnswers::class, 'subscription_id');
    }
}