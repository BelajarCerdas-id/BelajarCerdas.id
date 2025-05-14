<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishZoneUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feature_id',
        'feature_variant_id',
        'duration_paket_id',
        'status',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }

    public function FeaturePricesVariant()
    {
        return $this->belongsTo(FeaturePrices::class, 'feature_variant_id');
    }

    public function FeaturePricesDuration()
    {
        return $this->belongsTo(FeaturePrices::class, 'duration_paket_id');
    }
}