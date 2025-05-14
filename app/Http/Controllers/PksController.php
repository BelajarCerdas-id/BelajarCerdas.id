<?php

namespace App\Http\Controllers;

use App\Models\bulkUploadTemplate;
use App\Models\dataSuratPks;
use App\Models\sekolahPks;
use App\Models\userAccount;
use Illuminate\Http\Request;

class PksController extends Controller
{
    public function inputDataMurid()
    {
        return view('master-data-pks.input-pks.data-civitas-sekolah');
    }

    public function managementDataSekolah()
    {
        // ambil data sekolah yang sudah pks
        $getDataPKS = dataSuratPks::where('status_pks', 'PKS')->get();

        // lalu ambil data sekolah yang sesuai dengan nama sekolah yang sudah pks
        // $getSekolahPKS = sekolahPks::where('sekolah', $getDataPKS->pluck('sekolah'))->get();
        $getSekolahPKS = sekolahPks::all();

        //grouped data
        $groupedDataPKS = $getSekolahPKS->map(fn($item) => $item->first());

        return view('master-data-pks.management-pks.data-sekolah', compact( 'groupedDataPKS'));
    }

    public function managementDaftarSekolah()
    {
        $getDataPKS = dataSuratPks::where('status_pks', 'PKS')->get();

        $groupedDataPKS = $getDataPKS->map(fn($item) => $item->first());

        return view('master-data-pks.civitas-sekolah.daftar-sekolah', compact('groupedDataPKS'));
    }

    public function managementCivitasSekolah($sekolah)
    {
        $getSekolah = dataSuratPks::where('sekolah', $sekolah)->get();

        $getMurid = userAccount::where('sekolah', $sekolah)->where('status', 'Murid')->get();

        return view('master-data-pks.civitas-sekolah.data-civitas-sekolah', compact('getMurid'));
    }

    public function dataPksSekolah()
    {
        $getDataPKS = sekolahPks::where('status_pks', 'PKS')->get();

        $groupedDataPKS = $getDataPKS->map(fn($item) => $item->first());

        $today = now();

        sekolahPks::whereIn('status_paket_kerjasama', ['not-active'])
        ->whereDate('tanggal_mulai', '>=', $today)
        ->whereDate('tanggal_akhir', '>=', $today)
        ->update(['status_paket_kerjasama' => 'is-active']);

        sekolahPks::whereIn('status_paket_kerjasama', ['is-active'])
        ->whereDate('tanggal_mulai', '<', $today)
        ->whereDate('tanggal_akhir', '<', $today)
        ->update(['status_paket_kerjasama' => 'not-active']);

        return view('data-pks-sekolah', compact('getDataPKS', 'groupedDataPKS'));
    }

    public function viewDataPksSekolah($sekolah)
    {
    // get all data
    $getDataPKS = dataSuratPks::where('sekolah', $sekolah)
        ->where('status_pks', 'PKS')
        ->where('paket_kerjasama', 'englishZone')
        ->orderBy('created_at', 'desc') // Pastikan data terbaru ada di atas
        ->get()
        ->groupBy('paket_kerjasama')
        ->map(fn($items) => $items->first()); // Ambil hanya data terbaru dari setiap grup

        $today = now();

        sekolahPks::whereIn('status_paket_kerjasama', ['not-active'])
        ->whereDate('tanggal_mulai', '>=', $today)
        ->whereDate('tanggal_akhir', '>=', $today)
        ->update(['status_paket_kerjasama' => 'is-active']);

        sekolahPks::whereIn('status_paket_kerjasama', ['is-active'])
        ->whereDate('tanggal_mulai', '<', $today)
        ->whereDate('tanggal_akhir', '<', $today)
        ->update(['status_paket_kerjasama' => 'not-active']);

        return view('PKS.view-data-pks', compact( 'getDataPKS'));
    }

    public function inputSuratPks()
    {
        return view('PKS.surat-pks.input-surat-pks');
    }

    public function bulkUploadCivitasSekolah()
    {
        return view('templates.bulkUpload-template.data-civitas-sekolah-bulkUpload');
    }

    public function inputSuratPksStore(Request $request)
    {
        $request->validate([
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
            'jenjang_sekolah' => 'required',
            'sekolah' => 'required',
            'status_sekolah' => 'required',
            'alamat_sekolah' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'paket_kerjasama.*' => 'required',
            'tanggal_mulai.*' => 'required',
            'tanggal_akhir.*' => 'required',
        ], [
            'provinsi.required' => 'Harap pilih Provinsi!',
            'kab_kota.required' => 'Harap pilih Kabupaten / Kota!',
            'kecamatan.required' => 'Harap pilih Kecamatan!',
            'jenjang_sekolah.required' => 'Harap pilih jenjang sekolah!',
            'sekolah.required' => 'Harap pilih provinsi!',
            'status_sekolah.required' => 'Harap pilih status sekolah!',
            'alamat_sekolah.required' => 'Harap isi alamat sekolah!',
            'nama_kepsek.required' => 'Harap masukkan nama Kepala Sekolah!',
            'nip_kepsek.required' => 'Harap masukkan NIP Kepala Sekolah!',
            'paket_kerjasama.*.required' => 'Harap pilih tipe paket kerjasama!',
            'tanggal_mulai.*.required' => 'Harap pilih tanggal mulai!',
            'tanggal_akhir.*.required' => 'Harap pilih tanggal akhir!',
        ]);

        $getData = dataSuratPks::where('sekolah', $request->sekolah)->where('paket_kerjasama', $request->paket_kerjasama)->whereIn('status_paket_kerjasama', ['PKS tidak aktif','Belum Dimulai', 'Sedang Aktif'])->first();

        $paketKerjasama = $request->input('paket_kerjasama');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if($getData) {
            return redirect()->back()->with('error', 'Paket kerjasama pada sekolah ini sedang berada di antrean, silahkan tunggu paket kerjasama selesai!');
        }else {
            foreach($paketKerjasama as $data => $valuePaketKerjasama) {
                dataSuratPks::create([
                    'provinsi' => $request->provinsi,
                    'kab_kota' => $request->kab_kota,
                    'kecamatan' => $request->kecamatan,
                    'jenjang_sekolah' => $request->jenjang_sekolah,
                    'sekolah' => $request->sekolah,
                    'status_sekolah' => $request->status_sekolah,
                    'alamat_sekolah' => $request->alamat_sekolah,
                    'nama_kepsek' => $request->nama_kepsek,
                    'nip_kepsek' => $request->nip_kepsek,
                    'paket_kerjasama' => $valuePaketKerjasama,
                    'tanggal_mulai' => $tanggalMulai[$data],
                    'tanggal_akhir' => $tanggalAkhir[$data],
                ]);
            }

            return redirect()->back();
        }

    }

    public function updateDataPksSekolah(Request $request, String $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ], [
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai!',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir!',
        ]);

        $getDataPKS = dataSuratPks::find($id);

        $getDataPKS->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir
        ]);
        return redirect()->back();
    }

    public function updateStatusCetakPks(Request $request, String $id)
    {
        $getStatusPKS = dataSuratPks::find($id);

        $getStatusPKS->update([
            'status_pks' => 'PKS',
        ]);

        return redirect()->back();
    }

    public function tambahPaketPKS(Request $request)
    {

        $request->validate([
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
            'jenjang_sekolah' => 'required',
            'sekolah' => 'required',
            'status_sekolah' => 'required',
            'alamat_sekolah' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'paket_kerjasama' => 'required',
        ], [
            'provinsi.required' => 'Harap pilih Provinsi!',
            'kab_kota.required' => 'Harap pilih Kabupaten / Kota!',
            'kecamatan.required' => 'Harap pilih Kecamatan!',
            'jenjang_sekolah.required' => 'Harap pilih jenjang sekolah!',
            'sekolah.required' => 'Harap pilih provinsi!',
            'status_sekolah.required' => 'Harap pilih status sekolah!',
            'alamat_sekolah.required' => 'Harap isi alamat sekolah!',
            'nama_kepsek.required' => 'Harap masukkan nama Kepala Sekolah!',
            'nip_kepsek.required' => 'Harap masukkan NIP Kepala Sekolah!',
            'paket_kerjasama.required' => 'Harap pilih tipe paket kerjasama!',
        ]);

        $paketKerjasama = $request->input('paket_kerjasama');

        foreach($paketKerjasama as $dataPaket => $valuePaket) {
            sekolahPKS::create([
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'kecamatan' => $request->kecamatan,
                'jenjang_sekolah' => $request->jenjang_sekolah,
                'sekolah' => $request->sekolah,
                'status_sekolah' => $request->status_sekolah,
                'alamat_sekolah' => $request->alamat_sekolah,
                'nama_kepsek' => $request->nama_kepsek,
                'nip_kepsek' => $request->nip_kepsek,
                'paket_kerjasama' => $valuePaket,
            ]);
        }

        return redirect()->back();
    }
    public function bulkUploadCivitasSekolahStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'status' => 'required',
            'nama_file' => 'required|max:10000',
            'jenis_file' => 'required',
            'status_template' => 'required|unique:bulk_upload_templates,status_template',
        ], [
            'nama_lengkap.required' => 'Harap isi nama lengkap!',
            'status.required' => 'Harap isi status!',
            'nama_file.required' => 'Harap upload file template!',
            'nama_file.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!',
            'jenis_file.required' => 'Harap isi jenis file!',
            'status_template.required' => 'Harap isi status template!',
            'status_template.unique' => 'BulkUpload template sudah terdaftar!',
        ]);

        if ($request->hasFile('nama_file')) {
            $fileName = time() . $request->file('nama_file')->getClientOriginalName();
            $request->file('nama_file')->move(public_path('bulkUpload_template'), $fileName);
        }

        bulkUploadTemplate::create([
            'nama_lengkap' => $request->nama_lengkap,
            'status' => $request->status,
            'nama_file' => $fileName,
            'jenis_file' => $request->jenis_file,
            'status_template' => $request->status_template
        ]);

        return redirect()->back()->with('success', 'bulkUpload template berhasil diupload!');
    }

    public function generateTemplateExcel()
    {
        $getBulkUploadTemplateCivitasSekolah = bulkUploadTemplate::where('status_template', 'BulkUpload_civitas_sekolah')->first();

        $fileName = $getBulkUploadTemplateCivitasSekolah->nama_file;

        $filePath = public_path('bulkUpload_template/' . $fileName);

        return response()->download($filePath);
    }

}