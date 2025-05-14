<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabFeatureStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bab_id',
        'feature_id',
        'status_bab',
    ];

    public function Bab()
    {
        return $this->belongsTo(Bab::class, 'bab_id');
    }

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }
}