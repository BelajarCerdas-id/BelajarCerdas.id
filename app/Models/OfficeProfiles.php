<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeProfiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap'
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }
}