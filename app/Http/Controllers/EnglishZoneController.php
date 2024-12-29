<?php

namespace App\Http\Controllers;

use App\Models\Crud;
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
        $getMateri = englishZoneMateri::where('jenjang_murid', $user->kode_jenjang_murid)->get();
        $getSoal = englishZoneSoal::all();
        return view('english-zone', compact('user', 'getMateri', 'getSoal'));
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
    public function store(Request $request)
    {
        $user = session('user');
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required',
            'status' => 'required',
            'modul' => 'required',
            'judul' => 'required',
            'pdf_file' => 'required|max:10000',
            // 'video_materi' => [
            //     'requiired',
            //     'url',
            //     function ($atrribute, $value, $fail) {
            //         if (!preg_match('/^https?:\/\//', $value)) {
            //             $fail('Link video harus diawali dengan http:// atau https://');
            //         }
            // }],
            'video_materi' => 'required|url',
            'jenjang_murid' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama harus di isi',
            'email.required' => 'Email harus di isi',
            'status.required' => 'status harus di isi',
            'modul.required' => 'Harap pilih modul',
            'judul.required' => 'judul harus di isi',
            'uploadSoal.required' => 'Harap upload PDF',
            'video_materi.required' => 'Link video Harus di isi',
            'video_materi.url' => 'Format link tidak sesuai',
            'jenjang_murid.required' => 'Harap pilih jenjang',
            'pdf_file.required' => 'Harap upload materi',
            'pdf_file.max' => 'file PDF melebihi ukuran maksimal file'
        ]);

        // mwnggunakan storage bawaan laravel
        // $filePath = $request->file('uploadSoal')->store('englishZone_pdf', 'public');

        // membuat storage sendiri
        if($request->hasFile('uploadSoal')) {
            $filename = time() . $request->file('uploadSoal')->getClientOriginalName();
            $request->file('uploadSoal')->move(public_path('englishZone_pdf'), $filename);
            $validatedData['uploadSoal'] = $filename;
        }

        englishZoneMateri::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'status' => $request->status,
            'modul' => $request->modul,
            'judul' => $request->judul,
            'uploadSoal' => $filename,
            'video_materi' => $request->video_materi,
            'jenjang_murid' => $request->jenjang_murid,
        ]);

        return view('english-zone', compact('user'));
    }

    /**
     * Display the specified resource.
     */
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
    public function update(Request $request)
    {
        $ids = $request->input('id'); // Ambil ID soal dari checkbox

        if ($ids) {
            // Ambil semua soal yang dipilih
            $soalList = englishZoneSoal::whereIn('id', $ids)->get();

            // foreach untuk memproses setiap elemen (soal) yang ada dalam $soalList secara bersamaan,
            foreach ($soalList as $soal) {
                // Toggle status: jika unpublish, ubah ke published; jika published, ubah ke unpublish
                // mengecek apa status_soal saya ini = status_soal unpublish maka akan published; jika published akan unpublish
                $soal->status_soal = $soal->status_soal === 'unpublish' ? 'published' : 'unpublish';
                $soal->save();
            }
        }

        return redirect()->back()->with('success', 'Status soal berhasil diperbarui.');
        // return response()->json();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Administrator
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

    public function uploadSoal(Request $request) {
        $soal = new englishZoneSoal;

        $soal->nama_lengkap = $request->nama_lengkap;
        $soal->status = $request->status;
        $soal->soal = $request->soal;
        $soal->pilihan_A = $request->pilihan_A;
        $soal->bobot_A = $request->bobot_A;
        $soal->pilihan_B = $request->pilihan_B;
        $soal->bobot_B = $request->bobot_B;
        $soal->pilihan_C = $request->pilihan_C;
        $soal->bobot_C = $request->bobot_C;
        $soal->pilihan_D = $request->pilihan_D;
        $soal->bobot_D = $request->bobot_D;
        $soal->pilihan_E = $request->pilihan_E;
        $soal->bobot_E = $request->bobot_E;
        $soal->tingkat_kesulitan = $request->tingkat_kesulitan;
        $soal->jawaban = $request->jawaban;
        $soal->deskripsi_jawaban = $request->deskripsi_jawaban;
        $soal->tipe_upload = $request->tipe_upload;

        $soal->save();

        return redirect()->back();
    }

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

    // murid

    public function uploadJawaban(Request $request)
    {
        $request->validate([
            'jawaban' => 'required'
        ], [
            'jawaban.required' => 'Jawaban tidak boleh kosong!'
        ]);
        // Mengambil Semua Data dari Formulir
        $data = $request->all();

        // Mengambil Jawaban dari Formulir
        $jawabanArray = $data['jawaban']; // Array jawaban dari formulir

        foreach ($jawabanArray as $noSoal => $jawaban) {
            // Memisahkan pilihan, jawaban, dan bobot untuk A, B, C, D, E
            [$pilihanGanda, $jawabanDetail, $bobot_A, $bobot_B, $bobot_C, $bobot_D, $bobot_E] = explode('|', $jawaban);

            // Menyimpan jawaban dan bobot ke dalam tabel
            englishZoneJawaban::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'sekolah' => $data['sekolah'],
                'kelas' => $data['kelas'],
                'status' => $data['status'],
                'jenjang_murid' => $data['jenjang_murid'],
                'id_soal' => $data['id_soal'][$noSoal], // mengirimkan id_soal sesuai dengan nomor soal
                'pilihan_ganda' => $pilihanGanda,
                'jawaban' => $jawabanDetail,
                'no_soal' => $noSoal,
                'bobot_A' => $bobot_A, // Bobot untuk A
                'bobot_B' => $bobot_B, // Bobot untuk B
                'bobot_C' => $bobot_C, // Bobot untuk C
                'bobot_D' => $bobot_D, // Bobot untuk D
                'bobot_E' => $bobot_E, // Bobot untuk E
            ]);
        }

        return redirect()->back()->with('success', 'Jawaban berhasil disimpan!');
    }




}