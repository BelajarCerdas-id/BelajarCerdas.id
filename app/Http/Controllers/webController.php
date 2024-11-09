<?php

namespace App\Http\Controllers;

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
        // Retrieve the user from the session
        $user = session('user');

        if (!$user) {
            return redirect('/login');
        } else {
            // Pass the user data to the view
            // compact untuk meneruskan data dari controller (pengontrol) ke tampilan (view)
            return view('beranda', compact('user', 'mapelK13', 'mapelMerdeka', 'packetSiswa'));
        }
    }
}

