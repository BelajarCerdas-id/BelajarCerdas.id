<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_callback' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'feature_id',
        'feature_variant_id',
        'order_id',
        'payment_method',
        'snap_token',
        'transaction_status',
        'transaction_callback',
        'price',
        'transaction_source',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }

    public function FeaturePrices()
    {
        return $this->belongsTo(FeaturePrices::class, 'feature_variant_id');
    }

    public function FeatureSubscriptionHistory()
    {
        return $this->hasOne(FeatureSubscriptionHistory::class, 'transaction_id');
    }
}