<?php

namespace App\Services\SoalPembahasan;
use App\Events\BankSoalPembahasanUploaded;
use App\Models\SoalPembahasanQuestions;
use App\Services\DocxImageExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BankSoalWordImportService
{
    public function bankSoalImportService(Request $request)
    {
        // Buat instance dari class DocxImageExtractor yang berfungsi untuk ekstrak gambar + HTML styled dari file Word
        $extractor = new DocxImageExtractor();

        // Validasi input form dari frontend (wajib diisi)
        $validator = Validator::make($request->all(), [
            // File wajib ada, format .docx, max 10 MB
            'bulkUpload-soal-pembahasan' => 'required|file|mimes:docx|max:10000',
            'kurikulum_id' => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'bab_id' => 'required',
            'sub_bab_id' => 'required',
        ], [
            // Pesan error custom
            'bulkUpload-soal-pembahasan.required' => 'Harap upload soal pembahasan!',
            'bulkUpload-soal-pembahasan.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
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

        $validSoalData[] = $dataSoal;
        // Simpan soal ke database
        foreach ($validSoalData as $dataSoal) {
            $answerKeyRaw = $dataSoal['ANSWER'] ?? '';
            $plainAnswerKey = strtoupper(trim(strip_tags($answerKeyRaw)));
            $finalAnswerKey = $answerMap[$plainAnswerKey] ?? null;
            if (!$finalAnswerKey) continue;

            // Cek duplikasi soal
            $existingQuestion = SoalPembahasanQuestions::where('questions', $dataSoal['QUESTION'])->exists();

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
            broadcast(new BankSoalPembahasanUploaded($createBankSoal))->toOthers();
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
}