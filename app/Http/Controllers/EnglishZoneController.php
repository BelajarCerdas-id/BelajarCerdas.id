<?php

namespace App\Http\Controllers;

use App\Events\BankSoalEnglishZoneEditQuestion;
use App\Events\BankSoalEnglishZoneStatusUpdate;
use App\Events\EnglishZoneBatchScheduleListener;
use App\Events\EnglishZoneLevelsListener;
use App\Events\EnglishZoneMateriListener;
use App\Events\EnglishZoneMentorScheduleListener;
use App\Events\EnglishZonePassageImportListener;
use App\Events\EnglishZoneSessionListener;
use App\Events\EnglishZoneStudentBatchRefund;
use App\Events\EnglishZoneStudentBatchReschedule;
use App\Events\EnglishZoneZoomListener;
use App\Events\EventEnglishZoneBatch;
use App\Models\EnglishZoneAnswers;
use App\Models\EnglishZoneAttendance;
use App\Models\EnglishZoneBatch;
use App\Models\EnglishZoneBatchSchedule;
use App\Models\EnglishZoneLevel;
use App\Models\EnglishZoneMateri;
use App\Models\EnglishZoneMentorSchedule;
use App\Models\EnglishZonePassage;
use App\Models\EnglishZoneQuestions;
use App\Models\EnglishZoneSession;
use App\Models\EnglishZoneStudentBatch;
use App\Models\EnglishZoneZoom;
use App\Models\FeaturePrices;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Kurikulum;
use App\Models\MentorFeatureStatus;
use App\Models\Transactions;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Services\EnglishZone\BankSoalToepWordImportService;
use App\Services\EnglishZone\BankSoalQuizWordImportService;
use App\Services\EnglishZone\PassageWordImportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class EnglishZoneController extends Controller
{
    // ADMINISTRATOR
    // MANAGEMENT LEVELS
    // function management level view
    public function managementLevelView()
    {
        return view('Features.english-zone.management-level.management-level');
    }

    // function management level store
    public function managementLevelStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), ([
            'level_name' => [
                'required',
                Rule::unique('english_zone_levels', 'level_name')
            ],
        ]), [
            'level_name.required' => 'Harap isi nama level.',
            'level_name.unique' => 'Nama level telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $createLevel = EnglishZoneLevel::create([
            'administrator_id' => $user->id,
            'level_name' => $request->level_name,
        ]);

        broadcast(new EnglishZoneLevelsListener('EnglishZoneLevel', 'create', $createLevel))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Level berhasil ditambahkan.',
        ], 200);
    }

    // function paginate management level
    public function paginateManagementLevel()
    {
        $dataManagementLevel = EnglishZoneLevel::paginate(10);

        return response()->json([
            'data' => $dataManagementLevel->items(),
            'links' => (string) $dataManagementLevel->links(),
            'managementSession' => '/english-zone/management-levels/:levelId/management-session',
        ]);
    }

    // function management level edit
    public function managementLevelEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'level_name' => [
                'required',
                Rule::unique('english_zone_levels', 'level_name')
            ],
        ], [
            'level_name.required' => 'Harap isi nama level.',
            'level_name.unique' => 'Nama level telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataManagementLevel = EnglishZoneLevel::findOrFail($id);

        $dataManagementLevel->update([
            'administrator_id' => $user->id,
            'level_name' => $request->level_name,
        ]);

        broadcast(new EnglishZoneLevelsListener('EnglishZoneLevel', 'update', $dataManagementLevel))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Level berhasil diubah.',
        ]);
    }

    // function management level delete
    public function managementLevelDelete($id)
    {
        $dataManagementLevel = EnglishZoneLevel::findOrFail($id);

        $deletedData = $dataManagementLevel->toArray();

        broadcast(new EnglishZoneLevelsListener('EnglishZoneLevel', 'delete', $deletedData))->toOthers();

        $dataManagementLevel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Level berhasil dihapus.',
        ]);
    }

    // MANAGEMENT SESSION
    // function management session view
    public function managementSessionView($levelId)
    {
        return view('Features.english-zone.management-session.management-session', compact('levelId'));
    }

    // function paginate management session
    public function paginateManagementSession($levelId)
    {
        $dataManagementSession = EnglishZoneSession::with(['EnglishZoneLevel'])->where('level_id', $levelId)->get();

        return response()->json([
            'data' => $dataManagementSession,
        ]);
    }

    // function management session store
    public function managementSessionStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'session_name' => [
                'required',
                Rule::unique('english_zone_sessions', 'session_name')
            ],
        ], [
            'session_name.required' => 'Harap isi nama sesi.',
            'session_name.unique' => 'Nama sesi telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $createSession = EnglishZoneSession::create([
            'administrator_id' => $user->id,
            'session_name' => $request->session_name,
            'level_id' => $request->level_id,
        ]);

        broadcast(new EnglishZoneSessionListener('EnglishZoneSession', 'create', $createSession))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi berhasil ditambahkan.',
        ], 200);
    }

    // function management session edit
    public function managementSessionEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'session_name' => [
                'required',
                Rule::unique('english_zone_sessions', 'session_name')
            ],
        ], [
            'session_name.required' => 'Harap isi nama sesi.',
            'session_name.unique' => 'Nama sesi telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataManagementSession = EnglishZoneSession::findOrFail($id);

        $dataManagementSession->update([
            'administrator_id' => $user->id,
            'session_name' => $request->session_name,
        ]);

        broadcast(new EnglishZoneSessionListener('EnglishZoneSession', 'update', $dataManagementSession))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi berhasil diubah.',
        ]);
    }

    // function management session delete
    public function managementSessionDelete($id)
    {
        $dataManagementSession = EnglishZoneSession::findOrFail($id);

        $deletedData = $dataManagementSession->toArray();

        broadcast(new EnglishZoneSessionListener('EnglishZoneSession', 'delete', $deletedData))->toOthers();

        $dataManagementSession->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi berhasil dihapus.',
        ]);
    }

    // dropdown session by level
    public function dropdownSessionByLevel($levelId)
    {
        $session = EnglishZoneSession::where('level_id', $levelId)->get();

        return response()->json($session);
    }
    
    // BANK SOAL
    // function bankSoal view
    public function bankSoalView()
    {
        $getCuriculum = Kurikulum::all();

        return view('Features.english-zone.bank-soal.bank-soal', compact('getCuriculum'));
    }

    // function paginate bankSoal
    public function paginateBankSoal(Request $request)
    {
        $dataBankSoal = EnglishZoneQuestions::with(['UserAccount', 'EnglishZoneLevel'])->groupBy('level_id')
        ->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $dataBankSoal->items(),
            'links' => (string) $dataBankSoal->links(),
            'bankSoalDetail' => '/english-zone/bank-soal/:levelId/detail',
        ]);
    }

    // function bankSoal activate
    public function bankSoalActivate(Request $request, $levelId)
    {
        $user = Auth::user();

        $request->validate([
            'status_bank_soal' => 'required|in:Publish,Unpublish'
        ]);

        $dataBankSoal = EnglishZoneQuestions::where('level_id', $levelId)->get();

        foreach ($dataBankSoal as $soal) {
            $soal->update([
                'administrator_id' => $user->id,
                'status_bank_soal' => $request->status_bank_soal
            ]);
        }

        broadcast(new BankSoalEnglishZoneEditQuestion($dataBankSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $dataBankSoal
        ]);
    }

    // function bankSoal detail view
    public function bankSoalDetail($levelId)
    {
        return view('Features.english-zone.bank-soal.bank-soal-detail', compact('levelId'));
    }

    // function paginate bankSoal detail
    public function paginateBankSoalDetail(Request $request, $levelId)
    {
        // Ambil semua soal yang memiliki sub_bab_id tertentu, lalu ambil relasi SubBab juga
        $allQuestions = EnglishZoneQuestions::where('level_id', $levelId)->orderBy('created_at', 'desc')->get(); // hasilnya Collection, bukan query builder lagi

        // Group by column 'questions'
        $grouped = $allQuestions->groupBy('questions');

        // Filter question
        if ($request->filled('search_question')) {
            // Cek apakah request mengirim parameter search_question dan tidak kosong

            $search = strtolower($request->search_question);
            // Ambil nilai search_question, lalu ubah ke huruf kecil supaya pencarian case-insensitive

            $grouped = $grouped->filter(function ($item) use ($search) {
                // Lakukan filter ke setiap group soal
                // $item di sini adalah Collection (sekumpulan soal yang pertanyaannya sama)

                $questionText = strtolower($item->first()->questions);
                // Ambil pertanyaan dari soal pertama di group
                // lalu ubah ke huruf kecil juga supaya bisa dibandingkan dengan $search

                return Str::contains($questionText, $search);
                // Hanya pertahankan group jika teks pertanyaannya mengandung kata kunci pencarian
            })->values();
            // values() dipakai untuk mereset index Collection (0,1,2,...), bukan key asli groupBy
        }

        $videoIds = [];

        // Loop untuk mendapatkan ID video dari URL
        foreach ($grouped as $groupedSoal) {
            $videoId = null;

            // Cari explanation yang mengandung url video menggunakan regex, lalu mengambil 1 data pertama dari masing" array group soal.
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $groupedSoal[0]['explanation'], $matches)) {
                $videoId = $matches[1] ?? $matches[2];
            }

            // Menyiapkan array untuk ID video
            $videoIds[] = $videoId;
        }

        // Return sebagai JSON
        return response()->json([
            'data' => $grouped->values(), // daftar soal yang ditampilkan di halaman ini
            'videoIds' => $videoIds, // untuk menampilkan video in iframe
            'editQuestion' => '/english-zone/bank-soal/:levelId/:id',
        ]);
    }

    // function edit question view
    public function editQuestionView($levelId, $id)
    {
        // Mengambil data soal berdasarkan ID
        $editQuestion = EnglishZoneQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('EZ.bankSoal.detail.view', [$levelId]);
        }

        return view('Features.english-zone.bank-soal.bank-soal-edit-question', compact('levelId', 'id'));
    }

    // function load form edit question
    public function formEditQuestion(Request $request, $levelId, $id)
    {
        $editQuestion = EnglishZoneQuestions::with(['EnglishZoneLevel'])->find($id);

        if (!$editQuestion) {
            return redirect()->route('EZ.bankSoal.detail.view', [$levelId]);
        }

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = EnglishZoneQuestions::with(['EnglishZoneLevel'])->where('questions', $editQuestion->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataSoal;

        $getLevels = EnglishZoneLevel::all();

        $getSessions = EnglishZoneSession::where('level_id', $levelId)->get();

        return response()->json([
            'status' => 'success',
            'data' => $groupedSoal,
            'editQuestion' => $editQuestion,
            'getLevels' => $getLevels,
            'getSessions' => $getSessions
        ]);
    }

    // function bankSoal edit question
    public function editQuestion(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'questions' => 'required',
            'options_value.*' => 'required',
            'answer_key' => 'required',
            'difficulty' => 'required',
            'explanation' => 'required',
            'level_id' => 'required',
            'session_id' => 'required',
        ], [
            'questions.required' => 'Harap isi pertanyaan soal!',
            'options_value.*.required' => 'Harap isi jawaban soal!',
            'answer_key.required' => 'Harap isi jawaban soal!',
            'difficulty.required' => 'Harap isi difficulty soal!',
            'explanation.required' => 'Harap isi pembahasan soal!',
            'level_id.required' => 'Harap isi level soal!',
            'session_id.required' => 'Harap isi session soal!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $question = EnglishZoneQuestions::find($id);

        $dataQuestion = EnglishZoneQuestions::where('questions', $question->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataQuestion;

        foreach($groupedSoal as $key => $value) {
            foreach($value as $soal) {
                $soal->update([
                    'administrator_id' => $user->id,
                    'questions' => $request->questions,
                    'answer_key' => $request->answer_key,
                    'options_value' => $request->options_value[$soal->id], // untuk each option_value masing" options
                    'difficulty' => $request->difficulty,
                    'explanation' => $request->explanation,
                    'level_id' => $request->level_id,
                    'session_id' => $request->session_id,
                ]);
            }
        }

        broadcast(new BankSoalEnglishZoneEditQuestion($groupedSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil diupdate',
            'data' => $groupedSoal
        ]);
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

            $request->file('upload')->move(public_path('english-zone-image'), $fileName);

            $url = "/english-zone-image/$fileName";
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    // function delete image ckeditor
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

    // function bankSoal store
    public function bankSoalStore(Request $request)
    {
        return app(BankSoalToepWordImportService::class)->bankSoalWordImportService($request);
    }

    // MANAGEMENT BATCHES
    // function management batches view
    public function managementBatchesView() 
    {
        return view('Features.english-zone.batch.management-batch');
    }

    // function management batches store
    public function managementBatchesStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'batch_name' => 'required|unique:english_zone_batches,batch_name',
            'start_day' => 'required',
            'start_month' => 'required',
        ], [
            'batch_name.required' => 'Harap pilih batch.',
            'batch_name.unique' => 'Batch telah terdaftar.',
            'start_day.required' => 'Harap pilih hari.',
            'start_month.required' => 'Harap pilih bulan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $batch = EnglishZoneBatch::create([
            'administrator_id' => $user->id,
            'batch_name' => $request->batch_name,
            'start_day' => $request->start_day,
            'start_month' => $request->start_month,
        ]);

        broadcast(new EventEnglishZoneBatch($batch))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch berhasil ditambahkan.',
            'batch' => $batch,
        ]);
    }

    // function paginate management batches
    public function paginateManagementBatches(Request $request)
    {
        $batches = EnglishZoneBatch::all();

        return response()->json([
            'data' => $batches,
            'batchSchedule' => '/english-zone/management-batches/schedule/:batch_name/:batch_id',
        ]);
    }

    // function edit batch
    public function managementBatchEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'batch_name' => [
                'required',
                Rule::unique('english_zone_batches', 'batch_name')
            ],
            'start_day' => 'required',
            'start_month' => 'required',
        ], [
            'batch_name.required' => 'Harap pilih batch.',
            'batch_name.unique' => 'Batch telah terdaftar.',
            'start_day.required' => 'Harap pilih hari.',
            'start_month.required' => 'Harap pilih bulan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $batch = EnglishZoneBatch::find($id);

        $batch->update([
            'administrator_id' => $user->id,
            'batch_name' => $request->batch_name,
            'start_day' => $request->start_day,
            'start_month' => $request->start_month,
        ]);

        broadcast(new EventEnglishZoneBatch($batch))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch berhasil diubah.',
            'batch' => $batch,
        ]);
    }

    // MANAGEMENT BATCH SCHEDULE
    // function management batch schedule view
    public function managementBatchScheduleView($batch_name, $batch_id) 
    {
        $getBatch = EnglishZoneBatch::where('batch_name', $batch_name)->first();

        return view('Features.english-zone.batch.management-batch-schedule', compact('getBatch', 'batch_name', 'batch_id'));
    }

    // function management batch schedule store
    public function managementBatchScheduleStore(Request $request, $batch_name, $batch_id)
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
            'day_of_week.*' => 'required',
            'start_time.*' => 'required',
            'end_time.*' => 'required',
        ], [
            'day_of_week.*.required' => 'Harap pilih hari.',
            'start_time.*.required' => 'Harap pilih waktu mulai.',
            'end_time.*.required' => 'Harap pilih waktu berakhir.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $getBatch = EnglishZoneBatch::where('id', $batch_id)->first();

        $lastGroup = EnglishZoneBatchSchedule::where('batch_id', $getBatch->id)->max('batch_schedule_group');
        
        if ($request->filled('batch_schedule_group')) {
            // form mengirimkan nilai batch_schedule_group
            // -> Tambah jadwal ke group lama
            $batchScheduleGroup = $request->batch_schedule_group;
        } elseif ($lastGroup) {
            // form tidak mengirim batch_schedule_group tapi di DB sudah ada group
            // -> Tambah group baru
            $batchScheduleGroup = $lastGroup + 1;
        } else {
            // belum ada group sama sekali
            // -> buat group pertama
            $batchScheduleGroup = 1;
        }

        $dayOfWeek = $request->input('day_of_week');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $unique = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->where('batch_schedule_group', $batchScheduleGroup)
        ->where('day_of_week', $request->day_of_week)->where('start_time', $request->start_time)
        ->where('end_time', $request->end_time)->first();

        if ($unique) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => [
                    'day_of_week' => ['Hari dan jam tersebut sudah terdaftar.'],
                    'start_time'  => ['Hari dan jam tersebut sudah terdaftar.'],
                    'end_time'    => ['Hari dan jam tersebut sudah terdaftar.'],
                ],
            ], 422);
        }

        foreach ($dayOfWeek as $index => $day) {
            $batchSchedule = EnglishZoneBatchSchedule::create([
                'administrator_id' => $user->id,
                'batch_id' => $getBatch->id,
                'batch_schedule_group' => $batchScheduleGroup,
                'schedule_time_group' => $request->schedule_time_group ?? 1,
                'day_of_week' => $day,
                'start_time' => $startTime[$index],
                'end_time' => $endTime[$index],
            ]);            
        }

        broadcast(new EnglishZoneBatchScheduleListener('batchSchedule', 'create', $batchSchedule))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch Group berhasil ditambahkan.',
            'batchSchedule' => $batchSchedule,
        ]);
    }

    // function paginate management batch schedule
    public function paginateManagementBatchSchedule(Request $request, $batch_name, $batch_id)
    {
        $batchSchedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->get();

        $grouped = $batchSchedules->groupBy('batch_schedule_group')->map(function ($group) {
            return $group->groupBy('schedule_time_group');
        });

        // ambil data dari $batchSchedules lalu group by batch_schedule_group, setelah itu iterasi setiap group lalu ambil max schedule_time_group
        $lastScheduleTimeGroup = $batchSchedules->groupBy('batch_schedule_group')
        ->map(fn($group) => $group->max('schedule_time_group'));

        return response()->json([
            'data' => $grouped->values(),
            'lastScheduleTimeGroup' => $lastScheduleTimeGroup
        ]);
    }

    // function edit batch schedule
    public function managementBatchScheduleEdit(Request $request, $batch_id, $batch_schedule_id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ], [
            'day_of_week.required' => 'Harap pilih hari.',
            'start_time.required' => 'Harap pilih waktu mulai.',
            'end_time.required' => 'Harap pilih waktu berakhir.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $unique = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->where('batch_schedule_group', $request->batch_schedule_group)
        ->where('day_of_week', $request->day_of_week)->where('start_time', $request->start_time)
        ->where('end_time', $request->end_time)->exists();

        if ($unique) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => [
                    'day_of_week' => ['Hari dan jam tersebut sudah terdaftar.'],
                    'start_time'  => ['Hari dan jam tersebut sudah terdaftar.'],
                    'end_time'    => ['Hari dan jam tersebut sudah terdaftar.'],
                ],
            ], 422);
        }

        $batchSchedule = EnglishZoneBatchSchedule::find($batch_schedule_id);

        $batchSchedule->update([
            'administrator_id' => $user->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        broadcast(new EnglishZoneBatchScheduleListener('batchSchedule', 'update', $batchSchedule))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch Group berhasil diubah.',
            'batchSchedule' => $batchSchedule,
        ]);
    }
    
    // function delete batch schedule
    public function managementBatchScheduleDelete($batch_schedule_id)
    {
        $batchSchedule = EnglishZoneBatchSchedule::findOrFail($batch_schedule_id);

        $deletedData = $batchSchedule->toArray();

        broadcast(new EnglishZoneBatchScheduleListener('batchSchedule', 'delete', $deletedData))->toOthers();
        
        $batchSchedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch schedule berhasil dihapus.',
            'batchSchedule' => $batchSchedule,
        ]);
    }

    // function view management mentor schedule
    public function managementMentorScheduleView()
    {
        $getBatch = EnglishZoneBatch::all();

        $batchMap = [
            "Batch 1" => "Januari",
            "Batch 2" => "Februari",
            "Batch 3" => "Maret",
            "Batch 4" => "April",
            "Batch 5" => "Mei",
            "Batch 6" => "Juni",
            "Batch 7" => "Juli",
            "Batch 8" => "Agustus",
            "Batch 9" => "September",
            "Batch 10" => "Oktober",
            "Batch 11" => "November",
            "Batch 12" => "Desember"
        ];

        foreach ($getBatch as $batch) {
            if (isset($batchMap[$batch->batch_name])) {
                $batch->display_name = $batch->batch_name . ' - ' . $batchMap[$batch->batch_name];
            } else {
                $batch->display_name = $batch->batch_name; // fallback
            }
        }

        $scheduleTimeGroup = EnglishZoneBatchSchedule::get()->groupBy('batch_id')->map(function ($group) {
            return $group->groupBy('schedule_time_group')->map(function ($group) {
                return $group->groupBy('batch_schedule_group');
            });
        });

        // hitung total kolom jadwal
        $countScheduleTimeGroup = $scheduleTimeGroup->sum(function ($group) {
            return $group->count();
        });
        
        return view('Features.english-zone.mentor-schedule.management-mentor-schedule', compact('getBatch', 'scheduleTimeGroup', 'countScheduleTimeGroup'));
    }

    // function paginate management mentor schedule
    public function paginateManagementMentorSchedule(Request $request)
    {
        $batch = $request->query('batch', "Batch 1"); // default batch 1

        $schedules = EnglishZoneBatchSchedule::whereHas('EnglishZoneBatch', function ($query) use ($batch) {
            $query->where('batch_name', $batch);
        })->get();

        $scheduleTimeGroup = $schedules->groupBy(function ($item) {
            return $item->EnglishZoneBatch->batch_name; // gunakan batch_name sebagai key
        })->map(function ($group) {
            return $group->groupBy('schedule_time_group')->map(function ($group) {
                return $group->groupBy('batch_schedule_group');
            });
        });

        $mentorFeatureStatus = MentorFeatureStatus::where('status_mentor', 'aktif')
            ->where('feature_id', 3)
            ->pluck('mentor_id');

        $getMentorQuery = UserAccount::with(['MentorProfiles', 'EnglishZoneMentorSchedule'])
            ->where('role', 'Mentor')
            ->whereIn('id', $mentorFeatureStatus);

        // filter search_mentor
        if ($request->filled('search_mentor')) {
            $getMentorQuery->whereHas('MentorProfiles', function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%' . $request->search_mentor . '%');
            });
        }

        // baru ambil data
        $getMentor = $getMentorQuery->get();

        return response()->json([
            'data' => $getMentor,
            'scheduleTimeGroup' => $scheduleTimeGroup
        ]);
    }

    // function management mentor schedule activate
    public function managementMentorScheduleActivate(Request $request)
    {
        $request->validate([
            'status_schedule' => 'required|in:aktif,tidak_aktif',
        ]);

        // Ubah string "34,35" jadi [34, 35]
        $ids = $request->batch_schedule_ids; // sudah array ["34","35"]
        
        foreach ($ids as $id) {
            EnglishZoneMentorSchedule::updateOrCreate(
                [
                    'mentor_id' => $request->mentor_id,
                    'batch_schedule_id' => $id,
                ],
                [
                    'status_schedule' => $request->status_schedule
                ]
            );
        }

        broadcast(new EnglishZoneMentorScheduleListener($ids))->toOthers();

        return response()->json(['success' => true]);
    }

    // MANAGEMENT QUIZ
    // function management quiz view
    public function managementPassageView()
    {
        return view('Features.english-zone.management-quiz.management-passage');
    }

    // function management passage store
    public function managementPassageStore(Request $request, $id = null)
    {
        return app(PassageWordImportService::class)->passageWordImportService($request, $id);
    }

    // function management passage edit
    public function managementPassageEdit(Request $request, $id)
    {
        return app(PassageWordImportService::class)->passageWordImportService($request, $id);
    }

    // function management passage delete
    public function managementPassageDelete($id)
    {
        $dataPassage = EnglishZonePassage::findOrFail($id);

        $deletedData = $dataPassage->toArray();

        broadcast(new EnglishZonePassageImportListener('EnglishZonePassage','delete', $deletedData))->toOthers();

        $dataPassage->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Passage berhasil dihapus.',
        ]);
    }

    // function paginate management passage
    public function paginateManagementPassage()
    {
        $passages = EnglishZonePassage::with(['UserAccount', 'EnglishZoneLevel'])->orderBy('created_at', 'desc')->get()->groupBy('passage_type')
        ->map(function ($group) {
            return $group->groupBy('level_id');
        });

        return response()->json([
            'data' => $passages->values(),
            'passageDetail' => '/english-zone/management-quiz/management-passage/:level_id/:passage_type/detail'
        ]);
    }

    // function management passage detail
    public function managementPassageDetail($level_id, $passage_type)
    {
        return view('Features.english-zone.management-quiz.management-passage-detail', compact('level_id', 'passage_type'));
    }

    // function paginate management passage detail
    public function paginateManagementPassageDetail($level_id, $passage_type)
    {
        $passage = EnglishZonePassage::with(['UserAccount', 'EnglishZoneLevel'])->where('level_id', $level_id)->where('passage_type', $passage_type)
        ->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $passage,
            'previewBankSoalQuiz' => '/english-zone/management-quiz/management-passage/:level_id/:passage_id/:passage_type/bank-soal',
        ]);
    }

    public function managementPassageActivate(Request $request, $id)
    {
        $user = Auth::user();

        $passage = EnglishZonePassage::findOrFail($id);

        $passage->update([
            'administrator_id' => $user->id,
            'passage_status' => $request->passage_status,
        ]);

        broadcast(new EnglishZonePassageImportListener('EnglishZonePassage','activate', $passage))->toOthers();

        return response()->json([
            'status' => 'success'
        ]);
    }

    // function management bank soal quiz view
    public function managementBankSoalQuizView($level_id, $passage_id, $passage_type)
    {   
        return view('Features.english-zone.management-quiz.management-bank-soal-quiz', compact('level_id', 'passage_id', 'passage_type'));
    }

    // function paginate management bank soal quiz
    public function paginateManagementBankSoalQuiz(Request $request, $level_id, $passage_id, $passage_type)
    {
        // Ambil semua soal yang memiliki sub_bab_id tertentu, lalu ambil relasi SubBab juga
        $allQuestions = EnglishZoneQuestions::with('EnglishZonePassage')->whereHas('EnglishZonePassage', function($query) use ($passage_type) {
            $query->where('passage_type', $passage_type);
        })->where('level_id', $level_id)->where('passage_id', $passage_id)->get(); // hasilnya Collection, bukan query builder lagi

        // Group by column 'questions'
        $grouped = $allQuestions->groupBy('questions');

        // Filter question
        if ($request->filled('search_question')) {
            // Cek apakah request mengirim parameter search_question dan tidak kosong

            $search = strtolower($request->search_question);
            // Ambil nilai search_question, lalu ubah ke huruf kecil supaya pencarian case-insensitive

            $grouped = $grouped->filter(function ($item) use ($search) {
                // Lakukan filter ke setiap group soal
                // $item di sini adalah Collection (sekumpulan soal yang pertanyaannya sama)

                $questionText = strtolower($item->first()->questions);
                // Ambil pertanyaan dari soal pertama di group
                // lalu ubah ke huruf kecil juga supaya bisa dibandingkan dengan $search

                return Str::contains($questionText, $search);
                // Hanya pertahankan group jika teks pertanyaannya mengandung kata kunci pencarian
            })->values();
            // values() dipakai untuk mereset index Collection (0,1,2,...), bukan key asli groupBy
        }

        $videoIds = [];

        // Loop untuk mendapatkan ID video dari URL
        foreach ($grouped as $groupedSoal) {
            $videoId = null;

            // Cari explanation yang mengandung url video menggunakan regex, lalu mengambil 1 data pertama dari masing" array group soal.
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $groupedSoal[0]['explanation'], $matches)) {
                $videoId = $matches[1] ?? $matches[2];
            }

            // Menyiapkan array untuk ID video
            $videoIds[] = $videoId;
        }
        return response()->json([
            'data' => $grouped->values(),
            'videoIds' => $videoIds,
            'editQuestion' => '/english-zone/management-quiz/management-passage/:level_id/:passage_id/:passage_type/:question_id/bank-soal/edit',
        ]);
    }

    // function management bank soal quiz store
    public function bankSoalQuizStore(Request $request, $level_id, $passage_id, $passage_type)
    {
        return app(BankSoalQuizWordImportService::class)->bankSoalQuizWordImportService($request, $level_id, $passage_id, $passage_type);
    }

    // function management bank soal quiz detail view
    public function editQuestionQuizView($level_id, $passage_id, $passage_type, $question_id)
    {
        $editQuestion = EnglishZoneQuestions::with(['EnglishZoneLevel'])->find($question_id);

        if (!$editQuestion) {
            return redirect()->route('EZ.managementBankSoalQuiz.view', [$level_id, $passage_id, $passage_type]);
        }
        
        return view('Features.english-zone.management-quiz.management-bank-soal-quiz-edit', compact('level_id', 'passage_id', 
        'passage_type', 'question_id'));
    }

    // function management bank soal quiz edit form
    public function editQuestionQuizForm(Request $request, $level_id, $passage_id, $passage_type, $question_id)
    {
        $editQuestion = EnglishZoneQuestions::with(['EnglishZoneLevel'])->find($question_id);

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = EnglishZoneQuestions::with(['EnglishZoneLevel'])->where('questions', $editQuestion->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataSoal;

        $getLevels = EnglishZoneLevel::all();

        return response()->json([
            'status' => 'success',
            'data' => $groupedSoal,
            'editQuestion' => $editQuestion,
            'getLevels' => $getLevels,
        ]);
    }

    // function edit quiz question submit
    public function editQuestionQuizUpdate(Request $request, $level_id, $passage_id, $passage_type, $question_id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'questions' => 'required',
            'options_value.*' => 'required',
            'answer_key' => 'required',
            'difficulty' => 'required',
            'explanation' => 'required',
        ], [
            'questions.required' => 'Harap isi pertanyaan soal!',
            'options_value.*.required' => 'Harap isi jawaban soal!',
            'answer_key.required' => 'Harap isi jawaban soal!',
            'difficulty.required' => 'Harap isi difficulty soal!',
            'explanation.required' => 'Harap isi pembahasan soal!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $question = EnglishZoneQuestions::find($question_id);

        $dataQuestion = EnglishZoneQuestions::where('questions', $question->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataQuestion;

        foreach($groupedSoal as $key => $value) {
            foreach($value as $soal) {
                $soal->update([
                    'administrator_id' => $user->id,
                    'questions' => $request->questions,
                    'answer_key' => $request->answer_key,
                    'options_value' => $request->options_value[$soal->id], // untuk each option_value masing" options
                    'difficulty' => $request->difficulty,
                    'explanation' => $request->explanation,
                ]);
            }
        }

        broadcast(new BankSoalEnglishZoneEditQuestion($groupedSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil diupdate',
            'data' => $groupedSoal
        ]);
    }

    // public function bank soal quiz delete
    public function bankSoalQuizDelete($question_id)
    {
        $explodeQuestionIds = explode(',', $question_id);

        $question = EnglishZoneQuestions::whereIn('id', $explodeQuestionIds)->get();

        $deletedData = $question;

        broadcast(new BankSoalEnglishZoneStatusUpdate($deletedData))->toOthers();

        EnglishZoneQuestions::whereIn('id', $explodeQuestionIds)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil dihapus',

        ]);
    }

    // public function bank soal quiz activate
    public function bankSoalQuizActivate(Request $request, $question_id)
    {
        $user = Auth::user();

        $explodeQuestionIds = explode(',', $question_id);

        $question = EnglishZoneQuestions::whereIn('id', $explodeQuestionIds)->get();

        $activatedData = $question;

        foreach ($activatedData as $item) {
            $item->update([
                'administrator_id' => $user->id,
                'status_bank_soal' => $request->status_bank_soal,
            ]);
        }

        broadcast(new BankSoalEnglishZoneStatusUpdate($activatedData))->toOthers();

        return response()->json([
            'status' => 'success',
        ]);
    }

    // MANAGEMENT MATERI
    // function management materi view
    public function managementMateriView()
    {
        $getLevels = EnglishZoneLevel::all();

        return view('Features.english-zone.management-materi.management-materi', compact('getLevels'));
    }

    // function management materi store
    public function managementMateriStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'materi_vocabulary' => 'required|max:100000',
            'materi_grammar' => 'required|max:100000',
            'materi_lesson_plan' => 'required|max:100000',
            'materi_reading' => 'required|max:100000',
            'materi_writing' => 'required|max:100000',
            'materi_listening' => 'required|max:100000',
            'materi_speaking' => 'required|max:100000',
            'materi_pembelajaran' => 'required|max:100000',
            'worksheet' => 'required|max:100000',
            'video_materi' => 'required|url',
            'level_id' => 'required',
            'session_id' => [
                'required',
                Rule::unique('english_zone_materis', 'session_id')->where('level_id', $request->level_id),
            ],
        ], [
            'materi_vocabulary.required' => 'Harap upload materi vocabulary.',
            'materi_vocabulary.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_grammar.required' => 'Harap upload materi grammar.',
            'materi_grammar.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_lesson_plan.required' => 'Harap upload materi lesson plan.',
            'materi_lesson_plan.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_reading.required' => 'Harap upload materi reading.',
            'materi_reading.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_writing.required' => 'Harap upload materi writing.',
            'materi_writing.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_listening.required' => 'Harap upload materi listening.',
            'materi_listening.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_speaking.required' => 'Harap upload materi speaking.',
            'materi_speaking.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_pembelajaran.required' => 'Harap upload materi pembelajaran.',
            'materi_pembelajaran.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'worksheet.required' => 'Harap upload worksheet.',
            'worksheet.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'video_materi.required' => 'Harap isi video materi.',
            'video_materi.url' => 'Harap isi link video yang valid.',
            'level_id.required' => 'Harap pilih level.',
            'session_id.required' => 'Harap pilih sesi.',
            'session_id.unique' => 'Sesi telah terdaftar pada level tersebut.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $materiVocabularyName = null;
        $materiGrammarName = null;
        $materiLessonPlanName = null;
        $materiReading = null;
        $materiWriting = null;
        $materiListening = null;
        $materiSpeaking = null;
        $materiPembelajaran = null;
        $worksheet = null;

        // 🔹 Helper: simpan file unik berdasarkan hash
        $saveFileByHash = function ($file, $folder) {
            $hash = md5_file($file->getRealPath());
            $ext = $file->getClientOriginalExtension();
            $newName = $hash . '.' . $ext; // nama file = hash.ext
            $path = public_path($folder . '/' . $newName);

            if (!file_exists($path)) {
                $file->move(public_path($folder), $newName);
            }

            return $newName;
        };

        // Upload Vocabulary
        if ($request->hasFile('materi_vocabulary')) {
            $materiVocabularyName = $saveFileByHash($request->file('materi_vocabulary'), 'english-zone-materi');
        }

        // Upload Grammar
        if ($request->hasFile('materi_grammar')) {
            $materiGrammarName = $saveFileByHash($request->file('materi_grammar'), 'english-zone-materi');
        }

        // Upload Lesson Plan
        if ($request->hasFile('materi_lesson_plan')) {
            $materiLessonPlanName = $saveFileByHash($request->file('materi_lesson_plan'), 'english-zone-materi');
        }

        // Upload Materi Reading
        if ($request->hasFile('materi_reading')) {
            $materiReading = $saveFileByHash($request->file('materi_reading'), 'english-zone-materi');
        }

        // Upload Materi Writing
        if ($request->hasFile('materi_writing')) {
            $materiWriting = $saveFileByHash($request->file('materi_writing'), 'english-zone-materi');
        }

        // Upload Materi Listening
        if ($request->hasFile('materi_listening')) {
            $materiListening = $saveFileByHash($request->file('materi_listening'), 'english-zone-materi');
        }

        // Upload Materi Speaking
        if ($request->hasFile('materi_speaking')) {
            $materiSpeaking = $saveFileByHash($request->file('materi_speaking'), 'english-zone-materi');
        }

        // Upload Materi Pembelajaran
        if ($request->hasFile('materi_pembelajaran')) {
            $materiPembelajaran = $saveFileByHash($request->file('materi_pembelajaran'), 'english-zone-materi');
        }

        // Upload Worksheet
        if ($request->hasFile('worksheet')) {
            $worksheet = $saveFileByHash($request->file('worksheet'), 'english-zone-materi');
        }

        $createMateri = EnglishZoneMateri::create([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
            'materi_lesson_plan' => $materiLessonPlanName,
            'materi_reading' => $materiReading,
            'materi_writing' => $materiWriting,
            'materi_listening' => $materiListening,
            'materi_speaking' => $materiSpeaking,
            'materi_pembelajaran' => $materiPembelajaran,
            'worksheet' => $worksheet,
            'video_materi' => $request->video_materi,
            'link_zoom' => $request->link_zoom,
            'level_id' => $request->level_id,
            'session_id' => $request->session_id,
        ]);

        broadcast(new EnglishZoneMateriListener('EnglishZoneMateri', 'create', $createMateri))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Materi berhasil ditambahkan.',
        ]);
    }

    // function paginate management materi
    public function paginateManagementMateri(Request $request)
    {
        $dataMateri = EnglishZoneMateri::with(['EnglishZoneLevel', 'UserAccount'])->orderBy('created_at', 'desc')->get();

        $grouped = $dataMateri->groupBy('level_id');

        return response()->json([
            'data' => $grouped->values(),
            'materiDetail' => '/english-zone/management-materi/detail/:id',
        ]);
    }

    // function management materi detail
    public function managementMateriDetail($id)
    {
        $getLevels = EnglishZoneLevel::all();

        $getMateri = EnglishZoneMateri::with(['EnglishZoneLevel', 'UserAccount'])->find($id);

        return view('Features.english-zone.management-materi.management-materi-detail', compact('id', 'getMateri', 'getLevels'));
    }

    // function paginate management materi detail
    public function paginateManagementMateriDetail($id)
    {
        $dataMateri = EnglishZoneMateri::with(['EnglishZoneLevel', 'UserAccount', 'EnglishZoneSession'])
            ->where('level_id', $id)
            ->get()
            ->map(function ($item) {
                if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $item['video_materi'], $matches)) {
                    $item['video_id'] = $matches[1] ?? $matches[2];
                } else {
                    $item['video_id'] = null;
                }
                return $item;
            });

        $dataMateri = $dataMateri->sortBy('session');

        return response()->json([
            'data' => $dataMateri->values(),
        ]);
    }

    // function management materi edit
    public function managementMateriEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'materi_vocabulary' => 'required|max:100000',
            'materi_grammar' => 'required|max:100000',
            'materi_lesson_plan' => 'required|max:100000',
            'materi_reading' => 'required|max:100000',
            'materi_writing' => 'required|max:100000',
            'materi_listening' => 'required|max:100000',
            'materi_speaking' => 'required|max:100000',
            'materi_pembelajaran' => 'required|max:100000',
            'worksheet' => 'required|max:100000',
            'video_materi' => 'required|url',
        ], [
            'materi_vocabulary.required' => 'Harap upload materi vocabulary.',
            'materi_vocabulary.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_grammar.required' => 'Harap upload materi grammar.',
            'materi_grammar.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_lesson_plan.required' => 'Harap upload materi lesson plan.',
            'materi_lesson_plan.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_reading.required' => 'Harap upload materi reading.',
            'materi_reading.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_writing.required' => 'Harap upload materi writing.',
            'materi_writing.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_listening.required' => 'Harap upload materi listening.',
            'materi_listening.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_speaking.required' => 'Harap upload materi speaking.',
            'materi_speaking.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_pembelajaran.required' => 'Harap upload materi pembelajaran.',
            'materi_pembelajaran.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'worksheet.required' => 'Harap upload worksheet.',
            'worksheet.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'video_materi.required' => 'Harap isi video materi.',
            'video_materi.url' => 'Harap isi link video yang valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataMateri = EnglishZoneMateri::findOrFail($id);

        $materiVocabularyName = null;
        $materiGrammarName = null;
        $materiLessonPlanName = null;
        $materiReading = null;
        $materiWriting = null;
        $materiListening = null;
        $materiSpeaking = null;
        $materiPembelajaran = null;
        $worksheet = null;

        // 🔹 Helper: simpan file unik berdasarkan hash
        $saveFileByHash = function ($file, $folder) {
            $hash = md5_file($file->getRealPath());
            $ext = $file->getClientOriginalExtension();
            $newName = $hash . '.' . $ext; // nama file = hash.ext
            $path = public_path($folder . '/' . $newName);

            if (!file_exists($path)) {
                $file->move(public_path($folder), $newName);
            }

            return $newName;
        };

        // Upload Vocabulary
        if ($request->hasFile('materi_vocabulary')) {
            $materiVocabularyName = $saveFileByHash($request->file('materi_vocabulary'), 'english-zone-materi');
        }

        // Upload Grammar
        if ($request->hasFile('materi_grammar')) {
            $materiGrammarName = $saveFileByHash($request->file('materi_grammar'), 'english-zone-materi');
        }

        // Upload Lesson Plan
        if ($request->hasFile('materi_lesson_plan')) {
            $materiLessonPlanName = $saveFileByHash($request->file('materi_lesson_plan'), 'english-zone-materi');
        }

        // Upload Materi Reading
        if ($request->hasFile('materi_reading')) {
            $materiReading = $saveFileByHash($request->file('materi_reading'), 'english-zone-materi');
        }

        // Upload Materi Writing
        if ($request->hasFile('materi_writing')) {
            $materiWriting = $saveFileByHash($request->file('materi_writing'), 'english-zone-materi');
        }

        // Upload Materi Listening
        if ($request->hasFile('materi_listening')) {
            $materiListening = $saveFileByHash($request->file('materi_listening'), 'english-zone-materi');
        }

        // Upload Materi Speaking
        if ($request->hasFile('materi_speaking')) {
            $materiSpeaking = $saveFileByHash($request->file('materi_speaking'), 'english-zone-materi');
        }

        // Upload Materi Pembelajaran
        if ($request->hasFile('materi_pembelajaran')) {
            $materiPembelajaran = $saveFileByHash($request->file('materi_pembelajaran'), 'english-zone-materi');
        }

        // Upload Worksheet
        if ($request->hasFile('worksheet')) {
            $worksheet = $saveFileByHash($request->file('worksheet'), 'english-zone-materi');
        }

        $dataMateri->update([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
            'materi_lesson_plan' => $materiLessonPlanName,
            'materi_reading' => $materiReading,
            'materi_writing' => $materiWriting,
            'materi_listening' => $materiListening,
            'materi_speaking' => $materiSpeaking,
            'materi_pembelajaran' => $materiPembelajaran,
            'worksheet' => $worksheet,
            'video_materi' => $request->video_materi,
        ]);

        broadcast(new EnglishZoneMateriListener('EnglishZoneMateri', 'update', $dataMateri))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Materi berhasil diubah.',
        ]);
    }

    // function management materi delete
    public function managementMateriDelete($id)
    {
        $dataMateri = EnglishZoneMateri::findOrFail($id);

        $deletedData = $dataMateri->toArray();

        broadcast(new EnglishZoneMateriListener('EnglishZoneMateri', 'delete', $deletedData))->toOthers();

        $dataMateri->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Materi berhasil dihapus.',
        ]);
    }

    // MANAGEMENT ZOOM
    // function management zoom view
    public function managementZoomView()
    {
        $getLevels = EnglishZoneLevel::all();

        $getBatch = EnglishZoneBatch::all();

        $batchMap = [
            "Batch 1" => "Januari",
            "Batch 2" => "Februari",
            "Batch 3" => "Maret",
            "Batch 4" => "April",
            "Batch 5" => "Mei",
            "Batch 6" => "Juni",
            "Batch 7" => "Juli",
            "Batch 8" => "Agustus",
            "Batch 9" => "September",
            "Batch 10" => "Oktober",
            "Batch 11" => "November",
            "Batch 12" => "Desember"
        ];

        foreach ($getBatch as $batch) {
            if (isset($batchMap[$batch->batch_name])) {
                $batch->display_name = $batch->batch_name . ' - ' . $batchMap[$batch->batch_name];
            } else {
                $batch->display_name = $batch->batch_name; // fallback
            }
        }

        return view('Features.english-zone.management-zoom.management-zoom', compact('getLevels', 'getBatch'));
    }

    // function dropdown mentors
    public function dropdownMentors()
    {
        $getMentorStatus = MentorFeatureStatus::with('UserAccount.MentorProfiles')->where('feature_id', 3)->where('status_mentor', 'aktif')->get();
        
        return response()->json($getMentorStatus);
    }

    // function pagiante management zoom
    public function paginateManagementZoom(Request $request)
    {
        $getZoom = EnglishZoneZoom::with(['Administrator', 'Mentor.MentorProfiles']);

        // filter search_mentor
        if ($request->filled('search_mentor')) {
            $getZoom->whereHas('Mentor.MentorProfiles', function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%' . $request->search_mentor . '%');
            });
        }

        // baru ambil data
        $data = $getZoom->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
        ]);
    }

    // function management zoom store
    public function managementZoomStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'mentor_id' => [
                'required',
                Rule::unique('english_zone_zooms', 'mentor_id'),
            ],
            'link_zoom' => [
                'required',
                'url',
                Rule::unique('english_zone_zooms', 'link_zoom'),
            ],
            'meeting_id' => [
                'required',
                Rule::unique('english_zone_zooms', 'meeting_id'),
            ],
            'zoom_passcode' => [
                'required',
                Rule::unique('english_zone_zooms', 'zoom_passcode'),
            ],
        ], [
            'mentor_id.required' => 'Harap pilih mentor.',
            'mentor_id.unique' => 'Mentor ini telah terdaftar.',
            'link_zoom.required' => 'Harap isi link zoom.',
            'link_zoom.url' => 'Format link tidak sesuai.',
            'link_zoom.unique' => 'Link Zoom telah terdaftar.',
            'meeting_id.required' => 'Harap isi meeting id.',
            'meeting_id.unique' => 'Meeting ID telah terdaftar.',
            'zoom_passcode.required' => 'Harap isi passcode.',
            'zoom_passcode.unique' => 'Passcode telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $createZoom = EnglishZoneZoom::create([
            'administrator_id' => $user->id,
            'mentor_id' => $request->mentor_id,
            'link_zoom' => $request->link_zoom,
            'meeting_id' => $request->meeting_id,
            'zoom_passcode' => $request->zoom_passcode,
        ]);

        broadcast(new EnglishZoneZoomListener('EnglishZoneZoom', 'create', $createZoom))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Link Zoom berhasil ditambahkan.',
        ]);
    }

    // function management zoom edit
    public function managementZoomEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'link_zoom' => [
                'required',
                'url',
                Rule::unique('english_zone_zooms', 'link_zoom'),
            ],
            'meeting_id' => [
                'required',
                Rule::unique('english_zone_zooms', 'meeting_id'),
            ],
            'zoom_passcode' => [
                'required',
                Rule::unique('english_zone_zooms', 'zoom_passcode'),
            ],
        ], [
            'link_zoom.required' => 'Harap isi link zoom.',
            'link_zoom.url' => 'Format link tidak sesuai.',
            'link_zoom.unique' => 'Link Zoom telah terdaftar.',
            'meeting_id.required' => 'Harap isi meeting id.',
            'meeting_id.unique' => 'Meeting ID telah terdaftar.',
            'zoom_passcode.required' => 'Harap isi passcode.',
            'zoom_passcode.unique' => 'Passcode telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateZoom = EnglishZoneZoom::where('id', $id)->update([
            'administrator_id' => $user->id,
            'link_zoom' => $request->link_zoom,
            'meeting_id' => $request->meeting_id,
            'zoom_passcode' => $request->zoom_passcode,
        ]);

        broadcast(new EnglishZoneZoomListener('EnglishZoneZoom', 'update', $updateZoom))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Link Zoom berhasil diubah.',
        ]);
    }

    // function management zoom delete
    public function managementZoomDelete($id)
    {
        $dataZoom = EnglishZoneZoom::findOrFail($id);

        $deletedData = $dataZoom->toArray();

        broadcast(new EnglishZoneZoomListener('EnglishZoneZoom', 'delete', $deletedData))->toOthers();

        $dataZoom->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Link Zoom berhasil dihapus.',
        ]);
    }

    // dropdown bertingkan batches in ez purchase
    public function dropdownBatchPurchase($feature_variant_id)
    {
        $now = Carbon::now();

        $getBatch = EnglishZoneBatch::all();

        $monthMap = [
            "01" => "Januari",
            "02" => "Februari",
            "03" => "Maret",
            "04" => "April",
            "05" => "Mei",
            "06" => "Juni",
            "07" => "Juli",
            "08" => "Agustus",
            "09" => "September",
            "10" => "Oktober", 
            "11" => "November",
            "12" => "Desember"
        ];

        foreach ($getBatch as $batch) {
            // nama bulan
            $batch->display_name = $monthMap[$batch->start_month] ?? $batch->batch_name;
            // tanggal mulai
            $batch->startDay = $batch->start_day;

            // hitung tanggal start
            $startDate = Carbon::create(
                $now->year, 
                (int) $batch->start_month, 
                (int) $batch->start_day,
                0, 0, 0
            );

            if($startDate->lt($now->copy()->startOfDay())) {
                $startDate->addYear();
            }

            $featurePrices = FeaturePrices::where('id', $feature_variant_id)->first();

            $batch->duration = $featurePrices->duration;
            $month = (int) filter_var($batch->duration, FILTER_SANITIZE_NUMBER_INT);

            // tambahkan field start_date ke objek batch
            $batch->startDate = $startDate->format('d-m-Y');

            $batch->endDate = $startDate->addMonths($month)->format('d-m-Y');
        }

        return response()->json([
            'data' => $getBatch,
        ]);
    }

    // dropdown bertingkat days in ez purchase
    public function dropdownDaysPurchase($batch_id)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->get();

        // Ambil 1 record unik per batch_schedule_group
        $groups = $schedules->unique('batch_schedule_group')->values()->map(function ($item) use ($schedules) {
            return [
                'group_id'   => $item->batch_schedule_group,
                'days' => $schedules->where('batch_schedule_group', $item->batch_schedule_group)->pluck('day_of_week')->unique()->values(),
            ];
        });

        return response()->json($groups);
    }

    public function dropdownHoursPurchase($batch_id, $group_id, $level_id, $feature_variant_id)
    {
        $today = Carbon::now()->format('Y-m-d');

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

        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)
            ->where('batch_schedule_group', $group_id)
            ->get();

        // Kelompokkan berdasarkan jam
        $hours = $schedules->groupBy(function ($item) {
            return $item->start_time . '-' . $item->end_time;
        })->map(function ($items) {
            return [
                'ids' => $items->pluck('id')->toArray(),
                'time' => $items->first()->start_time . ' - ' . $items->first()->end_time,
                'schedule_time_group' => $items->first()->schedule_time_group,
            ];
        })->values();

        $levelIds = explode(',', $level_id);

        $studentCounts = EnglishZoneStudentBatch::with(['EnglishZoneBatchSchedule', 'FeatureSubscriptionHistory.Transactions'])->whereIn('level_id', $levelIds)
            ->whereHas('EnglishZoneBatchSchedule', function ($q) use ($batch_id, $group_id) {
                $q->where('batch_id', $batch_id)->where('batch_schedule_group', $group_id);
            })->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) use ($feature_variant_id) {
                $q->where('transaction_status', 'Berhasil')->where('feature_variant_id', $feature_variant_id)
                ->where('transaction_source', 'non_school_partner');
            })->whereHas('FeatureSubscriptionHistory', function ($q) use ($today) {
                $q->whereDate('end_date', '>=', $today)->where('subscription_status', 'aktif');
            })->get()
            ->groupBy(function ($item) {
                return $item->EnglishZoneBatchSchedule->schedule_time_group;
            })
            ->map(function ($items) {
                return $items->pluck('student_id')->unique()->count();
            });

        return response()->json([
            'data' => $hours,
            'studentCounts' => $studentCounts,
        ]);
    }

    // MANAGEMENT STUDENT BATCH
    // function management student batch view
    public function managementStudentBatchView()
    {
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

        return view('Features.english-zone.management-student-batch.management-student-batch');
    }
    
    // function paginate management student batch non school partner
    public function paginateStudentBatchNonSchoolPartner(Request $request)
    {
        $today = now()->format('Y-m-d');

        $studentBatch = EnglishZoneStudentBatch::with([
            'EnglishZoneLevel',
            'EnglishZoneBatchSchedule',
            'FeatureSubscriptionHistory.Transactions',
        ])->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) {
            $q->where('feature_id', 3)->where('transaction_status', 'Berhasil')->where('transaction_source', 'non_school_partner');
        })->whereHas('FeatureSubscriptionHistory', function ($q) use ($today) { 
            $q->whereDate('end_date', '>=', $today)->where('subscription_status', 'aktif'); 
        })->get();
        
        $studentBatchGroup = $studentBatch->groupBy(function ($item) {
            $variantId = $item->FeatureSubscriptionHistory->Transactions->feature_variant_id;
            $variantName = $item->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name;
            $level = $item->EnglishZoneLevel->id;
            $batch = $item->EnglishZoneBatchSchedule->EnglishZoneBatch->id;
            $batchScheduleGroup = $item->EnglishZoneBatchSchedule->batch_schedule_group;
            $day = $item->EnglishZoneBatchSchedule->schedule_time_group;
            $startTime = $item->EnglishZoneBatchSchedule->start_time;
            $endTime = $item->EnglishZoneBatchSchedule->end_time;

            if ($variantId == 10 || $variantName == 'Langganan 3 Bulan') {
                    return implode('|', [$variantId, $level, $batch, $batchScheduleGroup, $day, $startTime, $endTime]);
            } else {
                // Default: group by variant saja
                return implode('|', [$variantId, $batch, $batchScheduleGroup, $day, $startTime, $endTime]);
            }
            
        })->map(function ($items) {
            return [
                $startTime = $items->first()->EnglishZoneBatchSchedule->start_time,
                $endTime = $items->first()->EnglishZoneBatchSchedule->end_time,
                
                'variant_id' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->id,
                'variant_name' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name,
                'level_ids' => $items->pluck('EnglishZoneLevel.id')->unique()->values()->toArray(),
                'level_names' => $items->pluck('EnglishZoneLevel.level_name')->unique()->values()->toArray(),
                'batch_ids' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.id')->unique()->values()->toArray(),
                'batch_names' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.batch_name')->unique()->values()->toArray(),
                'days_of_week' => $items->pluck('EnglishZoneBatchSchedule.day_of_week')->unique()->values()->toArray(),
                'hours' => $startTime . ' - ' . $endTime,
                'batch_schedule_groups' => $items->pluck('EnglishZoneBatchSchedule.batch_schedule_group')->unique()->values()->toArray(),
                'batch_schedule_ids' => $items->pluck('EnglishZoneBatchSchedule.id')->unique()->values()->toArray(),
                'count_students' => $items->pluck('student_id')->unique()->count(),
                'student_batch_ids' => $items->pluck('id')->unique()->values()->toArray(),
                'student_ids' => $items->pluck('student_id')->unique()->values()->toArray(),
                'mentor_ids' => $items->pluck('mentor_id')->unique()->values(),
                'start_date' => $items->first()->FeatureSubscriptionHistory->start_date,
                'end_date' => $items->first()->FeatureSubscriptionHistory->end_date,
            ];
        })
        ->filter(); // untuk hapus group yang null

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $pagedData = $studentBatchGroup->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $studentBatchGroup->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil semua batch schedule ID dari data aslinya
        $batchScheduleIds = $studentBatch->pluck('EnglishZoneBatchSchedule.id')->filter()->unique();

        // Ambil mentor yang sesuai dengan batch schedule ID di atas
        $mentorSchedule = EnglishZoneMentorSchedule::with(['UserAccount.MentorProfiles'])->where('status_schedule', 'aktif')
        ->whereIn('batch_schedule_id', $batchScheduleIds)->get()->groupBy('batch_schedule_id');

        if ($request->filled('search_mentor')) {
            $mentorSchedule = $mentorSchedule->map(function ($group) use ($request) {
                return $group->filter(function ($mentor) use ($request) {
                    return stripos($mentor->UserAccount->MentorProfiles->nama_lengkap ?? '', $request->search_mentor) !== false;
                });
            })->filter(function ($group) {
                return $group->isNotEmpty();
            });
        }

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

        $getStudentIds = implode(',', $studentBatch->pluck('student_id')->unique()->toArray());

        return response()->json([
            'data' => $paginated->values(),
            'links' => (string) $paginated->links(),
            'mentorSchedule' => $mentorSchedule,
            'getStudentIds' => $getStudentIds,
            'studentBatchDetail' => '/english-zone/management-student-batch/detail/non-school-partner/:featureVariantId/:levelId/:batchId/:batchScheduleGroups/:batchScheduleIds/:studentIds',
        ]);
    }

    // function paginate management student batch school partner
    public function paginateStudentBatchSchoolPartner(Request $request)
    {
        $today = now()->format('Y-m-d');

        $studentBatchQuery = EnglishZoneStudentBatch::with([
            'EnglishZoneLevel',
            'EnglishZoneBatchSchedule',
            'FeatureSubscriptionHistory.Transactions',
            'Student.StudentProfiles', // tambahkan biar eager load sekolah juga
        ])->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) {
            $q->where('feature_id', 3)
            ->where('transaction_status', 'Berhasil')
            ->where('transaction_source', 'school_partner');
        })->whereHas('FeatureSubscriptionHistory', function ($q) use ($today) {
            $q->whereDate('end_date', '>=', $today)
            ->where('subscription_status', 'aktif');
        });

        // 🔍 Filter sekolah DI SINI (masih query builder)
        if ($request->filled('search_school_partner')) {
            $search = strtolower($request->search_school_partner);
            $studentBatchQuery->whereHas('Student.StudentProfiles', function ($q) use ($search) {
                $q->whereRaw('LOWER(sekolah) LIKE ?', ["%{$search}%"]);
            });
        }

        // Baru ambil hasilnya
        $studentBatch = $studentBatchQuery->get();

        $studentBatchGroup = $studentBatch->groupBy(function ($item) {
            $variantId = $item->FeatureSubscriptionHistory->Transactions->feature_variant_id;
            $variantName = $item->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name;
            $level = $item->EnglishZoneLevel->id;
            $batch = $item->EnglishZoneBatchSchedule->EnglishZoneBatch->id;
            $batchScheduleGroup = $item->EnglishZoneBatchSchedule->batch_schedule_group;
            $day = $item->EnglishZoneBatchSchedule->schedule_time_group;
            $startTime = $item->EnglishZoneBatchSchedule->start_time;
            $endTime = $item->EnglishZoneBatchSchedule->end_time;
            $schoolPartnerId = $item->Student->StudentProfiles->sekolah;

            if ($variantId == 10 || $variantName == 'Langganan 3 Bulan') {
                    return implode('|', [$variantId, $level, $batch, $batchScheduleGroup, $day, $startTime, $endTime, $schoolPartnerId]);
            } else {
                // Default: group by variant saja
                return implode('|', [$variantId, $batch, $batchScheduleGroup, $day, $startTime, $endTime, $schoolPartnerId]);
            }

        })->map(function ($items) {
            return [
                $startTime = $items->first()->EnglishZoneBatchSchedule->start_time,
                $endTime = $items->first()->EnglishZoneBatchSchedule->end_time,
                
                'variant_id' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->id,
                'variant_name' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name,
                'level_ids' => $items->pluck('EnglishZoneLevel.id')->unique()->values()->toArray(),
                'level_names' => $items->pluck('EnglishZoneLevel.level_name')->unique()->values()->toArray(),
                'batch_ids' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.id')->unique()->values()->toArray(),
                'batch_names' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.batch_name')->unique()->values()->toArray(),
                'days_of_week' => $items->pluck('EnglishZoneBatchSchedule.day_of_week')->unique()->values()->toArray(),
                'hours' => $startTime . ' - ' . $endTime,
                'batch_schedule_groups' => $items->pluck('EnglishZoneBatchSchedule.batch_schedule_group')->unique()->values()->toArray(),
                'batch_schedule_ids' => $items->pluck('EnglishZoneBatchSchedule.id')->unique()->values()->toArray(),
                'count_students' => $items->pluck('student_id')->unique()->count(),
                'student_batch_ids' => $items->pluck('id')->unique()->values()->toArray(),
                'student_ids' => $items->pluck('student_id')->unique()->values()->toArray(),
                'mentor_ids' => $items->pluck('mentor_id')->unique()->values(),
                'school' => $items->first()->Student->StudentProfiles->sekolah,
                'start_date' => $items->first()->FeatureSubscriptionHistory->start_date,
                'end_date' => $items->first()->FeatureSubscriptionHistory->end_date,
            ];
        })
        ->filter(); // untuk hapus group yang null

        // Pagination manual
        $page = (int) $request->get('page', 1);
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $pagedData = $studentBatchGroup->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $studentBatchGroup->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil semua batch schedule ID dari data aslinya
        $batchScheduleIds = $studentBatch->pluck('EnglishZoneBatchSchedule.id')->filter()->unique();

        // Ambil mentor yang sesuai dengan batch schedule ID di atas
        $mentorSchedule = EnglishZoneMentorSchedule::with(['UserAccount.MentorProfiles'])->where('status_schedule', 'aktif')
        ->whereIn('batch_schedule_id', $batchScheduleIds)->get()->groupBy('batch_schedule_id');

        if ($request->filled('search_mentor')) {
            $mentorSchedule = $mentorSchedule->map(function ($group) use ($request) {
                return $group->filter(function ($mentor) use ($request) {
                    return stripos($mentor->UserAccount->MentorProfiles->nama_lengkap ?? '', $request->search_mentor) !== false;
                });
            })->filter(function ($group) {
                return $group->isNotEmpty();
            });
        }

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

        $getStudentIds = implode(',', $studentBatch->pluck('student_id')->unique()->toArray());

        return response()->json([
            'data' => $paginated->values(),
            'links' => $paginated->isEmpty() ? '' : (string) $paginated->links(),
            'mentorSchedule' => $mentorSchedule,
            'getStudentIds' => $getStudentIds,
            'studentBatchDetail' => '/english-zone/management-student-batch/detail/school-partner/:featureVariantId/:levelId/:batchId/:batchScheduleGroups/:batchScheduleIds/:studentIds/:schoolId',
        ]);
    }

    // function student batch activate mentor
    public function studentBatchActivateMentor(Request $request, $studentBatchIds)
    {
        $explodeIds = explode(',', $studentBatchIds);

        $studentBatch = EnglishZoneStudentBatch::whereIn('id', $explodeIds)->get();

        foreach ($studentBatch as $item) {
            $item->update([
                'mentor_id' => $request->mentor_id ?? null
            ]);
        }

        broadcast(new EnglishZoneStudentBatchReschedule($studentBatch))->toOthers();
    }

    // function student batch detail view
    public function studentBatchDetailView($featureVariantId, $levelId, $batchId, $batchScheduleGroups, $batchScheduleIds, $studentIds, $schoolPartnerId = null)
    {
        $today = Carbon::now()->format('Y-m-d');

        $studentIdsMap = array_map('trim', explode(',', $studentIds));

        $studentBatch = EnglishZoneStudentBatch::whereIn('student_id', $studentIdsMap)->get()->toArray();

        $check = array_diff($studentIdsMap, array_column($studentBatch, 'student_id'));

        if (!empty($check)) {
            return redirect()->route('EZ.managementStudentBatch.view');
        }

        $getBatch = EnglishZoneBatch::all();

        $batchMap = [
            "Batch 1" => "Januari",
            "Batch 2" => "Februari",
            "Batch 3" => "Maret",
            "Batch 4" => "April",
            "Batch 5" => "Mei",
            "Batch 6" => "Juni",
            "Batch 7" => "Juli",
            "Batch 8" => "Agustus",
            "Batch 9" => "September",
            "Batch 10" => "Oktober",
            "Batch 11" => "November",
            "Batch 12" => "Desember"
        ];

        foreach ($getBatch as $batch) {
            if (isset($batchMap[$batch->batch_name])) {
                $batch->display_name = $batch->batch_name . ' - ' . $batchMap[$batch->batch_name];
            } else {
                $batch->display_name = $batch->batch_name; // fallback
            }
        }

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
                
        return view('Features.english-zone.management-student-batch.management-student-batch-detail', compact( 'featureVariantId', 'batchId', 'batchScheduleGroups', 
        'batchScheduleIds', 'levelId', 'studentIds', 'schoolPartnerId', 'getBatch'));
    }

    // paginate student batch detail
    public function paginateManagementStudentBatchDetail($featureVariantId, $levelId, $batchId, $batchScheduleGroups, $batchScheduleIds, $studentIds, $schoolPartnerId = null)
    {
        $today = Carbon::now()->format('Y-m-d');

        $getBatch = EnglishZoneBatch::where('id', $batchId)->first();

        $getLevels = EnglishZoneLevel::whereIn('id', explode(',', $levelId))->get();

        $studentIds = explode(',', $studentIds);

        $levelIds = explode(',', $levelId);

        $featureVariantId = explode(',', $featureVariantId);

        $batchScheduleIds = explode(',', $batchScheduleIds);

        $data = EnglishZoneStudentBatch::with(['Student.StudentProfiles', 'Mentor.MentorProfiles', 'FeatureSubscriptionHistory.Transactions.FeaturePrices', 
        'EnglishZoneLevel', 'EnglishZoneBatchSchedule', 'EnglishZoneBatchSchedule.EnglishZoneBatch'])
        ->whereHas('FeatureSubscriptionHistory.Transactions', function ($query) use ($featureVariantId) {
            $query->where('transaction_status', 'Berhasil')->where('feature_variant_id', $featureVariantId);
        })
        ->whereHas('FeatureSubscriptionHistory', function ($query) use ($today) {
            $query->where('end_date', '>=', $today)->where('subscription_status', 'aktif');
        })
        ->whereHas('EnglishZoneBatchSchedule', function ($query) use ($batchId, $batchScheduleGroups) {
            $query->where('batch_id', $batchId)->where('batch_schedule_group', $batchScheduleGroups);
        })
        ->whereIn('batch_schedule_id', $batchScheduleIds)->whereIn('level_id', $levelIds)
        ->whereIn('student_id', $studentIds)->get()->groupBy('student_id');

        $batchSchedule = EnglishZoneBatchSchedule::with(['EnglishZoneBatch'])
            ->whereIn('id', $batchScheduleIds)
            ->get()
            ->map(function ($item) {
                return [
                    'day_of_week' => $item->day_of_week,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                ];
            });

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

        return response()->json([
            'data' => $data->values(),
            'batchSchedules' => $batchSchedule->values(),
            'getBatch' => $getBatch,
            'getLevels' => $getLevels
        ]);
    }

    // function student batch detail reschedule
    public function studentBatchDetailReSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required',
            'day_of_week_id' => 'required',
            'hours_id' => 'required',
        ], [
            'batch_id.required' => 'Harap pilih batch.',
            'day_of_week_id.required' => 'Harap pilih hari.',
            'hours_id.required' => 'Harap pilih jam.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'form' => 'error-form-reschedule',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $newScheduleIds = explode(',', $request->batch_schedule_id);
        $studentBatchIds = explode(',', $request->student_batch_id);
        $batchScheduleGroupId = $request->batch_schedule_group;
        $levelId = explode(',', $request->level_id);

        // Ambil semua student berdasarkan id lama
        $studentBatches = EnglishZoneStudentBatch::whereIn('id', $studentBatchIds)->get();

        $studentCounts = EnglishZoneStudentBatch::whereHas('EnglishZoneBatchSchedule', function ($query) use ($request, $batchScheduleGroupId, $newScheduleIds, $levelId) {
            $query->whereIn('level_id', $levelId)->where('batch_id', $request->batch_id)->where('batch_schedule_group', $batchScheduleGroupId);

            $query->whereIn('id', $newScheduleIds);
        })->whereHas('FeatureSubscriptionHistory.Transactions', function ($query) use ($request) {
            $query->where('transaction_status', 'Berhasil')->where('feature_variant_id', $request->feature_variant_id)
                ->where('transaction_source', $request->transaction_source);
        })->when($request->school_partner_id, function ($query) use ($request) {
            $query->whereHas('Student.StudentProfiles', function ($q) use ($request) {
                $q->where('sekolah', $request->school_partner_id);
            });
        })->pluck('student_id')->unique()->count();

        if ($studentCounts >= 10) {
            return response()->json([
                'status' => 'error',
                'form' => 'error-max-slot-batch',
                'message' => 'Jadwal belajar pada batch tersebut sudah penuh, harap pilih batch atau jadwal belajar yang lain.'
            ], 422);
        }

        foreach ($studentBatches as $batch) {
            $studentId = $batch->student_id;
            $levelId = $batch->level_id;

            // Ambil jadwal lama student ini
            $oldScheduleIds = EnglishZoneStudentBatch::whereHas('EnglishZoneBatchSchedule', function ($query) use ($batchScheduleGroupId) {
                $query->where('batch_schedule_group', $batchScheduleGroupId);
            })->where('student_id', $studentId)->where('level_id', $levelId)->pluck('batch_schedule_id')->toArray();

            // Jika jumlah sama -> update saja jadwal lama dengan yang baru
            if (count($newScheduleIds) === count($oldScheduleIds)) {
                foreach (array_values($oldScheduleIds) as $index => $oldScheduleId) {
                    $newScheduleId = $newScheduleIds[$index] ?? null;
                    if ($newScheduleId) {
                        EnglishZoneStudentBatch::whereHas('EnglishZoneBatchSchedule', function ($query) use ($batchScheduleGroupId) {
                            $query->where('batch_schedule_group', $batchScheduleGroupId);
                        })->where('student_id', $studentId)->where('batch_schedule_id', $oldScheduleId)
                            ->where('level_id', $levelId)
                            ->update(['batch_schedule_id' => $newScheduleId]);
                    }
                }
            } 
            // Jika jumlah beda -> create yang baru dan hapus yang lama
            else {
                foreach ($newScheduleIds as $newScheduleId) {
                    EnglishZoneStudentBatch::firstOrCreate(
                        [
                            'student_id' => $studentId,
                            'batch_schedule_id' => $newScheduleId,
                            'level_id' => $levelId,
                        ],
                        [
                            'subscription_history_id' => $batch->subscription_history_id,
                            'level_start_date' => $batch->level_start_date,
                            'level_end_date' => $batch->level_end_date,
                            'mentor_id' => $batch->mentor_id,
                        ]
                    );
                }

                $toDelete = array_diff($oldScheduleIds, $newScheduleIds);
                if (!empty($toDelete)) {
                    EnglishZoneStudentBatch::where('student_id', $studentId)
                        ->where('level_id', $levelId)
                        ->whereIn('batch_schedule_id', $toDelete)
                        ->delete();
                }
            }
        }

        broadcast(new EnglishZoneStudentBatchReschedule($studentBatches))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Reschedule berhasil.',
        ]);
    }

    // function student batch detail refund
    public function studentBatchDetailRefund($studentId, $transactionSource)
    {
        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) use ($transactionSource) {
            $query->where('transaction_status', 'Berhasil')->where('transaction_source', $transactionSource)->where('feature_id', 3);
        })->where('student_id', $studentId)->first();

        if ($featureSubscriptionHistory) {
            $featureSubscriptionHistory->update([
                'subscription_status' => 'tidak_aktif'
            ]);
        }

        broadcast(new EnglishZoneStudentBatchRefund($featureSubscriptionHistory))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Refund berhasil.',
        ]);
    }

    // function dropdown day student batch detail
    public function dropdownDayStudentBatch($batch_id)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->get();

        // Ambil 1 record unik per batch_schedule_group
        $groups = $schedules->unique('batch_schedule_group')->values()->map(function ($item) use ($schedules) {
            return [
                'group_id'   => $item->batch_schedule_group,
                'days' => $schedules->where('batch_schedule_group', $item->batch_schedule_group)->pluck('day_of_week')->unique()->values(),
            ];
        });

        return response()->json($groups);
    }

    // function dropdown hour student batch detail
    public function dropdownHourStudentBatch($batch_id, $group_id, $level_id, $feature_variant_id, $transaction_source, $school_id = null)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)
            ->where('batch_schedule_group', $group_id)
            ->get();

        // Kelompokkan berdasarkan jam
        $hours = $schedules->groupBy(function ($item) {
            return $item->start_time . '-' . $item->end_time;
        })->map(function ($items) {
            return [
                'ids' => $items->pluck('id')->toArray(),
                'time' => $items->first()->start_time . ' - ' . $items->first()->end_time,
                'schedule_time_group' => $items->first()->schedule_time_group,
            ];
        })->values();

        $levelIds = explode(',', $level_id);

        $studentCounts = EnglishZoneStudentBatch::with(['EnglishZoneBatchSchedule', 'FeatureSubscriptionHistory.Transactions'])->whereIn('level_id', $levelIds)
            ->whereHas('EnglishZoneBatchSchedule', function ($q) use ($batch_id, $group_id) {
                $q->where('batch_id', $batch_id)->where('batch_schedule_group', $group_id);
            })->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) use ($feature_variant_id, $transaction_source) {
                $q->where('transaction_status', 'Berhasil')->where('feature_variant_id', $feature_variant_id)
                ->where('transaction_source', $transaction_source);
            })->when($school_id, function ($query) use ($school_id) {
                // when digunakan untuk menjalankan query di dalamnya jika value pada when atau school_id ada
                $query->whereHas('Student.StudentProfiles', function ($q) use ($school_id) {
                    $q->where('sekolah', $school_id);
                });
            })->get()
            ->groupBy(function ($item) {
                return $item->EnglishZoneBatchSchedule->schedule_time_group;
            })
            ->map(function ($items) {
                return $items->pluck('student_id')->unique()->count();
            });

        return response()->json([
            'data' => $hours,
            'studentCounts' => $studentCounts,
        ]);
    }

    // MENTOR
    public function englishZoneMentorView()
    {
        return view('Features.english-zone.mentor.english-zone-mentor');
    }

    public function paginateMentorStudentBatchNonSchoolPartner(Request $request)
    {
        $today = now()->format('Y-m-d');

        $user = Auth::user();

        $studentBatch = EnglishZoneStudentBatch::with([
            'EnglishZoneLevel',
            'EnglishZoneBatchSchedule',
            'FeatureSubscriptionHistory.Transactions',
        ])->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) {
            $q->where('feature_id', 3)->where('transaction_status', 'Berhasil')->where('transaction_source', 'non_school_partner');
        })->whereHas('FeatureSubscriptionHistory', function ($q) use ($today) { 
            $q->whereDate('end_date', '>=', $today)->where('subscription_status', 'aktif'); 
        })->where('mentor_id', $user->id)->get();
        
        $studentBatchGroup = $studentBatch->groupBy(function ($item) use($user) {
            $variantId = $item->FeatureSubscriptionHistory->Transactions->feature_variant_id;
            $variantName = $item->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name;
            $level = $item->EnglishZoneLevel->id;
            $batch = $item->EnglishZoneBatchSchedule->EnglishZoneBatch->id;
            $batchScheduleGroup = $item->EnglishZoneBatchSchedule->batch_schedule_group;
            $day = $item->EnglishZoneBatchSchedule->schedule_time_group;
            $startTime = $item->EnglishZoneBatchSchedule->start_time;
            $endTime = $item->EnglishZoneBatchSchedule->end_time;

            if ($variantId == 10 || $variantName == 'Langganan 3 Bulan') {
                    return implode('|', [$variantId, $level, $batch, $batchScheduleGroup, $day, $startTime, $endTime]);
            } else {
                // Default: group by variant saja
                return implode('|', [$variantId, $batch, $batchScheduleGroup, $day, $startTime, $endTime]);
            }
            
        })->map(function ($items) {
            return [
                $startTime = $items->first()->EnglishZoneBatchSchedule->start_time,
                $endTime = $items->first()->EnglishZoneBatchSchedule->end_time,
                
                'variant_id' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->id,
                'variant_name' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name,
                'level_ids' => $items->pluck('EnglishZoneLevel.id')->unique()->values()->toArray(),
                'level_names' => $items->pluck('EnglishZoneLevel.level_name')->unique()->values()->toArray(),
                'batch_ids' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.id')->unique()->values()->toArray(),
                'batch_names' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.batch_name')->unique()->values()->toArray(),
                'days_of_week' => $items->pluck('EnglishZoneBatchSchedule.day_of_week')->unique()->values()->toArray(),
                'hours' => $startTime . ' - ' . $endTime,
                'batch_schedule_groups' => $items->pluck('EnglishZoneBatchSchedule.batch_schedule_group')->unique()->values()->toArray(),
                'batch_schedule_ids' => $items->pluck('EnglishZoneBatchSchedule.id')->unique()->values()->toArray(),
                'count_students' => $items->pluck('student_id')->unique()->count(),
                'student_batch_ids' => $items->pluck('id')->unique()->values()->toArray(),
                'student_ids' => $items->pluck('student_id')->unique()->values()->toArray(),
                'mentor_ids' => $items->pluck('mentor_id')->unique()->values(),
                'start_date' => $items->first()->FeatureSubscriptionHistory->start_date,
                'end_date' => $items->first()->FeatureSubscriptionHistory->end_date,
            ];
        })
        ->filter(); // untuk hapus group yang null

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $pagedData = $studentBatchGroup->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $studentBatchGroup->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

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

        $getStudentIds = implode(',', $studentBatch->pluck('student_id')->unique()->toArray());

        return response()->json([
            'data' => $paginated->values(),
            'links' => (string) $paginated->links(),
            'getStudentIds' => $getStudentIds,
            'studentBatchDetail' => '/english-zone-mentor/student-batch/detail/:featureVariantId/:levelId/:batchId/:batchScheduleGroups/:batchScheduleIds/:studentIds',
        ]);
    }

    public function paginateMentorStudentBatchSchoolPartner(Request $request)
    {
        $today = now()->format('Y-m-d');

        $user = Auth::user();

        $studentBatchQuery = EnglishZoneStudentBatch::with([
            'EnglishZoneLevel',
            'EnglishZoneBatchSchedule',
            'FeatureSubscriptionHistory.Transactions',
            'Student.StudentProfiles', // tambahkan biar eager load sekolah juga
        ])->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) {
            $q->where('feature_id', 3)
            ->where('transaction_status', 'Berhasil')
            ->where('transaction_source', 'school_partner');
        })->whereHas('FeatureSubscriptionHistory', function ($q) use ($today) {
            $q->whereDate('end_date', '>=', $today)
            ->where('subscription_status', 'aktif');
        })->where('mentor_id', $user->id);

        // 🔍 Filter sekolah DI SINI (masih query builder)
        if ($request->filled('search_school_partner')) {
            $search = strtolower($request->search_school_partner);
            $studentBatchQuery->whereHas('Student.StudentProfiles', function ($q) use ($search) {
                $q->whereRaw('LOWER(sekolah) LIKE ?', ["%{$search}%"]);
            });
        }

        // Baru ambil hasilnya
        $studentBatch = $studentBatchQuery->get();

        $studentBatchGroup = $studentBatch->groupBy(function ($item) {
            $variantId = $item->FeatureSubscriptionHistory->Transactions->feature_variant_id;
            $variantName = $item->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name;
            $level = $item->EnglishZoneLevel->id;
            $batch = $item->EnglishZoneBatchSchedule->EnglishZoneBatch->id;
            $batchScheduleGroup = $item->EnglishZoneBatchSchedule->batch_schedule_group;
            $day = $item->EnglishZoneBatchSchedule->schedule_time_group;
            $startTime = $item->EnglishZoneBatchSchedule->start_time;
            $endTime = $item->EnglishZoneBatchSchedule->end_time;
            $schoolPartnerId = $item->Student->StudentProfiles->sekolah;

            if ($variantId == 10 || $variantName == 'Langganan 3 Bulan') {
                    return implode('|', [$variantId, $level, $batch, $batchScheduleGroup, $day, $startTime, $endTime, $schoolPartnerId]);
            } else {
                // Default: group by variant saja
                return implode('|', [$variantId, $batch, $batchScheduleGroup, $day, $startTime, $endTime, $schoolPartnerId]);
            }

        })->map(function ($items) {
            return [
                $startTime = $items->first()->EnglishZoneBatchSchedule->start_time,
                $endTime = $items->first()->EnglishZoneBatchSchedule->end_time,
                
                'variant_id' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->id,
                'variant_name' => $items->first()->FeatureSubscriptionHistory->Transactions->FeaturePrices->variant_name,
                'level_ids' => $items->pluck('EnglishZoneLevel.id')->unique()->values()->toArray(),
                'level_names' => $items->pluck('EnglishZoneLevel.level_name')->unique()->values()->toArray(),
                'batch_ids' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.id')->unique()->values()->toArray(),
                'batch_names' => $items->pluck('EnglishZoneBatchSchedule.EnglishZoneBatch.batch_name')->unique()->values()->toArray(),
                'days_of_week' => $items->pluck('EnglishZoneBatchSchedule.day_of_week')->unique()->values()->toArray(),
                'hours' => $startTime . ' - ' . $endTime,
                'batch_schedule_groups' => $items->pluck('EnglishZoneBatchSchedule.batch_schedule_group')->unique()->values()->toArray(),
                'batch_schedule_ids' => $items->pluck('EnglishZoneBatchSchedule.id')->unique()->values()->toArray(),
                'count_students' => $items->pluck('student_id')->unique()->count(),
                'student_batch_ids' => $items->pluck('id')->unique()->values()->toArray(),
                'student_ids' => $items->pluck('student_id')->unique()->values()->toArray(),
                'mentor_ids' => $items->pluck('mentor_id')->unique()->values(),
                'school' => $items->first()->Student->StudentProfiles->sekolah,
                'start_date' => $items->first()->FeatureSubscriptionHistory->start_date,
                'end_date' => $items->first()->FeatureSubscriptionHistory->end_date,
            ];
        })
        ->filter(); // untuk hapus group yang null

        // Pagination manual
        $page = (int) $request->get('page', 1);
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $pagedData = $studentBatchGroup->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $studentBatchGroup->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

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

        $getStudentIds = implode(',', $studentBatch->pluck('student_id')->unique()->toArray());

        return response()->json([
            'data' => $paginated->values(),
            'links' => $paginated->isEmpty() ? '' : (string) $paginated->links(),
            'getStudentIds' => $getStudentIds,
            'studentBatchDetail' => '/english-zone-mentor/student-batch/detail/:featureVariantId/:levelId/:batchId/:batchScheduleGroups/:batchScheduleIds/:studentIds',
        ]);
    }

    public function mentorStudentBatchDetailView($featureVariantId, $levelId, $batchId, $batchScheduleGroups, $batchScheduleIds, $studentIds)
    {
        return view('Features.english-zone.mentor.english-zone-mentor-student-batch-detail', compact('featureVariantId', 'levelId', 
        'batchId', 'batchScheduleGroups', 'batchScheduleIds', 'studentIds'));
    }

    public function paginateMentorStudentBatchDetail($featureVariantId, $levelId, $batchId, $batchScheduleGroups, $batchScheduleIds, $studentIds)
    {
        // Ambil tanggal hari ini dalam format YYYY-MM-DD
        $today = Carbon::now()->format('Y-m-d');

        // Ambil data batch berdasarkan batchId
        $getBatch = EnglishZoneBatch::where('id', $batchId)->first();

        // Ambil data level berdasarkan levelId (bisa lebih dari satu ID)
        $getLevels = EnglishZoneLevel::whereIn('id', explode(',', $levelId))->get();

        // Ubah semua parameter string menjadi array untuk query whereIn()
        $studentIds = explode(',', $studentIds);
        $levelIds = explode(',', $levelId);
        $featureVariantId = explode(',', $featureVariantId);
        $batchScheduleIds = explode(',', $batchScheduleIds);

        // 🔹 Ambil daftar siswa dalam batch berdasarkan berbagai relasi dan filter
        $data = EnglishZoneStudentBatch::with(['Student.StudentProfiles', 'Mentor.MentorProfiles', 'FeatureSubscriptionHistory.Transactions.FeaturePrices', 'EnglishZoneLevel',
        'EnglishZoneBatchSchedule','EnglishZoneBatchSchedule.EnglishZoneBatch'])
        // Filter hanya data dengan transaksi yang sukses & variant yang cocok
        ->whereHas('FeatureSubscriptionHistory.Transactions', function ($query) use ($featureVariantId) {
            $query->where('transaction_status', 'Berhasil')
                ->where('feature_variant_id', $featureVariantId);
        })
        // Filter hanya subscription yang masih aktif dan belum lewat tanggalnya
        ->whereHas('FeatureSubscriptionHistory', function ($query) use ($today) {
            $query->where('end_date', '>=', $today)->where('subscription_status', 'aktif');
        })
        // Filter berdasarkan batch yang sama dan grup jadwal tertentu
        ->whereHas('EnglishZoneBatchSchedule', function ($query) use ($batchId, $batchScheduleGroups) {
            $query->where('batch_id', $batchId)->where('batch_schedule_group', $batchScheduleGroups);
        })->whereIn('batch_schedule_id', $batchScheduleIds)->whereIn('level_id', $levelIds)
        ->whereIn('student_id', $studentIds)->get()->groupBy('student_id');

        // Ambil daftar jadwal batch (hari, jam mulai, jam selesai)
        $batchSchedule = EnglishZoneBatchSchedule::with(['EnglishZoneBatch'])
            ->whereIn('id', $batchScheduleIds)
            ->get()
            ->map(function ($item) {
                return [
                    'day_of_week' => $item->day_of_week,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                ];
            });

        // Ambil semua subscription yang sudah lewat masa aktifnya (end_date < hari ini)
        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $today)->get();

        // Jika ada subscription yang kadaluwarsa, ubah statusnya jadi tidak_aktif
        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        // Kembalikan hasil dalam bentuk JSON untuk frontend
        return response()->json([
            'data' => $data->values(),
            'batchSchedules' => $batchSchedule->values(),
            'getBatch' => $getBatch,
            'getLevels' => $getLevels
        ]);
    }

    public function paginateMentorStudentBatchDetailMateri($levelId, $studentIds, $activeLevel)
    {
        // Ambil tanggal hari ini dalam format YYYY-MM-DD
        $date = Carbon::now()->format('Y-m-d');

        // Ambil data user yang sedang login (mentor)
        $user = Auth::user();

        // Pisahkan string studentIds (misal "1,2,3") menjadi array [1,2,3]
        $explodeStudentIds = explode(',', $studentIds);

        // Ambil semua batch yang terkait dengan level aktif dan mentor saat ini
        $studentBatch = EnglishZoneStudentBatch::whereHas('FeatureSubscriptionHistory', function ($query) use ($date) {
            $query->where('end_date', '>=', $date)->where('subscription_status', 'aktif');
        })->where('level_id', $activeLevel)->whereIn('student_id', $explodeStudentIds)->where('mentor_id', $user->id)->get();

        // Ambil tanggal mulai dan selesai dari level (berdasarkan batch pertama)
        $levelStartDate = Carbon::parse($studentBatch->first()->level_start_date);
        $levelEndDate = Carbon::parse($studentBatch->first()->level_end_date);

        // Ambil semua batch_schedule_id dari studentBatch (jadwal tiap siswa)
        $batchScheduleIds = $studentBatch->pluck('batch_schedule_id')->toArray();

        // Ambil daftar hari dari jadwal (misal ['Senin', 'Rabu'])
        $batchSchedules = EnglishZoneBatchSchedule::whereIn('id', $batchScheduleIds)->pluck('day_of_week')->toArray();

        // Urutkan daftar hari sesuai urutan umum
        $order = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        usort($batchSchedules, fn($a, $b) => array_search($a, $order) <=> array_search($b, $order));

        // Ambil semua materi berdasarkan level aktif
        $materiList = EnglishZoneMateri::with(['EnglishZoneLevel', 'EnglishZoneSession'])
            ->where('level_id', $activeLevel)
            ->get()
            ->map(function ($item) {
                // Ekstrak ID video YouTube jika ada link-nya
                if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $item['video_materi'], $matches)) {
                    $item['video_id'] = $matches[1] ?? $matches[2]; // simpan hanya ID video
                } else {
                    $item['video_id'] = null; // tidak ada video YouTube
                }
                return $item;
            });

        $materiList = $materiList->values(); // reset index
        foreach ($materiList as $i => $item) {
            $item->session_number = $i + 1; // nomor sesi baru dimulai dari 1
        }


        // Ambil daftar Zoom berdasarkan level & mentor
        $zoomList = EnglishZoneZoom::where('mentor_id', $user->id)->first();

        // Buat peta sesi Zoom agar mudah diakses
        $zoomMap = [
            'link_zoom' => $zoomList->link_zoom,
        ];

        // Buat daftar materi lengkap dengan tanggal dan link Zoom
        $materiWithZoom = $materiList->map(function ($materi) use ($levelStartDate, $levelEndDate, $batchSchedules, $zoomMap) {
            $session = $materi->session_number; // nomor sesi materi
            $daysCount = count($batchSchedules); // jumlah hari dalam seminggu (misal 2 hari: Senin & Rabu)
            $weekOffset = floor(($session - 1) / $daysCount); // minggu ke berapa
            $dayIndex = ($session - 1) % $daysCount; // indeks hari ke berapa (0 untuk Senin, 1 untuk Rabu, dst)

            // Ambil nama hari dari daftar jadwal
            $dayName = $batchSchedules[$dayIndex];

            // Daftar offset hari (untuk hitung selisih hari)
            $dayOffsets = [
                'Senin' => 0,
                'Selasa' => 1,
                'Rabu' => 2,
                'Kamis' => 3,
                'Jumat' => 4,
                'Sabtu' => 5,
                'Minggu' => 6,
            ];

            // Ambil hari pertama batch (misal: Senin)
            $firstDayName = $batchSchedules[0];
            $firstDayOffset = $dayOffsets[$firstDayName] ?? 0;
            $targetOffset = $dayOffsets[$dayName] ?? 0;

            // Temukan tanggal pertama kali hari "Senin" muncul setelah level_start_date
            $firstDay = $levelStartDate->copy();
            while ($firstDay->dayOfWeekIso != ($firstDayOffset + 1)) {
                $firstDay->addDay();
            }

            // Hitung tanggal sesi berdasarkan minggu ke-n dan perbedaan hari
            $sessionDate = $firstDay->copy()->addWeeks($weekOffset)->addDays($targetOffset - $firstDayOffset);

            // Jika tanggal sesi melebihi tanggal akhir level, jangan ditampilkan
            if ($sessionDate->greaterThan($levelEndDate)) {
                return null;
            }

            // Tambahkan data tambahan ke objek materi
            $materi->zoom_link = $zoomMap['link_zoom'] ?? null;
            $materi->day_of_week = $dayName;
            $materi->session_date = $sessionDate->translatedFormat('d F Y'); // format tanggal misal "05 November 2025"
            $materi->session_date_check = $sessionDate->translatedFormat('Y-m-d'); // format ISO untuk perbandingan
            $materi->level_start_date = $levelStartDate->translatedFormat('Y-m-d');
            $materi->level_end_date = $levelEndDate->translatedFormat('Y-m-d');

            return $materi;
        });

        // Hapus materi yang null (karena sesi lewat tanggal akhir level)
        $materiWithZoom = $materiWithZoom->filter(fn($m) => $m !== null)->values();

        // Ambil data level (bisa lebih dari satu) berdasarkan ID yang dikirim
        $getLevels = EnglishZoneLevel::whereIn('id', explode(',', $levelId))->get();

        // Kembalikan hasil sebagai JSON ke frontend
        return response()->json([
            'data' => $materiWithZoom,
            'getLevels' => $getLevels,
            'date' => $date,
            'studentBatch' => $studentBatch
        ]);
    }

    // STUDENT
    // function view student page
    public function englishZoneStudentView()
    {
        // ambil data hari ini
        $date = now()->format('Y-m-d');

        // mendapatkan data user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $date)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        $getSubscriptionStudent = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('feature_id', 3)->where('transaction_status', 'Berhasil');
        })->where('student_id', $user->id)
        ->whereDate('end_date', '>=', $date)->where('subscription_status', 'aktif')->first();

        if ($getSubscriptionStudent) {
            $levelIds = implode(',', $getSubscriptionStudent->Transactions->transaction_callback['level_id']);
        } else {
            $levelIds = EnglishZoneLevel::first()->id;
        }

        $dataAttendance = EnglishZoneAttendance::where('student_id', $user->id)->whereDate('attendance_time_in', '>=', $date)->first();

        return view('Features.english-zone.student.english-zone-student', compact('date', 'getSubscriptionStudent', 'levelIds', 'dataAttendance'));
    }

    // paginate materi
    public function paginateStudentMateri($levelIds, $activeLevel)
    {
        // ambil data hari ini
        $date = now()->format('Y-m-d');

        // mendapatkan data user yang sedang login
        $user = Auth::user();

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $date)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        $getSubscriptionStudent = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('feature_id', 3)->where('transaction_status', 'Berhasil');
        })->where('student_id', $user->id)->where('end_date', '>=', $date)->where('subscription_status', 'aktif')->first();

        if ($getSubscriptionStudent) {
            // Ambil semua batch yang terkait dengan level aktif dan mentor saat ini
            $studentBatch = EnglishZoneStudentBatch::where('level_id', $activeLevel)->where('student_id', $user->id)->get();
    
            // Ambil tanggal mulai dan selesai dari level (berdasarkan batch pertama)
            $levelStartDate = Carbon::parse($studentBatch->first()->level_start_date ?? null);
            $levelEndDate = Carbon::parse($studentBatch->first()->level_end_date ?? null);
    
            // Ambil semua batch_schedule_id dari studentBatch (jadwal tiap siswa)
            $batchScheduleIds = $studentBatch->pluck('batch_schedule_id')->toArray();
    
            // Ambil daftar hari dari jadwal (misal ['Senin', 'Rabu'])
            $batchSchedules = EnglishZoneBatchSchedule::whereIn('id', $batchScheduleIds)->pluck('day_of_week')->toArray();
    
            // Urutkan daftar hari sesuai urutan umum
            $order = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            usort($batchSchedules, fn($a, $b) => array_search($a, $order) <=> array_search($b, $order));

            // Ambil semua materi berdasarkan level aktif
            $materiList = EnglishZoneMateri::with(['EnglishZoneLevel', 'EnglishZoneSession'])
            ->where('level_id', $activeLevel)
            ->get()
            ->map(function ($item) {
                // Ekstrak ID video YouTube jika ada link-nya
                if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $item['video_materi'], $matches)) {
                    $item['video_id'] = $matches[1] ?? $matches[2]; // simpan hanya ID video
                } else {
                    $item['video_id'] = null; // tidak ada video YouTube
                }
                return $item;
            });

            $materiList = $materiList->values(); // reset index
            foreach ($materiList as $i => $item) {
                $item->session_number = $i + 1; // nomor sesi baru dimulai dari 1
            }

            // Ambil daftar Zoom berdasarkan level & mentor
            $zoomList = EnglishZoneZoom::where('mentor_id', $studentBatch->first()->mentor_id ?? null)->first();
    
            // Buat peta sesi Zoom agar mudah diakses
            $zoomMap = [
                'link_zoom' => $zoomList->link_zoom ?? null,
            ];
    
            // Buat daftar materi lengkap dengan tanggal dan link Zoom
            $materiWithZoom = $materiList->map(function ($materi) use ($levelStartDate, $levelEndDate, $batchSchedules, $zoomMap) {
                $session = $materi->session_number; // nomor sesi materi
                $daysCount = count($batchSchedules); // jumlah hari dalam seminggu (misal 2 hari: Senin & Rabu)
                $weekOffset = floor(($session - 1) / $daysCount); // minggu ke berapa
                $dayIndex = ($session - 1) % $daysCount; // indeks hari ke berapa (0 untuk Senin, 1 untuk Rabu, dst)
    
                // Ambil nama hari dari daftar jadwal
                $dayName = $batchSchedules[$dayIndex];
    
                // Daftar offset hari (untuk hitung selisih hari)
                $dayOffsets = [
                    'Senin' => 0,
                    'Selasa' => 1,
                    'Rabu' => 2,
                    'Kamis' => 3,
                    'Jumat' => 4,
                    'Sabtu' => 5,
                    'Minggu' => 6,
                ];
    
                // Ambil hari pertama batch (misal: Senin)
                $firstDayName = $batchSchedules[0];
                $firstDayOffset = $dayOffsets[$firstDayName] ?? 0;
                $targetOffset = $dayOffsets[$dayName] ?? 0;
    
                // Temukan tanggal pertama kali hari "Senin" muncul setelah level_start_date
                $firstDay = $levelStartDate->copy();
                while ($firstDay->dayOfWeekIso != ($firstDayOffset + 1)) {
                    $firstDay->addDay();
                }
    
                // Hitung tanggal sesi berdasarkan minggu ke-n dan perbedaan hari
                $sessionDate = $firstDay->copy()->addWeeks($weekOffset)->addDays($targetOffset - $firstDayOffset);
    
                // Jika tanggal sesi melebihi tanggal akhir level, jangan ditampilkan
                if ($sessionDate->greaterThan($levelEndDate)) {
                    return null;
                }
    
                // Tambahkan data tambahan ke objek materi
                $materi->zoom_link = $zoomMap['link_zoom'] ?? null;
                $materi->day_of_week = $dayName;
                $materi->session_date = $sessionDate->translatedFormat('d F Y'); // format tanggal misal "05 November 2025"
                $materi->session_date_check = $sessionDate->translatedFormat('Y-m-d'); // format ISO untuk perbandingan
                $materi->level_start_date = $levelStartDate->translatedFormat('Y-m-d');
                $materi->level_end_date = $levelEndDate->translatedFormat('Y-m-d');
    
                return $materi;
            });

            // Ambil data level (bisa lebih dari satu) berdasarkan ID yang dikirim
            $getLevels = EnglishZoneLevel::whereIn('id', explode(',', $levelIds))->get();

            // ambil data absensi hari ini berdasarkan user yang sedang login
            $dataAttendance = EnglishZoneAttendance::where('student_id', $user->id)->whereDate('attendance_time_in', '>=', $date)->first();
        } else {
            // Ambil semua materi berdasarkan level aktif
            $materiList = EnglishZoneMateri::with(['EnglishZoneLevel', 'EnglishZoneSession'])
                ->where('level_id', $activeLevel)
                ->get()
                ->map(function ($item) {
                    // Ekstrak ID video YouTube jika ada link-nya
                    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})|youtube\.com\/.*v=([a-zA-Z0-9_-]{11})/', $item['video_materi'], $matches)) {
                        $item['video_id'] = $matches[1] ?? $matches[2]; // simpan hanya ID video
                    } else {
                        $item['video_id'] = null; // tidak ada video YouTube
                    }
                    return $item;
                });
                $getLevels = EnglishZoneLevel::all();
        }

        $bankSoal = EnglishZoneQuestions::where('level_id', $activeLevel)->groupBy('session_id')->pluck('session_id');

        return response()->json([
            'data' => $materiWithZoom ?? $materiList,
            'getLevels' => $getLevels,
            'date' => $date,
            'studentBatch' => $studentBatch ?? null,
            'getSubscriptionStudent' => $getSubscriptionStudent ?? null,
            'dataAttendance' => $dataAttendance ?? null,
            'bankSoal' => $bankSoal->values(),
            'worksheetDetail' => '/english-zone/:levelId/worksheet-detail',
            'examDetail' => '/english-zone/:levelId/:sessionId/exam',
            'quizDetail' => '/english-zone/:levelId/quiz-detail',
        ]);
    }

    // worksheet detail view
    public function worksheetDetailView($levelId)
    {
        $user = Auth::user();

        $date = now()->format('Y-m-d');

        $getSubscriptionStudent = FeatureSubscriptionHistory::whereHas('Transactions', function($query) {
            $query->where('feature_id', 3)->where('transaction_status', 'Berhasil');    
        })->where('student_id', $user->id)->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)
        ->where('subscription_status', 'aktif')->exists();

        if (!$getSubscriptionStudent) {
            return redirect()->route('EZ.student.view');
        }
        
        return view('Features.english-zone.student.english-zone-worksheet-detail', compact('levelId'));
    }

    // paginate worksheet detail
    public function paginateWorksheetDetail($levelId)
    {
        $worksheet = EnglishZoneMateri::with(['EnglishZoneLevel', 'EnglishZoneSession'])->where('level_id', $levelId)->get();

        return response()->json([
            'data' => $worksheet,
        ]);
    }

    // submit student attendance
    public function submitStudentAttendance(Request $request)
    {
        $user = Auth::user();

        $storeAttendance = EnglishZoneAttendance::create([
            'student_id' => $user->id,
            'subscription_history_id' => $request->subscription_history_id,
            'attendance_time_in' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen berhasil'
        ]);
    }

    // paginate attendance history
    public function paginateStudentAttendanceHistory()
    {
        $user = Auth::user();

        $dataAttendance = EnglishZoneAttendance::with(['UserAccount', 'FeatureSubscriptionHistory', 'FeatureSubscriptionHistory.Transactions.FeaturePrices'])
        ->where('student_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'data' => $dataAttendance->items(),
            'links' => (string) $dataAttendance->links(),
        ]);
    }

    // exam TOEP view
    public function examView($levelId, $sessionId)
    {
        $user = Auth::user();

        $date = now()->format('Y-m-d');

        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('transaction_status', 'Berhasil');
        })->whereDate('end_date', '<', $date)->get();

        if ($featureSubscriptionHistory) {
            foreach ($featureSubscriptionHistory as $history) {
                $history->update([
                    'subscription_status' => 'tidak_aktif'
                ]);
            }
        }

        $getSubscriptionStudent = FeatureSubscriptionHistory::whereHas('Transactions', function($query) {
            $query->where('feature_id', 3)->where('transaction_status', 'Berhasil');    
        })->where('student_id', $user->id)->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)
        ->where('subscription_status', 'aktif')->exists();

        if (!$getSubscriptionStudent) {
            return redirect()->route('EZ.student.view');
        }

        $levelName = EnglishZoneLevel::where('id', $levelId)->pluck('level_name')->first();

        $sessionName = EnglishZoneSession::where('id', $sessionId)->pluck('session_name')->first();

        return view('Features.english-zone.student.exam.english-zone-student-exam', compact('levelId', 'sessionId', 'levelName', 'sessionName'));
    }

    // exam TOEP questions form
    public function questionFormExamTOEP($levelId, $sessionId)
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

        // Ambil ulang soal-soal yang masih `Publish` dari DB dan tipe soal adalah `TOEP`
        $publishedQuestionIds = EnglishZoneQuestions::where('tipe_soal', 'TOEP')
        ->where('status_bank_soal', 'Publish')->where('level_id', $levelId)
        ->where('session_id', $sessionId)->pluck('id')->implode(',');

        // Ambil informasi user yang berlangganan fitur english zone
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 3); // feature_id 3 menunjukkan fitur english zone
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->first();

        $subscriptionId = $subscription ? $subscription->id : null;

        // Buat key cache unik berdasarkan setiap subscription, user, levelId, dan session_id
        $cacheKey = "english-zone-exam-questions-{$subscriptionId}-{$userId}-{$levelId}-{$sessionId}-{$publishedQuestionIds}";

        // Cek apakah data soal sudah disimpan di cache hari ini
        if  (Cache::has($cacheKey)) {
            // Ambil data soal dari cache dan ubah ke bentuk collection dalam bentuk nested group
            $groupedQuestions = collect(Cache::get($cacheKey))->map(fn($group) => collect($group))->values();
        } else {
            // Jika tidak ada di session, ambil soal dari database berdasarkan level dan session_id, status Publish, dan tipe TOEP
            $getQuestions = EnglishZoneQuestions::where('level_id', $levelId)->where('session_id', $sessionId)
            ->where('status_bank_soal', 'Publish')->where('tipe_soal', 'TOEP')->get();

            // Mengelompokkan data berdasarkan soal
            $groupedQuestions = $getQuestions->groupBy('questions');

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
            $questionsAnswer = EnglishZoneAnswers::where('student_id', Auth::id())
                ->whereIn('question_id', $questionIds)
                ->where('subscription_history_id', $subscription->id)
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
            'scoreEachQuestion' => $scoreEachQuestion,
            'today' => $today, // Tambahkan tanggal hari ini ke response
            'subscription' => $subscription,
            'now' => now()->toISOString(), // Tambahkan waktu saat ini ke response
        ]);
    }

    public function examTOEPAnswers(Request $request, $levelId, $sessionId)
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
        ], [
            'user_answer_option.required' => 'Harap pilih jawaban.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil informasi user yang berlangganan fitur english zone
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 3); // feature_id 3 menunjukkan fitur english zone
        })->where('student_id', $userId)->where('subscription_status', 'aktif')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->pluck('id')->first();

        // ambil soal dari database berdasarkan bab, status Publish, dan tipe ujian
        $getQuestions = EnglishZoneQuestions::where('level_id', $levelId)->where('session_id', $sessionId)->where('status_bank_soal', 'Publish')
        ->where('tipe_soal', 'TOEP')->whereDate('created_at', $today)->get();

        // Mengelompokkan data berdasarkan soal
        $groupedQuestions = $getQuestions->groupBy('questions');

        // Ambil semua ID soal (karena groupedQuestions adalah nested collection, gunakan flatten)
        $questionIds = $groupedQuestions->flatten()->pluck('id')->toArray();

        // mencari soal berdasarkan request question_id
        $question = EnglishZoneQuestions::findOrFail($request->question_id);

        $dataQuestionAnswer = EnglishZoneAnswers::where('student_id', $userId)->where('question_id', $request->question_id)
        ->whereDate('created_at', $today)->first();
        
        if (!$dataQuestionAnswer && $subscription) {
            EnglishZoneAnswers::create([
                'student_id' => $userId,
                'subscription_history_id' => $request->subscription_history_id,
                'question_id' => $request->question_id,
                'user_answer_option' => $request->user_answer_option,
                'question_score' => $request->user_answer_option === $question->answer_key ? $request->question_score : 0,
            ]);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    // QUIZ EXAM (Reading, Writing, Listening, Speaking)
    // function quiz detail view
    public function quizDetailView($levelId)
    {
        $user = Auth::user();

        $date = now()->format('Y-m-d');

        $getSubscriptionStudent = FeatureSubscriptionHistory::whereHas('Transactions', function($query) {
            $query->where('feature_id', 3)->where('transaction_status', 'Berhasil');    
        })->where('student_id', $user->id)->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)
        ->where('subscription_status', 'aktif')->exists();

        if (!$getSubscriptionStudent) {
            return redirect()->route('EZ.student.view');
        }

        return view('Features.english-zone.student.quiz.english-zone-quiz-detail', compact('levelId'));
    }

    // function quiz detail fetch (untuk link href latihan dan ujian pada quiz masing")
    public function quizDetailFetch($levelId)
    {
        $levelName = EnglishZoneLevel::where('id', $levelId)->pluck('level_name')->first();

        return response()->json([
            'data' => $levelName,
        ]);
    }
}