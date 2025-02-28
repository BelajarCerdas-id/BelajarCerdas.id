<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modulLock extends Model
{

    protected $fillable = [
        'nama_lengkap',
        'module_id',
        'is_completed',
    ];
    public function englishZoneMateri()
    {
        return $this->belongsTo(englishZoneMateri::class);
    }
}