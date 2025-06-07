<?php

namespace App\Http\Controllers;

use App\Events\TransactionFailed;
use App\Models\userAccount;
use App\Models\Level;
use App\Models\Tanya;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use App\Models\englishZoneMateri;
use App\Models\englishZoneJawaban;
use App\Models\StudentProfiles;
use App\Models\MentorPayments;
use App\Models\MentorProfiles;
use App\Models\Transactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                'Link' => '/tanya-coin',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket2.jpg',
                'Desc1' => 'Rekomendasi jurusan dan PTN',
                'Desc2' => '96 kali pembelajaran hybrid dan 20 kali Try Out',
                'Discount' => 'Rp. 7.000.000',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Link' => '/tanya-coin',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket3.jpg',
                'Desc1' => 'Catatan',
                'Desc2' => 'Pemberian catatan per sub-bab dari guru',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Link' => '/tanya-coin',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket3.jpg',
                'Desc1' => 'Tanya dijawab langsung oleh Guru Ahli',
                'Desc2' => 'Jawaban PR dan Tugas lengkap dengan penjelasan',
                'Discount' => '',
                'Price' => 'Rp. 3.000/Koin',
                'Link' => '/tanya-coin',
                'Button' => 'Langganan Sekarang'
            ],
            [
                'Image' => 'image/paket5.jpg',
                'Desc1' => 'English Zone',
                'Desc2' => 'Belajar "conversation" bersama guru ahli',
                'Discount' => 'Rp. 7.000.000 ',
                'Price' => 'Rp. 1.300.000/Tahun',
                'Link' => '/tanya-coin',
                'Button' => 'Langganan Sekarang'
            ],
        ];
        return view('index', [
            'packets' => $packets,
        ]);
    }

    public function beranda()
    {
        $user = Auth::user()->id;

        $getData = userAccount::where('status', 'Mentor')->get();

        // FOR BERANDA ADMINISTRATOR
        $getMentor = UserAccount::where('role', 'Mentor')->get(); // mendapatkan user mentor

        $getDataSiswa = UserAccount::where('role', 'Siswa')->get(); // mendapatkan user siswa (B2C)

        $getDataMurid = userAccount::where('role', 'Murid')->get(); // mendapatkan user murid (B2B)

        $countDataMentor = MentorProfiles::where('status_mentor', 'Diterima')->get(); // menghitung jumlah mentor yang diterima

        $getSiswa = UserAccount::where('role', 'Siswa')->orWhere('role', 'Murid')->get(); /// mendapatkan user siswa dan murid

        // Ambil jumlah Tanya untuk semua user_id dalam satu kali loop
        $countSiswaTanya = Tanya::withTrashed()
            ->whereIn('user_id', $getSiswa->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(fn($items) => $items->count()); // Hitung jumlah Tanya per user

        // Tambahkan jumlah Tanya ke dalam objek siswa tanpa looping manual
        // each() → Memasukkan jumlah Tanya ke dalam setiap objek siswa tanpa membuat objek baru.
        $sortedSiswa = $getSiswa->each(function ($siswa) use ($countSiswaTanya) {
            $siswa->jumlah_tanya = $countSiswaTanya[$siswa->id]?? 0; // Default 0 jika tidak ada
        })->filter(function ($siswa) {
            return $siswa->jumlah_tanya > 0; // Filter siswa dengan jumlah Tanya lebih besar dari 0
        })->sortByDesc('jumlah_tanya')->take(10); // Urutkan & ambil 10 terbesar

        $countDataTanyaAll = Tanya::withTrashed()->get(); // menghitung jumlah seluruh data Tanya

        $packetSiswa = [
            [
                'image' => 'image/paket1.jpg',
                'text' => 'Sesi private online mapel Matematika & IPA.',
                'url' => '',
                'button' => 'HaloGur'
            ],
            [
                'image' => 'image/logo-fitur/logo-englishZone.png',
                'text' => 'Sesi Boot Camp for Conversation Only.',
                'url' => '/english-zone',
                'button' => 'English Zone'
            ],
            [
                'image' => 'image/logo-fitur/logo-tanya.png',
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
        return view('beranda', compact('packetSiswa', 'getData',  'getDataSiswa', 'getDataMurid', 'countSiswaTanya', 'getSiswa', 'sortedSiswa', 'countDataTanyaAll', 'countDataMentor'));
    }

    public function sidebarBeranda()
    {
        return view('components/sidebar_beranda');
    }

    public function sidebarBerandaMobile()
    {
        return view('components/sidebar_beranda');
    }

    // controller upload soal bobot a - e
    // public function uploadSoal()
    // {
    //     $englishZoneBobot= [
    //         [
    //             'title_editor' => 'Pilihan A',
    //             'title_bobot' => 'Bobot A',
    //             'value_editor' => 'pilihan_A',
    //             'value_bobot' => 'bobot_A',
    //         ],
    //         [
    //             'title_editor' => 'Pilihan B',
    //             'title_bobot' => 'Bobot B',
    //             'value_editor' => 'pilihan_B',
    //             'value_bobot' => 'bobot_B',
    //         ],
    //         [
    //             'title_editor' => 'Pilihan C',
    //             'title_bobot' => 'Bobot C',
    //             'value_editor' => 'pilihan_C',
    //             'value_bobot' => 'bobot_C',
    //         ],
    //         [
    //             'title_editor' => 'Pilihan D',
    //             'title_bobot' => 'Bobot D',
    //             'value_editor' => 'pilihan_D',
    //             'value_bobot' => 'bobot_D',
    //         ],
    //         [
    //             'title_editor' => 'Pilihan E',
    //             'title_bobot' => 'Bobot E',
    //             'value_editor' => 'pilihan_E',
    //             'value_bobot' => 'bobot_E',
    //         ],
    //     ];

    //     $user = session('user');
    //     if(!isset($user)) {
    //         return redirect('/login');
    //     }

    //     return view('upload-soal', compact('user', 'englishZoneBobot'));
    // }

    public function certificate()
    {
        $user = session('user');
        if(!isset($user)) {
            return redirect('/login');
        }

        return view('certif', compact('user'));
    }

    public function mitraCerdas()
    {
        return view('services-pages.mitra-cerdas');
    }

    public function siswa()
    {
        return view('services-pages.siswa');
    }

    public function historiPembelian()
    {
        $today = now();
        // history pembelian success
        $transactionUserSuccess = Transactions::where('user_id', Auth::user()->id)->where('transaction_status', 'Berhasil')->orderBy('created_at', 'desc')->get();

        $transactionUserWaiting = Transactions::where('user_id', Auth::user()->id)->where('transaction_status', 'Pending')->orderBy('created_at', 'desc')->get();

        $transactionUserFailed = Transactions::where('user_id', Auth::user()->id)->whereIn('transaction_status', ['Gagal', 'Kadaluarsa', 'Dibatalkan'])->orderBy('created_at', 'desc')->get();

        //         $today = now();

        // // expired status
        // $pendingStatus = Transactions::where('transaction_status', 'Pending')->get();

        // // $expiredStatus = $pendingStatus->filter(function ($transaction) use ($today) {
        // //     return $transaction->created_at->addDays(3) < $today;
        // // });

        // $expiredStatus = $pendingStatus->filter(function ($transaction) use ($today) {
        //     return $transaction->created_at->diffInMinutes($today) >= 1;
        // });


        // foreach ($expiredStatus as $transaction) {
        //     $transaction->update([
        //         'transaction_status' => 'Kadaluarsa'
        //     ]);
        // }

        return view('history.histori-pembelian', compact('transactionUserSuccess', 'transactionUserWaiting', 'transactionUserFailed'));
    }

    public function historiKoin()
    {
        return view('history.histori-koin');
    }

    public function reportMentor()
    {
        // report for mentor
        $mentors = UserAccount::with('MentorProfiles')->where('role', 'Mentor')->get();

        $mentorIds = $mentors->pluck('id');

        $countDataTanyaMentor = Tanya::onlyTrashed()->whereIn('mentor_id', $mentorIds)->where('status_soal', 'Diterima')->count();

        $countPendapatanTanyaMentor = MentorPayments::whereIn('mentor_id', $mentorIds)->where('status_payment',  'Paid')->sum('total_ammount');

        // Ambil semua kode referral mentor
        $referralCodes = $mentors->pluck('MentorProfiles.kode_referral');

        // Hitung semua siswa yang memakai referral code mentor
        $countReferralCodeUserMentor = StudentProfiles::whereIn('mentor_referral_code', $referralCodes)->count();

        return view('report-user.laporan-mentor', compact('countDataTanyaMentor', 'countPendapatanTanyaMentor', 'countReferralCodeUserMentor'));
    }

    public function batchDetailPaymentMentor($id)
    {
        return view('report-user.batch-detail-laporan-mentor', compact('id'));
    }
}