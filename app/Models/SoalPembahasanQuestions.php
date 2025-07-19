<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPembahasanQuestions extends Model
{
    use HasFactory;
    protected $fillable = [
        'administrator_id',
        'kurikulum_id',
        'kelas_id',
        'mapel_id',
        'bab_id',
        'sub_bab_id',
        'questions',
        'options_key',
        'options_value',
        'answer_key',
        'skilltag',
        'difficulty',
        'explanation',
        'status_bank_soal',
        'status_soal',
        'tipe_soal',
    ];

    public function UserAccount() {
        return $this->belongsTo(UserAccount::class, 'administrator_id');
    }

    public function Kurikulum() {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function Kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function Mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function Bab() {
        return $this->belongsTo(Bab::class, 'bab_id');
    }

    public function SubBab() {
        return $this->belongsTo(SubBab::class, 'sub_bab_id');
    }

    public function SoalPembahasanAnswers() {
        return $this->hasOne(SoalPembahasanAnswers::class, 'question_id');
    }
}
