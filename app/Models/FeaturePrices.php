<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturePrices extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'variant_name',
        'type',
        'quantity',
        'duration',
        'price',
    ];

    public function Features() {
        return $this->belongsTo(Features::class, 'feature_id');
    }

    public function Transactions() {
        return $this->hasOne(Transactions::class, 'feature_variant_id');
    }
}