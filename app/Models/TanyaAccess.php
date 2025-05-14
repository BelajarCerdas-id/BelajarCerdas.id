<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanyaAccess extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_akhir',
        'status_access',
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    public function getTanggalMulaiAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d', $value) : null;
    }

    public function getTanggalAkhirAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d', $value) : null;
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}
