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

    // kalo uda clear semua, nanti ini dihapus
    // public function bankSoalStore(Request $request)
    // {
    //     $extractor = new DocxImageExtractor();

    //     $validator = Validator::make($request->all(), [
    //         'bulkUpload-soal-pembahasan' => 'required|file|mimes:docx|max:10240',
    //         'kurikulum_id' => 'required',
    //         'kelas_id' => 'required',
    //         'mapel_id' => 'required',
    //         'bab_id' => 'required',
    //         'sub_bab_id' => 'required',
    //     ], [
    //         'bulkUpload-soal-pembahasan.required' => 'Harap upload soal pembahasan!',
    //         'kurikulum_id.required' => 'Harap pilih kurikulum!',
    //         'kelas_id.required' => 'Harap pilih kelas!',
    //         'mapel_id.required' => 'Harap pilih mapel!',
    //         'bab_id.required' => 'Harap pilih bab!',
    //         'sub_bab_id.required' => 'Harap pilih sub bab!',
    //     ]);

    //     $formErrors = $validator->fails() ? $validator->errors()->toArray() : [];
    //     $allWordValidationErrors = [];
    //     $userId = Auth::id();
    //     $uploadedFile = $request->file('bulkUpload-soal-pembahasan');

    //     if ($uploadedFile) {
    //         $docxPath = storage_path('app/tmp_soal.docx');
    //         $outputHtmlPath = storage_path('app/converted_soal.html');
    //         $uploadedFile->move(storage_path('app'), 'tmp_soal.docx');

    //         $process = new Process(['pandoc', $docxPath, '-f', 'docx', '-t', 'html', '--mathml', '-o', $outputHtmlPath]);
    //         $process->run();
    //         if (!$process->isSuccessful()) {
    //             throw new ProcessFailedException($process);
    //         }

    //         $styledData = [];
    //         $mediaImages = [];
    //         $validSoalData = [];

    //         $extractor->extractImagesFromDocxFile($docxPath, $mediaImages);

    //         $forcePandocMode = false;
    //         if ($extractor->docxHasEquation($docxPath) || $extractor->docxHasList($docxPath)) {
    //             Log::info('⚠️ Deteksi equation OMML, skip PhpWord dan pakai hasil Pandoc sepenuhnya.');
    //             Log::info("⚠️ Deteksi " . ($extractor->docxHasEquation($docxPath) ? "equation " : "") . ($extractor->docxHasList($docxPath) ? "list " : "") . "- gunakan Pandoc.");
    //             $styledData = [];
    //             $forcePandocMode = true;
    //         } else {
    //             try {
    //                 $phpWord = IOFactory::load($docxPath);
    //                 $styledData = $extractor->extractStyledTableData($phpWord, $mediaImages);
    //             } catch (\Throwable $e) {
    //                 Log::warning('⚠️ Gagal load PhpWord atau extractStyledTableData: ' . $e->getMessage());
    //                 $styledData = [];
    //             }
    //         }

    //         $htmlContent = file_get_contents($outputHtmlPath);
    //         Log::debug("FULL raw Pandoc HTML content (truncated): " . substr($htmlContent, 0, 2000));

    //         libxml_use_internal_errors(true);
    //         $dom = new \DOMDocument();
    //         $htmlForLoad = mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8');
    //         $dom->loadHTML($htmlForLoad, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
    //         libxml_clear_errors();

    //         $tables = $dom->getElementsByTagName('table');
    //         Log::debug("📄 Jumlah tabel hasil Pandoc: " . $tables->length);

    //         $pandocTableValues = [];
    //         foreach ($tables as $tIndex => $t) {
    //             $rowsForFallback = $t->getElementsByTagName('tr');
    //             foreach ($rowsForFallback as $row) {
    //                 if (!$row instanceof \DOMElement) continue;

    //                 $cells = [];
    //                 foreach ($row->childNodes as $child) {
    //                     if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['td', 'th'])) {
    //                         $cells[] = $child;
    //                     }
    //                 }
    //                 if (count($cells) < 2) continue;

    //                 $keyHtml = '';
    //                 foreach ($cells[0]->childNodes as $child) {
    //                     $keyHtml .= $dom->saveHTML($child);
    //                 }
    //                 $normalizedKey = strtoupper(trim($extractor->normalizeTextContent($keyHtml)));
    //                 $key = preg_replace('/[\s\xA0]+/u', '', $normalizedKey);
    //                 if ($key === '') $key = 'QUESTION';

    //                 $valueHtml = '';
    //                 foreach ($cells[1]->childNodes as $child) {
    //                     $valueHtml .= $dom->saveHTML($child);
    //                 }

    //                 $pandocTableValues[$tIndex][$key] = $valueHtml;
    //             }
    //         }

    //         foreach ($tables as $index => $table) {
    //             if (!$table instanceof \DOMElement) continue;

    //             $rows = $table->getElementsByTagName('tr');
    //             $dataSoal = [];
    //             $validationErrors = [];
    //             $soalNumber = $index + 1;

    //             if (!$forcePandocMode && isset($styledData[$index]) && is_array($styledData[$index]) && count($styledData[$index]) > 0) {
    //                 $dataSoal = $styledData[$index];
    //                 Log::info("Soal ke-$soalNumber: memakai styledData + fallback Pandoc untuk key/value.");
    //                 foreach ($dataSoal as $k => $htmlValue) {
    //                     $styledHtml = $htmlValue;
    //                     $pandocHtmlRaw = $pandocTableValues[$index][$k] ?? '';
    //                     $pandocHtml = $pandocHtmlRaw;
    //                     $dataSoal[$k] = $extractor->combineStyledAndPandoc($styledHtml, $pandocHtml);
    //                 }
    //             } else {
    //                 foreach ($rows as $row) {
    //                     if (!$row instanceof \DOMElement) continue;

    //                     $cells = [];
    //                     foreach ($row->childNodes as $child) {
    //                         if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['td', 'th'])) {
    //                             $cells[] = $child;
    //                         }
    //                     }
    //                     if (count($cells) < 2) continue;

    //                     $innerHtml = '';
    //                     foreach ($cells[0]->childNodes as $child) {
    //                         $innerHtml .= $dom->saveHTML($child);
    //                     }
    //                     $normalizedText = $extractor->normalizeTextContent($innerHtml);
    //                     $rawHtmlKey = strtoupper(trim($normalizedText));
    //                     $key = preg_replace('/[\s\xA0]+/u', '', $rawHtmlKey);
    //                     if (empty($key) && empty($dataSoal['QUESTION'])) $key = 'QUESTION';

    //                     $rawHtmlValue = '';
    //                     foreach ($cells[1]->childNodes as $child) {
    //                         $rawHtmlValue .= $dom->saveHTML($child);
    //                     }

    //                     $pandocValue = $rawHtmlValue;

    //                     $styledValue = $styledData[$index][$key] ?? '';
    //                     $plainPandoc = $extractor->normalizeTextContent($pandocValue);
    //                     $plainStyled = $extractor->normalizeTextContent($styledValue);

    //                     if (empty($styledValue)) {
    //                         $value = $pandocValue;
    //                     } elseif ($plainPandoc === $plainStyled) {
    //                         $value = $styledValue;
    //                     } elseif (str_contains($pandocValue, '<math') || str_contains($pandocValue, '<img') || str_contains($pandocValue, '<ul') || str_contains($pandocValue, '<ol')) {
    //                         $value = $extractor->mergeStyledAndPandocHtml($pandocValue, $styledValue, $mediaImages);
    //                     } else {
    //                         $value = $styledValue;
    //                     }

    //                     if (!str_contains($value, '<p>') && !str_contains($value, '<div>')) {
    //                         $value = "<p>$value</p>";
    //                     }
    //                     if (!empty($key)) $dataSoal[$key] = $value;
    //                 }
    //             }

    //             Log::debug("Final dataSoal for soal #$soalNumber:", $dataSoal);

    //             if (!isset($dataSoal['QUESTION']) || $extractor->isMeaningfullyEmpty($dataSoal['QUESTION'])) {
    //                 $validationErrors[] = "Soal ke-$soalNumber: QUESTION tidak boleh kosong.";
    //             }

    //             $answerMap = [
    //                 'OPTION1' => 'A',
    //                 'OPTION2' => 'B',
    //                 'OPTION3' => 'C',
    //                 'OPTION4' => 'D',
    //                 'OPTION5' => 'E',
    //             ];
    //             $presentOptions = array_filter(array_keys($answerMap), fn($opt) => isset($dataSoal[$opt]) && $extractor->isMeaningfullyEmpty($dataSoal[$opt]));
    //             $requiredFields = array_merge($presentOptions, ['ANSWER', 'EXPLANATION', 'SKILLTAG', 'DIFFICULTY', 'STATUS', 'TYPE']);

    //             foreach ($requiredFields as $field) {
    //                 if (!isset($dataSoal[$field]) || $extractor->isMeaningfullyEmpty($dataSoal[$field])) {
    //                     $validationErrors[] = "Soal ke-$soalNumber: Field '$field' tidak boleh kosong.";
    //                 }
    //             }

    //             if (!empty($validationErrors)) {
    //                 $allWordValidationErrors = array_merge($allWordValidationErrors, $validationErrors);
    //                 continue;
    //             }

    //             // Replace image src hanya setelah validasi berhasil
    //             foreach ($dataSoal as $k => $v) {
    //                 $dataSoal[$k] = $extractor->replaceImageSrc($v, $mediaImages);
    //             }

    //             $validSoalData[] = $dataSoal;
    //         }

    //         if (!empty($formErrors) || !empty($allWordValidationErrors)) {
    //             foreach ($mediaImages as $img) {
    //                 if (!empty($img['public_url'] ?? '')) {
    //                     $imgPath = public_path($img['public_url']);
    //                     if (file_exists($imgPath)) {
    //                         unlink($imgPath);
    //                         Log::info("🗑 Hapus gambar karena validasi gagal: $imgPath");
    //                     }
    //                 }
    //             }

    //             return response()->json([
    //                 'status' => 'validation-error',
    //                 'errors' => [
    //                     'form_errors' => $formErrors,
    //                     'word_validation_errors' => $allWordValidationErrors,
    //                 ],
    //             ], 422);
    //         }

    //         foreach ($validSoalData as $dataSoal) {
    //             $answerKeyRaw = $dataSoal['ANSWER'] ?? '';
    //             $plainAnswerKey = strtoupper(trim(strip_tags($answerKeyRaw)));
    //             $finalAnswerKey = $answerMap[$plainAnswerKey] ?? null;
    //             if (!$finalAnswerKey) continue;

    //             $existingQuestion = SoalPembahasanQuestions::where('questions', $dataSoal['QUESTION'])->exists();

    //             $statusBankSoal = SoalPembahasanQuestions::where('sub_bab_id', $request->sub_bab_id)
    //                 ->where('status_bank_soal', 'Publish')
    //                 ->exists() ? 'Publish' : 'Unpublish';

    //             foreach ($answerMap as $optionField => $label) {
    //                 if (!$existingQuestion) {
    //                     if (!empty($dataSoal[$optionField])) {
    //                         $createBankSoal = SoalPembahasanQuestions::create([
    //                             'administrator_id' => $userId,
    //                             'kurikulum_id' => $request->kurikulum_id,
    //                             'kelas_id' => $request->kelas_id,
    //                             'mapel_id' => $request->mapel_id,
    //                             'bab_id' => $request->bab_id,
    //                             'sub_bab_id' => $request->sub_bab_id,
    //                             'questions' => $dataSoal['QUESTION'],
    //                             'options_key' => $label,
    //                             'options_value' => $dataSoal[$optionField],
    //                             'answer_key' => $finalAnswerKey,
    //                             'skilltag' => trim(strip_tags($dataSoal['SKILLTAG'] ?? '')),
    //                             'difficulty' => trim(strip_tags($dataSoal['DIFFICULTY'] ?? '')),
    //                             'explanation' => $dataSoal['EXPLANATION'] ?? '',
    //                             'status_soal' => trim(strip_tags($dataSoal['STATUS'] ?? '')),
    //                             'tipe_soal' => trim(strip_tags($dataSoal['TYPE'] ?? '')),
    //                             'status_bank_soal' => $statusBankSoal,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if (isset($createBankSoal)) {
    //         broadcast(new BankSoalListener($createBankSoal))->toOthers();
    //     }

    //     @unlink($docxPath);
    //     @unlink($outputHtmlPath);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Bank Soal berhasil diupload.',
    //     ]);
    // }


    public function bankSoalStore(Request $request)
    {
        // Buat instance dari class DocxImageExtractor yang berfungsi untuk ekstrak gambar + HTML styled dari file Word
        $extractor = new DocxImageExtractor();

        // Validasi input form dari frontend (wajib diisi)
        $validator = Validator::make($request->all(), [
            // File wajib ada, format .docx, max 10 MB
            'bulkUpload-soal-pembahasan' => 'required|file|mimes:docx|max:10240',
            'kurikulum_id' => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'bab_id' => 'required',
            'sub_bab_id' => 'required',
        ], [
            // Pesan error custom
            'bulkUpload-soal-pembahasan.required' => 'Harap upload soal pembahasan!',
            'kurikulum_id.required' => 'Harap pilih kurikulum!',
            'kelas_id.required' => 'Harap pilih kelas!',
            'mapel_id.required' => 'Harap pilih mapel!',
            'bab_id.required' => 'Harap pilih bab!',
            'sub_bab_id.required' => 'Harap pilih sub bab!',
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
                $requiredFields = array_merge($presentOptions, ['ANSWER', 'EXPLANATION', 'SKILLTAG', 'DIFFICULTY', 'STATUS', 'TYPE']);

                foreach ($requiredFields as $field) {
                    if (!isset($dataSoal[$field]) || $extractor->isMeaningfullyEmpty($dataSoal[$field])) {
                        $validationErrors[] = "Soal ke-$soalNumber: Field '$field' tidak boleh kosong.";
                    }
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
                $validSoalData[] = $dataSoal;
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

                // Return respon error validasi
                return response()->json([
                    'status' => 'validation-error',
                    'errors' => [
                        'form_errors' => $formErrors,
                        'word_validation_errors' => $allWordValidationErrors,
                    ],
                ], 422);
            }

            // Simpan soal ke database
            foreach ($validSoalData as $dataSoal) {
                $answerKeyRaw = $dataSoal['ANSWER'] ?? '';
                $plainAnswerKey = strtoupper(trim(strip_tags($answerKeyRaw)));
                $finalAnswerKey = $answerMap[$plainAnswerKey] ?? null;
                if (!$finalAnswerKey) continue;

                // Cek duplikasi soal
                $existingQuestion = SoalPembahasanQuestions::where('questions', $dataSoal['QUESTION'])->exists();
                if ($existingQuestion) continue;

                // Tentukan status bank soal (Publish kalau sudah ada soal publish sebelumnya di sub_bab_id yang sama)
                $statusBankSoal = SoalPembahasanQuestions::where('sub_bab_id', $request->sub_bab_id)
                    ->where('status_bank_soal', 'Publish')
                    ->exists() ? 'Publish' : 'Unpublish';

                // Simpan setiap opsi jawaban ke DB
                if (!$allWordValidationErrors) {
                    if (!$existingQuestion) {
                        foreach ($answerMap as $optionField => $label) {
                            if (!empty($dataSoal[$optionField])) {
                                $createBankSoal = SoalPembahasanQuestions::create([
                                    'administrator_id' => $userId,
                                    'kurikulum_id' => $request->kurikulum_id,
                                    'kelas_id' => $request->kelas_id,
                                    'mapel_id' => $request->mapel_id,
                                    'bab_id' => $request->bab_id,
                                    'sub_bab_id' => $request->sub_bab_id,
                                    'questions' => $dataSoal['QUESTION'],
                                    'options_key' => $label,
                                    'options_value' => $dataSoal[$optionField],
                                    'answer_key' => $finalAnswerKey,
                                    'skilltag' => trim(strip_tags($dataSoal['SKILLTAG'] ?? '')),
                                    'difficulty' => trim(strip_tags($dataSoal['DIFFICULTY'] ?? '')),
                                    'explanation' => $dataSoal['EXPLANATION'] ?? '',
                                    'status_soal' => trim(strip_tags($dataSoal['STATUS'] ?? '')),
                                    'tipe_soal' => trim(strip_tags($dataSoal['TYPE'] ?? '')),
                                    'status_bank_soal' => $statusBankSoal,
                                ]);
                            }
                        }
                    }
                }
            }

            // Kirim event broadcast kalau soal berhasil ditambahkan
            if (isset($createBankSoal)) {
                broadcast(new BankSoalListener($createBankSoal))->toOthers();
            }
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