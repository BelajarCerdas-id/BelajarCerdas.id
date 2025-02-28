<?php

namespace App\Http\Controllers;

use App\Models\masterData;
use App\Models\visitasiData;
use Illuminate\Http\Request;

class VisitasiDataController extends Controller
{
    public function jadwalKunjungan()
    {
        $user = session('user');
        if(!$user) {
            return redirect()->route('login');
        }
        return view('visitasi.jadwal-kunjungan', compact('user'));
    }

    public function dataKunjungan() {
        $user = session('user');

        if(!$user) {
            return redirect()->route('login');
        }

        $getVisitasiData = visitasiData::all();

        $today = now();

        // Update status Kunjungan ke 'Pending' jika status masih kosong atau masih 'Belum dikunjungi'
        visitasiData::whereIn('status_kunjungan', ['Belum dikunjungi'])
            ->whereDate('tanggal_mulai', '>=', $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->update(['status_kunjungan' => 'Belum dikunjungi']);

        visitasiData::whereIn('status_kunjungan', ['Pending'])
            ->whereDate('tanggal_mulai', '>=', $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->update(['status_kunjungan' => 'Belum dikunjungi']);

        // Update status ke 'Belum dikunjungi' hanya jika belum ada perubahan lain
        visitasiData::whereIn('status_kunjungan', ['Belum dikunjungi'])
            ->whereDate('tanggal_mulai', '<', $today)
            ->whereDate('tanggal_akhir', '<', $today)
            ->update(['status_kunjungan' => 'Pending']);

        return view('visitasi.data-kunjungan', compact('user', 'getVisitasiData'));
    }

    public function cetakPKS() {
        $user = session('user');

        if(!$user) {
            return redirect()->route('login');
        }

        $getDataSekolahPKS = visitasiData::where('status_kunjungan', 'Telah dikunjungi')->get();

        $getPaketKerjasamaPKS = masterData::whereIn('sekolah', $getDataSekolahPKS->pluck('sekolah'))->get()->groupBy('sekolah');

        $groupedData = $getPaketKerjasamaPKS->map(fn($item) => $item->first());
        $dataPaketKerjasama = $getPaketKerjasamaPKS;

        return view('visitasi.cetak-pks', compact('user', 'getDataSekolahPKS', 'groupedData', 'dataPaketKerjasama'));
    }

    public function visitasiDataStore(Request $request)
    {
        $user = session('user');

        $request->validate([
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
            'jenjang_sekolah' => 'required',
            'sekolah' => 'required',
            'status_sekolah' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required'
        ], [
            'provinsi.required' => 'Harap pilih Provinsi!',
            'kab_kota.required' => 'Harap pilih Kabupaten / Kota!',
            'kecamatan.required' => 'Harap pilih Kecamatan!',
            'jenjang_sekolah.required' => 'Harap pilih jenjang sekolah!',
            'sekolah.required' => 'Harap pilih provinsi!',
            'status_sekolah.required' => 'Harap pilih status sekolah!',
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai!',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir!',
        ]);

        visitasiData::create([
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'kecamatan' => $request->kecamatan,
                'jenjang_sekolah' => $request->jenjang_sekolah,
                'sekolah' => $request->sekolah,
                'status_sekolah' => $request->status_sekolah,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir
            ]);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function updateStatusKunjungan(Request $request, string $id)
    {

        $updateStatus = visitasiData::find($id);

        if (!$updateStatus) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $updateStatus->update([
            'status_kunjungan' => $request->status_kunjungan
        ]);

        return redirect()->back()->with('success', 'Status kunjungan berhasil diubah!');
    }

}
