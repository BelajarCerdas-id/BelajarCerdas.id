<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_fitur',
    ];

    public function FeaturesRoles()
    {
        return $this->hasOne(FeaturesRoles::class, 'feature_id');
    }

    public function FeaturePrices()
    {
        return $this->hasOne(FeaturePrices::class, 'feature_id');
    }

    public function Transactions()
    {
        return $this->hasOne(Transactions::class, 'feature_id');
    }

    public function BabFeatureStatus()
    {
        return $this->hasOne(BabFeatureStatus::class, 'feature_id');
    }

    public function SubBabFeatureStatus()
    {
        return $this->hasOne(SubBabFeatureStatus::class, 'feature_id');
    }

    public function MentorFeatureStatus()
    {
        return $this->hasOne(MentorFeatureStatus::class, 'feature_id');
    }

    public function EnglishZoneUser()
    {
        return $this->hasOne(EnglishZoneUser::class, 'feature_id');
    }
}