<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\modulLock;
use App\Models\englishZone;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use App\Models\englishZoneMateri;
use App\Models\englishZoneJawaban;
use Illuminate\Support\Facades\DB;

class EnglishZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = session('user');

        if(!$user) {
            return redirect('/login');
        }
        // mencari jenjang murid yang sesuai dengan jenjang murid user login
        // Group materi berdasarkan modul
        $groupedMateri = englishZoneMateri::where('jenjang_murid', $user->kode_jenjang_murid)->get()->groupBy('modul');

        $completedModules = modulLock::where('nama_lengkap', $user->nama_lengkap)->where('is_completed', true)->pluck('module_id')->toArray();

        // Tandai module yang terkunci
        foreach ($groupedMateri as $modul => $materis) {
            $mainMateri = $materis->first(); // Ambil satu materi utama dari setiap modul

            $mainMateri->is_locked = !in_array($mainMateri->id, $completedModules) &&
            ($mainMateri->modul !== 'Modul 1' && !in_array($mainMateri->id - 1, $completedModules));
            // Jika modul bukan Modul 1, tandai modul sebelumnya sebagai terkunci jika belum diselesaikan)
        }

        // Ambil data utama per modul (satu per modul) dan semua data materi lainnya
        $mainMateri = $groupedMateri->map(fn($materis) => $materis->first()); // Data utama tiap modul
        $allMateri = $groupedMateri; // Semua data materi, termasuk video
        $getSoal = englishZoneSoal::all();
        return view('english-zone', compact('user', 'mainMateri', 'allMateri', 'getSoal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function uploadMateriStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required',
            'status' => 'required',
            'modul' => 'required',
            'judul_modul' => 'required',
            'judul_video.*' => 'required',
            'link_video.*' => 'required',
            'jenjang_murid' => 'required',
            'materi_pdf' => 'required|max:10000',
            'modul_download' => $request->modul === 'Final Exam' ? 'required|max:10000' : 'nullable|max:10000',
        ], [
            'modul.required' => 'Harap pilih modul!',
            'jenjang_murid.required' => 'Harap pilih jenjang!',
            'materi_pdf.required' => 'Harap upload pdf!',
            'materi_pdf.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!',
            'modul_download.required' => 'Harap upload modul!',
            'modul_download.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!',
            'judul_modul' => 'Harap isi judul modul!',
            'judul_video.*.required' => 'Judul video harus diisi!',
            'link_video.*.required' => 'Link video harus diisi!',
        ]);

        // Inisialisasi variabel untuk menyimpan nama file certificate
        $filename = null; // Inisialisasi variabel agar tidak undefined
        $modulDownload = null;

        if ($request->hasFile('materi_pdf')) {
            $filename = time() . $request->file('materi_pdf')->getClientOriginalName();
            $request->file('materi_pdf')->move(public_path('englishZone_pdf'), $filename);
        }

        if ($request->hasFile('modul_download')) {
            $modulDownload = time() . $request->file('modul_download')->getClientOriginalName();
            $request->file('modul_download')->move(public_path('englishZone_modul'), $modulDownload);
        }

        $judul_video = $request->input('judul_video');
        $link_video = $request->input('link_video');

        foreach ($judul_video as $index => $value) {
            englishZoneMateri::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'status' => $request->status,
                'modul' => $request->modul,
                'judul_modul' => $request->judul_modul,
                'materi_pdf' => $filename,
                'modul_download' => $modulDownload,
                'judul_video' => $value,
                'link_video' => $link_video[$index],
                'jenjang_murid' => $request->jenjang_murid,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */

    // controller show pdf in pdfviewer (ga kepake tapi simpen aja)
    public function show(string $id)
    {
        $getPDF = englishZoneMateri::find($id);

        // Ambil path file PDF dari database
        $filePath = public_path('englishZone_pdf/' .$getPDF->uploadSoal);
        // $fileName = pathinfo($getPDF->uploadSoal, PATHINFO_FILENAME);
        // $fileUrl = asset('englishZone_pdf/' . $getPDF->uploadSoal); // menggunakan pdf.js untu ngakalin hilangin tombol download pdf

        return response()->file($filePath); // Menampilkan PDF di browser
        // return view('englishZone-view', compact('filePath'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // controller update status_soal (administrator)
    public function update(Request $request)
    {
        $ids = $request->input('id'); // Ambil ID soal dari checkbox

        if ($ids) {
            // Ambil semua soal yang dipilih
            $soalList = englishZoneSoal::whereIn('id', $ids)->get();

            // Iterasi melalui soal untuk memperbarui status pilihan A-E
            foreach($soalList as $soal) {
                // Toggle status untuk semua pilihan (A-E) yang terhubung ke soal ini
                $optionSoal = englishZoneSoal::where('soal', $soal->soal)->get();
                foreach($optionSoal as $value) {
                    // Toggle status: jika unpublish, ubah ke published; jika published, ubah ke unpublish
                    $value->status_soal = $value->status_soal === 'unpublish' ? 'published' : 'unpublish';
                    $value->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Status soal dan pilihan berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // controller upload image ckeditor
    public function uploadImage(Request $request) {
    // Menangani upload gambar
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathInfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('media'), $fileName);

            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    // controller delete image ckeditor
    public function deleteImage(Request $request) {
        $request->validate([
            'imageUrl' => 'required|url',
        ]);

        $imagePath = str_replace(asset(''), '', $request->imageUrl); // Hapus base URL
        $fullImagePath = public_path($imagePath);

        if (file_exists($fullImagePath)) {
            unlink($fullImagePath); // Hapus gambar
            return response()->json(['message' => 'Gambar berhasil dihapus']);
        }

        return response()->json(['message' => 'Gambar tidak ditemukan'], 404);
    }

    // controller upload soal (administrator)
    public function uploadSoalStore(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required',
            'modul_soal' => 'required',
            'jenjang' => 'required',
            'status' => 'required',
            'soal' => 'required',
            'option_pilihan.*' => 'required',
            'jawaban_pilihan.*' => 'required',
            'tingkat_kesulitan' => 'required',
            'jawaban_benar' => 'required',
            'deskripsi_jawaban' => 'required',
            'tipe_upload' => 'required'
        ], [
            'nama_lengkap.required' => 'Harap isi nama lengkap!',
            'modul_soal.required' => 'Harap pilih modul!',
            'jenjang.required' => 'Harap pilih jenjang!',
            'status.required' => 'Harap pilih status!',
            'soal.required' => 'Harap isi soal!',
            'option_pilihan.*.required' => 'Harap isi pilihan jawaban!',
            'jawaban_pilihan.*.required' => 'Harap isi pilihan jawaban!',
            'tingkat_kesulitan.required' => 'Harap pilih tingkat kesulitan!',
            'jawaban_benar.required' => 'Harap pilih jawaban benar!',
            'deskripsi_jawaban.required' => 'Harap isi deskripsi jawaban!',
            'tipe_upload.required' => 'Harap pilih tipe upload!'
        ]);
        $jawaban_pilihan = $request->input('jawaban_pilihan');
        $option_pilihan = $request->input('option_pilihan');

        // elemen yang di loop dalam foreach dia menggunakan nilai dari setiap elemen yang di loop berarti yang setelah =>, sedangkan array lain menggunakan key (variabel setelah $as)
        foreach ($jawaban_pilihan as $index => $value) {
            englishZoneSoal::create([
                'nama_lengkap' => $request->nama_lengkap,
                'modul_soal' => $request->modul_soal,
                'jenjang' => $request->jenjang,
                'status' => $request->status,
                'soal' => $request->soal,
                'option_pilihan' => $option_pilihan[$index], // Cocokkan dengan iterasi
                'jawaban_pilihan' => $value, // Isi jawaban
                'tingkat_kesulitan' => $request->tingkat_kesulitan,
                'jawaban_benar' => $request->jawaban_benar,
                'deskripsi_jawaban' => $request->deskripsi_jawaban,
                'tipe_upload' => $request->tipe_upload
            ]);
        }
            return redirect()->back();
    // $soal = new englishZoneSoal;
        // $soal->nama_lengkap = $request->nama_lengkap;
        // $soal->modul = $request->modul;
        // $soal->jenjang = $request->jenjang;
        // $soal->status = $request->status;
        // $soal->soal = $request->soal;
        // $soal->pilihan_A = $request->pilihan_A;
        // $soal->bobot_A = $request->bobot_A;
        // $soal->pilihan_B = $request->pilihan_B;
        // $soal->bobot_B = $request->bobot_B;
        // $soal->pilihan_C = $request->pilihan_C;
        // $soal->bobot_C = $request->bobot_C;
        // $soal->pilihan_D = $request->pilihan_D;
        // $soal->bobot_D = $request->bobot_D;
        // $soal->pilihan_E = $request->pilihan_E;
        // $soal->bobot_E = $request->bobot_E;
        // $soal->tingkat_kesulitan = $request->tingkat_kesulitan;
        // $soal->jawaban = $request->jawaban;
        // $soal->deskripsi_jawaban = $request->deskripsi_jawaban;
        // $soal->tipe_upload = $request->tipe_upload;
    }


    // public function uploadJawaban(Request $request)
    // {
    //     $jawaban = $request->input('jawaban'); // Jawaban user
    //     $nomorSoal = $request->input('id_soal'); // Nomor soal
    //     $nilaiJawaban = $request->input('nilai_jawaban'); // Nilai jawaban user

    //     foreach ($jawaban as $Soal => $value) {
    //         // Pastikan jawaban user untuk soal ini ada
    //         if (isset($nilaiJawaban[$Soal])) {
    //             // Pecah nilai jawaban menjadi opsi dan nilai
    //             [$jawabanPilihan, $optionPilihan] = explode('|', $value);

    //             // Ambil nilai jawaban user untuk opsi yang dipilih
    //             $nilai = $nilaiJawaban[$Soal][$optionPilihan] ?? 0;

    //             // Simpan hanya jawaban yang dipilih user
    //             englishZoneJawaban::create([
    //                 'nama_lengkap' => $request->nama_lengkap,
    //                 'email' => $request->email,
    //                 'sekolah' => $request->sekolah,
    //                 'kelas' => $request->kelas,
    //                 'status' => $request->status,
    //                 'jenjang_murid' => $request->jenjang_murid,
    //                 'modul' => $request->modul,
    //                 'id_soal' => $nomorSoal[$value],
    //                 'jawaban' => $jawabanPilihan, // Jawaban yang dipilih user
    //                 'pilihan_ganda' => $optionPilihan, // Opsi yang dipilih
    //                 'nilai_jawaban' => $nilai, // Nilai jawaban
    //                 'no_soal' => $Soal,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Jawaban berhasil disimpan.');
    // }

    // controller upload jawaban soal pg menggunakan benar salah (dapat point atau tidak) (murid)
    public function uploadJawaban(Request $request, $id)
    {
        $user = session('user');
        $module = englishZoneMateri::findOrFail($id);

        // update status module sebagai selesai
        modulLock::updateOrCreate(
            ['nama_lengkap' => $user->nama_lengkap, 'module_id' => $module->id],
            ['is_completed' => true]
        );

        $jawaban = $request->input('jawaban'); // Jawaban user
        $nomorSoal = $request->input('no_soal'); // Nomor soal
        $nilaiJawaban = $request->input('nilai_jawaban'); // Nilai jawaban user

        if (!$jawaban || !$nomorSoal) {
            return redirect()->back()->withErrors(['msg' => 'Data jawaban tidak lengkap.']);
        }

        foreach ($jawaban as $noSoal => $value) {
            // Ambil ID soal berdasarkan nomor soal
            $idSoalKey = "id_soal{$noSoal}";
            $soalId = $request->input($idSoalKey);

            if ($soalId) {
                // Pecah jawaban menjadi opsi dan nilai
                [$jawabanPilihan, $optionPilihan] = explode('|', $value);

                // Ambil nilai jawaban user untuk opsi yang dipilih
                $nilai = $nilaiJawaban[$noSoal][$optionPilihan] ?? null;

                if ($nilai === null) {
                    return redirect()->back()->withErrors(['msg' => 'Nilai jawaban tidak ditemukan.']);
                }

                // Simpan jawaban user ke database
                englishZoneJawaban::create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => $request->email,
                    'sekolah' => $request->sekolah,
                    'kelas' => $request->kelas,
                    'status' => $request->status,
                    'jenjang_murid' => $request->jenjang_murid,
                    'modul' => $request->modul,
                    'id_soal' => $soalId, // Gunakan ID soal yang benar
                    'jawaban' => $jawabanPilihan, // Jawaban yang dipilih user
                    'pilihan_ganda' => $optionPilihan, // Opsi yang dipilih
                    'nilai_jawaban' => $nilai, // Nilai jawaban
                    'no_soal' => $noSoal, // Nomor soal dari form
                ]);
            }
        }

        return redirect()->back()->with('success', 'Jawaban berhasil disimpan.');
    }

    public function pengayaan($modul, $id) {
        // lalu controller akan menerima parameter modul tadi dan diproses pada function pengayaan

        $user = session('user');
        if(!isset($user)) {
            return redirect('/login');
        }

        $module = englishZoneMateri::findOrFail($id);

        // terakhir, $getSoal akan mengambil semua data yang sesuai dengan column modul englishZoneSoal dengan operator $modul yang berasal dari parameter url
        // cara ini hampir sama dengan metode view pada crud find($id), hanya saja ini menggunakan kondisi where, karena melakukan relasi antara column modul englishZoneSoal dengan column modul englishZoneMateri
        // get data untuk soal pilihan ganda
        $groupedSoal = englishZoneSoal::where('modul_soal', $modul)->where('status_soal', 'published')->where('jenjang', $user->kode_jenjang_murid)->inRandomOrder()->get()->groupBy('soal');
        $getSoal = $groupedSoal->map(fn($materis) => $materis->first()); // Data utama tiap modul
        $dataSoal = $groupedSoal;

        $getDataSoal = englishZoneSoal::where('modul_soal', $modul)->where('status_soal', 'published')->where('jenjang', $user->kode_jenjang_murid)->get();
        // mengambil semua data yang id_soal (englishZoneJawaban) dengan id (englishZoneSoal) sesuai.
        $getJawaban = englishZoneJawaban::whereIn('id_soal', $getDataSoal->pluck('id'))->where('email', $user->email)->get()->groupBy('id_soal');

        // menghitung nilai(poin) setiap soal pilihan ganda
        $soal = englishZoneSoal::where('modul_soal', $modul)->where('status_soal', 'published')->where('jenjang', $user->kode_jenjang_murid)->get()->groupBy('soal');
        $jumlahSoal = $soal->count();
        $totalNilai = 100;
        $getPoint = $jumlahSoal > 0 ? $totalNilai / $jumlahSoal : 0;

        $getNilai = englishZoneJawaban::where('email', $user->email)->where('modul', $modul)->get();
        $countNilai = $getNilai->sum('nilai_jawaban');

        return view('pengayaan', compact('user', 'getSoal', 'dataSoal', 'getJawaban', 'totalNilai', 'getPoint', 'countNilai', 'module'));
    }

    public function questionForRelease()
    {
        $user = session('user');

        if(!isset($user)) {
            return redirect('/login');
        }

        $getSoal = englishZoneSoal::paginate(20);

        return view('question-for-release', compact('user','getSoal'));
    }

    public function video($modul)
    {
        // Mengambil informasi pengguna dari sesi
        $user = session('user');

        // Jika pengguna belum login, arahkan ke halaman login
        if (!$user) {
            return redirect('/login');
        }

        // Mengambil video berdasarkan modul dari database
        $getVideo = englishZoneMateri::where('modul', $modul)->get();

        // Memastikan ada video yang ditemukan untuk modul ini
        if ($getVideo->isEmpty()) {
            // Menangani jika tidak ada video yang ditemukan
            return redirect()->back()->with('error', 'Tidak ada video ditemukan untuk modul ini.');
        }

        // Menyiapkan array untuk ID video
        $videoIds = [];

        // Loop untuk mendapatkan ID video dari URL
        foreach ($getVideo as $video) {
            $videoId = null;
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $video->link_video, $matches)) {
                $videoId = $matches[1] ?? $matches[2];
            }
            $videoIds[] = $videoId;
        }

        // Mengirim data ke view
        return view('english-zone-video', compact('user', 'getVideo', 'videoIds'));
    }

    public function certificate()
    {
        $user = session('user');
        if(!isset($user)) {
            return redirect('/login');
        }

        return view('certif', compact('user'));
    }


// controller upload jawaban soal pg menggunakan bobot setiap pilihan ganda (murid)
    // controller untuk upload jawaban pg murid dengan option A - E dan bobot 1 - 5
    // public function uploadJawaban(Request $request)
    // {
    //     $request->validate([
    //         'jawaban' => 'required'
    //     ], [
    //         'jawaban.required' => 'Jawaban tidak boleh kosong!'
    //     ]);
    //     // Mengambil Semua Data dari Formulir
    //     $data = $request->all();

    //     // Mengambil Jawaban dari Formulir
    //     $jawabanArray = $data['jawaban']; // Array jawaban dari formulir

    //     foreach ($jawabanArray as $noSoal => $jawaban) {
    //         // Memisahkan pilihan, jawaban, dan bobot untuk A, B, C, D, E
    //         [$pilihanGanda, $jawabanDetail, $bobot_A, $bobot_B, $bobot_C, $bobot_D, $bobot_E, $nilai_jawaban] = explode('|', $jawaban);
    //         // Menyimpan jawaban dan bobot ke dalam tabel
    //         englishZoneJawaban::create([
    //             'nama_lengkap' => $data['nama_lengkap'],
    //             'email' => $data['email'],
    //             'sekolah' => $data['sekolah'],
    //             'kelas' => $data['kelas'],
    //             'status' => $data['status'],
    //             'jenjang_murid' => $data['jenjang_murid'],
    //             'id_soal' => $data['id_soal'][$noSoal], // mengirimkan id_soal sesuai dengan nomor soal
    //             'pilihan_ganda' => $pilihanGanda,
    //             'jawaban' => $jawabanDetail,
    //             'nilai_jawaban' => $nilai_jawaban,
    //             'no_soal' => $noSoal,
    //             'bobot_A' => $bobot_A, // Bobot untuk A
    //             'bobot_B' => $bobot_B, // Bobot untuk B
    //             'bobot_C' => $bobot_C, // Bobot untuk C
    //             'bobot_D' => $bobot_D, // Bobot untuk D
    //             'bobot_E' => $bobot_E, // Bobot untuk E
    //             'modul' => $data['modul'],
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Jawaban berhasil disimpan!');
    // }
}