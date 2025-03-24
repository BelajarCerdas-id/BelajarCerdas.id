<?php

namespace App\Http\Controllers;

use App\Models\dataSuratPks;
use App\Models\sekolahPks;
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
        return view('PKS.visitasi.jadwal-kunjungan', compact('user'));
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

        return view('PKS.visitasi.data-kunjungan', compact('user', 'getVisitasiData'));
    }

    public function cetakPKS() {
        $user = session('user');

        if(!$user) {
            return redirect()->route('login');
        }

        // mengambil data sekolah yang sudah dikunjungi
        $getDataSekolahPKS = visitasiData::where('status_kunjungan', 'Telah dikunjungi')->get();

        // mengambil data sekolah yang sama dengan data sekolah yang sudah dikunjungi
        $getPaketKerjasamaPKS = dataSuratPks::whereIn('sekolah', $getDataSekolahPKS->pluck('sekolah'))->get();

        $today = now();

        // 1. Jika status PKS tidak aktif, tetapi tanggal_mulai masih di masa depan -> ubah ke 'Belum Dimulai'
        dataSuratPks::where('status_paket_kerjasama', 'PKS tidak aktif')
            ->where('status_pks', 'PKS')
            ->whereDate('tanggal_mulai', '>', $today)
            ->update(['status_paket_kerjasama' => 'Belum Dimulai']);

        // 2. Jika status PKS tidak aktif atau 'Belum Dimulai', tetapi tanggal_mulai sudah dimulai dan masih dalam periode kerja sama -> ubah ke 'Sedang Aktif'
        dataSuratPks::whereIn('status_paket_kerjasama', ['PKS tidak aktif', 'Belum Dimulai'])
            ->where('status_pks', 'PKS')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->update(['status_paket_kerjasama' => 'Sedang Aktif']);

        // 3. Jika status sudah 'Sedang Aktif', tetapi tanggal_akhir telah lewat -> ubah ke 'Selesai'
        dataSuratPks::where('status_paket_kerjasama', 'Sedang Aktif')
            ->where('status_pks', 'PKS')
            ->whereDate('tanggal_akhir', '<', $today)
            ->update(['status_paket_kerjasama' => 'Selesai']);

        // Ambil data pertama kali yang di-insert untuk englishZone berdasarkan sekolah
        $getDataPKS = dataSuratPks::whereIn('sekolah', $getDataSekolahPKS->pluck('sekolah'))->orderBy('created_at', 'asc')->get();
        $firstContract = $getDataPKS->groupBy(['sekolah', 'paket_kerjasama'])
            ->map(fn($group) => $group->map(fn($items) => $items->first()->id));


        return view('PKS.surat-pks.cetak-pks', compact('user', 'getPaketKerjasamaPKS', 'firstContract'));
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
            'status_kunjungan' => $request->status_kunjungan,
        ]);

        return redirect()->back()->with('success', 'Status kunjungan berhasil diubah!');
    }

}