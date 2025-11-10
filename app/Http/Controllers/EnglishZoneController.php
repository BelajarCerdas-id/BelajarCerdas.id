<?php

namespace App\Http\Controllers;

use App\Events\BankSoalEnglishZoneEditQuestion;
use App\Events\BankSoalEnglishZoneUploaded;
use App\Events\EnglishZoneBatchScheduleListener;
use App\Events\EnglishZoneLevelsListener;
use App\Events\EnglishZoneMateriListener;
use App\Events\EnglishZoneMentorScheduleListener;
use App\Events\EnglishZoneStudentBatchRefund;
use App\Events\EnglishZoneStudentBatchReschedule;
use App\Events\EnglishZoneZoomListener;
use App\Events\EventEnglishZoneBatch;
use App\Models\EnglishZoneBatch;
use App\Models\EnglishZoneBatchSchedule;
use App\Models\EnglishZoneLevel;
use App\Models\EnglishZoneMateri;
use App\Models\EnglishZoneMentorSchedule;
use App\Models\EnglishZoneQuestions;
use App\Models\EnglishZoneStudentBatch;
use App\Models\EnglishZoneZoom;
use App\Models\FeaturePrices;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Kurikulum;
use App\Models\MentorFeatureStatus;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Services\DocxImageExtractor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
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

        return response()->json([
            'status' => 'success',
            'data' => $groupedSoal,
            'editQuestion' => $editQuestion,
            'getLevels' => $getLevels
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
            'session' => 'required',
            'status_soal' => 'required',
        ], [
            'questions.required' => 'Harap isi pertanyaan soal!',
            'options_value.*.required' => 'Harap isi jawaban soal!',
            'answer_key.required' => 'Harap isi jawaban soal!',
            'difficulty.required' => 'Harap isi difficulty soal!',
            'explanation.required' => 'Harap isi pembahasan soal!',
            'level_id.required' => 'Harap isi level soal!',
            'session.required' => 'Harap isi session soal!',
            'status_soal.required' => 'Harap isi status soal!',
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
                    'session' => $request->input('session'),
                    'status_soal' => $request->status_soal
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
        // Buat instance dari class DocxImageExtractor yang berfungsi untuk ekstrak gambar + HTML styled dari file Word
        $extractor = new DocxImageExtractor();

        // Validasi input form dari frontend (wajib diisi)
        $validator = Validator::make($request->all(), [
            // File wajib ada, format .docx, max 10 MB
            'bulkUpload-soal-pembahasan' => 'required|file|mimes:docx|max:10000',
        ], [
            // Pesan error custom
            'bulkUpload-soal-pembahasan.required' => 'File tidak boleh kosong.',
            'bulkUpload-soal-pembahasan.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
        ]);

        // Simpan error validasi form (tidak langsung return, biar bisa digabung dengan error validasi isi file Word)
        $formErrors = $validator->fails() ? $validator->errors()->toArray() : [];

        // Array untuk menampung semua error validasi dari isi tabel di file Word
        $allWordValidationErrors = [];

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil file .docx yang diupload
        $uploadedFile = $request->file('bulkUpload-soal-pembahasan');

        // Mengecek apakah ada file yang diupload
        if ($uploadedFile) {
            // Tentukan path sementara untuk file docx dan file html hasil konversi
            $docxPath = storage_path('app/tmp_soal.docx');
            $outputHtmlPath = storage_path('app/converted_soal.html');

            // Pindahkan file upload ke storage/app sebagai tmp_soal.docx
            $uploadedFile->move(storage_path('app'), 'tmp_soal.docx');

            // Konversi file Word ke HTML menggunakan Pandoc (dengan mathml untuk equation)
            $process = new Process(['pandoc', $docxPath, '-f', 'docx', '-t', 'html', '--mathml', '-o', $outputHtmlPath]);
            $process->run();

            // Jika pandoc gagal, lempar exception
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Inisialisasi variabel
            $styledData = [];    // Hasil ekstrak HTML styled dari PhpWord
            $mediaImages = [];   // List gambar dari file Word
            $validSoalData = []; // Soal-soal yang lolos validasi

            // Ekstrak semua gambar dari file .docx → disimpan di $mediaImages
            $extractor->extractImagesFromDocxFile($docxPath, $mediaImages);

            // Deteksi apakah file punya equation atau list
            $forcePandocMode = false;
            if ($extractor->docxHasEquation($docxPath) || $extractor->docxHasList($docxPath)) {
                // Jika iya → skip PhpWord dan pakai hasil Pandoc saja
                Log::info('⚠️ Deteksi equation OMML, skip PhpWord dan pakai hasil Pandoc sepenuhnya.');
                Log::info("⚠️ Deteksi " . ($extractor->docxHasEquation($docxPath) ? "equation " : "") . ($extractor->docxHasList($docxPath) ? "list " : "") . "- gunakan Pandoc.");
                $styledData = [];
                $forcePandocMode = true;
            } else {
                // Jika tidak ada equation/list → coba parsing HTML styled dengan PhpWord
                try {
                    $phpWord = IOFactory::load($docxPath);
                    $styledData = $extractor->extractStyledTableData($phpWord, $mediaImages);
                } catch (\Throwable $e) {
                    // Jika gagal → log warning dan kosongkan styledData
                    Log::warning('⚠️ Gagal load PhpWord atau extractStyledTableData: ' . $e->getMessage());
                    $styledData = [];
                }
            }

            // Parse HTML hasil Pandoc
            $htmlContent = file_get_contents($outputHtmlPath);
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true); // Supaya error parsing HTML tidak mematikan proses
            $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors();

            // Ambil semua tabel dari hasil Pandoc
            $tables = $dom->getElementsByTagName('table');

            // Ambil semua key-value dari tabel hasil Pandoc
            $pandocTableValues = [];
            foreach ($tables as $tIndex => $t) {
                if (!$t instanceof \DOMElement) continue;
                $rowsForFallback = $t->getElementsByTagName('tr');
                foreach ($rowsForFallback as $row) {
                    if (!$row instanceof \DOMElement) continue;

                    // Ambil cell per baris
                    $cells = [];
                    foreach ($row->childNodes as $child) {
                        if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['td', 'th'])) {
                            $cells[] = $child;
                        }
                    }
                    if (count($cells) < 2) continue; // Harus ada minimal 2 kolom (key & value)

                    // Ambil key (kolom 1)
                    $keyHtml = '';
                    foreach ($cells[0]->childNodes as $child) {
                        $keyHtml .= $dom->saveHTML($child);
                    }
                    $normalizedKey = strtoupper(trim($extractor->normalizeTextContent($keyHtml)));
                    $key = preg_replace('/[\s\xA0]+/u', '', $normalizedKey);
                    if ($key === '') $key = 'QUESTION'; // Default key kalau kosong

                    // Ambil value (kolom 2)
                    $valueHtml = '';
                    foreach ($cells[1]->childNodes as $child) {
                        $valueHtml .= $dom->saveHTML($child);
                    }

                    // Simpan ke array berdasarkan index tabel
                    $pandocTableValues[$tIndex][$key] = $valueHtml;
                }
            }

            // Proses setiap tabel (setiap tabel = 1 soal)
            foreach ($tables as $index => $table) {
                if (!$table instanceof \DOMElement) continue;
                $rows = $table->getElementsByTagName('tr');
                $dataSoal = [];
                $validationErrors = [];
                $soalNumber = $index + 1;

                // Kalau styledData tersedia → gabungkan dengan hasil Pandoc
                if (!$forcePandocMode && isset($styledData[$index]) && is_array($styledData[$index]) && count($styledData[$index]) > 0) {
                    $dataSoal = $styledData[$index];
                    Log::info("Soal ke-$soalNumber: memakai styledData + fallback Pandoc untuk key/value.");
                    foreach ($dataSoal as $k => $htmlValue) {
                        $styledHtml = $htmlValue;
                        $pandocHtmlRaw = $pandocTableValues[$index][$k] ?? '';
                        $pandocHtml = $pandocHtmlRaw;
                        // Gabungkan styled HTML dan Pandoc HTML
                        $dataSoal[$k] = $extractor->combineStyledAndPandoc($styledHtml, $pandocHtml);
                    }
                } else {
                    // Kalau styledData kosong → ambil dari Pandoc + merge styled kalau ada
                    foreach ($rows as $row) {
                        if (!$row instanceof \DOMElement) continue;

                        $cells = [];
                        foreach ($row->childNodes as $child) {
                            if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['td', 'th'])) {
                                $cells[] = $child;
                            }
                        }
                        if (count($cells) < 2) continue;

                        // Ambil key (kolom 1)
                        $innerHtml = '';
                        foreach ($cells[0]->childNodes as $child) {
                            $innerHtml .= $dom->saveHTML($child);
                        }
                        $normalizedText = $extractor->normalizeTextContent($innerHtml);
                        $rawHtmlKey = strtoupper(trim($normalizedText));
                        $key = preg_replace('/[\s\xA0]+/u', '', $rawHtmlKey);
                        if (empty($key) && empty($dataSoal['QUESTION'])) $key = 'QUESTION';

                        // Ambil value (kolom 2)
                        $rawHtmlValue = '';
                        foreach ($cells[1]->childNodes as $child) {
                            $rawHtmlValue .= $dom->saveHTML($child);
                        }
                        $pandocValue = $rawHtmlValue;

                        // Coba ambil styled value jika ada
                        $styledValue = $styledData[$index][$key] ?? '';
                        $plainPandoc = $extractor->normalizeTextContent($pandocValue);
                        $plainStyled = $extractor->normalizeTextContent($styledValue);

                        // Tentukan value akhir
                        if (empty($styledValue)) {
                            $value = $pandocValue;
                        } elseif ($plainPandoc === $plainStyled) {
                            $value = $styledValue;
                        } elseif (str_contains($pandocValue, '<math') || str_contains($pandocValue, '<img') || str_contains($pandocValue, '<ul') || str_contains($pandocValue, '<ol')) {
                            $value = $extractor->mergeStyledAndPandocHtml($pandocValue, $styledValue, $mediaImages);
                        } else {
                            $value = $styledValue;
                        }

                        // Pastikan value dibungkus <p>
                        if (!str_contains($value, '<p>') && !str_contains($value, '<div>')) {
                            $value = "<p>$value</p>";
                        }
                        if (!empty($key)) $dataSoal[$key] = $value;
                    }
                }

                // Validasi field wajib
                if (!isset($dataSoal['QUESTION']) || $extractor->isMeaningfullyEmpty($dataSoal['QUESTION'])) {
                    $validationErrors[] = "Soal ke-$soalNumber: QUESTION tidak boleh kosong.";
                }

                $answerMap = [
                    'OPTION1' => 'A',
                    'OPTION2' => 'B',
                    'OPTION3' => 'C',
                    'OPTION4' => 'D',
                    'OPTION5' => 'E',
                ];
                // Pastikan semua OPTION yang ada terisi, plus field wajib lain
                $presentOptions = array_filter(array_keys($answerMap), fn($opt) => isset($dataSoal[$opt]) && $extractor->isMeaningfullyEmpty($dataSoal[$opt]));
                $requiredFields = array_merge($presentOptions, ['ANSWER', 'EXPLANATION', 'LEVEL', 'SESI', 'STATUS', 'DIFFICULTY']);

                foreach ($requiredFields as $field) {
                    if (!isset($dataSoal[$field]) || $extractor->isMeaningfullyEmpty($dataSoal[$field])) {
                        $validationErrors[] = "Soal ke-$soalNumber: Field '$field' tidak boleh kosong.";
                    }
                }

                $getLevel = EnglishZoneLevel::where('level_name', trim(strip_tags($dataSoal['LEVEL'] ?? '')))->first();

                if (!$getLevel) {
                    $validationErrors[] = "Soal ke-$soalNumber: Level '".($dataSoal['LEVEL'] ?? '')."' tidak ditemukan.";
                }

                // Jika validasi gagal → simpan error & lanjut ke soal berikutnya
                if (!empty($validationErrors)) {
                    $allWordValidationErrors = array_merge($allWordValidationErrors, $validationErrors);
                    continue;
                }

                // Kalau validasi lolos → baru ganti placeholder gambar & bersihkan HTML
                foreach ($dataSoal as $k => $v) {
                    $v = $extractor->replaceImageSrc($v, $mediaImages);
                    $v = $extractor->cleanHtml($v); // Hilangkan tag sampah
                    $dataSoal[$k] = $v;
                }

                $dataSoal['LEVEL'] = $getLevel->id; // simpan instance Level untuk dipakai nanti
                $validSoalData[] = $dataSoal; // masukkan ke kumpulan soal valid
            }

            // Kalau ada error form atau word → hapus semua gambar yang sudah tersimpan
            if (!empty($formErrors) || !empty($allWordValidationErrors)) {
                foreach ($mediaImages as $img) {
                    if (!empty($img['public_url'] ?? '')) {
                        $imgPath = public_path($img['public_url']);
                        if (file_exists($imgPath)) {
                            unlink($imgPath);
                            Log::info("🗑 Hapus gambar karena validasi gagal: $imgPath");
                        }
                    }
                }
            }
        }

        // Return respon error validasi
        if (!empty($formErrors) || !empty($allWordValidationErrors)) {
            return response()->json([
                'status' => 'validation-error',
                'errors' => [
                    'form_errors' => $formErrors,
                    'word_validation_errors' => $allWordValidationErrors,
                ],
            ], 422);
        }

        // $validSoalData[] = $dataSoal;
        // Simpan soal ke database
        foreach ($validSoalData as $index => $dataSoal) {
            $answerKeyRaw = $dataSoal['ANSWER'] ?? '';
            $plainAnswerKey = strtoupper(trim(strip_tags($answerKeyRaw)));
            $finalAnswerKey = $answerMap[$plainAnswerKey] ?? null;
            if (!$finalAnswerKey) continue;

            // Cek duplikasi soal
            $existingQuestion = EnglishZoneQuestions::where('questions', $dataSoal['QUESTION'])->exists();

            // Tentukan status bank soal (Publish kalau sudah ada soal publish sebelumnya di sub_bab_id yang sama)
            $statusBankSoal = EnglishZoneQuestions::where('sub_bab_id', $request->sub_bab_id)
                ->where('status_bank_soal', 'Publish')
                ->exists() ? 'Publish' : 'Unpublish';

            // Simpan setiap opsi jawaban ke DB
            if (!$allWordValidationErrors) {
                if (!$existingQuestion) {
                    foreach ($answerMap as $optionField => $label) {
                        if (!empty($dataSoal[$optionField])) {
                            $createBankSoal = EnglishZoneQuestions::create([
                                'administrator_id' => $userId,
                                'questions' => $dataSoal['QUESTION'],
                                'options_key' => $label,
                                'options_value' => $dataSoal[$optionField],
                                'answer_key' => $finalAnswerKey,
                                'difficulty' => trim(strip_tags($dataSoal['DIFFICULTY'] ?? '')),
                                'explanation' => $dataSoal['EXPLANATION'] ?? '',
                                'level_id' => $dataSoal['LEVEL'], // ambil dari luar scope  foreach $table as $index
                                'session' => trim(strip_tags($dataSoal['SESI'] ?? '')),
                                'status_soal' => trim(strip_tags($dataSoal['STATUS'] ?? '')),
                                'status_bank_soal' => $statusBankSoal,
                            ]);
                        }
                    }
                }
            }
        }

        // Kirim event broadcast kalau soal berhasil ditambahkan
        if (isset($createBankSoal)) {
            broadcast(new BankSoalEnglishZoneUploaded($createBankSoal))->toOthers();
        }

        // Bersihkan file sementara
        @unlink($docxPath);
        @unlink($outputHtmlPath);

        // Return sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Bank Soal berhasil diupload.',
        ]);
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
            'video_materi' => 'required|url',
            'level_id' => 'required',
            'session' => [
                'required',
                Rule::unique('english_zone_materis', 'session')->where('level_id', $request->level_id),
            ],
        ], [
            'materi_vocabulary.required' => 'Harap upload materi vocabulary.',
            'materi_vocabulary.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_grammar.required' => 'Harap upload materi grammar.',
            'materi_grammar.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'video_materi.required' => 'Harap isi video materi.',
            'video_materi.url' => 'Harap isi link video yang valid.',
            'level_id.required' => 'Harap pilih level.',
            'session.required' => 'Harap pilih sesi.',
            'session.unique' => 'Sesi telah terdaftar pada level tersebut.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $materiVocabularyName = null;
        $materiGrammarName = null;

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

        $createMateri = EnglishZoneMateri::create([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
            'video_materi' => $request->video_materi,
            'link_zoom' => $request->link_zoom,
            'level_id' => $request->level_id,
            'session' => $request->input('session'),
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
        $dataMateri = EnglishZoneMateri::with(['EnglishZoneLevel', 'UserAccount'])
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
            'materi_vocabulary' => 'required',
            'materi_grammar' => 'required',
            'video_materi' => 'required',
        ], [
            'materi_vocabulary.required' => 'Harap isi materi vocabulary.',
            'materi_grammar.required' => 'Harap isi materi grammar.',
            'video_materi.required' => 'Harap isi video materi.',
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

        $dataMateri->update([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
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
        $materiList = EnglishZoneMateri::with('EnglishZoneLevel')
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

        // Ambil daftar Zoom berdasarkan level & mentor
        $zoomList = EnglishZoneZoom::where('mentor_id', $user->id)->get();

        // Buat peta sesi Zoom agar mudah diakses berdasarkan sesi
        $zoomMap = [];
        foreach ($zoomList as $zoom) {
            $zoomMap = [
                'link_zoom' => $zoom->link_zoom,
            ];
        }

        // Buat daftar materi lengkap dengan tanggal dan link Zoom
        $materiWithZoom = $materiList->map(function ($materi) use ($levelStartDate, $levelEndDate, $batchSchedules, $zoomMap) {
            $session = $materi->session; // nomor sesi materi
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


}