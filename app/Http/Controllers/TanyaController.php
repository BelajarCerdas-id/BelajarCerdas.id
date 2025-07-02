<?php

namespace App\Http\Controllers;

use App\Events\CountMentorQuestionsAwaitVerification;
use App\Events\EventTanyaAccess;
use App\Events\PaymentTanyaMentor;
use App\Events\QuestionRejected;
use App\Events\RollbackQuestion;
use Midtrans\Snap;
use App\Models\Fase;
use App\Models\Star;
use App\Models\Tanya;
use App\Models\testing;
use App\Models\TanyaAccess;
use App\Models\UserAccount;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Events\QuestionAsked;
use App\Models\FeaturePrices;
use App\Models\TanyaUserCoin;
use App\Events\QuestionAnswered;
use App\Events\TanyaCoinRefunded;
use App\Events\TanyaMentorVerifications;
use App\Events\UpdateLihatDetailTanyaMentor;
use App\Models\CoinHistory;
use App\Models\MentorPaymentDetail;
use App\Models\MentorPayments;
use App\Models\TanyaRankMentor;
use App\Models\TanyaRankMentorProgress;
use App\Models\TanyaVerifications;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentHandlers\CheckoutCoinHandler;
use App\Services\PaymentHandlers\RenewCheckoutCoinHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TanyaController extends Controller
{
    // END TO END
    // function view tanya (student & mentor)
    public function index()
    {
        // mengambil tanggal hari ini
        $today = now();

        // ambil semua data di tanya (belum di soft delete)
        $getTanya = Tanya::with('Student')->get();

        // HISTORY DAILY TANYA
        // history belum terjawab
        $historyStudent = Tanya::with('Kelas', 'Mapel', 'Bab')->where('user_id', Auth::user()->id)
        ->orderBy('created_at', 'desc')->whereDate('created_at', $today)->get();

        // history terjawab
        $historyStudentAnswered = Tanya::with('Kelas', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)
        ->where('status_soal', 'Diterima')->orderBy('created_at', 'desc')
        ->whereDate('created_at', $today)->get();

        // history ditolak
        $historyStudentReject = Tanya::with('Kelas', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)
        ->where('status_soal', 'Ditolak')->orderBy('created_at', 'desc')
        ->whereDate('created_at', $today)->get();

        // HISTORY TANYA (student & mentor after soft delete)
        $siswaHistoryRestore = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(2); // dataSiswa session tanya for page siswa (after soft delete)
        $teacherHistoryRestore = Tanya::onlyTrashed()->where('mentor_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(); // getStore session tanya for page guru (after soft delete)

        $getData = UserAccount::where('status', 'Mentor')->get();

        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor');

        // Mengambil jumlah Tanya berdasarkan user login
        $countDataTanyaAnsweredUser = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal', 'Diterima')->whereDate('created_at', $today)->count();

        $countDataTanyaRejectedUser = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal', 'Ditolak')->whereDate('created_at', $today)->count();

        // CLAIM COIN TANYA DAILY
        $historyCoinDaily = CoinHistory::where('user_id', Auth::user()->id)->where('tipe_koin', 'Masuk')
        ->where('sumber_koin', 'Koin Harian')->whereDate('created_at', now())->first();

        // get data fase
        $getFase = Fase::all();

        return view('Features.Tanya.end-to-end..tanya', compact('getTanya', 'historyStudent', 'historyStudentAnswered', 'historyStudentReject', 'teacherHistoryRestore', 'siswaHistoryRestore', 'dataAccept',  'countDataTanyaAnsweredUser', 'countDataTanyaRejectedUser', 'historyCoinDaily', 'getFase'));
    }

    // function insert question (student)
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'fase_id' => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'bab_id' => 'required',
            'pertanyaan' => 'required',
            'image_tanya' => 'nullable|image|mimes:jpg,jpeg,png|max:2000'
        ], [
            'fase_id.required' => 'Harap pilih fase!',
            'kelas_id.required' => 'Harap Pilih kelas!',
            'mapel_id.required' => 'Harap Pilih mata pelajaran!',
            'bab_id.required' => 'Harap pilih bab!',
            'pertanyaan.required' => 'Pertanyaan harus diisi!',
            'image_tanya.max' => 'Ukuran gambar maksimal 2MB!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $image = null;
        if ($request->hasFile('image_tanya')) {
            $filename = time() . '_' . $request->file('image_tanya')->getClientOriginalName();
            $request->file('image_tanya')->move(public_path('images_tanya'), $filename);
            $image = $filename;
        }

        if(TanyaUserCoin::where('user_id', $user->id)->first()->jumlah_koin < $request->harga_koin) {
            return redirect()->back()->with('not-enough-coin-tanya', 'Maaf, Koin anda tidak cukup untuk mata pelajaran ini, silahkan pilih mata pelajaran lain atau isi ulang koin anda.');
        }

        $questionCreate = Tanya::create([
            'user_id' => $user->id,
            'fase_id' => $request->fase_id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'bab_id' => $request->bab_id,
            'harga_koin' => $request->harga_koin,
            'pertanyaan' => $request->pertanyaan,
            'image_tanya' => $image,
        ]);

        TanyaUserCoin::where('user_id', $user->id)->decrement('jumlah_koin', $request->harga_koin);

        broadcast(new QuestionAsked($questionCreate))->toOthers();

        // masukkan pertanyaan sebagai histori koin keluar
        $coinHistoryUser = CoinHistory::where('user_id', $user->id)->first();

        CoinHistory::create([
            'user_id' => $user->id,
            'jumlah_koin' => $request->harga_koin,
            'tipe_koin' => 'Keluar',
            'sumber_koin' => 'berTANYA',
            'tanya_id' => $questionCreate->id // Menyimpan ID pertanyaan sebagai tanya_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil dikirim.',
            'data' => $questionCreate
        ]);
    }

    // function edit question (mentor)
    public function edit(string $id)
    {
        $getTanya = Tanya::with('Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab')->find($id); // for answer

        $postReject = Tanya::withTrashed()->findOrFail($id); // for reject

        $getRestore = Tanya::withTrashed()->findOrFail($id);


        if (!$getTanya || !$postReject || !$getRestore ||$getTanya->is_being_viewed && $getTanya->viewed_by !== Auth::user()->id) {
            return redirect('/tanya');
        }

        return view('Features.Tanya.end-to-end.view', compact('getTanya', 'postReject', 'getRestore'));
    }

    // function mark viewed "lihat soal" menjadi "sedang dilihat" (mentor)
    public function markViewed($id)
    {
        $currentMentorId = Auth::user()->id;
        try {
            DB::beginTransaction();

            // 🔒 Kunci baris soal
            $tanya = Tanya::where('id', $id)->lockForUpdate()->firstOrFail();

            // 🚫 Jika sudah dilihat orang lain, tolak
            if ($tanya->is_being_viewed) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Soal sedang dilihat oleh mentor lain.'
                ], 409); // Conflict
            }

            // ✅ Tandai sebagai sedang dilihat
            $tanya->update([
                'is_being_viewed' => true,
                'viewed_by' => Auth::user()->id
            ]);

            DB::commit();

            // Broadcast ke client lain
            broadcast(new QuestionAsked($tanya))->toOthers();

            return response()->json([
                'message' => 'Berhasil mengambil soal.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengambil soal.'
            ], 500);
        }
    }

    // function mark viewed "lihat soal" menjadi "tidak sedang dilihat" back button di view pertanyaan (mentor)
    public function markViewedBackButton($id)
    {
        $updateLihatBackButton = Tanya::where('id',$id)->firstOrFail();

        $updateLihatBackButton->update([
            'is_being_viewed' => false,
            'viewed_by' => null
        ]);

        broadcast(new QuestionAsked($updateLihatBackButton))->toOthers();

        return redirect('/tanya');
    }

    // function answer question 'diterima' student (mentor)
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Validasi input
        $validatedData = $request->validate([
            'jawaban' => 'required',
            'image_jawab' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'jawaban.required' => 'Jawaban tidak boleh kosong!',
            'image_jawab.max' => 'File gambar melebihi jumlah ukuran maksimal'
        ]);

        // Cari record yang akan diupdate
        $getTanya = Tanya::find($id);

        if($getTanya) {
            // Update data lain
            $getTanya->mentor_id = $user->id;
            $getTanya->jawaban = $request->jawaban;
            $getTanya->status_soal = 'Diterima';
            $getTanya->is_being_viewed = false;
            $getTanya->viewed_by = null;

            if ($request->hasFile('image_jawab')) {
                $filename = time() . $request->file('image_jawab')->getClientOriginalName();
                $request->file('image_jawab')->move(public_path('images_tanya'), $filename);
                $getTanya->image_jawab = $filename; // Simpan nama file baru ke database
            }
                $getTanya->save(); // save all update's in database
                $getTanya->delete(); // delete data after update data (supaya masuk ke softdelete)

            // broadcast untuk menjawab tanya student dari mentor (diterima)
            broadcast(new QuestionAnswered($getTanya))->toOthers();
            broadcast(new QuestionAsked($getTanya))->toOthers();

            // untuk cashback koin student after tanya diterima mentor
            $tanyaKoinUsers = TanyaUserCoin::where('user_id', $getTanya->Student->id)->firstOrFail();

            $incrementKoinUser = $tanyaKoinUsers->increment('jumlah_koin', 1);

            $tanyaKoinUsers->update([
                'jumlah_koin' => $tanyaKoinUsers->jumlah_koin
            ]);

            // untuk mendengarkan event broadcast update koin student terbaru (cashback)
            broadcast(new TanyaCoinRefunded($tanyaKoinUsers))->toOthers();

            // history coin student (cashback)
            $coinHistoryUser = CoinHistory::where('user_id', $user->id)->where('tanya_id', $getTanya->id)->first();

            CoinHistory::create([
                'user_id' => $getTanya->user_id,
                'jumlah_koin' => $incrementKoinUser,
                'tipe_koin' => 'Masuk',
                'sumber_koin' => 'Cashback berTANYA',
                'tanya_id' => $getTanya->id // Menyimpan ID pertanyaan sebagai tanya_id
            ]);

            // untuk lanjut soal di verifikasi administrator
            $getFeeQuestionsMentor = TanyaRankMentorProgress::where('mentor_id', $user->id)->first();

            $createTanyaVerifications = TanyaVerifications::create([
                'mentor_id' => $user->id,
                'tanya_id' => $getTanya->id,
                'harga_soal' => $getFeeQuestionsMentor->TanyaRank->harga_per_soal ?? 0,
            ]);

            $mentorId = $user->id;

            $countData = [
                $mentorId => TanyaVerifications::where('mentor_id', $mentorId)
                    ->where('status_verifikasi', 'Menunggu')
                    ->count()
            ];

            // broadcast untuk menghitung pertanyaan mentor yang belum di verifikasi
            broadcast(new CountMentorQuestionsAwaitVerification($countData))->toOthers();

            // broadcast untuk menampilkan data pertanyaan mentor yang belum di verifikasi
            broadcast(new TanyaMentorVerifications($createTanyaVerifications))->toOthers();

            // untuk menghitung jumlah_soal diterima mentor (rank)
            $jumlahSoalDiterima = Tanya::onlyTrashed()->where('mentor_id', $user->id)->where('status_soal', 'Diterima')->count();

            // update atau create progress
            $tanyaRankProgress = TanyaRankMentorProgress::updateOrCreate(
                ['mentor_id' => $user->id],
                [
                    'jumlah_soal_diterima' => $jumlahSoalDiterima,
                ]
            );
        }

        return redirect('/tanya')->with('success-answer-tanya', 'Jawaban telah terkirim!');
    }


    // function reject question 'ditolak' student (mentor)
    public function updateReject(Request $request, string $id)
    {
        $user = Auth::user();

        $validatedReject = $request->validate([
            'alasan_ditolak' => 'required',
        ], [
            'alasan_ditolak.required' => 'Harap pilih alasan untuk menolak pertanyaan!'
        ]);

        $postReject = Tanya::with('Student')->find($id);

        // $tanyaKoinUsers = TanyaUserCoin::where('user_id', $postReject->Student->id)->get();

        // foreach ($tanyaKoinUsers as $koinUser) {
        //     $koinUser->increment('jumlah_koin', $postReject->harga_koin);

        //     $koinUser->update([
        //         'harga_koin' => $koinUser->jumlah_koin
        //     ]);
        // }

        $tanyaKoinUsers = TanyaUserCoin::where('user_id', $postReject->Student->id)->firstOrFail();

        $tanyaKoinUsers->increment('jumlah_koin', $postReject->harga_koin);

        $tanyaKoinUsers->update([
            'harga_koin' => $tanyaKoinUsers->jumlah_koin
        ]);

        $postReject->update([
            'mentor_id' => $user->id,
            'status_soal' => 'Ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
            'is_being_viewed' => false,
            'viewed_by' => null
        ]);
        $postReject->delete(); // delete data after update data (supaya masuk ke softdelete)

        // broadcast untuk menjawab tanya student dari mentor (ditolak)
        broadcast(new QuestionRejected($postReject))->toOthers();
        broadcast(new QuestionAsked($postReject))->toOthers();

        // mendengarkan event broadcast untuk update koin student terbaru secara realtime (koin dikembalikan)
        broadcast(new TanyaCoinRefunded($tanyaKoinUsers))->toOthers();

        // menolak pertanyaan dan update tipe koin dari keluar menjadi dikembalikan
        $coinHistoryUser = CoinHistory::where('user_id', $postReject->Student->id)->where('tanya_id', $postReject->id)->first();

        $coinHistoryUser->update([
            'tipe_koin' => 'Dikembalikan',
        ]);

        // untuk menghitung jumlah_soal ditolak mentor (rank)
        $jumlahSoalDitolak = Tanya::onlyTrashed()->where('mentor_id', $user->id)->where('status_soal', 'Ditolak')->count();

        // update atau create progress
        $tanyaRankProgress = TanyaRankMentorProgress::updateOrCreate(['mentor_id' => $user->id],
    [
                'jumlah_soal_ditolak' => $jumlahSoalDitolak,
            ]
        );

        return redirect('/tanya')->with('success-reject-tanya', 'Pertanyaan berhasil ditolak!');
    }

    // function for update koin student in realtime
    public function getKoinStudent(Request $request)
    {
        $user = Auth::user();
        $tanyaKoinUsers = TanyaUserCoin::where('user_id', $user->id)->firstOrFail();

        broadcast(new TanyaCoinRefunded($tanyaKoinUsers))->toOthers();

        return response()->json([
            'jumlah_koin' => $tanyaKoinUsers->jumlah_koin
        ]);
    }

    public function restore($id)
    {
        $user = Tanya::withTrashed()->with('Student.StudentProfiles', 'Mentor.MentorProfiles')->findOrFail($id);

        $user->restore();

        return redirect()->route('tanya')->with('flashdata', 'user restores succcessfully.');
    }

    // function for show onlyTrashed question student (student & mentor)
    public function viewRestore(string $id)
    {
        // untuk menampilkan data tanya yang sudah dijawab sesuai dengan id user
        $getRestore = Tanya::with('Student.StudentProfiles', 'Mentor.MentorProfiles', 'Kelas', 'Mapel', 'Bab')->withTrashed()->where(function ($query) {
            $query->where('user_id', Auth::user()->id)->orWhere('mentor_id', Auth::user()->id);
        })->find($id);

        if(!$getRestore) {
            return redirect('/tanya');
        }

        return view('Features.Tanya.end-to-end.restore', compact('getRestore'));
    }

    // CLAIM COIN DAILY TANYA (student)
    public function claimCoinDaily(Request $request)
    {
        $user = Auth::user();

        $historyCoinDaily = CoinHistory::where('user_id', $user->id)->where('tipe_koin', 'Masuk')
        ->where('sumber_koin', 'Koin Harian')->whereDate('created_at', now())->first();

        $tanyaCoinUsers = TanyaUserCoin::where('user_id', $user->id)->first();

        if(!$tanyaCoinUsers) {
            TanyaUserCoin::create([
                'user_id' => $user->id,
                'jumlah_koin' => 10
            ]);
        } else {
            $tanyaCoinUsers->update([
                'user_id' => $user->id,
                'jumlah_koin' => $tanyaCoinUsers->jumlah_koin + 10
            ]);
        }

        CoinHistory::create([
            'user_id' => $user->id,
            'jumlah_koin' => 10,
            'tipe_koin' => 'Masuk',
            'sumber_koin' => 'Koin Harian',
        ]);

        return redirect()->route('tanya.index')->with('success-claim-daily-coin', 'Klaim koin tanya harian berhasil!');
    }


    // HISTORY CONTENT DAILY TANYA UNANSWERED, ANSWERED, REJECTED WITH PUSHER (student)
    // daily history unAnswered student
    public function getHistoryUnansweredTanya(Request $request)
    {
        $today = now();

        // history belum terjawab
        $historyStudentUnAnswered = Tanya::with('Kelas', 'Mapel', 'Bab')->withTrashed()->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')
        ->where('status_soal', 'Menunggu')->whereDate('created_at', $today)->get();

        return response()->json([
            'data' => $historyStudentUnAnswered
        ]);
    }

    // daily history answered student
    public function getHistoryAnsweredTanya(Request $request)
    {
        $today = now();

        // history terjawab
        $historyStudentAnswered = Tanya::with('Kelas', 'Mapel', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)
        ->where('status_soal', 'Diterima')->orderBy('updated_at', 'desc')
        ->whereDate('created_at', $today)->get();

        $data = $historyStudentAnswered->map(function ($item) {
            $item->questionAnswerDaily = route('tanya.updateStatusSoalRestore', ['id' => $item->id]);
            return $item;
        });

        return response()->json([
            'data' => $historyStudentAnswered
        ]);
    }

    // daily history rejected student
    public function getHistoryRejectedTanya(Request $request)
    {
        $today = now();

        // history ditolak
        $historyStudentRejected = Tanya::with('Kelas', 'Mapel', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)
        ->where('status_soal', 'Ditolak')->orderBy('updated_at', 'desc')
        ->whereDate('created_at', $today)->get();

        $data = $historyStudentRejected->map(function ($item) {
            $item->questionRejectedDaily = route('tanya.updateStatusSoalRestore', ['id' => $item->id]);
            return $item;
        });

        return response()->json([
            'data' => $historyStudentRejected
        ]);
    }

    // UPDATE STATUS QUESTION IN HISTORY DAILY EVERY HISTORY IS CLICKED
    //update status soal riwayat harian per satuan klik (student)
    public function markQuestionAsReadById($id)
    {
        $getTanya = Tanya::onlyTrashed()->where('status_soal_student', 'Belum Dibaca')
        ->where(function ($query) {
            $query->where('status_soal', 'Diterima')->orWhere('status_soal', 'Ditolak');
        })->find($id);

        $getTanya->update([
            'status_soal_student' => 'Telah Dibaca'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    //update semua status soal di riwayat harian dalam satu klik menggunakan button (student)
    public function markAllQuestionsAsReadById($id)
    {
        $getTanyaAnswered = Tanya::onlyTrashed()->where('user_id', $id)->where('status_soal_student', 'Belum Dibaca')
        ->where('status_soal',  'Diterima')->get();

        foreach ($getTanyaAnswered as $tanya) {
            $tanya->update(['status_soal_student' => 'Telah Dibaca']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    public function markAllQuestionsRejectedAsReadById($id)
    {
        $getTanyaRejected = Tanya::onlyTrashed()->where('user_id', $id)->where('status_soal_student', 'Belum Dibaca')
        ->where('status_soal', 'Ditolak')->get();

        foreach ($getTanyaRejected as $tanya) {
            $tanya->update(['status_soal_student' => 'Telah Dibaca']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    //update status soal riwayat harian pada saat melihat jawaban menggunakan href (student)
    public function updateStatusSoalRestore($id)
    {
        $getRestore = Tanya::onlyTrashed()->find($id);

        $getRestore->update([
            'status_soal_student' => 'Telah Dibaca'
        ]);

        return view('Features.Tanya.end-to-end.restore', compact('getRestore'));
    }

    // FUNCTION TANYA ACCESS
    // tanya access view
    public function tanyaAccess()
    {
        $dataTanyaAccess = tanyaAccess::all();

        $today = now();

        // 1. Jika status_access tidak aktif, dan tanggal mulai sudah lewat dari tanggal hari ini maka status_access diubah menjadi aktif
        tanyaAccess::whereIn('status_access', ['Tidak Aktif'])
            ->whereDate('tanggal_mulai', "<=", $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->update(['status_access' => 'Aktif']);

        // 2. jika status_acccess aktif, dan tanggal_mulai sesuai atau lebih dari tanggal hari ini, dan tanggal_akhir sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Aktif'])
            ->whereDate('tanggal_mulai', ">", $today)
            ->update(['status_access' => 'Tidak Aktif']);

        // 3. jika status_access aktif, dan tanggal_mulai sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Aktif'])
            ->whereDate('tanggal_mulai', "<", $today)
            ->update(['status_access' => 'Tidak Aktif']);

        // 4. jika status_access aktif atau tidak aktif, dan tanggal_akhir sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Tidak Aktif', 'Aktif'])
        ->whereDate('tanggal_akhir', "<", $today)
        ->update(['status_access' => 'Tidak Aktif']);

        return view('Features.Tanya.access.tanya-access', compact('dataTanyaAccess'));
    }

    // function insert tanya access
    public function tanyaAccessStore(Request $request)
    {
        $user = Auth::user();

        $today = Carbon::today();
        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_akhir);

        $statusAccess = ($today->between($start, $end)) ? 'Aktif' : 'Tidak Aktif';

        $getDataTanyaAccess = tanyaAccess::all();

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => [
                'required',
                Rule::unique('tanya_accesses', 'tanggal_mulai')
            ],
            'tanggal_akhir' => [
                'required',
                Rule::unique('tanya_accesses', 'tanggal_akhir')
            ],
        ], [
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai.',
            'tanggal_mulai.unique' => 'Tanggal mulai telah terdaftar.',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir.',
            'tanggal_akhir.unique' => 'Tanggal akhir telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        if($getDataTanyaAccess->isEmpty()) {
            $insertDataTanyaAccess = TanyaAccess::create([
                'user_id' => $user->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status_access' => $statusAccess
            ]);

            broadcast(new EventTanyaAccess($insertDataTanyaAccess))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.',
                'data' => $insertDataTanyaAccess
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Data libur telah terdaftar, silahkan edit data untuk melakukan perubahan.',
                'data' => $getDataTanyaAccess
            ]);
        }
    }

    // function update tanya access
    public function updateTanyaAccess(Request $request, String $id)
    {
        $today = Carbon::today();
        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_akhir);

        $statusAccess = ($today->between($start, $end)) ? 'Aktif' : 'Tidak Aktif';

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => [
                'required',
                Rule::unique('tanya_accesses', 'tanggal_mulai')
            ],
            'tanggal_akhir' => [
                'required',
                Rule::unique('tanya_accesses', 'tanggal_akhir')
            ],
        ], [
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai.',
            'tanggal_mulai.unique' => 'Tanggal mulai telah terdaftar.',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir.',
            'tanggal_akhir.unique' => 'Tanggal akhir telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $updateDataLibur = tanyaAccess::find($id);

        $updateDataLibur->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'status_access' => $statusAccess
        ]);

        broadcast(new EventTanyaAccess($updateDataLibur))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Data libur berhasil diubah!',
            'data' => $updateDataLibur
        ]);
    }

    // FUNCTION QUESTION ROLLBACK (administrator)
    // rollback question view
    public function listQuestion()
    {
        $questionData = Tanya::all();

        return view('Features.Tanya.end-to-end.rollback-question', compact('questionData'));
    }

    // function rollback question
    public function rollbackQuestion($id)
    {
        $rollbackQuestionData = Tanya::where('id', $id)->firstOrFail();

        $rollbackQuestionData->update([
            'is_being_viewed' => false,
            'viewed_by' => null
        ]);

        broadcast(new QuestionAsked($rollbackQuestionData))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil dikembalikan ke antrean.',
            'data' => $rollbackQuestionData
        ]);
    }

    // FUNCTION TANYA RANK (MENTOR)
    // tanya rank view
    public function tanyaRank()
    {
        // untuk mendapatkan rank mentor saat ini
        $tanyaRankProgress = TanyaRankMentorProgress::where('mentor_id', Auth::user()->id)->first();

        $tanyaRankMentor = TanyaRankMentor::where('id', $tanyaRankProgress->rank_id)->first();

        if($tanyaRankProgress->rank_id) {
            $currentRankMentor = TanyaRankMentorProgress::with('TanyaRank')->where('mentor_id', Auth::user()->id)->where('rank_id', $tanyaRankMentor->id)->first();
        } else {
            $currentRankMentor = null;
        }

        // menghitung jumlah soal diterima, ditolak, verification approved, verification rejected mentor
        $dataTanyaRankProgressDiterima = TanyaRankMentorProgress::where('mentor_id', Auth::user()->id)->sum('jumlah_soal_diterima');

        $dataTanyaRankProgressDitolak = TanyaRankMentorProgress::where('mentor_id', Auth::user()->id)->sum('jumlah_soal_ditolak');

        $dataTanyaRankProgressApproved = TanyaRankMentorProgress::with('TanyaRank')->where('mentor_id', Auth::user()->id)->sum('jumlah_soal_approved');

        $dataTanyaRankProgressRejected = TanyaRankMentorProgress::where('mentor_id', Auth::user()->id)->sum('jumlah_soal_rejected');

        // mengambil data TanyaRankMentor
        $rewardRankMentor = TanyaRankMentor::all();

        return view('Features.Tanya.rank.tanya-rank', compact('currentRankMentor', 'dataTanyaRankProgressDiterima', 'dataTanyaRankProgressDitolak', 'dataTanyaRankProgressApproved', 'dataTanyaRankProgressRejected', 'rewardRankMentor'));
    }

    // FUNCTION VERIFICATION QUESTION MENTOR (ADMINISTRATOR)
    // function list mentor tanya view
    public function mentorTanya()
    {
        $mentorIds = UserAccount::whereHas('MentorProfiles')->pluck('id');


        $dataMentorTanya = Tanya::onlyTrashed()
            ->whereIn('mentor_id', $mentorIds)
            ->with('Mentor.MentorProfiles')
            ->get()
            ->groupBy('mentor_id');

            $getMentor = $dataMentorTanya->map(fn($item) => $item->first());

            $dataMentorTanyaVerification = TanyaVerifications::where('mentor_id', '')
            ->where('status_verifikasi', 'Menunggu')->count();

        $userMentor = UserAccount::with('MentorProfiles')->where('role', 'Mentor')->get();

        $countData = [];

        foreach($userMentor as $item) {
            $countData[$item->id] = TanyaVerifications::where('mentor_id', $item->id)->count();
        }

        return view('Features.Tanya.payment-mentor.list-mentor-tanya', compact('dataMentorTanya', 'getMentor', 'dataMentorTanyaVerification', 'userMentor', 'countData'));
    }

    // function question mentor accepted view
    public function questionMentorVerifiedView($id)
    {
        $getTanyaMentorAccepted = Tanya::onlyTrashed()->where('status_soal', 'Diterima')->where('mentor_id', $id)->count();
        $getTanyaMentorRejected = Tanya::onlyTrashed()->where('status_soal', 'Ditolak')->where('mentor_id', $id)->count();

        return view('Features.Tanya.payment-mentor.pertanyaan-mentor-verification', compact('id', 'getTanyaMentorAccepted', 'getTanyaMentorRejected'));
    }

    // function question mentor accepted
    public function questionMentorVerifiedAccepted($id)
    {
        $dataTanyaVerifiedAccepted = TanyaVerifications::where('id', $id)->firstOrFail();

        // untuk mengupdate status verifikasi
        $dataTanyaVerifiedAccepted->update([
            'administrator_id' => Auth::user()->id,
            'status_verifikasi' => 'Diterima'
        ]);

        $mentorPayments = MentorPayments::where('mentor_id', $dataTanyaVerifiedAccepted->mentor_id)->orderBy('created_at', 'desc')->first();

        // untuk mengupdate batch atau membuat batch baru setiap mentor (pembayaran mentor)
        $getFeeQuestionsMentor = TanyaRankMentorProgress::where('mentor_id', $dataTanyaVerifiedAccepted->mentor_id)->first();

        // untuk mengecek apakah data tanya_verification_id suda ada atau belum (untuk menghindari submit data yang sama)
        $existingPaymentDetail = MentorPaymentDetail::where('tanya_verification_id', $dataTanyaVerifiedAccepted->id)->first();

        if($getFeeQuestionsMentor && $getFeeQuestionsMentor->rank_id) {
            if(!$existingPaymentDetail) {
                if(!$mentorPayments || $mentorPayments->total_ammount > 50000) {

                    $payMentorQuestions = MentorPayments::create([
                        'mentor_id' => $dataTanyaVerifiedAccepted->mentor_id,
                        'total_amount' => $dataTanyaVerifiedAccepted->harga_soal,
                    ]);

                    $mentorPaymentDetail = MentorPaymentDetail::create([
                        'mentor_id' => $dataTanyaVerifiedAccepted->mentor_id,
                        'payment_mentor_id' => $payMentorQuestions->id,
                        'tanya_verification_id' => $dataTanyaVerifiedAccepted->id,
                        'source_payment_mentor' => 'TANYA',
                        'amount' => $dataTanyaVerifiedAccepted->harga_soal
                    ]);
                } else {
                    $mentorPayments->update([
                        'total_amount' => $mentorPayments->total_amount + $dataTanyaVerifiedAccepted->harga_soal,
                    ]);

                    $mentorPaymentDetail = MentorPaymentDetail::create([
                        'mentor_id' => $dataTanyaVerifiedAccepted->mentor_id,
                        'payment_mentor_id' => $mentorPayments->id,
                        'tanya_verification_id' => $dataTanyaVerifiedAccepted->id,
                        'source_payment_mentor' => 'TANYA',
                        'amount' => $dataTanyaVerifiedAccepted->harga_soal
                    ]);

                    // parameter buat pusher
                    $payMentorQuestions = $mentorPayments;
                }
                // broadcast untuk menampilkan data ketika ada verifikasi soal yang sudah memiliki rank_id (untuk dapet harga_per_soal nya)
                broadcast(new PaymentTanyaMentor($payMentorQuestions))->toOthers();
            }
        }

        // broadcast untuk mendengarkan event ketika verifikasi soal diterima
        broadcast(new TanyaMentorVerifications($dataTanyaVerifiedAccepted))->toOthers();

        // untuk menghitung ulang jumlah data tanyaVerifications yang menunggu di verifikasi ketika admin telah memverifikasi soal mentor
        $userMentor = UserAccount::with('MentorProfiles')->where('role', 'Mentor');

        $data = $userMentor->orderBy('created_at', 'desc')->paginate(6);

        $countData = [];

        foreach($data as $item) {
            $countData[$item->id] = TanyaVerifications::where('mentor_id', $item->id)->where('status_verifikasi', 'Menunggu')->count();
        }

        // broadcast untuk mendengarkan event ketika menghitung ulang jumlah data tanyaVerifications
        broadcast(new CountMentorQuestionsAwaitVerification($countData))->toOthers();

        // untuk menghitung jumlah_soal diterima administrator (rank)
        $jumlahSoalApproved = TanyaVerifications::where('mentor_id', $dataTanyaVerifiedAccepted->mentor_id)
        ->where('status_verifikasi', 'Diterima')->count();

        // lalu data di update atau di create ke tanyaProgress mentor
        $rankProgress = TanyaRankMentorProgress::updateOrCreate(
            ['mentor_id' => $dataTanyaVerifiedAccepted->mentor_id],
            [
                'jumlah_soal_approved' => $jumlahSoalApproved
            ]
        );

        $jumlahDataTanyaDiterima = TanyaRankMentorProgress::where('mentor_id', $dataTanyaVerifiedAccepted->mentor_id)->sum('jumlah_soal_diterima');
        $jumlahDataTanyaApproved = TanyaRankMentorProgress::where('mentor_id', $dataTanyaVerifiedAccepted->mentor_id)->sum('jumlah_soal_approved');

        $rankMentor = TanyaRankMentor::where('jumlah_soal_diterima', $jumlahDataTanyaDiterima)
        ->where('jumlah_soal_approved', $jumlahDataTanyaApproved)->first();

        if($rankMentor) {
            $rankProgress->update([
                'rank_id' => $rankMentor->id
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil dikembalikan ke antrean.',
            'data' => $dataTanyaVerifiedAccepted
        ]);
    }

    // function question mentor rejected
    public function questionMentorVerifiedRejected(Request $request, $id)
    {
        $dataTanyaVerifiedRejected = TanyaVerifications::with('Administrator')->where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'alasan_verifikasi_ditolak' => 'required'
        ], [
            'alasan_verifikasi_ditolak.required' => 'Harap masukkan alasan untuk menolak!'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataTanyaVerifiedRejected->update([
            'administrator_id' => Auth::user()->id ?? '',
            'alasan_verifikasi_ditolak' => $request->alasan_verifikasi_ditolak ?? '',
            'status_verifikasi' => 'Ditolak'
        ]);

        broadcast(new TanyaMentorVerifications($dataTanyaVerifiedRejected))->toOthers();

        // untuk menghitung ulang jumlah data tanyaVerifications yang menunggu di verifikasi ketika admin telah memverifikasi soal mentor
        $userMentor = UserAccount::with('MentorProfiles')->where('role', 'Mentor');

        $data = $userMentor->orderBy('created_at', 'desc')->paginate(6);

        $countData = [];

        foreach($data as $item) {
            $countData[$item->id] = TanyaVerifications::where('mentor_id', $item->id)->where('status_verifikasi', 'Menunggu')->count();
        }

        // broadcast nya
        broadcast(new CountMentorQuestionsAwaitVerification($countData))->toOthers();

        // untuk menghitung jumlah_soal ditolak administrator (rank)
        $jumlahSoalRejected = TanyaVerifications::where('mentor_id', $dataTanyaVerifiedRejected->mentor_id)
        ->where('status_verifikasi', 'Ditolak')->count();

        // lalu data di update atau di create ke tanyaProgress mentor
        $rankProgress = TanyaRankMentorProgress::updateOrCreate(
            ['mentor_id' => $dataTanyaVerifiedRejected->mentor_id],
            [
                'jumlah_soal_rejected' => $jumlahSoalRejected
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil dikembalikan ke antrean.',
            'data' => $dataTanyaVerifiedRejected
        ]);
    }

    // function PAYMENT MENTOR (administrator)
    // payment mentor view
    public function paymentMentorView()
    {
        $getTanyaMentorVerifiedSuccess = MentorPayments::with('Mentor.MentorProfiles')->orderBy('created_at', 'desc')->get();
        return view('Features.Tanya.payment-mentor.pembayaran-tanya-mentor', compact('getTanyaMentorVerifiedSuccess'));
    }

    // function payment mentor (paid)
    public function paymentMentorUpdate(Request $request, $id)
    {
        $getTanyaMentorVerifiedSuccess = MentorPayments::with('Mentor.MentorProfiles')->where('id', $id)->firstOrFail();

        $getTanyaMentorVerifiedSuccess->update([
            'status_payment' => 'Paid'
        ]);

        broadcast(new PaymentTanyaMentor($getTanyaMentorVerifiedSuccess))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil diupdate.',
            'data' => $getTanyaMentorVerifiedSuccess
        ]);
    }

    // FUNCTION VIEW COIN PACKAGE TANYA
    // public function coinPackage()
    // {
    //     $paymentMethods = [
    //         [
    //             'id' => '1',
    //             'tipe_payment' => 'ATM / Bank Transfer',
    //             'logo-payment' => 'image/logo-payment-method/bca.png',
    //             'name' => 'BCA'
    //         ],
    //         [
    //             'id' => '2',
    //             'tipe_payment' => 'ATM / Bank Transfer',
    //             'logo-payment' => 'image/logo-payment-method/BNI.png',
    //             'name' => 'BNI'
    //         ],
    //         [
    //             'id' => '3',
    //             'tipe_payment' => 'ATM / Bank Transfer',
    //             'logo-payment' => 'image/logo-payment-method/bri.png',
    //             'name' => 'BRI'
    //         ],
    //         [
    //             'id' => '4',
    //             'tipe_payment' => 'ATM / Bank Transfer',
    //             'logo-payment' => 'image/logo-payment-method/mandiri.png',
    //             'name' => 'MANDIRI'
    //         ],
    //         [
    //             'id' => '5',
    //             'tipe_payment' => 'E-Wallet',
    //             'logo-payment' => 'image/logo-payment-method/dana.png',
    //             'name' => 'DANA'
    //         ],
    //         [
    //             'id' => '6',
    //             'tipe_payment' => 'E-Wallet',
    //             'logo-payment' => 'image/logo-payment-method/gopay.png',
    //             'name' => 'GOPAY'
    //         ],
    //         [
    //             'id' => '7',
    //             'tipe_payment' => 'E-Wallet',
    //             'logo-payment' => 'image/logo-payment-method/ovo.png',
    //             'name' => 'OVO'
    //         ],
    //         [
    //             'id' => '8',
    //             'tipe_payment' => 'E-Wallet',
    //             'logo-payment' => 'image/logo-payment-method/qris.png',
    //             'name' => 'QRIS'
    //         ],
    //     ];
    //     $groupedPaymentMethods = collect($paymentMethods)->groupBy('tipe_payment');

    //     $dataCoinOptions = FeaturePrices::where('type', 'coin')->get();

    //     return view('Features.payment-features.partials.tanya-coin-package', compact('dataCoinOptions', 'paymentMethods', 'groupedPaymentMethods'));
    // }
}