<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaRankMentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_rank',
        'jumlah_soal_diterima',
        'jumlah_soal_approved',
        'harga_per_soal',
    ];

    public function TanyaRankProgress()
    {
        return $this->hasOne(TanyaRankMentorProgress::class, 'rank_id');
    }

    public function TanyaRankMentorPayment()
    {
        return $this->hasOne(TanyaRankMentorProgress::class, 'rank_id');
    }
}