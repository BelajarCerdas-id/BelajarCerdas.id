<?php

namespace App\Http\Controllers;

use App\Events\CountMentorQuestionsAwaitVerification;
use App\Events\TanyaMentorVerifications;
use App\Events\UpdateLihatDetailTanyaMentor;
use App\Models\Bab;
use App\Models\CoinHistory;
use App\Models\Star;
use App\Models\Tanya;
use App\Models\Keynote;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use App\Models\Fase;
use App\Models\FeaturesRoles;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Mapel;
use App\Models\MentorPaymentDetail;
use App\Models\SubBab;
use App\Models\MentorPayments;
use App\Models\TanyaVerifications;
use App\Models\Transactions;
use App\Models\userAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class FilterController extends Controller
{
    // FILTERING & PAGINATION TANYA STUDENT, MENTOR, ADMINISTRATOR
    public function filterHistoryStudent(Request $request)
    {

        $query = Tanya::onlyTrashed()->where('user_id', Auth::user()->id);

        // Apply the status filter if provided
        if ($request->filled('status_soal') && $request->status_soal !== 'semua') {
            $query->where('status_soal', $request->status_soal);
        }

        // Paginate the filtered results
        $data = $query->with('Mapel', 'Bab')->orderBy('updated_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links()->render(), // Convert pagination links to string
            'restoreUrl' => route('tanya.updateStatusSoalRestore', ':id')
        ]);
    }

    public function filterHistoryTeacher(Request $request)
    {
        $query = Tanya::onlyTrashed()->where('mentor_id', Auth::user()->id);

        if($request->filled('status_soal') && $request->status_soal !== 'semua') {
            $query->where('status_soal', $request->status_soal);
        }

        $data = $query->with('Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab')->orderBy('updated_at', 'desc')->paginate(10);


        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'restoreUrl' => route('getRestore.edit', ':id')
        ]);
    }

    public function filterTanyaTeacher(Request $request)
    {
        $query = Tanya::query();

        if($request->filled('status_soal') && $request->status !== 'semua') {
            $query->where('status_soal', $request->status);
        }

        $data = $query->with(['Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab'])->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'restoreUrl' => route('tanya.edit', ':id')
        ]);
    }

    public function paginateTanyaRollback(Request $request)
    {
        $query = Tanya::query();

        $data = $query->with(['Student.StudentProfiles', 'ViewedBy.MentorProfiles', 'Kelas', 'Mapel', 'Bab'])->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'updateIsBeingViewed' => route('rollbackQuestion.update', ':id'),
        ]);
    }

    public function filterTanyaTL(Request $request)
    {
        $user = session('user');

        if(!isset($user)) {
            return redirect('/login');
        }

        $query = Tanya::query();

        if($request->filled('status') && $request->status !== 'semua'){
            $query->where('status', $request->status );
        }

        $data = $query->onlyTrashed()->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'restoreUrl' => route('tanya.edit', ':id')
        ]);
    }

    // PAGINATION HISTORY PURCHASE
    public function paginateHistoryPurchaseSuccess(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->where('transaction_status', 'Berhasil')->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
        ]);
    }

    public function paginateHistoryPurchaseWaiting(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->where('transaction_status', 'Pending')->orderBy('created_at', 'desc')->paginate(6);

        $data = $transactions->getCollection()->map(function ($item) {
            $item->renewCheckout = route('checkout.pending', ['id' => $item->id]);
            return $item;
        });

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
        ]);
    }

    public function paginateHistoryPurchaseFailed(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->whereIn('transaction_status', ['Gagal', 'Kadaluarsa'])->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
        ]);
    }

    // PAGINATE HISTORY COIN
    public function paginateHistoryCoinIn(Request $request)
    {
        $user = Auth::user();

        $historyCoinIn = CoinHistory::with('UserAccount.StudentProfiles')->where('user_id', $user->id)->where('tipe_koin', 'Masuk')
        ->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $historyCoinIn->items(),
            'links' => (string) $historyCoinIn->links()->render(),
        ]);
    }

    public function paginateHistoryCoinOut(Request $request)
    {
        $user = Auth::user();

        $historyCoinIn = CoinHistory::with(['UserAccount.StudentProfiles',
            'Tanya' => function ($query) {
                $query->withTrashed()->with('Fase', 'Kelas', 'Mapel', 'Bab');
            }])->where('user_id', $user->id)->where('tipe_koin', 'Keluar')
            ->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $historyCoinIn->items(),
            'links' => (string) $historyCoinIn->links()->render(),
        ]);
    }

    // PAGINATE PAYMENT MENTOR (ADMINISTRATOR)
    public function paginateListMentorTanya(Request $request)
    {
        // mengurutkan berdasarkan jumlah verifikasi menunggu terbanyak (withCount diambil dari tanyaVerificationMentor relasi dengann UserAccount)
        $userMentor = UserAccount::with('MentorProfiles')
            ->where('role', 'Mentor')
            ->withCount([
                'tanyaVerificationMentor as verifikasi_menunggu_count' => function ($query) {
                    $query->where('status_verifikasi', 'Menunggu');
                }
            ])
            ->orderByDesc('verifikasi_menunggu_count')
            ->orderBy('created_at', 'desc') // fallback jika sama
            ->paginate(6);

        $countData = [];

        foreach($userMentor as $item) {
            $countData[$item->id] = $item->verifikasi_menunggu_count;
        }

        broadcast(new CountMentorQuestionsAwaitVerification($countData))->toOthers();

        return response()->json([
            'data' => $userMentor->items(),
            'countData' => $countData,
            'links' => (string) $userMentor->links(),
            'detailDataTanyaMentor' => route('tanya.mentor.accepted.view', ':id'),
        ]);
    }

    public function paginateVerificationTanyaMentor(Request $request, $mentor_id)
    {
        $dataTanyaVerifications = TanyaVerifications::with(['Tanya.Fase', 'Tanya.Kelas', 'Tanya.Mapel', 'Tanya.Bab', 'Tanya' => function ($query){
            $query->onlyTrashed();
        }])
            ->where('mentor_id', $mentor_id)
            ->where('status_verifikasi', 'Menunggu');

        $data = $dataTanyaVerifications->orderBy('created_at', 'desc')->paginate(10);

        // return dalam bentuk JSON
        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'updateDataTanyaVerificationAccepted' => route('verificationTanyaMentor.accepted', ':id'),
            'restoreUrl' => route('tanya.updateStatusSoalRestore', ':id'),
        ]);
    }


    public function paginateListPaymentTanyaMentor(Request $request)
    {
        $getTanyaMentorVerifiedSuccess = MentorPayments::with('Mentor.MentorProfiles')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $getTanyaMentorVerifiedSuccess->items(),
            'links' => (string) $getTanyaMentorVerifiedSuccess->links(),
            'paymentTanyaMentorUpdate' => route('pembayaran.tanya.mentor.update', ':id'),
        ]);
    }

    // PAGINATE SYLLABUS SERVICES
    public function paginateSyllabusCuriculum(Request $request)
    {
        $getSyllabusCuriculum = Kurikulum::with(['UserAccount.OfficeProfiles'])->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'data' => $getSyllabusCuriculum->items(),
            'links' => (string) $getSyllabusCuriculum->links(),
            'faseDetail' => '/syllabus/curiculum/:nama_kurikulum/:id/fase',
            'kurikulumUpdate' => route('kurikulum.update', ':id'),
        ]);
    }

    public function paginateSyllabusFase(Request $request, $nama_kurikulum, $id)
    {
        $getSyllabusFase = Fase::with(['UserAccount.OfficeProfiles', 'Kurikulum'])->where('kurikulum_id', $id)->orderBy('created_at', 'asc')->paginate(20);

        return response()->json([
            'data' => $getSyllabusFase->items(),
            'links' => (string) $getSyllabusFase->links(),
            'kelasDetail' => '/syllabus/curiculum/:nama_kurikulum/:kurikulum_id/:fase_id/kelas',
            'faseUpdate' => '/syllabus/curiculum/fase/update/:id',
        ]);
    }

    public function paginateSyllabusKelas(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id)
    {
        $getSyllabusKelas = Kelas::with(['UserAccount.OfficeProfiles', 'Kurikulum'])->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)
        ->orderBy('created_at', 'asc')->paginate(20);

        return response()->json([
            'data' => $getSyllabusKelas->items(),
            'links' => (string) $getSyllabusKelas->links(),
            'mapelDetail' => '/syllabus/curiculum/:nama_kurikulum/:kurikulum_id/:fase_id/:kelas_id/mapel',
            'kelasUpdate' => '/syllabus/curiculum/kelas/update/:id',
        ]);
    }

    public function paginateSyllabusMapel(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id)
    {

        // Query dengan filter lengkap
        $getSyllabusMapel = Mapel::with(['UserAccount.OfficeProfiles', 'Kurikulum'])->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)
            ->where('kelas_id', $kelas_id)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return response()->json([
            'data' => $getSyllabusMapel->items(),
            'links' => (string) $getSyllabusMapel->links(),
            'babDetail' => '/syllabus/curiculum/:nama_kurikulum/:kurikulum_id/:fase_id/:kelas_id/:mapel_id/bab',
            'mapelUpdate' => '/syllabus/curiculum/mapel/update/:id/:kelas_id',
        ]);
    }

    public function paginateSyllabusBab(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id)
    {
        // Query dengan filter lengkap
        $getSyllabusBab = Bab::with(['BabFeatureStatuses', 'UserAccount.OfficeProfiles', 'Kurikulum'])->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)
            ->where('kelas_id', $kelas_id)->where('mapel_id', $mapel_id)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'syllabus')->get();

        // Siapkan mapping id bab + feature_id => status
        $statusBabFeature = [];

        foreach ($getSyllabusBab as $bab) {
            foreach ($bab->BabFeatureStatuses as $featureStatus) {
                $statusBabFeature[$bab->id][$featureStatus->feature_id] = $featureStatus->status_bab;
            }
        }

        return response()->json([
            'data' => $getSyllabusBab->items(),
            'links' => (string) $getSyllabusBab->links(),
            'subBabDetail' => '/syllabus/curiculum/:nama_kurikulum/:kurikulum_id/:fase_id/:kelas_id/:mapel_id/:bab_id/sub-bab',
            'babUpdate' => '/syllabus/curiculum/bab/update/:kurikulum_id/:kelas_id/:mapel_id/:id',
            'dataFeaturesRoles' => $dataFeaturesRoles,
            'statusBabFeature' => $statusBabFeature,
        ]);
    }

    public function paginateSyllabusSubBab(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id, $bab_id)
    {
        // Query dengan filter lengkap
        $getSyllabusSubBab = SubBab::with(['SubBabFeatureStatuses', 'UserAccount.OfficeProfiles', 'Kurikulum'])->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)
            ->where('kelas_id', $kelas_id)->where('mapel_id', $mapel_id)->where('bab_id', $bab_id)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'syllabus')->get();

        $statusSubBabFeature = [];

        foreach ($getSyllabusSubBab as $subBab) {
            foreach ($subBab->SubBabFeatureStatuses as $featureStatus) {
                $statusSubBabFeature[$subBab->id][$featureStatus->feature_id] = $featureStatus->status_sub_bab;
            }
        }

        return response()->json([
            'data' => $getSyllabusSubBab->items(),
            'links' => (string) $getSyllabusSubBab->links(),
            'subBabUpdate' => '/syllabus/curiculum/sub-bab/update/:kurikulum_id/:kelas_id/:mapel_id/:bab_id/:id',
            'dataFeaturesRoles' => $dataFeaturesRoles,
            'statusSubBabFeature' => $statusSubBabFeature,
        ]);
    }

    // PAGINATE LAPORAN MENTOR
    public function paginateReportPaymentMentor(Request $request)
    {
        $user = Auth::user();

        $dataPaymentTanyaMentor = MentorPayments::where('mentor_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $dataPaymentTanyaMentor->items(),
            'links' => (string) $dataPaymentTanyaMentor->links()->render(),
            'batchDetailPaymentMentor' => route('batch.detail.payment.mentor', ':id'),
        ]);
    }

    public function paginateBatchDetailPaymentMentor(Request $request, $id)
    {
        $user = Auth::user();

        $dataBatchDetailPaymentMentor = MentorPaymentDetail::with('TanyaVerifications')->where('payment_mentor_id', $id)->where('mentor_id', $user->id)
        ->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $dataBatchDetailPaymentMentor->items(),
            'links' => (string) $dataBatchDetailPaymentMentor->links()->render(),
            'batchDetailPaymentMentor' => '/batch-detail-pembayaran-mentor/:id',
        ]);
    }

    // LEADERBOARD RANK TANYA STUDENT
    public function leaderboardRankTanya(Request $request)
    {
        $user = UserAccount::with('StudentProfiles')->find(Auth::id()); // mengambil objek user
        $userId = Auth::user()->id; // hanya ID user

        $getSiswa = UserAccount::with(['StudentProfiles.Kelas'])->where('role', 'Siswa')->orWhere('role', 'Murid')->get();

        // FOR BERANDA STUDENT
        $countTanyaStudent = Tanya::with('CoinHistory')->withTrashed()->whereIn('user_id', $getSiswa->pluck('id'))->whereIn('status_soal', ['Diterima', 'Menunggu'])
        ->get()->groupBy('user_id')->map(fn($items) => $items->count()); // Hitung jumlah Tanya per user

        // menghitung jumlah koin yang keluar untuk seluruh student
        $countKoinStudent = Tanya::with(['CoinHistory' => function ($query) {
            $query->where('tipe_koin', 'Keluar');
        }]) // pastikan CoinHistory adalah relasi valid dari Tanya
            ->withTrashed()
            ->whereIn('user_id', $getSiswa->pluck('id'))
            ->get()
            ->groupBy('user_id')
            ->map(function ($items) {
                return $items->reduce(function ($carry, $item) {
                    return $carry + ($item->CoinHistory->jumlah_koin ?? 0);
                }, 0);
            });

        // Tambahkan jumlah Tanya dan jumlah koin ke dalam objek siswa tanpa looping manual
        $sortedTanyaStudent = $getSiswa->each(function ($siswa) use ($countTanyaStudent, $countKoinStudent) {
            $siswa->jumlah_tanya = $countTanyaStudent[$siswa->id] ?? 0;
            $siswa->jumlah_koin = $countKoinStudent[$siswa->id] ?? 0;
        })->filter(function ($siswa) {
            return $siswa->jumlah_tanya > 0;
        })->sortByDesc('jumlah_tanya')->take(100)->values(); // pastikan pakai values() agar index dimulai dari 0

        $sortedTanyaStudent->each(function ($student, $index) {
            $rank = $index + 1;
            $student->rank = $rank;

            $student->rankIcon = match ($rank) {
                1 => "<i class='fa-solid fa-crown text-yellow-400 font-bold text-lg'></i>",
                2 => "<i class='fa-solid fa-crown text-gray-400 font-bold text-lg'></i>",
                3 => "<i class='fa-solid fa-crown text-amber-800 font-bold text-lg'></i>",
                default => $rank,
            };
        });

        // mengambil data tanya student yang sedang login
        $countDataTanyaUserLogin = Tanya::withTrashed()->where('user_id', Auth::user()->id)->whereIn('status_soal', ['Diterima', 'Menunggu'])->count();

        // membuat ranking pengguna tanya terbanyak
        $rankingTanyaUser = $sortedTanyaStudent->values()->search(function ($item) use ($userId) {
            return $item->id === $userId;
        });

        $rankingTanyaUser = $rankingTanyaUser !== false ? $rankingTanyaUser + 1 : null; // tambahkan 1 karena index dimulai dari 0

        return response()->json([
            'data' => $sortedTanyaStudent,
            'countDataTanyaUserLogin' => $countDataTanyaUserLogin,
            'rankingTanyaUser' => $rankingTanyaUser,
            'user' => $user,
        ]);
    }

    public function questionStatus(Request $request)
    {
        $user = session('user');

        $query = englishZoneSoal::query()->groupBy('soal');

        // filtering by status_soal
        if ($request->filled('status_soal') && $request->status_soal !== 'semua') {
        $query->where('status_soal', $request->status_soal);
        }

        // filtering by modul
        if ($request->filled('modul_soal') && $request->modul_soal !== 'semua') {
            $query->where('modul_soal', $request->modul_soal);
        }

        // filtering by jenjang
        if($request->filled('jenjang') && $request->jenjang !== 'semua') {
            $query->where('jenjang', $request->jenjang);
        }

        // Paginate the filtered results
        $data = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links()
        ]);
    }
}