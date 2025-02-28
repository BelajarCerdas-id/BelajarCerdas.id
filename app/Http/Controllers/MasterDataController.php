<?php

namespace App\Http\Controllers;

use App\Models\masterData;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function inputDataSekolah(Request $request)
    {
        $user = session('user');

        $request->validate([
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
            'jenjang_sekolah' => 'required',
            'sekolah' => 'required',
            'status_sekolah' => 'required',
            'paket_kerjasama.*' => 'required'
        ], [
            'provinsi.required' => 'Harap pilih Provinsi!',
            'kab_kota.required' => 'Harap pilih Kabupaten / Kota!',
            'kecamatan.required' => 'Harap pilih Kecamatan!',
            'jenjang_sekolah.required' => 'Harap pilih jenjang sekolah!',
            'sekolah.required' => 'Harap pilih provinsi!',
            'status_sekolah.required' => 'Harap pilih status sekolah!',
            'paket_kerjasama.*.required' => 'Harap pilih tipe paket kerjasama!',
        ]);

        $paketKerjasama = $request->input('paket_kerjasama');

        foreach($paketKerjasama as $data => $valuePaketKerjasama) {
            masterData::create([
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'kecamatan' => $request->kecamatan,
                'jenjang_sekolah' => $request->jenjang_sekolah,
                'sekolah' => $request->sekolah,
                'status_sekolah' => $request->status_sekolah,
                'paket_kerjasama' => $valuePaketKerjasama,
            ]);
        }

        return redirect()->back();
    }
}