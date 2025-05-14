<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_kurikulum',
        'kode',
    ];

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function Fase()
    {
        return $this->hasOne(Fase::class, 'kurikulum_id');
    }

    public function Mapel()
    {
        return $this->hasOne(Mapel::class, 'kurikulum_id');
    }

    public function Bab()
    {
        return $this->hasOne(Bab::class, 'mapel_id');
    }
}