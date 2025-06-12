<?php

namespace App\Http\Controllers;

use App\Events\TransactionFailed;
use App\Models\UserAccount;
use App\Models\Level;
use App\Models\Tanya;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use App\Models\englishZoneMateri;
use App\Models\englishZoneJawaban;
use App\Models\Features;
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
        $features = Features::all();

        $descriptionsFeatures = [
            'TANYA' => [
                'image_feature' => asset("image/logo-fitur/logo-tanya.png"),
                'textButton' => 'Lihat Paket',
                'price' => 'Rp 2.000 / Koin',

                'descriptions' => [
                    "Jam berTANYA 7.00 - 21.00",
                    "Jenjang TANYA SD (Kelas 1 - 6) Mapel: B. Indonesia, English, IPAS, Matematika",
                    "Jenjang TANYA SMP (Kelas 7 - 9) Mapel: B. Indonesia, English, IPA Terpadu, Matematika",
                    "Jenjang TANYA SMA (Kelas 10 - 12) Mapel: B. Indonesia, English, Biologi, Fisika, Kimia, Matematika",
                    "Gratis 10 koin TANYA setiap hari",
                    "Cashback 1 koin per 1 perTANYAan terjawab",
                    "5 koin untuk 1 berTANYA",
                    "Koin akan dikembalikan jika perTANYAan ditolak",
                    "Leaderboard TANYA Nasional",
                    "Respons cepat kurang dari 5 menit",
                    "Dijawab langsung oleh Tutor Expert",
                ]
            ],
            'Soal dan Pembahasan' => [
                'image_feature' => asset("image/logo-fitur/logo-englishZone.png"),
                'textButton' => 'Segera Hadir',
                'price' => 'Rp 20.000',

                'descriptions' => [
                    "Bank Soal per Sub-bab",
                    "Soal Latihan dan Video Pembahasan",
                    "Soal Ujian dan Pembahasan",
                    "Laporan Perkembangan Hasil Latihan",
                    "Laporan Perkembangan Hasil Ujian untuk orang tua",
                    "Jenjang SOAL SD (Kelas 1 - 6) Mapel: B. Indonesia, English, IPAS, Matematika",
                    "Jenjang SOAL SMP (Kelas 7 - 9) Mapel: B. Indonesia, English, IPA Terpadu, Matematika",
                    "Jenjang SOAL SMA (Kelas 10 - 12) Mapel: B. Indonesia, English, Biologi, Fisika, Kimia, Matematika",
                    "Bonus 100 Koin TANYA",
                ]
            ],
            'English Zone' => [
                'image_feature' => asset("image/logo-fitur/logo-englishZone.png"),
                'textButton' => 'Segera Hadir',
                'price' => 'Rp 500.000',

                'descriptions' => [
                    "Kurikulum berstandar internasional (CEFR Level)",
                    "Jenjang Belajar Per Level Selama 3 Bulan",
                    "24x Sesi Interaktif Bersama Tutor Expert Selama 3 Bulan",
                    "2x Sesi Interaktif per Minggu Selama 3 Bulan",
                    "Belajar Bersama Tutor Expert Berpengalaman",
                    "Metode Belajar Interaktif dan Praktis",
                    "Tersedia Self-Assessment Test",
                    "Laporan Belajar Setiap Bulan",
                    "Materi dan Sertifikat Digital",
                    "Rp.500.000/Bulan",
                ]
            ],
        ];

        return view('index', compact('features', 'descriptionsFeatures'));
    }

    public function mitraCerdas()
    {
        return view('services-pages.mitra-cerdas');
    }

    public function siswa()
    {
        return view('services-pages.siswa');
    }

    public function about()
    {
        return view('about');
    }

    public function beranda()
    {
        $user = Auth::user();

        $getData = UserAccount::where('status', 'Mentor')->get();

        // FOR BERANDA ADMINISTRATOR
        $getMentor = UserAccount::where('role', 'Mentor')->get(); // mendapatkan user mentor

        $getDataSiswa = UserAccount::where('role', 'Siswa')->get(); // mendapatkan user siswa (B2C)

        $getDataMurid = UserAccount::where('role', 'Murid')->get(); // mendapatkan user murid (B2B)

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

        if($user->role == 'Siswa') {
            $packetSiswa = [
                [
                    'image' => 'image/logo-fitur/logo-tanya.png',
                    'text' => 'TANYAkan soal sulit ke Guru Ahli.',
                    'url' => "/tanya",
                    'button' => 'TANYA'
                ],
                [
                    'image' => 'image/logo-fitur/logo-englishZone.png',
                    'text' => 'Sesi Boot Camp for Conversation Only.',
                    'url' => '',
                    'button' => 'Segera Hadir'
                ],
                [
                    'image' => 'image/logo-fitur/logo-englishZone.png',
                    'text' => '[Soal dan Pembahasan].',
                    'url' => '',
                    'button' => 'Segera Hadir'
                ],
            ];
        } else {
            $packetSiswa = [
                [
                    'image' => 'image/logo-fitur/logo-tanya.png',
                    'text' => 'TANYAkan soal sulit ke Guru Ahli.',
                    'url' => "/tanya",
                    'button' => 'TANYA'
                ],
            ];
        }

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