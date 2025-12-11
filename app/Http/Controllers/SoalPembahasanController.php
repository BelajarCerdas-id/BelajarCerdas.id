<?php

namespace App\Http\Controllers;

use App\Events\BankSoalPembahasanEditQuestion;
use App\Models\Bab;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Mapel;
use App\Models\SoalPembahasanAnswers;
use App\Models\SoalPembahasanQuestions;
use App\Models\SubBab;
use App\Services\SoalPembahasan\BankSoalWordImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SoalPembahasanController extends Controller
{
    // CONTROLLER FOR ADMINISTRATOR
    // FUNCTION BANK SOAL
    public function bankSoalView()
    {
        $getCuriculum = Kurikulum::all();

        return view('Features.soal-pembahasan.bank-soal.bank-soal', compact('getCuriculum'));
    }

    // function bankSoal activate
    public function bankSoalActivate(Request $request, $subBabId)
    {
        $request->validate([
            'status_bank_soal' => 'required|in:Publish,Unpublish'
        ]);

        $dataBankSoal = SoalPembahasanQuestions::where('sub_bab_id', $subBabId)->get();

        foreach ($dataBankSoal as $soal) {
            $soal->update([
                'status_bank_soal' => $request->status_bank_soal
            ]);
        }

        broadcast(new BankSoalPembahasanEditQuestion($dataBankSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $dataBankSoal
        ]);
    }

    // function bankSoal detail view
    public function bankSoalDetail($subBabId)
    {
        return view('Features.soal-pembahasan.bank-soal.bank-soal-detail', compact('subBabId'));
    }

    // function bankSoal edit question view
    public function editQuestionView($subBabId, $id)
    {
        // Mengambil data soal berdasarkan ID
        $editQuestion = SoalPembahasanQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('SP.bankSoal.detail.view', [$subBabId]);
        }

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = SoalPembahasanQuestions::where('questions', $editQuestion->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataSoal;

        return view('Features.soal-pembahasan.bank-soal.bank-soal-edit-question', compact('subBabId', 'id', 'editQuestion', 'groupedSoal'));
    }

    public function formEditQuestion(Request $request, $subBabId, $id)
    {
        $editQuestion = SoalPembahasanQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('SP.bankSoal.detail.view', [$subBabId]);
        }

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = SoalPembahasanQuestions::where('questions', $editQuestion->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataSoal;

        return response()->json([
            'status' => 'success',
            'data' => $groupedSoal,
            'editQuestion' => $editQuestion,
        ]);
    }
    // function bankSoal edit question
    public function editQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required',
            'options_value.*' => 'required',
            'answer_key' => 'required',
            'skilltag' => 'required',
            'difficulty' => 'required',
            'explanation' => 'required',
            'status_soal' => 'required',
            'tipe_soal' => 'required',
        ], [
            'questions.required' => 'Harap isi pertanyaan soal!',
            'options_value.*.required' => 'Harap isi jawaban soal!',
            'answer_key.required' => 'Harap isi jawaban soal!',
            'skilltag.required' => 'Harap isi skilltag soal!',
            'difficulty.required' => 'Harap isi difficulty soal!',
            'explanation.required' => 'Harap isi pembahasan soal!',
            'status_soal.required' => 'Harap isi status soal!',
            'tipe_soal.required' => 'Harap isi tipe soal!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $question = SoalPembahasanQuestions::find($id);

        $dataQuestion = SoalPembahasanQuestions::where('questions', $question->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataQuestion;

        foreach($groupedSoal as $key => $value) {
            foreach($value as $soal) {
                $soal->update([
                    'questions' => $request->questions,
                    'answer_key' => $request->answer_key,
                    'options_value' => $request->options_value[$soal->id], // untuk each option_value masing" options
                    'skilltag' => $request->skilltag,
                    'difficulty' => $request->difficulty,
                    'explanation' => $request->explanation,
                    'status_soal' => $request->status_soal,
                    'tipe_soal' => $request->tipe_soal
                ]);
            }
        }

        broadcast(new BankSoalPembahasanEditQuestion($groupedSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil diupdate',
            'data' => $groupedSoal
        ]);
    }

    public function bankSoalStore(Request $request)
    {
        return app(BankSoalWordImportService::class)->bankSoalImportService($request);
    }

    // FUNCTION EDIT DELETE IMAGE BANKSOAL CKEDITOR
    // controller upload & delete image edit image in question with ckeditor
    public function editImageBankSoal(Request $request) {
    // Menangani upload gambar
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathInfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('soal-pembahasan-image'), $fileName);

            $url = "/soal-pembahasan-image/$fileName";
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    // controller delete image ckeditor
    public function deleteImageBankSoal(Request $request) {
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

    // FUNCTION SOAL PEMBAHASAN (STUDENT)
    // function soal pembahasan preview kelas by fase student
    public function soalPembahasanKelasView()
    {
        // Mendapatkan tanggal hari ini
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::with('Fase')->whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->where('student_id', $user->id)->where('subscription_status', 'aktif')
        ->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Mendapatkan kelas berdasarkan fase user
        if ($featureSubscriptionHistory) {
            // mendapatkan kelas berdasarkan fase pada saat pembelian paket soal dan pembahasan
            $getKelasByFase = Kelas::where('fase_id', $featureSubscriptionHistory->fase_id)->get();
        } else {
            // mendapatkan kelas berdasarkan fase ketika user tidak memiliki paket soal dan pembahasan aktif
            $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();
        }

        return view('Features.soal-pembahasan.soal-pembahasan-kelas', compact('featureSubscriptionHistory', 'getKelasByFase'));
    }

    // function soal pembahasan preview mapel by kelas
    public function soalPembahasanMapelView($kelas, $kelas_id)
    {
        // Mendapatkan tanggal hari ini
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::with('Fase')->whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->where('student_id', $user->id)->where('subscription_status', 'aktif')
        ->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Mendapatkan kelas berdasarkan fase user
        if ($featureSubscriptionHistory) {
            // mendapatkan kelas berdasarkan fase pada saat pembelian paket soal dan pembahasan
            $getKelasByFase = Kelas::where('fase_id', $featureSubscriptionHistory->fase_id)->get();
        } else {
            // mendapatkan kelas berdasarkan fase ketika user tidak memiliki paket soal dan pembahasan aktif
            $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();
        }

        // Mendapatkan mapel berdasarkan kelas yang dipilih user
        $getMapelByKelas = Mapel::where('kelas_id', $kelas_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-mapel', compact(
            'kelas', 'kelas_id', 'getKelasByFase', 'getMapelByKelas'
        ));
    }

    // function soal pembahasan preview bab by mapel
    public function soalPembahasanBabView($kelas, $kelas_id, $mata_pelajaran, $mapel_id)
    {
        // Mendapatkan tanggal hari ini
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::with('Fase')->whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->where('student_id', $user->id)->where('subscription_status', 'aktif')
        ->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Mendapatkan kelas berdasarkan fase user
        if ($featureSubscriptionHistory) {
            // mendapatkan kelas berdasarkan fase pada saat pembelian paket soal dan pembahasan
            $getKelasByFase = Kelas::where('fase_id', $featureSubscriptionHistory->fase_id)->get();
        } else {
            // mendapatkan kelas berdasarkan fase ketika user tidak memiliki paket soal dan pembahasan aktif
            $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();
        }

        // Mendapatkan bab berdasarkan mapel yang dipilih user
        $getBabByMapel = Bab::where('mapel_id', $mapel_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-bab', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'getKelasByFase', 'getBabByMapel'
        ));
    }

    // function soal pembahasan preview sub bab by bab
    public function soalPembahasanSubBabView($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id)
    {
        // Mendapatkan tanggal hari ini
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::with('Fase')->whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->where('student_id', $user->id)->where('subscription_status', 'aktif')
        ->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Mendapatkan kelas berdasarkan fase user
        if ($featureSubscriptionHistory) {
            // mendapatkan kelas berdasarkan fase pada saat pembelian paket soal dan pembahasan
            $getKelasByFase = Kelas::where('fase_id', $featureSubscriptionHistory->fase_id)->get();
        } else {
            // mendapatkan kelas berdasarkan fase ketika user tidak memiliki paket soal dan pembahasan aktif
            $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();
        }

        // Mendapatkan sub bab berdasarkan mapel yang dipilih user
        $getSubBabByBab = SubBab::where('bab_id', $bab_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-sub-bab', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id',  'getKelasByFase', 'getSubBabByBab'
        ));
    }

    // function soal pembahasan preview assessment (practice or exam)
    public function soalPembahasanAssessmentView($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id)
    {
        // Mendapatkan tanggal hari ini
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::with('Fase')->whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->where('student_id', $user->id)->where('subscription_status', 'aktif')
        ->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Mendapatkan kelas berdasarkan fase user
        if ($featureSubscriptionHistory) {
            // mendapatkan kelas berdasarkan fase pada saat pembelian paket soal dan pembahasan
            $getKelasByFase = Kelas::where('fase_id', $featureSubscriptionHistory->fase_id)->get();
        } else {
            // mendapatkan kelas berdasarkan fase ketika user tidak memiliki paket soal dan pembahasan aktif
            $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();
        }

        return view('Features.soal-pembahasan.soal-pembahasan-list-assessment', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id', 'getKelasByFase',
        ));
    }

    // FUNCTION PRACTICE
    public function practice($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id, $sub_bab_id)
    {
        // Ambil tanggal hari ini hanya dalam format 'Y-m-d'
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan history subscription
        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        // mendapatkan bab name
        $getBabName = Bab::where('id', $bab_id)->first();

        // mendapatkan sub bab name
        $getSubBabName = SubBab::where('id', $sub_bab_id)->first();

        return view('Features.soal-pembahasan.assessment.practice.soal-pembahasan-practice', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id', 'sub_bab_id', 'getBabName', 'getSubBabName'
        ));
    }

    // function untuk menampilkan form soal latihan
    public function practiceQuestionsForm($sub_bab_id)
    {
        // Ambil tanggal hari ini hanya dalam format 'Y-m-d'
        $today = now()->format('Y-m-d');

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        // Ambil ulang soal-soal yang masih `Publish` dari DB
        $publishedQuestionIds = SoalPembahasanQuestions::where('tipe_soal', 'Latihan')->where('status_bank_soal', 'Publish')
        ->where('sub_bab_id', $sub_bab_id)->pluck('id')->implode(',');

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Buat key cache unik berdasarkan tanggal, user, dan sub bab
        $cacheKey = "soal-pembahasan-practice-questions-{$userId}-{$today}-{$sub_bab_id}-{$publishedQuestionIds}";

        // Cek apakah soal sudah pernah disimpan di cache hari ini
        if (Cache::has($cacheKey)) {
            // Ambil data soal dari cache dan ubah ke bentuk collection dalam bentuk nested group
            $cachedGroupIds = Cache::get($cacheKey);

            $groupedQuestions = collect($cachedGroupIds)->map(function($groupIds) {

            return SoalPembahasanQuestions::whereIn('id', $groupIds)
                ->get()->sortBy(function($q) use ($groupIds){
                    return array_search($q->id, $groupIds->toArray());
                })->values();
            });
        } else {
            // Ambil semua soal latihan yang sudah di-publish berdasarkan sub_bab_id
            $getQuestions = SoalPembahasanQuestions::where('sub_bab_id', $sub_bab_id)
                ->where('status_bank_soal', 'Publish')
                ->where('tipe_soal', 'Latihan')
                ->get()
                ->sortBy(fn($item) => $item->status_soal === 'Free' ? 0 : 1) // Soal Free ditampilkan lebih dulu
                ->values();

            // Grouping soal berdasarkan field 'questions'
            $grouped = $getQuestions->groupBy('questions');

            // Bagi dua: soal Free dan soal Premium
            $partitioned = $grouped->partition(fn($g) => $g[0]->status_soal === 'Free');

            // Ambil 3 soal Free secara acak
            $free = $partitioned[0]->shuffle()->take(3);

            // Acak soal Premium
            $premium = $partitioned[1]->shuffle();

            // Gabungkan 3 Free dan Premium, lalu ambil maksimal 60 soal
            $selected = $free->concat($premium)->take(60);

            // Simpan hanya ID soal dalam nested group + urutan shuffle
            $cachePayload = $selected->map(function($group) {
                return $group->pluck('id');
            });
            
            // Simpan hasil akhir ke cache sampai akhir hari (pukul 23:59:59)
            Cache::put($cacheKey, $cachePayload, now()->endOfDay());
        }

        // Mendapatkan jawaban user berdasarkan question id
        if ($subscription) {
             // Ambil jawaban user sebelumnya untuk ditampilkan sebagai isian otomatis (per hari)
            $questionsAnswer = SoalPembahasanAnswers::whereHas('SoalPembahasanQuestions', function ($query) {
                $query->where('tipe_soal', 'Latihan');
            })->where('student_id', $userId)->where('subscription_id', $subscription->id)->whereDate('created_at', $today)
                ->pluck('user_answer_option', 'question_id');
        } else {
            // Handle ketika user tidak punya subscription aktif
            // Bisa return kosong atau kasih message bahwa data tidak ditemukan
            $questionsAnswer = collect(); // kosong, tidak ada jawaban
        }

        // Inisialisasi array kosong untuk menampung video ID dari YouTube
        $videoIds = [];

        // Ambil ID video YouTube dari penjelasan (explanation) tiap soal (jika ada)
        foreach ($groupedQuestions as $group) {

            // ambil soal pertama dari group
            $question = $group->first();

            if (!$question) {
                $videoIds[] = null;
                continue;
            }

            $videoId = null;

            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $question->explanation, $matches)) {
                $videoId = $matches[1] ?? $matches[2];
            }

            $videoIds[] = $videoId;
        }

        // Kembalikan response JSON ke client (misalnya ke JavaScript)
        return response()->json([
            'data' => $groupedQuestions,
            'questionsAnswer' => $questionsAnswer,
            'videoIds' => $videoIds, // untuk menampilkan video in iframe
            'subscription' => $subscription,
            'today' => now()->toISOString(),
        ]);
    }

    // function untuk menyimpan jawaban latihan
    public function practiceAnswer(Request $request, $id)
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil tanggal hari ini
        $today = now()->format('Y-m-d');

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'user_answer_option' => 'required',
        ], [
            'user_answer_option.required' => 'Harap pilih jawaban',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->pluck('id')->first();

        // mengambil data soal berdasarkan pada hari ini berdasarkan soal yang dijawab
        $dataQuestionsAnswer = SoalPembahasanAnswers::where('student_id', $userId)
            ->where('question_id', $request->question_id)->whereDate('created_at', $today)->first();

        // jika soal belum dijawab, maka simpan jawaban (untuk menghindari duplikasi data ketika user spam simpan jawaban)
        if (!$dataQuestionsAnswer && $subscription) {
            SoalPembahasanAnswers::create([
                'student_id' => $userId,
                'subscription_id' => $request->subscription_id,
                'question_id' => $request->question_id,
                'user_answer_option' => $request->user_answer_option,
                'status_answer' => 'Saved',
            ]);

            // ini untuk testing created_at dan updated_at jika menggunakan beda hari menggunakan carbon lewat $today
            // DB::table('soal_pembahasan_answers')->insert([
            //     'student_id' => $userId,
            //     'subscription_id' => $request->subscription_id ?? null,
            //     'question_id' => $request->question_id,
            //     'user_answer_option' => $request->user_answer_option,
            //     'status_answer' => 'Saved',
            //     'created_at' => $today,
            //     'updated_at' => $today,
            // ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jawaban berhasil disimpan',
        ]);
    }

    // FUNCTION EXAM
    public function exam($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id)
    {
        // Ambil tanggal hari ini hanya dalam format 'Y-m-d'
        $today = now()->format('Y-m-d');

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan history subscription
        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        // mendapatkan bab name
        $getBabName = Bab::where('id', $bab_id)->first();

        return view('Features.soal-pembahasan.assessment.exam.soal-pembahasan-exam', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id', 'getBabName'
        ));
    }

    // function untuk menampilkan form soal ujian
    public function examQuestionsForm($bab_id)
    {
        // Ambil tanggal hari ini hanya dalam format 'Y-m-d'
        $today = Carbon::now()->format('Y-m-d');

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        // Ambil ulang soal-soal yang masih `Publish` dari DB dan status soal adalah `Premium` dan tipe soal adalah `Ujian`
        $publishedQuestionIds = SoalPembahasanQuestions::where('status_soal', 'Premium')->where('tipe_soal', 'Ujian')
        ->where('status_bank_soal', 'Publish')->where('bab_id', $bab_id)->pluck('id')->implode(',');

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->first();

        $subscriptionId = $subscription ? $subscription->id : null;

        // Buat key cache unik berdasarkan setiap subscription, user, dan sub bab
        $cacheKey = "soal-pembahasan-exam-questions-{$subscriptionId}-{$userId}-{$bab_id}-{$publishedQuestionIds}";

        // Cek apakah data soal sudah disimpan di cache hari ini
        if  (Cache::has($cacheKey)) {
            // Ambil data soal dari cache dan ubah ke bentuk collection dalam bentuk nested group
            $groupedQuestions = collect(Cache::get($cacheKey))->map(fn($group) => collect($group))->values();
        } else {
            // Jika tidak ada di session, ambil soal dari database berdasarkan bab, status Publish, dan tipe ujian
            $getQuestionsByBab = SoalPembahasanQuestions::where('bab_id', $bab_id)->where('status_bank_soal', 'Publish')
                ->where('tipe_soal', 'Ujian')->get();

            // Mengelompokkan data berdasarkan soal
            $groupedQuestions = $getQuestionsByBab->groupBy('questions');

            // Acak urutan soal, ambil hanya 60 soal pertama
            $shuffleQuestions = $groupedQuestions->values()->shuffle()->take(60);

            // Acak urutan opsi jawaban dalam setiap soal
            $groupedQuestions = $shuffleQuestions->map(fn($group) => $group->shuffle()->values())->values();

            // Simpan hasil akhir ke cache sampai akhir hari (pukul 23:59:59)
            Cache::put($cacheKey, $groupedQuestions, now()->endOfDay());
        }

        // Ambil semua ID soal (karena groupedQuestions adalah nested collection, gunakan flatten)
        $questionIds = $groupedQuestions->flatten()->pluck('id')->toArray();

        // Mendapatkan jawaban user berdasarkan question id
        if ($subscription) {
             // Ambil jawaban user
            $questionsAnswer = SoalPembahasanAnswers::where('student_id', Auth::id())
                ->whereIn('question_id', $questionIds)
                ->where('subscription_id', $subscription->id)
                ->get()
                ->mapWithKeys(fn($item) => [$item->question_id => $item->attributesToArray()]);
        } else {
            // Handle ketika user tidak punya subscription aktif
            // Bisa return kosong atau kasih message bahwa data tidak ditemukan
            $questionsAnswer = collect(); // kosong, tidak ada jawaban
        }

        // Hitung skor ujian dengan menjumlahkan skor dari soal-soal yang sudah dijawab
        $scoreExam = $questionsAnswer->sum('question_score');

        // menghitung banyaknya soal
        $total = $groupedQuestions->count();

        // Hitung nilai masing-masing soal => 100 / total soal => nilai setiap soal
        $scoreEachQuestion = $total ? 100 / $total : 0;

        // mengambil waktu pengerjaan ujian pertama dari answer
        $examAnswerDuration = $questionsAnswer->pluck('exam_answer_duration')->first();

        // Inisialisasi array kosong untuk menampung video ID dari YouTube
        $videoIds = [];

        // Loop untuk mendapatkan ID video dari URL
        foreach ($groupedQuestions as $item) {
            $videoId = null;

            // Cari explanation yang mengandung url video menggunakan regex, lalu mengambil 1 data pertama dari masing" array group soal.
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $item[0]['explanation'], $matches)) {
                $videoId = $matches[1] ?? $matches[2];
            }

            // Simpan videoId ke array videoIds
            $videoIds[] = $videoId;
        }

        return response()->json([
            'data' => $groupedQuestions->values(),
            'questionsAnswer' => $questionsAnswer,
            'videoIds' => $videoIds,
            'scoreExam' => $scoreExam,
            'examAnswerDuration' => $examAnswerDuration,
            'scoreEachQuestion' => $scoreEachQuestion,
            'today' => $today, // Tambahkan tanggal hari ini ke response
            'subscription' => $subscription,
            'now' => now()->toISOString(), // Tambahkan waktu saat ini ke response
        ]);
    }

    // Function untuk menjawab soal ujian
    public function examAnswer(Request $request, $id)
    {
        // Ambil tanggal hari ini
        $today = Carbon::now()->format('Y-m-d');

        $userId = Auth::id();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'user_answer_option' => 'required',
            'status_answer' => 'required|in:Draft,Saved',
        ], [
            'user_answer_option.required' => 'Harap pilih jawaban.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->pluck('id')->first();

        // ambil soal dari database berdasarkan bab, status Publish, dan tipe ujian
        $getQuestionsByBab = SoalPembahasanQuestions::where('bab_id', $id)->where('status_bank_soal', 'Publish')
        ->where('tipe_soal', 'Ujian')->whereDate('created_at', $today)->get();

        // Mengelompokkan data berdasarkan soal
        $groupedQuestions = $getQuestionsByBab->groupBy('questions');

        // Ambil semua ID soal (karena groupedQuestions adalah nested collection, gunakan flatten)
        $questionIds = $groupedQuestions->flatten()->pluck('id')->toArray();

        // mencari soal berdasarkan request question_id
        $question = SoalPembahasanQuestions::findOrFail($request->question_id);

        // Ambil jawaban user yang sudah disimpan (status "Saved") dengan tanggal hari ini
        $answer = SoalPembahasanAnswers::where('student_id', $userId)->where('question_id', $request->question_id)
        ->whereDate('created_at', $today)->first();

        // memeriksa jika ada subscription, maka jalankan kode berikut
        if ($subscription) {
            // jika jawaban sudah ada maka update, jika belum ada maka create
            if ($answer) {
                $answer->update([
                    'subscription_id' => $request->subscription_id,
                    'user_answer_option' => $request->user_answer_option,
                    'status_answer' => $request->status_answer,
                    'question_score' => in_array($request->status_answer, ['Saved', 'Draft']) && $request->user_answer_option === $question->answer_key ? $request->question_score : 0,
                    'exam_answer_duration' => $request->exam_answer_duration,
                ]);
            } else {
                SoalPembahasanAnswers::create([
                    'student_id' => $userId,
                    'subscription_id' => $request->subscription_id,
                    'question_id' => $request->question_id,
                    'user_answer_option' => $request->user_answer_option,
                    'status_answer' => $request->status_answer,
                    'question_score' => in_array($request->status_answer, ['Saved', 'Draft']) && $request->user_answer_option === $question->answer_key ? $request->question_score : 0,
                    'exam_answer_duration' => $request->exam_answer_duration,
                ]);
            }
        }

        // ini untuk testing created_at dan updated_at jika menggunakan beda hari menggunakan carbon lewat $today
        // if ($answer) {
        //     DB::table('soal_pembahasan_answers')
        //         ->where('id', $answer->id) // atau where student_id + question_id
        //         ->update([
        //             'subscription_id' => $request->subscription_id,
        //             'user_answer_option' => $request->user_answer_option,
        //             'status_answer' => $request->status_answer,
        //             'question_score' => in_array($request->status_answer, ['Saved', 'Draft']) && $request->user_answer_option === $question->answer_key ? $request->question_score : 0,
        //             'exam_answer_duration' => $request->exam_answer_duration,
        //             'created_at' => $today,
        //             'updated_at' => $today,
        //         ]);
        // } else {
        //     DB::table('soal_pembahasan_answers')->insert([
        //         'student_id' => $userId, // JANGAN LUPA: harus lengkap
        //         'subscription_id' => $request->subscription_id,
        //         'question_id' => $request->question_id,
        //         'user_answer_option' => $request->user_answer_option,
        //         'status_answer' => $request->status_answer,
        //         'question_score' => in_array($request->status_answer, ['Saved', 'Draft']) && $request->user_answer_option === $question->answer_key ? $request->question_score : 0,
        //         'exam_answer_duration' => $request->exam_answer_duration,
        //         'created_at' => $today,
        //         'updated_at' => $today,
        //     ]);
        // }

        return response()->json([
            'status' => 'success',
            'message' => $request->status_answer === 'Saved' ? 'Jawaban disimpan' : 'Jawaban ditandai',
        ]);
    }

    // FUNCTION QUESTIONS HISTORY ASSESSMENT (PRACTICE AND EXAM)
    public function historyAssessmentView($materi_id, $tipe_soal, $date, $kelas, $mata_pelajaran)
    {
        // Ambil data user yang sedang login
        $userId = Auth::id();

        // Jika tipe soal adalah "Latihan"
        if ($tipe_soal === 'Latihan') {
            // Ambil data SubBab berdasarkan ID
            $getSubBabName = SubBab::where('id', $materi_id)->first();

            // Ambil data Bab dari relasi SubBab (karena sub bab adalah relasi dari bab)
            // $getSubBabName->bab_id didapat dari kolom foreign key
            $getBabName = $getSubBabName?->bab ?? null;

        // Jika tipe soal adalah "Ujian"
        } else {
            // Ambil data Bab langsung dari ID
            $getBabName = Bab::where('id', $materi_id)->first();

            // Karena ini ujian, tidak ada sub_bab, jadi di-set null
            $getSubBabName = null;
        }

        // ambil semua nilai soal user (untuk ujian)
        $getScoreExam = SoalPembahasanAnswers::where('student_id', $userId)->where('status_answer', 'Saved'
        )->whereDate('created_at', $date)->get();

        // hitung total nilai setiap soal (untuk ujian)
        $countScore = $getScoreExam->sum('question_score');

        // ambil durasi ujian user
        $getDurationExam = SoalPembahasanAnswers::whereHas(
            'soalPembahasanQuestions',  function ($item) {
                $item->where('tipe_soal', 'Ujian');
        })->where('student_id', $userId)->where('status_answer', 'Saved')->whereDate('created_at', $date)->first();

        // Kirim data ke view soal-pembahasan-riwayat-assessment.blade.php
        return view('Features.soal-pembahasan.assessment.history.soal-pembahasan-riwayat-assessment', compact('materi_id','tipe_soal', 'date', 'kelas',
            'mata_pelajaran', 'getBabName', 'getSubBabName' , 'countScore' , 'getDurationExam'
        ));
    }

    public function historyQuestionsAssessment($materi_id, $tipe_soal, $date, $kelas, $mata_pelajaran)
    {
        $userId = Auth::id();

        $savedAnswers = SoalPembahasanAnswers::where('student_id', $userId)->where('status_answer', 'Saved')->whereDate('created_at', $date)
        ->pluck('question_id');

        // Langkah 1: Ambil semua isi kolom `questions` dari soal yang dijawab
        $questionTexts = SoalPembahasanQuestions::whereIn('id', $savedAnswers)->pluck('questions');

        // Langkah 2: Ambil semua opsi dari soal-soal tersebut berdasarkan isi `questions`
        // memeriksa jika tipe soal adalah latihan maka ambil soal berdasarkan sub_bab_id
        if ($tipe_soal === 'Latihan') {
            $getQuestions = SoalPembahasanQuestions::whereIn('questions', $questionTexts)->where('tipe_soal', $tipe_soal)
            ->where('sub_bab_id', $materi_id)->where('status_bank_soal', 'Publish')->get()
            ->sortBy(fn($item) => $item->status_soal === 'Free' ? 0 : 1)->values();
        // jika tipe soal adalah ujian maka ambil soal berdasarkan bab_id
        } else {
            $getQuestions = SoalPembahasanQuestions::whereIn('questions', $questionTexts)->where('tipe_soal', $tipe_soal)
            ->where('bab_id', $materi_id)->where('status_bank_soal', 'Publish')->get()
            ->sortBy(fn($item) => $item->status_soal === 'Free' ? 0 : 1)->values();
        }

        // Grouping berdasarkan isi `questions`
        $grouped = $getQuestions->groupBy('questions');
        $groupedQuestions = $grouped->map(fn($g) => $g)->values();
        $questionIds = $grouped->flatten()->pluck('id')->toArray();

        // Ambil jawaban user sebelumnya untuk ditampilkan sebagai isian otomatis (per hari)
        $questionsAnswer = SoalPembahasanAnswers::where('student_id', $userId)->where('status_answer', 'Saved')->whereDate('created_at', $date)
        ->whereIn('question_id', $questionIds)->pluck('user_answer_option', 'question_id');

        // Ambil ID video YouTube dari penjelasan (explanation) tiap soal (jika ada)
        $videoIds = $groupedQuestions->map(function ($group) {
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $group[0]['explanation'], $matches)) {
                return $matches[1] ?? $matches[2]; // ambil ID video dari link
            }
            return null; // jika tidak ditemukan, kembalikan null
        });

        // Kembalikan response JSON ke client (misalnya ke JavaScript)
        return response()->json([
            'data' => $groupedQuestions,
            'questionsAnswer' => $questionsAnswer,
            'videoIds' => $videoIds, // untuk menampilkan video in iframe
        ]);
    }
}