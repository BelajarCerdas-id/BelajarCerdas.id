<?php

namespace App\Http\Controllers;

use App\Events\BankSoalEditQuestion;
use App\Events\BankSoalListener;
use App\Models\Bab;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Mapel;
use App\Models\SoalPembahasanAnswers;
use App\Models\SoalPembahasanQuestions;
use App\Models\SubBab;
use App\Services\DocxImageExtractor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

        broadcast(new BankSoalEditQuestion($dataBankSoal))->toOthers();

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
    public function editQuestionView($subBab, $subBabId, $id)
    {
        // Mengambil data soal berdasarkan ID
        $editQuestion = SoalPembahasanQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('bankSoal.detail.view', [$subBab, $subBabId]);
        }

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = SoalPembahasanQuestions::where('questions', $editQuestion->questions)->get()->groupBy('questions');

        // Simpan hasil pengelompokan ke variabel baru
        $groupedSoal = $dataSoal;

        return view('Features.soal-pembahasan.bank-soal.bank-soal-edit-question', compact('subBab', 'subBabId', 'id', 'editQuestion', 'groupedSoal'));
    }

    public function formEditQuestion(Request $request, $subBab, $subBabId, $id)
    {
        $editQuestion = SoalPembahasanQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('bankSoal.detail.view', [$subBab, $subBabId]);
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
        ], [
            'questions.required' => 'Harap isi pertanyaan soal!',
            'options_value.*.required' => 'Harap isi jawaban soal!',
            'answer_key.required' => 'Harap isi jawaban soal!',
            'skilltag.required' => 'Harap isi skilltag soal!',
            'difficulty.required' => 'Harap isi difficulty soal!',
            'explanation.required' => 'Harap isi pembahasan soal!',
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
                ]);
            }
        }

        broadcast(new BankSoalEditQuestion($groupedSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Soal berhasil diupdate',
            'data' => $groupedSoal
        ]);
    }

    // Function bulkUpload Bank Soal Store with phpWord(for image), pandoc, mathml(equation word symbols, text styles)
    public function bankSoalStore(Request $request)
    {
        $extractor = new DocxImageExtractor();

        // Validasi form input dari frontend (kurikulum, kelas, mapel, dll)
        $validator = Validator::make($request->all(), [
            'bulkUpload-soal-pembahasan' => 'required|file|mimes:docx|max:10240',
            'kurikulum_id' => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'bab_id' => 'required',
            'sub_bab_id' => 'required',
        ], [
            'bulkUpload-soal-pembahasan.required' => 'Harap upload soal pembahasan!',
            'kurikulum_id.required' => 'Harap pilih kurikulum!',
            'kelas_id.required' => 'Harap pilih kelas!',
            'mapel_id.required' => 'Harap pilih mapel!',
            'bab_id.required' => 'Harap pilih bab!',
            'sub_bab_id.required' => 'Harap pilih sub bab!',
        ]);

        // Simpan error form (jangan langsung return)
        $formErrors = $validator->fails() ? $validator->errors()->toArray() : [];

        // Siapkan array untuk validasi dari isi file Word
        $allWordValidationErrors = [];

        // Proses upload file docx
        $userId = Auth::id();
        $uploadedFile = $request->file('bulkUpload-soal-pembahasan');

        // mengecek apakah ada file yang diupload
        if ($uploadedFile) {
            $docxPath = storage_path('app/tmp_soal.docx');
            $outputHtmlPath = storage_path('app/converted_soal.html');

            $uploadedFile->move(storage_path('app'), 'tmp_soal.docx');

            // Konversi file Word ke HTML menggunakan Pandoc
            $process = new Process(['pandoc', $docxPath, '-f', 'docx', '-t', 'html', '--mathml', '-o', $outputHtmlPath]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $styledData = [];
            $mediaImages = [];

            // Ekstrak gambar dan teks styled dari file Word dengan PhpWord
            try {
                $phpWord = IOFactory::load($docxPath);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        try {
                            $extractor->extractImages($element, $mediaImages);
                        } catch (\Throwable $e) {
                            Log::warning('Gagal ekstrak elemen: ' . $e->getMessage());
                        }
                    }
                }

                $styledData = $extractor->extractStyledTableData($phpWord, $mediaImages);
            } catch (\Throwable $e) {
                if (!str_contains($e->getMessage(), 'Namespace prefix m on oMath')) {
                    Log::warning('Gagal parse PhpWord: ' . $e->getMessage());
                }
            }

            // Parse file HTML hasil konversi Pandoc
            $htmlContent = file_get_contents($outputHtmlPath);
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors();

            $tables = $dom->getElementsByTagName('table');

            foreach ($tables as $index => $table) {
                $rows = $table->getElementsByTagName('tr');
                $dataSoal = [];
                $validationErrors = [];
                $soalNumber = $index + 1;

                foreach ($rows as $row) {
                    $cells = $row->getElementsByTagName('td');
                    if ($cells->length < 2) continue;

                    // Ambil key dan isi HTML value-nya
                    $key = strtoupper(trim(strip_tags($cells->item(0)?->textContent ?? '')));
                    $rawHtml = '';
                    foreach ($cells->item(1)->childNodes as $child) {
                        $rawHtml .= $dom->saveHTML($child);
                    }

                    // Gunakan styled value jika tersedia, atau fallback ke hasil Pandoc
                    $pandocValue = $extractor->replaceImageSrc($rawHtml, $mediaImages);
                    $styledValue = $styledData[$key] ?? '';
                    $plainPandoc = trim(str_replace('&nbsp;', ' ', $pandocValue));
                    $plainStyled = trim(str_replace('&nbsp;', ' ', $styledValue));

                    $value = empty($styledValue)
                        ? $pandocValue
                        : (($plainPandoc === $plainStyled || str_contains($pandocValue, '<math')) ? $pandocValue : $styledValue);

                    if (!str_contains($value, '<p>') && !str_contains($value, '<div>')) {
                        $value = "<p>$value</p>";
                    }

                    if (!empty($key)) {
                        $dataSoal[$key] = $value;
                    }
                }

                // Validasi wajib untuk field tertentu
                if (!isset($dataSoal['QUESTION']) || $extractor->isHtmlEmpty($dataSoal['QUESTION'])) {
                    $validationErrors[] = "Soal ke-$soalNumber: QUESTION tidak boleh kosong.";
                }

                // Simpan ke database
                $answerMap = [
                    'OPTION1' => 'A',
                    'OPTION2' => 'B',
                    'OPTION3' => 'C',
                    'OPTION4' => 'D',
                    'OPTION5' => 'E',
                ];

                $availableOptions = array_filter(array_keys($answerMap), fn($key) => isset($dataSoal[$key]) && $extractor->isHtmlEmpty($dataSoal[$key]));

                $requiredFields = array_merge($availableOptions, ['ANSWER', 'EXPLANATION', 'SKILLTAG', 'DIFFICULTY', 'STATUS', 'TYPE']);
                foreach ($requiredFields as $field) {
                    if (!isset($dataSoal[$field]) || $extractor->isHtmlEmpty($dataSoal[$field])) {
                        $validationErrors[] = "Soal ke-$soalNumber: Field '$field' tidak boleh kosong.";
                    }
                }

                // Jika ada error validasi Word, simpan dulu dan lanjut soal berikutnya
                if (!empty($validationErrors)) {
                    $allWordValidationErrors = array_merge($allWordValidationErrors, $validationErrors);
                    continue;
                }

                if (empty($formErrors)) {
                    // Loop semua data gambar yang diekstrak dari Word
                    foreach ($mediaImages as $key => &$imgData) {
                        // Jika gambar belum disimpan (path masih null) dan data binernya tersedia
                        if (is_null($imgData['path']) && !is_null($imgData['binary'])) {
                            // Simpan gambar ke folder publik dan ambil path-nya
                            $imgData['path'] = $extractor->saveImage($imgData['binary'], $imgData['mime']);
                        }
                    }

                    // Gantikan semua placeholder gambar (src="PLACEHOLDER:<key>") dengan path sebenarnya
                    foreach ($dataSoal as $key => $html) {
                        $dataSoal[$key] = $extractor->replaceAllPlaceholdersWithPath($html, $mediaImages);
                    }
                }

                $answerKey = strtoupper(trim(strip_tags($dataSoal['ANSWER'] ?? '')));
                $finalAnswerKey = $answerMap[$answerKey] ?? null;

                if (empty($formErrors)) {
                    // Cek apakah soal dengan pertanyaan yang sama sudah ada
                    $existingQuestion = SoalPembahasanQuestions::where('questions', $dataSoal['QUESTION'])->first();

                    // Insert hanya akan dijalankan jika soal belum ada
                    if (!$existingQuestion) {
                        // mengecek Jika ada soal dengan sub_bab yang sama dan status_bank_soal = 'Publish', jika tidak maka Unpublish
                        $statusBankSoal = SoalPembahasanQuestions::where('sub_bab_id', $request->sub_bab_id)
                            ->where('status_bank_soal', 'Publish')
                            ->exists() ? 'Publish' : 'Unpublish';

                        foreach ($answerMap as $optionField => $label) {
                            if (!empty($dataSoal[$optionField])) {
                                $createBankSoal = SoalPembahasanQuestions::create([
                                    'administrator_id' => $userId,
                                    'kurikulum_id'     => $request->kurikulum_id,
                                    'kelas_id'         => $request->kelas_id,
                                    'mapel_id'         => $request->mapel_id,
                                    'bab_id'           => $request->bab_id,
                                    'sub_bab_id'       => $request->sub_bab_id,
                                    'questions'        => $dataSoal['QUESTION'],
                                    'options_key'      => $label,
                                    'options_value'    => $dataSoal[$optionField],
                                    'answer_key'       => $finalAnswerKey,
                                    'skilltag'         => trim(strip_tags($dataSoal['SKILLTAG'] ?? '')),
                                    'difficulty'       => trim(strip_tags($dataSoal['DIFFICULTY'] ?? '')),
                                    'explanation'      => $dataSoal['EXPLANATION'] ?? '',
                                    'status_soal'      => trim(strip_tags($dataSoal['STATUS'] ?? '')),
                                    'tipe_soal'        => trim(strip_tags($dataSoal['TYPE'] ?? '')),
                                    'status_bank_soal' => $statusBankSoal,
                                ]);
                            }
                        }
                    }
                }
            }

            // Kirim event jika soal berhasil ditambahkan
            if (isset($createBankSoal)) {
                broadcast(new BankSoalListener($createBankSoal))->toOthers();
            }
        }

        // Tampilkan error jika ada dari form atau dari Word
        if (!empty($formErrors) || !empty($allWordValidationErrors)) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => [
                    'form_errors' => $formErrors,
                    'word_validation_errors' => $allWordValidationErrors,
                ],
            ], 422);
        }

        // Bersihkan file sementara
        @unlink($docxPath);
        @unlink($outputHtmlPath);

        // Jika tidak ada error, kirim respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Bank Soal berhasil diupload.',
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
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan kelas berdasarkan fase user
        $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-kelas', compact('getKelasByFase'));
    }

    // function soal pembahasan preview mapel by kelas
    public function soalPembahasanMapelView($kelas, $kelas_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan kelas berdasarkan fase user
        $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();

        // Mendapatkan mapel berdasarkan kelas yang dipilih user
        $getMapelByKelas = Mapel::where('kelas_id', $kelas_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-mapel', compact(
            'kelas', 'kelas_id', 'getKelasByFase', 'getMapelByKelas'
        ));
    }

    // function soal pembahasan preview bab by mapel
    public function soalPembahasanBabView($kelas, $kelas_id, $mata_pelajaran, $mapel_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan kelas berdasarkan fase user
        $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();

        // Mendapatkan bab berdasarkan mapel yang dipilih user
        $getBabByMapel = Bab::where('mapel_id', $mapel_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-bab', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'getKelasByFase', 'getBabByMapel'
        ));
    }

    // function soal pembahasan preview sub bab by bab
    public function soalPembahasanSubBabView($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan kelas berdasarkan fase user
        $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();

        // Mendapatkan sub bab berdasarkan mapel yang dipilih user
        $getSubBabByBab = SubBab::where('bab_id', $bab_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-sub-bab', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id',  'getKelasByFase', 'getSubBabByBab'
        ));
    }

    // function soal pembahasan preview assessment (practice or exam)
    public function soalPembahasanAssessmentView($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan kelas berdasarkan fase user
        $getKelasByFase = Kelas::where('fase_id', $user->Profile->fase_id)->get();

        return view('Features.soal-pembahasan.soal-pembahasan-list-assessment', compact(
            'kelas', 'kelas_id', 'mata_pelajaran', 'mapel_id', 'bab_id', 'getKelasByFase',
        ));
    }

    // FUNCTION PRACTICE
    public function practice($kelas, $kelas_id, $mata_pelajaran, $mapel_id, $bab_id, $sub_bab_id)
    {
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
        // $today = Carbon::createFromFormat('Y-m-d', '2025-08-23')->format('Y-m-d');
        // $today = Carbon::parse('2025-08-23')->startOfDay();

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil ulang soal-soal yang masih `Publish` dari DB
        $publishedQuestionIds = SoalPembahasanQuestions::where('status_bank_soal', 'Publish')->where('sub_bab_id', $sub_bab_id)
        ->pluck('id')->implode(',');

        // Buat key cache unik berdasarkan tanggal, user, dan sub bab
        $cacheKey = "soal-pembahasan-practice-questions-{$today}-{$userId}-{$sub_bab_id}-{$publishedQuestionIds}";

        // Cek apakah soal sudah pernah disimpan di cache hari ini
        if (Cache::has($cacheKey)) {
            // Ambil data soal dari cache dan bungkus ulang jadi koleksi Laravel
            $groupedQuestions = collect(Cache::get($cacheKey))
                ->map(fn($group) => collect($group)) // pastikan setiap grup tetap berbentuk Collection
                ->values();
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

            // Gabungkan 3 Free dan Premium, lalu ambil maksimal 20 soal
            $selected = $free->concat($premium)->take(20);

            // Setiap grup soal diacak isinya (misalnya pilihan jawabannya)
            $groupedQuestions = $selected->map(fn($g) => $g->shuffle()->values())->values();

            // Simpan hasil akhir ke cache sampai akhir hari (pukul 23:59:59)
            Cache::put($cacheKey, $groupedQuestions, now()->endOfDay());
        }

        // Ambil jawaban user sebelumnya untuk ditampilkan sebagai isian otomatis (per hari)
        $questionsAnswer = SoalPembahasanAnswers::whereHas('SoalPembahasanQuestions', function ($query) {
            $query->where('tipe_soal', 'Latihan');
        })->where('student_id', $userId)->whereDate('created_at', $today)
            ->pluck('user_answer_option', 'question_id');

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

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
        // $today = Carbon::createFromFormat('Y-m-d', '2025-08-23')->format('Y-m-d');
        // $today = Carbon::parse('2025-08-23')->startOfDay();


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

        // mengambil data soal berdasarkan pada hari ini berdasarkan soal yang dijawab
        $dataQuestionsAnswer = SoalPembahasanAnswers::where('student_id', $userId)
            ->where('question_id', $request->question_id)->whereDate('created_at', $today)->first();

        // jika soal belum dijawab, maka simpan jawaban (untuk menghindari duplikasi data ketika user spam simpan jawaban)
        if (!$dataQuestionsAnswer) {
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
        // $today = Carbon::createFromFormat('Y-m-d', '2026-08-25')->format('Y-m-d');
        // $today = Carbon::parse('2025-08-24')->startOfDay();

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil ulang soal-soal yang masih `Publish` dari DB
        $publishedQuestionIds = SoalPembahasanQuestions::where('status_bank_soal', 'Publish')->where('bab_id', $bab_id)->pluck('id')->implode(',');

        // Ambil informasi user yang berlangganan fitur soal dan pembahasan
        $subscription = FeatureSubscriptionHistory::whereHas('Transactions', function ($query){
            $query->where('feature_id', 2); // feature_id 2 menunjukkan fitur soal dan pembahasan
        })->where('student_id', $userId)->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

        // Buat key cache unik berdasarkan setiap subscription, user, dan sub bab
        $cacheKey = "soal-pembahasan-exam-questions-{$subscription}-{$userId}-{$bab_id}-{$publishedQuestionIds}";

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
        // $today = Carbon::createFromFormat('Y-m-d', '2025-08-24')->format('Y-m-d');
        // $today = Carbon::parse('2025-08-24')->startOfDay();

        $userId = Auth::id();

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
