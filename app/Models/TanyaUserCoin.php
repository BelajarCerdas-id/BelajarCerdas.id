<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaUserCoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah_koin'
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}