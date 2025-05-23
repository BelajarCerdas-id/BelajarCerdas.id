<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaMentorPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'total_ammount',
        'status_payment',
    ];

    public function Mentor()
    {
        return $this->belongsTo(UserAccount::class, 'mentor_id');
    }

    public function TanyaRank()
    {
        return $this->belongsTo(TanyaRankMentor::class, 'rank_id');
    }
}