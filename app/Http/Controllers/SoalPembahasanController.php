<?php

namespace App\Http\Controllers;

use App\Events\BankSoalEditQuestion;
use App\Events\BankSoalListener;
use App\Models\Kurikulum;
use App\Models\SoalPembahasanQuestions;
use App\Services\DocxImageExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\TextBreak;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
            'status_soal' => 'required|in:Publish,Unpublish'
        ]);

        $dataBankSoal = SoalPembahasanQuestions::where('sub_bab_id', $subBabId)->get();

        foreach ($dataBankSoal as $soal) {
            $soal->update([
                'status_soal' => $request->status_soal
            ]);
        }

        broadcast(new BankSoalEditQuestion($dataBankSoal))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $dataBankSoal
        ]);
    }

    // function bankSoal detail view
    public function bankSoalDetail($subBab, $subBabId)
    {
        return view('Features.soal-pembahasan.bank-soal.bank-soal-detail', compact('subBab', 'subBabId'));
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
                    foreach ($answerMap as $optionField => $label) {
                        if (!empty($dataSoal[$optionField])) {
                            $createBankSoal = SoalPembahasanQuestions::firstOrCreate([
                                'questions' => $dataSoal['QUESTION'],
                                'options_key' => $label,
                            ], [
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
                            ]);
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


    // FUNCTION PRACTICE
    public function practice()
    {
        return view('Features.soal-pembahasan.assessment.practice.soal-pembahasan-practice');
    }

    // FUNCTION EXAM

}
