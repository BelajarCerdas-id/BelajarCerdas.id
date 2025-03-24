<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataSuratPks extends Model
{
    use HasFactory;

    protected $fillable = [
        'provinsi',
        'kab_kota',
        'kecamatan',
        'jenjang_sekolah',
        'sekolah',
        'status_sekolah',
        'alamat_sekolah',
        'nama_kepsek',
        'nip_kepsek',
        'paket_kerjasama',
        'tanggal_mulai',
        'tanggal_akhir',
        'status_paket_kerjasama',
        'status_pks',
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
}
