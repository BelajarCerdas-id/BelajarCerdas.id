<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class visitasiData extends Model
{
    use HasFactory;

    protected $fillable = [
        'provinsi',
        'kab_kota',
        'kecamatan',
        'jenjang_sekolah',
        'sekolah',
        'status_sekolah',
        'tanggal_mulai',
        'tanggal_akhir',
        'status_kunjungan',
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    public function getTanggalMulaiAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value);
    }

    public function getTanggalAkhirAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value);
    }
}