<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnglishZoneMateri extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id',
    'modul',
    'judul_modul',
    'materi_pdf',
    'judul_video',
    'link_video',
    'modul_download',
    'modul_jenjang',
    ];

    public function modulLock()
    {
        return $this->hasMany(modulLock::class);
    }

    public function UserAccount()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }
}
