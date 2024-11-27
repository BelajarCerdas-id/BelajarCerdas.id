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
                'Price' => 'Rp. 1.400.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket2.jpg',
                'Desc1' => 'Rekomendasi jurusan dan PTN',
                'Desc2' => '96 kali pembelajaran hybrid dan 20 kali Try Out',
                'Discount' => 'Rp. 7.000.000',
                'Price' => 'Rp. 1.400.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket3.jpg',
                'Desc1' => 'Catatan',
                'Desc2' => 'Pemberian catatan per sub-bab dari guru',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.400.000/Tahun',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket4.jpg',
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
                'Price' => 'Rp. 1.400.000/Tahun',
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
        $getTanyaTL = Tanya::onlyTrashed()->get();

        if (!$user) {
            return redirect('/login');
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
                'image' => 'image/paket4.jpg',
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
        return view('beranda', compact('user', 'mapelK13', 'mapelMerdeka', 'packetSiswa', 'getTanyaTL'));
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
        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email')) ->where('status', 'Diterima')->get()->groupBy('email_mentor'); // Mengambil semua pertanyaan yang email_mentornya ada di antara daftar email mentor
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

        // Kirimkan data mentor dan laporan ke view
        return view('viewLaporan', compact('user', 'getLaporan', 'mentor', 'statusStar', 'dataReject', 'dataAccept', 'validatedMentorAccepted', 'validatedMentorRejected'));
    }

    
}
