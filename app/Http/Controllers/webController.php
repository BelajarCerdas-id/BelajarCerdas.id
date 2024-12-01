<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\Star;
use App\Models\Level;
use App\Models\Tanya;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\DB;

class webController extends Controller
{
    public function index()
    {
        $packets = [
            [
                'Image' => 'image/paket1.jpg',
                'Desc1' => 'Membership Program',
                'Desc2' => 'HaloGuru, Catatan, TANYA, PTN, dan English Zone',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket2.jpg',
                'Desc1' => 'Rekomendasi jurusan dan PTN',
                'Desc2' => '96 kali pembelajaran hybrid dan 20 kali Try Out',
                'Discount' => 'Rp. 7.000.000',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket3.jpg',
                'Desc1' => 'Catatan',
                'Desc2' => 'Pemberian catatan per sub-bab dari guru',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket3.jpg',
                'Desc1' => 'Tanya dijawab langsung oleh Guru Ahli',
                'Desc2' => 'Jawaban PR dan Tugas lengkap dengan penjelasan',
                'Discount' => '',
                'Price' => 'Rp. 3.000/Koin',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket5.jpg',
                'Desc1' => 'English Zone',
                'Desc2' => 'Belajar "conversation" bersama guru ahli',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
        ];
        return view('index', [
            'packets' => $packets,
        ]);
    }

    public function beranda()
    {
        // Retrieve the user from the session
        $user = session('user');
        
        if (!$user) {
            return redirect('/login');
        }

        $getTanyaTL = Tanya::onlyTrashed()->get();

        $getData = Crud::where('status', 'Mentor')->get();

        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor');
        $validatedMentorAccepted = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email');

        $countData = [];

        foreach($getData as $item) {
            $countData[$item->email] = Star::where('email', $item->email)->count();
        }                                                           

        $data = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email'); // Ambil semua data yang statusnya diterima

        $totalPaidCount = 0;
        $totalUnpaidCount = 0;
        $totalWaitingUnpaidCount = 0;
        $paidBatchCount = [];
        $unpaidBatchCount = [];
        $waitingBatchCount = 0;
        
        foreach ($data as $email => $batchUsers) {
            // Hitung berapa banyak batch yang perlu dibuat
            $batchCount = ceil($batchUsers->count() / 3);  // Setiap batch berisi maksimal 3 data

            // Loop untuk setiap batch
            for ($i = 0; $i < $batchCount; $i++) {
                // Ambil batch 3 data per iterasi
                $currentBatch = $batchUsers->slice($i * 3, 3);  // Ambil 3 data untuk batch saat ini

                // Cek apakah semua data dalam batch ini sudah berstatus 'paid' dan 'YA'
                $allPaid = $currentBatch->every(function ($item) {
                    return $item->payment_status === 'paid' && $item->kode_payment === 'YA';
                });

                // Tentukan status batch ('paid' atau 'pay')
                $batchStatus = $allPaid ? 'paid' : 'pay';

                // keseluruhan data payment (beranda XR)
                if ($batchStatus === 'paid') {
                    $totalPaidCount++;
                }elseif($batchStatus === 'pay' && $currentBatch->count() == 3) {
                    $totalUnpaidCount++;
                }elseif($currentBatch->count() < 3) {
                    $totalWaitingUnpaidCount++;
                }

                // Data payment masing - masing user berdasarkan email user (beranda XR)
                // Increment sesuai status batch
                if ($batchStatus === 'paid') {
                    $paidBatchCount[$email] = isset($paidBatchCount[$email]) ? $paidBatchCount[$email] + 1 : 1;
                } elseif ($batchStatus === 'pay' && $currentBatch->count() == 3) {
                    $unpaidBatchCount[$email] = isset($unpaidBatchCount[$email]) ? $unpaidBatchCount[$email] + 1 : 1;
                }elseif($currentBatch->count() < 3) {
                    $waitingBatchCount++;
                }
            }
        }
        
        $mapelK13 = [
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
        ];

        $mapelMerdeka = [
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
            [
                'title' => 'Merdeka',
                'image' => 'image/pkn.png',
                'judul' => 'Pkn'
            ],
        ];

        $packetSiswa = [
            [
                'image' => 'image/paket1.jpg',
                'text' => 'Sesi private online mapel Matematika & IPA.',
                'url' => '',
                'button' => 'HaloGur'
            ],
            [
                'image' => 'image/paket2.jpg',
                'text' => 'Sesi Boot Camp for Conversation Only.',
                'url' => '',
                'button' => 'English Camp'
            ],
            [
                'image' => 'image/paket3.jpg',
                'text' => 'TANYAkan soal sulit ke Guru Ahli.',
                'url' => "/tanya",
                'button' => 'TANYA'
            ],
            [
                'image' => 'image/paket3.jpg',
                'text' => 'Pendampingan belajar untuk masuk kampus idaman.',
                'url' => '',
                'button' => 'SNBT'
            ],
            [
                'image' => 'image/paket5.jpg',
                'text' => 'Lorem Ipsum',
                'url' => '/catatan',
                'button' => 'CATATAN'
            ],
        ];
        return view('beranda', compact('user', 'mapelK13', 'mapelMerdeka', 'packetSiswa', 'getTanyaTL', 'getData', 'countData', 'dataAccept',  'validatedMentorAccepted', 'paidBatchCount', 'unpaidBatchCount', 'totalPaidCount', 'totalUnpaidCount', 'totalWaitingUnpaidCount'));
    }

    public function laporan()
    {
         // Mengambil user_id dari session
        $user = session('user');  // Misalnya session hanya menyimpan ID user
        if(!$user) {
            return redirect('/login');
        }

        // Controller laporan Team Leader
        // Mengambil data mentor yang berstatus 'Mentor'
        $getData = Crud::where('status', 'Mentor')->get();

        
        // // Mengambil jumlah Tanya berdasarkan email_mentor yang sesuai dengan email mentor
        $countData = [];
        foreach ($getData as $item) {
            // Mengambil jumlah Tanya untuk setiap mentor berdasarkan email_mentor
            $countData[$item->email] = Tanya::onlyTrashed()->where('email_mentor', $item->email)->count();
        }

        // Controller laporan Mentor
        // pluck() akan mengambil semua email dari koleksi getData
        // groupBy() akan mengambil dan mengelompokkan semua data berdasarkan email_mentor
        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor'); // Mengambil semua pertanyaan yang email_mentornya ada di antara daftar email mentor
        $dataReject = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Ditolak')->get()->groupBy('email_mentor');

        $validatedMentorAccepted = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email');
        $validatedMentorRejected = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Ditolak')->get()->groupBy('email');


        return view('laporan', compact('user', 'getData', 'countData', 'dataAccept', 'dataReject', 'validatedMentorAccepted', 'validatedMentorRejected'));
    }



    public function viewLaporan(string $id)
    {
        $user = session('user');

        // Ambil data mentor berdasarkan ID
        $mentor = Crud::find($id);  // Mengambil data mentor berdasarkan ID yang dikirimkan

        // Ambil semua pertanyaan yang di-trashed berdasarkan email_mentor dari mentor yang dipilih
        $getLaporan = Tanya::onlyTrashed()->where('email_mentor', $mentor->email)->get();
        $dataAccept = Tanya::onlyTrashed()->where('email_mentor', $mentor->email)->where('status', 'Diterima')->get();
        $dataReject = Tanya::onlyTrashed()->where('email_mentor', $mentor->email)->where('status', 'Ditolak')->get();

        $validatedMentorAccepted = Star::where('email', $mentor->email)->where('status', 'Diterima')->get();
        $validatedMentorRejected = Star::where('email', $mentor->email)->where('status', 'Ditolak')->get();

        $statusStar = Star::whereIn('id_tanya', $getLaporan->pluck('id'))->get()->keyBy('id_tanya'); // pluck('id') akan menghasilkan sebuah array yang hanya berisi nilai dari kolom id tersebut.

        $data = Star::where('status', 'Diterima')->where('email', $mentor->email)->get(); // Ambil semua data yang statusnya diterima

        
        $tableData = [];
        $batchCount = ceil($data->count() / 3); // Menghitung berapa banyak batch yang perlu dibuat
        
        $paidBatchCount = 0;
        $unpaidBatchCount = 0;
        $waitingBatchCount = 0;

        // Loop untuk mengelompokkan data berdasarkan batch
        for ($i = 0; $i < $batchCount; $i++) {
            $batchUsers = $data->slice($i * 3, 3); // Mengambil 3 data untuk setiap batch

              // Cek apakah semua data dalam batch ini sudah berstatus 'paid'
                $allPaid = $batchUsers->every(function ($item) {
                    return $item->payment_status === 'paid' && $item->kode_payment === 'YA';
                });

                // Tentukan apakah batch ini 'paid' atau 'pay'
                $batchStatus = $allPaid ? 'paid' : 'pay';

                // Jika batch ini sudah 'paid', increment counter
                if ($batchStatus === 'paid') {
                    $paidBatchCount++;
                }

                if ($batchStatus === 'pay' && $batchUsers->count() == 3) {
                    $unpaidBatchCount++;
                }

                if ($batchStatus === 'pay' && $batchUsers->count() <  3) {
                    $waitingBatchCount++;
                }
        
            // Ambil informasi umum yang sama untuk semua entri dalam satu batch
            $nama_mentor = $batchUsers->first()->nama_mentor;
            $email = $batchUsers->first()->email;
            $sekolah = $batchUsers->first()->sekolah;
            $payment_status = $batchUsers->first()->payment_status;
            $kode_payment = $batchUsers->first()->kode_payment;

            // Masukkan informasi per batch ke dalam array
            $tableData[] = [
                'batch' => $i + 1, // Menandai batch ke-1, ke-2, dst.
                'count' => $batchUsers->count(), // Ambil data per batch
                'nama_mentor' => $nama_mentor,
                'email' => $email,
                'sekolah' => $sekolah,
                'payment_status' => $payment_status,
                'kode_payment' => $kode_payment,
            ];
        }

        // Kirimkan data mentor dan laporan ke view
        return view('viewLaporan', compact('user', 'getLaporan', 'mentor', 'statusStar', 'dataReject', 'dataAccept', 'validatedMentorAccepted', 'validatedMentorRejected', 'tableData', 'data', 'paidBatchCount', 'unpaidBatchCount', 'waitingBatchCount'));
    }

    public function sidebarBeranda()
    {
        $user = session('user');
        $getData = Crud::where('status', 'Mentor')->get();

        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor');
        $validatedMentorAccepted = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email');

        return view('components/sidebar_beranda', compact('user', 'getData', 'dataAccept', 'validatedMentorAccepted'));
    }

    
}
