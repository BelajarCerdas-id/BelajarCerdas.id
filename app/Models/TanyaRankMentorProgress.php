<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaRankMentorProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'rank_id',
        'jumlah_soal_diterima',
        'jumlah_soal_ditolak',
        'jumlah_soal_approved',
        'jumlah_soal_rejected',
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