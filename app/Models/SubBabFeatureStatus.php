<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBabFeatureStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_bab_id',
        'feature_id',
        'status_sub_bab',
    ];

    public function Subbab()
    {
        return $this->belongsTo(SubBab::class, 'sub_bab_id');
    }

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }
}