<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturesRoles extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'feature_role',
    ];

    public function Features()
    {
        return $this->belongsTo(Features::class, 'feature_id');
    }
}