<?php

namespace App\Services\EnglishZone;
use App\Events\EnglishZonePassageImportListener;
use App\Models\EnglishZoneLevel;
use App\Models\EnglishZonePassage;
use Illuminate\Http\Request;
use App\Services\DocxImageExtractor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PassageWordImportService
{
    public function passageWordImportService(Request $request, $id = null)
    {
        // Buat instance dari class DocxImageExtractor yang berfungsi untuk ekstrak gambar + HTML styled dari file Word
        $extractor = new DocxImageExtractor('english_zone');

        // Validasi input form dari frontend (wajib diisi)
        $validator = Validator::make($request->all(), [
            // File wajib ada, format .docx, max 100 MB
            'bulkUpload-management-passage' => 'required|file|mimes:docx|max:100000',
        ], [
            // Pesan error custom
            'bulkUpload-management-passage.required' => 'File tidak boleh kosong.',
            'bulkUpload-management-passage.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
        ]);

        // Simpan error validasi form (tidak langsung return, biar bisa digabung dengan error validasi isi file Word)
        $formErrors = $validator->fails() ? $validator->errors()->toArray() : [];

        // Array untuk menampung semua error validasi dari isi tabel di file Word
        $allWordValidationErrors = [];

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil file .docx yang diupload
        $uploadedFile = $request->file('bulkUpload-management-passage');

        // Mengecek apakah ada file yang diupload
        if ($uploadedFile) {
            // Tentukan path sementara untuk file docx dan file html hasil konversi
            $docxPath = storage_path('app/tmp_passage.docx');
            $outputHtmlPath = storage_path('app/converted_passage.html');

            // Pindahkan file upload ke storage/app sebagai tmp_passage.docx
            $uploadedFile->move(storage_path('app'), 'tmp_passage.docx');

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
            $validPassage = []; // passage yang lolos validasi

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

                    // Ambil value (kolom 2)
                    $valueHtml = '';
                    foreach ($cells[1]->childNodes as $child) {
                        $valueHtml .= $dom->saveHTML($child);
                    }

                    // Simpan ke array berdasarkan index tabel
                    $pandocTableValues[$tIndex][$key] = $valueHtml;
                }
            }

            // Proses setiap tabel (setiap tabel = 1 passage)
            foreach ($tables as $index => $table) {
                if (!$table instanceof \DOMElement) continue;
                $rows = $table->getElementsByTagName('tr');
                $dataPassage = [];
                $validationErrors = [];
                $passageNumber = $index + 1;

                // Kalau styledData tersedia → gabungkan dengan hasil Pandoc
                if (!$forcePandocMode && isset($styledData[$index]) && is_array($styledData[$index]) && count($styledData[$index]) > 0) {
                    $dataPassage = $styledData[$index];
                    foreach ($dataPassage as $k => $htmlValue) {
                        $styledHtml = $htmlValue;
                        $pandocHtmlRaw = $pandocTableValues[$index][$k] ?? '';
                        $pandocHtml = $pandocHtmlRaw;
                        // Gabungkan styled HTML dan Pandoc HTML
                        $dataPassage[$k] = $extractor->combineStyledAndPandoc($styledHtml, $pandocHtml);
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
                        if (!empty($key)) $dataPassage[$key] = $value;
                    }
                }

                $type = strtoupper(trim($extractor->normalizeTextContent($dataPassage['TYPE'] ?? '')));


                if ($type === 'LISTENING PRACTICE TEST' || $type === 'LISTENING EXAM TEST') {
                    $requiredFields = ['LEVEL', 'AUDIO_SCRIPT', 'TYPE'];

                    $validator = Validator::make($request->all(), [
                        // File wajib ada, format .mp3, max 100 MB
                        'audio_file' => 'required|file|mimes:mp3|max:100000',
                    ], [
                        // Pesan error custom
                        'audio_file.required' => 'File tidak boleh kosong.',
                        'bulkUpload-management-passage.max' => 'Ukuran file melebihi kapasitas yang ditentukan.',
                    ]);

                    // Simpan error validasi form (tidak langsung return, biar bisa digabung dengan error validasi isi file Word)
                    $formErrors = $validator->fails() ? $validator->errors()->toArray() : [];

                    // Array untuk menampung semua error validasi dari isi tabel di file Word
                    $allWordValidationErrors = [];
                } else if ($type === 'WRITING PRACTICE TEST' || $type === 'WRITING EXAM TEST') {
                    $requiredFields = ['LEVEL', 'PASSAGE_CONTENT', 'EXAMPLE_ANSWER', 'TYPE'];
                } else {
                    $requiredFields = ['LEVEL', 'PASSAGE_CONTENT', 'TYPE'];
                }


                foreach ($requiredFields as $field) {
                    if (!isset($dataPassage[$field]) || $extractor->isMeaningfullyEmpty($dataPassage[$field])) {
                        $validationErrors[] = "Passage ke-$passageNumber: Field '$field' tidak boleh kosong.";
                    }
                }

                $getLevel = EnglishZoneLevel::where('level_name', trim(strip_tags($dataPassage['LEVEL'] ?? '')))->first();

                if (!$getLevel) {
                    $validationErrors[] = "Passage ke-$passageNumber: Level tidak ditemukan.";
                }

                // Jika validasi gagal → simpan error & lanjut ke passage berikutnya
                if (!empty($validationErrors)) {
                    $allWordValidationErrors = array_merge($allWordValidationErrors, $validationErrors);
                    continue;
                }

                // Kalau validasi lolos → baru ganti placeholder gambar & bersihkan HTML
                foreach ($dataPassage as $k => $v) {
                    $v = $extractor->replaceImageSrc($v, $mediaImages);
                    $v = $extractor->cleanHtml($v); // Hilangkan tag sampah
                    $dataPassage[$k] = $v;
                }

                $dataPassage['LEVEL'] = $getLevel->id; // simpan instance Level untuk dipakai nanti
                $validPassage[] = $dataPassage; // masukkan ke kumpulan passage valid
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

        $audioFile = null;

        // simpan file unik berdasarkan hash
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

        if ($request->hasFile('audio_file')) {
            $audioFile = $saveFileByHash($request->file('audio_file'), 'english-zone-audio');
        }

        // Simpan passage ke database
        foreach ($validPassage as $index => $dataPassage) {

        // jika passage type LISTENING PRACTICE TEST atau LISTENING EXAM TEST maka cek audio script sudah terdaftar atau belum
        if ($type === 'LISTENING PRACTICE TEST' || $type === 'LISTENING EXAM TEST') {
            // Cek duplikasi passage
            $existingPassage = EnglishZonePassage::where('audio_script', $dataPassage['AUDIO_SCRIPT'])->exists();
        } else {
            // Cek duplikasi passage
            $existingPassage = EnglishZonePassage::where('passage_content', $dataPassage['PASSAGE_CONTENT'])->exists();
        }
            // jika $id ada, maka update. jika tidak ada maka create
            if ($id) {
                $updatePassage = EnglishZonePassage::findOrFail($id);

                if (!$allWordValidationErrors) {
                    if (!$existingPassage) {
                        $updatePassage->update([
                            'administrator_id' => $userId,
                            'passage_content' => $dataPassage['PASSAGE_CONTENT'] ?? '',
                            'level_id' => $dataPassage['LEVEL'],
                            'audio_file' => $audioFile ?? '',
                            'audio_script' => $dataPassage['AUDIO_SCRIPT'] ?? '',
                            'example_answer' => $dataPassage['EXAMPLE_ANSWER'] ?? '',
                            'passage_type' => strip_tags($dataPassage['TYPE']),
                        ]);
                    }
                }
            } else {
                if (!$allWordValidationErrors) {
                    if (!$existingPassage) {
                        $createPassage = EnglishZonePassage::create([
                            'administrator_id' => $userId,
                            'passage_content' => $dataPassage['PASSAGE_CONTENT'] ?? '',
                            'level_id' => $dataPassage['LEVEL'],
                            'audio_file' => $audioFile ?? '',
                            'audio_script' => $dataPassage['AUDIO_SCRIPT'] ?? '',
                            'example_answer' => $dataPassage['EXAMPLE_ANSWER'] ?? '',
                            'passage_type' => strip_tags($dataPassage['TYPE']),
                        ]);
                    }
                }
            }
        }

        // Kirim event broadcast kalau passage berhasil ditambahkan
        if (isset($createPassage)) {
            broadcast(new EnglishZonePassageImportListener('EnglishZonePassage','create', $createPassage))->toOthers();
        }

        // Kirim event broadcast kalau passage berhasil diupdate
        if (isset($updatePassage)) {
            broadcast(new EnglishZonePassageImportListener('EnglishZonePassage','update', $updatePassage))->toOthers();
        }

        // Bersihkan file sementara
        @unlink($docxPath);
        @unlink($outputHtmlPath);

        // Return sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Passage berhasil diupload.',
        ]);
    }
}