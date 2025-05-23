<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah_koin',
        'tipe_koin',
        'sumber_koin',
        'tanya_id',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Tanya()
    {
        return $this->belongsTo(Tanya::class, 'tanya_id');
    }
}