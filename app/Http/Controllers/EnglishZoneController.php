<?php

namespace App\Http\Controllers;

use App\Events\BankSoalEnglishZoneEditQuestion;
use App\Events\BankSoalEnglishZoneUploaded;
use App\Events\EnglishZoneBatchScheduleListener;
use App\Events\EnglishZoneLevelsListener;
use App\Events\EnglishZoneMateriListener;
use App\Events\EnglishZoneMentorScheduleListener;
use App\Events\EnglishZoneUnitListener;
use App\Events\EnglishZoneZoomListener;
use App\Events\EventEnglishZoneBatch;
use App\Models\EnglishZoneBatch;
use App\Models\EnglishZoneBatchSchedule;
use App\Models\EnglishZoneLevel;
use App\Models\EnglishZoneMateri;
use App\Models\EnglishZoneMentorSchedule;
use App\Models\EnglishZoneQuestions;
use App\Models\EnglishZoneUnit;
use App\Models\EnglishZoneStudentBatch;
use App\Models\EnglishZoneZoom;
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
            'level_name' => $request->level_name
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
            'managementUnit' => '/english-zone/management-levels/unit/:id',
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
            'level_name' => $request->level_name
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
    
    public function managementUnitView($id)
    {
        return view('Features.english-zone.management-unit.management-unit', compact('id'));
    }

    public function managementUnitStore(Request $request, $levelId)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'unit_name' => [
                'required',
                Rule::unique('english_zone_units', 'unit_name')
            ]
        ], [
            'unit_name.required' => 'Harap isi nama unit.',
            'unit_name.unique' => 'Unit telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $createUnit = EnglishZoneUnit::create([
            'administrator_id' => $user->id,
            'level_id' => $levelId,
            'unit_name' => $request->unit_name
        ]);

        broadcast(new EnglishZoneUnitListener('EnglishZoneUnit', 'create', $createUnit))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit berhasil ditambahkan.',
        ], 200);
    }

    // function paginate management level detail
    public function paginateManagementUnit($levelId)
    {
        $dataManagementUnit = EnglishZoneUnit::where('level_id', $levelId)->paginate(20);

        return response()->json([
            'data' => $dataManagementUnit->items(),
            'links' => (string) $dataManagementUnit->links(),
        ]);
    }

    public function managementUnitEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'unit_name' => [
                'required',
                Rule::unique('english_zone_units', 'unit_name')
            ],
        ], [
            'unit_name.required' => 'Harap isi nama unit.',
            'unit_name.unique' => 'Nama unit telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataManagementUnit = EnglishZoneUnit::findOrFail($id);

        $dataManagementUnit->update([
            'administrator_id' => $user->id,
            'unit_name' => $request->unit_name
        ]);

        broadcast(new EnglishZoneUnitListener('EnglishZoneUnit', 'update', $dataManagementUnit))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit berhasil diubah.',
        ]);
    }

    // function management level delete
    public function managementUnitDelete($id)
    {
        $dataManagementUnit = EnglishZoneUnit::findOrFail($id);

        $deletedData = $dataManagementUnit->toArray();

        broadcast(new EnglishZoneUnitListener('EnglishZoneUnit', 'delete', $deletedData))->toOthers();

        $dataManagementUnit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit berhasil dihapus.',
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
        $editQuestion = EnglishZoneQuestions::find($id);

        if (!$editQuestion) {
            return redirect()->route('EZ.bankSoal.detail.view', [$levelId]);
        }

        // Mengambil data soal yang punya pertanyaan (questions) yang sama, lalu dikelompokkan berdasarkan isi questions-nya
        $dataSoal = EnglishZoneQuestions::where('questions', $editQuestion->questions)->get()->groupBy('questions');

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
                $requiredFields = array_merge($presentOptions, ['ANSWER', 'EXPLANATION', 'LEVEL', 'UNIT', 'SESI', 'STATUS', 'DIFFICULTY']);

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
                                'unit' => trim(strip_tags($dataSoal['UNIT'] ?? '')),
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
            'max_capacity' => 'required',
        ], [
            'batch_name.required' => 'Harap pilih batch.',
            'batch_name.unique' => 'Batch telah terdaftar.',
            'start_day.required' => 'Harap pilih hari.',
            'start_month.required' => 'Harap pilih bulan.',
            'max_capacity.required' => 'Harap pilih kapasitas.',
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
            'max_capacity' => $request->max_capacity,
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
            'max_capacity' => 'required',
        ], [
            'batch_name.required' => 'Harap pilih batch.',
            'batch_name.unique' => 'Batch telah terdaftar.',
            'start_day.required' => 'Harap pilih hari.',
            'start_month.required' => 'Harap pilih bulan.',
            'max_capacity.required' => 'Harap pilih kapasitas.',
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
            'max_capacity' => $request->max_capacity,
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
            'lesson_plan' => 'required|max:10000',
            'level_id' => 'required',
            'unit_id' => 'required',
            'session' => [
                'required',
                Rule::unique('english_zone_materis', 'session')->where('level_id', $request->level_id)->where('unit_id', $request->unit_id),
            ],
        ], [
            'materi_vocabulary.required' => 'Harap upload materi vocabulary.',
            'materi_vocabulary.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'materi_grammar.required' => 'Harap upload materi grammar.',
            'materi_grammar.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'video_materi.required' => 'Harap isi video materi.',
            'video_materi.url' => 'Harap isi link video yang valid.',
            'lesson_plan.required' => 'Harap upload lesson plan.',
            'lesson_plan.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
            'level_id.required' => 'Harap pilih level.',
            'unit_id.required' => 'Harap pilih unit.',
            'session.required' => 'Harap pilih sesi.',
            'session.unique' => 'Sesi telah terdaftar pada level dan unit tersebut.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $materiVocabularyName = null;
        $materiGrammarName = null;
        $lessonPlanName = null;

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

        // Upload lesson_plan
        if ($request->hasFile('lesson_plan')) {
            $lessonPlanName = $saveFileByHash($request->file('lesson_plan'), 'english-zone-materi');
        }

        $createMateri = EnglishZoneMateri::create([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
            'video_materi' => $request->video_materi,
            'link_zoom' => $request->link_zoom,
            'lesson_plan' => $lessonPlanName,
            'level_id' => $request->level_id,
            'unit_id' => $request->unit_id,
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
        $dataMateri = EnglishZoneMateri::with(['EnglishZoneLevel', 'EnglishZoneUnit', 'UserAccount'])
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

        $dataMateri = $dataMateri->sortBy('session')->sortBy(function ($item) {
            // Ambil angka dari unit_name: "Unit 1 - ..." → 1
            if (preg_match('/Unit (\d+)/i', $item->EnglishZoneUnit->unit_name, $matches)) {
                return (int) $matches[1];
            }
            return 999; // Kalau gagal, taruh di akhir
        });

        return response()->json([
            'data' => $dataMateri->values(),
        ]);
    }

    // DROPDOWN BERTINGKAT UNIT BY LEVEL
    public function getUnitByLevel($levelId)
    {
        $unit = EnglishZoneUnit::where('level_id', $levelId)->get();
        return response()->json($unit);
    }

    // function management materi edit
    public function managementMateriEdit(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'materi_vocabulary' => 'required',
            'materi_grammar' => 'required',
            'video_materi' => 'required',
            'lesson_plan' => 'required',
        ], [
            'materi_vocabulary.required' => 'Harap isi materi vocabulary.',
            'materi_grammar.required' => 'Harap isi materi grammar.',
            'video_materi.required' => 'Harap isi video materi.',
            'lesson_plan.required' => 'Harap isi lesson plan.',
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
        $lessonPlanName = null;

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
        if ($request->hasFile('lesson_plan')) {
            $lessonPlanName = $saveFileByHash($request->file('lesson_plan'), 'english-zone-materi');
        }

        $dataMateri->update([
            'administrator_id' => $user->id,
            'materi_vocabulary' => $materiVocabularyName,
            'materi_grammar' => $materiGrammarName,
            'video_materi' => $request->video_materi,
            'lesson_plan' => $lessonPlanName,
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

    // function dropdown bertingkat batch schedule groups
    public function dropdownBatchScheduleGroup($batch_id)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->get();

        $batchScheduleGroups = $schedules->groupBy('batch_schedule_group')->map(function ($group) {
            return [
                'batch_schedule_group' => $group->pluck('batch_schedule_group')->unique(),
                'days' => $group->pluck('day_of_week')->unique(),
            ];
        })->values();

        return response()->json($batchScheduleGroups);
    }

    // function dropdown bertingkat days
    public function dropdownDays($batch_id, $batch_schedule_group)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->where('batch_schedule_group', $batch_schedule_group)->get();

        $days = $schedules->pluck('day_of_week')->unique()->map(function ($day) {
            return ['day' => $day];
        })->values();

        return response()->json($days);
    }

    // function dropdown bertingkat hours
    public function dropdownHours($batch_id, $batch_schedule_group, $day)
    {
        $schedules = EnglishZoneBatchSchedule::where('batch_id', $batch_id)->where('batch_schedule_group', $batch_schedule_group)
            ->where('day_of_week', $day)
            ->get();

        // group by jam unik
        $hours = $schedules->groupBy(function ($item) {
            return $item->start_time . '-' . $item->end_time;
        })->map(function ($items) {
            return [
                'ids' => $items->pluck('id')->toArray(),
                'time' => $items->first()->start_time . ' - ' . $items->first()->end_time,
                'schedule_time_group' => $items->first()->schedule_time_group,
            ];
        })->values();

        return response()->json($hours);
    }

    // function dropdown bertingkat mentors
    public function dropdownMentors($batch_id, $batch_schedule_group, $day, $schedule_time_group)
    {
        $getMentorStatus = MentorFeatureStatus::where('feature_id', 3)->where('status_mentor', 'aktif')->pluck('mentor_id');
        
        $mentors = EnglishZoneMentorSchedule::with('UserAccount.MentorProfiles')->where('status_schedule', 'aktif')->whereIn('mentor_id', $getMentorStatus)
        ->whereHas('EnglishZoneBatchSchedule', function($q) use ($batch_id, $batch_schedule_group, $day, $schedule_time_group) {
                $q->where('batch_id', $batch_id)->where('batch_schedule_group', $batch_schedule_group)
                ->where('day_of_week', $day)
                ->where('schedule_time_group', $schedule_time_group);
            })
            ->get();

            $grouped = $mentors->groupBy('mentor_id');

        return response()->json($grouped);
    }

    // function pagiante management zoom
    public function paginateManagementZoom(Request $request)
    {
        $getZoom = EnglishZoneZoom::with(['Administrator', 'Mentor.MentorProfiles', 'EnglishZoneBatchSchedule', 'EnglishZoneBatchSchedule.EnglishZoneBatch', 
        'EnglishZoneLevel', 'EnglishZoneUnit']);

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
            'batch_id' => 'required',
            'batch_schedule_group_id' => 'required',
            'days_id' => 'required',
            'hours_id' => 'required',
            'mentor_id' => 'required',
            'level_id' => 'required',
            'unit_id' => 'required',
            'session' => [
                'required',
                Rule::unique('english_zone_zooms', 'session')->where('level_id', $request->level_id)->where('unit_id', $request->unit_id)
                ->where('mentor_id', $request->mentor_id),
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
            'batch_id.required' => 'Harap pilih batch.',
            'batch_schedule_group_id.required' => 'Harap pilih batch schedule group.',
            'days_id.required' => 'Harap pilih hari.',
            'hours_id.required' => 'Harap pilih jam.',
            'hours_id.unique' => 'Jam telah terdaftar pada mentor ini.',
            'mentor_id.required' => 'Harap pilih mentor.',
            'level_id.required' => 'Harap pilih level.',
            'unit_id.required' => 'Harap pilih unit.',
            'session.required' => 'Harap pilih sesi.',
            'session.unique' => 'Sesi telah terdaftar pada level, unit, dan mentor tersebut.',
            'link_zoom.required' => 'Harap isi link zoom.',
            'link_zoom.url' => 'Format link tidak sesuai.',
            'link_zoom.unique' => 'Link Zoom telah terdaftar.',
            'meeting_id.required' => 'Harap isi meeting id.',
            'meeting_id.unique' => 'Meeting ID telah terdaftar.',
            'zoom_passcode.required' => 'Harap isi passcode.',
            'zoom_passcode.unique' => 'Passcode telah terdaftar.',
        ]);

        // memeriksa apakah schedule tela terdaftar pada mentor yang di request atau belum
        $check = EnglishZoneZoom::where('batch_schedule_id', $request->batch_schedule_id)->where('mentor_id', $request->mentor_id)->exists();

        if ($validator->fails() || $check) {
            $errors = $validator->errors()->toArray();

            if ($check) {
                $errors['batch_schedule_id'] = ['Schedule zoom pada mentor ini telah terdaftar, silahkan pilih mentor atau schedule yang lain.'];
            }

            return response()->json([
                'status' => 'error',
                'errors' => $errors,
            ], 422);
        }

        $createZoom = EnglishZoneZoom::create([
            'administrator_id' => $user->id,
            'batch_schedule_id' => $request->batch_schedule_id,
            'mentor_id' => $request->mentor_id,
            'level_id' => $request->level_id,
            'unit_id' => $request->unit_id,
            'session' => $request->input('session'),
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

    public function dropdownHoursPurchase($batch_id, $group_id)
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

        return response()->json($hours);
    }

    public function dropdownMentorsPurchase($batch_id, $group_id, $schedule_time_group)
    {
        $getMentorStatus = MentorFeatureStatus::where('feature_id', 3)->where('status_mentor', 'aktif')->pluck('mentor_id');
        
        $mentors = EnglishZoneMentorSchedule::with('UserAccount.MentorProfiles')->where('status_schedule', 'aktif')->whereIn('mentor_id', $getMentorStatus)
        ->whereHas('EnglishZoneBatchSchedule', function($q) use ($batch_id, $group_id, $schedule_time_group) {
            $q->where('batch_id', $batch_id)
            ->where('batch_schedule_group', $group_id)
            ->where('schedule_time_group', $schedule_time_group);
        })->get();

        $grouped = $mentors->groupBy('mentor_id');

        // ambil count student per mentor sesuai schedule
        $studentCounts = EnglishZoneStudentBatch::whereHas('EnglishZoneBatchSchedule', function($q) use ($batch_id, $group_id, $schedule_time_group) {
                $q->where('batch_id', $batch_id)
                ->where('batch_schedule_group', $group_id)
                ->where('schedule_time_group', $schedule_time_group);
        })->get()->groupBy('mentor_id')->map(function ($group) {
            return $group->pluck('student_id')->unique()->count();
        });

        return response()->json([
            'data' => $grouped,
            'getStudentBatch' => $studentCounts
        ]);
    }
}