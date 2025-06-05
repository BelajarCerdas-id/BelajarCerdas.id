<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorPaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'payment_mentor_id',
        'tanya_verification_id',
        'source_payment_mentor',
        'amount',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function MentorPayments()
    {
        return $this->belongsTo(MentorPayments::class, 'payment_mentor_id');
    }

    public function TanyaVerifications()
    {
        return $this->belongsTo(TanyaVerifications::class, 'tanya_verification_id');
    }
}