<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'status_otp',
    ];
}