<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\Star;
use App\Models\Tanya;
use Illuminate\Http\Request;

class DataController extends Controller
{
    // Ambil data laporan untuk mentor
    public function getLaporanData($id)
    {
        $mentor = Crud::find($id);

        // Ambil semua pertanyaan yang di-trashed berdasarkan email_mentor
        $getLaporan = Tanya::onlyTrashed()->where('email_mentor', $mentor->email)->get();
        
        // Mengambil status "Diterima" dan "Ditolak" dari tabel Star
        $statusStar = Star::whereIn('id_tanya', $getLaporan->pluck('id'))->get()->keyBy('id_tanya');

        return response()->json([
            'laporan' => $getLaporan,
            'statusStar' => $statusStar
        ]);
    }

    // Terima status untuk pertanyaan tertentu
    public function terima(Request $request)
    {
        $status = Star::create([
            'id_tanya' => $request->id_tanya,
            'status' => 'Diterima',
            'nama_mentor' => $request->nama_mentor,  // Pastikan ada email di request
            'email' => $request->email,  // Pastikan ada email di request
            'sekolah' => $request->sekolah,  // Pastikan ada email di request
        ]);

        return response()->json(['status' => 'success', 'message' => 'Diterima']);
    }

    // Tolak status untuk pertanyaan tertentu
    public function tolak(Request $request, $id)
    {
        $status = Star::create([
            'id_tanya' => $id,
            'status' => 'Ditolak',
            'nama_mentor' => $request->nama_mentor,  // Pastikan ada email di request
            'email' => $request->email,  // Pastikan ada email di request
            'sekolah' => $request->sekolah,  // Pastikan ada email di request
        ]);

        return response()->json(['status' => 'success', 'message' => 'Ditolak']);
    }
}

