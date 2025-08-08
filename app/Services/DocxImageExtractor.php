<?php

namespace App\Services;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\ListItem;
use ZipArchive;

// kalo uda clear semua, nanti ini dihapus
// class DocxImageExtractor
// {
//     // Mengubah elemen Word menjadi HTML styled (warna, ukuran, gambar)
//     public function getStyledHtmlFromElement($element, &$mediaImages = [])
//     {
//         $html = '';

        // if ($element instanceof Text) {
        //     // Elemen teks biasa
        //     $text = $element->getText();
        //     $style = $element->getFontStyle();

        //     $styleAttr = '';
        //     $prefix = '';
        //     $suffix = '';

        //     if ($style) {
        //         // Warna teks
        //         if ($color = $style->getColor()) {
        //             $styleAttr .= "color:#{$color};";
        //         }

        //         // Ukuran font dari pt ke px (1pt ≈ 1.33px)
        //         if ($size = $style->getSize()) {
        //             $styleAttr .= "font-size:" . ($size * 1.33) . "px;";
        //         }

        //         // Font-family
        //         if ($fontName = $style->getName()) {
        //             $styleAttr .= "font-family:'{$fontName}';";
        //         }

        //         // Bold dan italic menggunakan tag
        //         if ($style->isBold()) {
        //             $prefix .= '<strong>';
        //             $suffix = '</strong>' . $suffix;
        //         }
        //         if ($style->isItalic()) {
        //             $prefix .= '<em>';
        //             $suffix = '</em>' . $suffix;
        //         }

        //         // Underline dan strike-through
        //         if ($style->getUnderline() !== 'none') {
        //             $styleAttr .= "text-decoration:underline;";
        //         }
        //         if ($style->isStrikeThrough()) {
        //             $styleAttr .= "text-decoration:line-through;";
        //         }

        //         // Superscript dan subscript
        //         if ($style->isSubScript()) {
        //             $prefix .= '<sub>';
        //             $suffix = '</sub>' . $suffix;
        //         }
        //         if ($style->isSuperScript()) {
        //             $prefix .= '<sup>';
        //             $suffix = '</sup>' . $suffix;
        //         }

        //         // Highlight warna background
        //         $highlight = null;
        //         if ($style && method_exists($style, 'getStyleValues')) {
        //             $values = $style->getStyleValues();
        //             if (isset($values['style']['fgColor'])) {
        //                 $highlight = strtolower($values['style']['fgColor']);
        //             }
        //         }

        //         if ($highlight) {
        //             $highlightMap = [
        //                 'yellow' => '#ffff00', 'green' => '#00ff00', 'cyan' => '#00ffff',
        //                 'magenta' => '#ff00ff', 'blue' => '#0000ff', 'red' => '#ff0000',
        //                 'darkblue' => '#00008b', 'darkred' => '#8b0000', 'darkgreen' => '#006400',
        //                 'darkcyan' => '#008b8b', 'darkmagenta' => '#8b008b', 'gray' => '#808080',
        //                 'lightgray' => '#d3d3d3',
        //             ];
        //             if (preg_match('/^[0-9a-f]{6}$/i', $highlight)) {
        //                 $styleAttr .= "background-color:#{$highlight};";
        //             } elseif (isset($highlightMap[$highlight])) {
        //                 $styleAttr .= "background-color:{$highlightMap[$highlight]};";
        //             }
        //         }

        //         // Bungkus teks jika ada style
        //         if (!empty($styleAttr)) {
        //             $text = "<span style=\"$styleAttr\">$text</span>";
        //         }

        //         $text = $prefix . $text . $suffix;
        //     }

        //     $html .= $text;

//         } elseif ($element instanceof TextRun) {
//             // Elemen yang berisi kumpulan teks/gambar
//             foreach ($element->getElements() as $child) {
//                 $html .= $this->getStyledHtmlFromElement($child, $mediaImages);
//             }

//         } elseif ($element instanceof Image) {
//             // Gambar dari dokumen Word
//             $binary = base64_decode($element->getImageStringData(true));
//             $key = $this->generateImageHash($binary);

//             $imgUrl = "PLACEHOLDER:$key";
//             $mediaImages[$key] = [
//                 'binary' => $binary,
//                 'mime' => $this->detectMimeType($binary),
//                 'path' => null,
//             ];

//             $html .= "<p><img src=\"$imgUrl\"></p>";

//         } elseif ($element instanceof Table) {
//             // Tabel: proses semua sel
//             foreach ($element->getRows() as $row) {
//                 foreach ($row->getCells() as $cell) {
//                     foreach ($cell->getElements() as $cellElement) {
//                         $html .= $this->getStyledHtmlFromElement($cellElement, $mediaImages);
//                     }
//                 }
//             }
//         }

//         return $html;
//     }

//     // Proses array elemen dengan deteksi List (ol/ul)
//     public function getStyledHtmlFromElements(array $elements, &$mediaImages = [])
//     {
//         $html = '';
//         $listBuffer = [];
//         $listTag = null;

//         foreach ($elements as $element) {
//             if ($element instanceof ListItem) {
//                 $text = $this->getStyledHtmlFromElement($element->getTextObject(), $mediaImages);
//                 $styleName = $element->getStyle();
//                 $isNumbered = is_string($styleName) && str_starts_with(strtolower($styleName), 'number');
//                 $currentTag = $isNumbered ? 'ol' : 'ul';

//                 if ($listTag && $listTag !== $currentTag) {
//                     // Flush list buffer with previous tag
//                     $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
//                     $listBuffer = [];
//                 }

//                 $listTag = $currentTag;
//                 $listBuffer[] = $text;

//             } else {
//                 if ($listBuffer && $listTag) {
//                     // Flush buffer before non-list element
//                     $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
//                     $listBuffer = [];
//                     $listTag = null;
//                 }

//                 $html .= $this->getStyledHtmlFromElement($element, $mediaImages);
//             }
//         }

//         // Flush if ends with list
//         if ($listBuffer && $listTag) {
//             $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
//         }

//         return $html;
//     }


//     // Ambil data dari tabel Word dengan format HTML styled
// public function extractStyledTableData($phpWord, &$mediaImages)
// {
//     $styledData = [];
//     $soalIndex = 0;

//     Log::info('[extractStyledTableData] Mulai memproses sections...');

//     foreach ($phpWord->getSections() as $sectionIndex => $section) {
//         foreach ($section->getElements() as $elementIndex => $element) {

//             if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
//                 Log::info("[extractStyledTableData] Table ditemukan di section $sectionIndex, elemen ke-$elementIndex");

//                 foreach ($element->getRows() as $rowIndex => $row) {
//                     $cells = $row->getCells();
//                     Log::info("  Row $rowIndex: " . count($cells) . " cells");

//                     if (count($cells) < 2) continue;

//                     // Ambil key dari cell pertama
//                     $keyHtml = $this->getStyledHtmlFromCell($cells[0], $mediaImages);
//                     $keyText = strtoupper(trim(strip_tags($keyHtml)));

//                     // Ambil value dari cell kedua
//                     $value = $this->getStyledHtmlFromCell($cells[1], $mediaImages);

//                     if (!empty($keyText)) {
//                         $styledData[$soalIndex][$keyText] = $value;
//                         Log::info("    Key ditemukan: $keyText");
//                     }
//                 }

//                 $soalIndex++;
//             } else {
//                 Log::info("[extractStyledTableData] Elemen bukan tabel: " . get_class($element));
//             }
//         }
//     }

//     Log::info('[extractStyledTableData] Total soal styled: ' . count($styledData));
//     foreach ($styledData as $i => $data) {
//         Log::info("  Soal ke-$i, keys: " . implode(', ', array_keys($data)));
//     }

//     return $styledData;
// }
//     // Ambil isi HTML styled dari sebuah sel tabel
//     public function getStyledHtmlFromCell($cell, &$mediaImages = [])
//     {
//         $html = '';

//         if (!method_exists($cell, 'getElements')) {
//             return $html;
//         }

//         $elements = $cell->getElements();

//         // Buat dokumen PhpWord sementara
//         $phpWord = new \PhpOffice\PhpWord\PhpWord();
//         $section = $phpWord->addSection();

//         foreach ($elements as $element) {
//             $section->addElement($element);

//             // Tangani gambar
//             if ($element instanceof \PhpOffice\PhpWord\Element\Image) {
//                 $imagePath = $element->getSource();
//                 if (!file_exists($imagePath)) continue;

//                 $binary = file_get_contents($imagePath);
//                 $mime = $this->detectMimeType($binary);
//                 $ext = $this->mimeToExtension($mime);

//                 // Gunakan nama file dari path sebagai key (konsisten dengan Pandoc)
//                 $filename = pathinfo($imagePath, PATHINFO_FILENAME); // contoh: image1
//                 $key = "media/$filename";
//                 $publicPath = "/soal-pembahasan-image/$filename.$ext";
//                 $savePath = public_path("soal-pembahasan-image/$filename.$ext");

//                 if (!isset($mediaImages[$key])) {
//                     if (!file_exists(dirname($savePath))) {
//                         mkdir(dirname($savePath), 0775, true);
//                     }
//                     file_put_contents($savePath, $binary);

//                     $mediaImages[$key] = [
//                         'binary' => $binary,
//                         'mime' => $mime,
//                         'path' => $publicPath,
//                     ];
//                 }
//             }
//         }

//         // Simpan hasil render HTML
//         $tempPath = storage_path('app/tmp-html-preview.html');
//         $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
//         $writer->save($tempPath);

//         $html = file_get_contents($tempPath);

//         return $html;
//     }

//     // Ekstraksi gambar dari elemen Word dan simpan unik berdasarkan hash
//     public int $currentImageCounter = 1;
//     public function extractImages($element, &$mediaImages)
//     {
//         if ($element instanceof Image) {
//             $source = $element->getSource();
//             $filename = pathinfo($source, PATHINFO_FILENAME);
//             $key = "media/$filename";

//             $binary = base64_decode($element->getImageStringData(true));
//             if (!isset($mediaImages[$key])) {
//                 $mediaImages[$key] = [
//                     'binary' => $binary,
//                     'mime' => $this->detectMimeType($binary),
//                     'path' => null,
//                 ];
//             }

//         } elseif ($element instanceof TextRun) {
//             foreach ($element->getElements() as $child) {
//                 $this->extractImages($child, $mediaImages);
//             }
//         } elseif ($element instanceof Table) {
//             foreach ($element->getRows() as $row) {
//                 foreach ($row->getCells() as $cell) {
//                     foreach ($cell->getElements() as $cellElement) {
//                         $this->extractImages($cellElement, $mediaImages);
//                     }
//                 }
//             }
//         }
//     }


//     // Gabungkan HTML dari Pandoc dan PhpWord
//     public function mergeStyledAndPandocHtml($pandocHtml, $styledHtml, $mediaImages)
//     {
//         // Load DOM hasil Pandoc
//         $pandocDom = new \DOMDocument();
//         libxml_use_internal_errors(true);
//         $pandocDom->loadHTML(mb_convert_encoding($pandocHtml, 'HTML-ENTITIES', 'UTF-8'));
//         libxml_clear_errors();

//         // Load DOM styled hasil PhpWord
//         $styledDom = new \DOMDocument();
//         libxml_use_internal_errors(true);
//         $styledDom->loadHTML(mb_convert_encoding($styledHtml, 'HTML-ENTITIES', 'UTF-8'));
//         libxml_clear_errors();

//         // Perbaiki <img src="media/image1.png"> dari hasil Pandoc
//         foreach ($pandocDom->getElementsByTagName('img') as $img) {
//             $src = $img->getAttribute('src');
//             $key = preg_replace('/\.\w+$/', '', strtolower($src)); // Contoh: media/image1.jpg → media/image1

//             if (isset($mediaImages[$key])) {
//                 $img->setAttribute('src', $mediaImages[$key]);
//             }
//         }

//         // Ambil isi body dari kedua dokumen
//         $pandocBody = $pandocDom->getElementsByTagName('body')->item(0);
//         $styledBody = $styledDom->getElementsByTagName('body')->item(0);

//         $resultHtml = '';

//         for ($i = 0; $i < $pandocBody->childNodes->length; $i++) {
//             $pandocChild = $pandocBody->childNodes->item($i);
//             $pandocHtmlChunk = $pandocDom->saveHTML($pandocChild);

//             // Jika chunk dari Pandoc mengandung MathML, pertahankan chunk Pandoc
//             if (str_contains($pandocHtmlChunk, '<math')) {
//                 $resultHtml .= $pandocHtmlChunk;
//             } else {
//                 // Ambil styledHtml dari index yang sama jika ada
//                 $styledChild = $styledBody->childNodes->item($i);
//                 $styledHtmlChunk = $styledChild ? $styledDom->saveHTML($styledChild) : null;

//                 // Gunakan styledHtml jika ada, fallback ke Pandoc
//                 $resultHtml .= $styledHtmlChunk ?: $pandocHtmlChunk;
//             }
//         }

//         return $resultHtml;
//     }

//     // Simpan gambar ke folder public dan kembalikan URL-nya
//     public function saveImage($binaryData, $mime)
//     {
//         $ext = $this->mimeToExtension($mime);
//         $hash = $this->generateImageHash($binaryData);
//         $fileName = "img_$hash.$ext"; // Gunakan hash sebagai nama file
//         $path = public_path("soal-pembahasan-image/$fileName");

//         // Buat folder jika belum ada
//         if (!file_exists(dirname($path))) {
//             mkdir(dirname($path), 0775, true);
//         }

//         // Simpan file
//         file_put_contents($path, $binaryData);

//         // Return URL publik
//         return "/soal-pembahasan-image/$fileName";
//     }

//     // Deteksi mime dari data biner
//     public function detectMimeType($binaryData)
//     {
//         $finfo = new \finfo(FILEINFO_MIME_TYPE);
//         return $finfo->buffer($binaryData);
//     }

//     // Konversi mime ke ekstensi file
//     public function mimeToExtension($mime)
//     {
//         return match ($mime) {
//             'image/jpeg', 'image/jpg' => 'jpg',
//             'image/png' => 'png',
//             'image/gif' => 'gif',
//             'image/bmp' => 'bmp',
//             'image/webp' => 'webp',
//             default => 'jpg',
//         };
//     }

//     // function agar image yang sama tidak masuk ke folder public duplikat
//     public function generateImageHash($binaryData)
//     {
//         return md5($binaryData);
//     }

//     // Cek apakah HTML masih kosong (jika kosong hapus tag html)
//     public function isHtmlEmpty($html)
//     {
//         $text = trim(strip_tags($html));

//         // Jika tidak ada teks, cek apakah ada <img> tag
//         if (empty($text)) {
//             return !str_contains($html, '<img');
//         }

//         return false;
//     }

//     // Ganti semua placeholder dengan path public
//     public function replaceAllPlaceholdersWithPath($html, $mediaImages)
//     {
//         foreach ($mediaImages as $key => $img) {
//             if (!empty($img['path'])) {
//                 // $html = str_replace("src=\"PLACEHOLDER:$key\"", "src=\"{$img['path']}\"", $html);
//                 $html = str_replace("src=\"PLACEHOLDER:$key\"", "src=\"{$img['path']}\"", $html);
//                 $html = str_replace("PLACEHOLDER:$key", "{$img['path']}", $html); // tambahan untuk jaga-jaga
//             }
//         }
//         return $html;
//     }


//     // Ganti src pada HTML hasil Pandoc berdasarkan gambar yang sudah diekstrak
//     public function replaceImageSrc($html, &$mediaImages)
//     {
//         if (empty(trim($html))) {
//             return '';
//         }

//         $dom = new \DOMDocument();
//         libxml_use_internal_errors(true);
//         $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
//         libxml_clear_errors();

//         foreach ($dom->getElementsByTagName('img') as $img) {
//             $originalSrc = $img->getAttribute('src');
//             $filename = pathinfo($originalSrc, PATHINFO_FILENAME);
//             $key = "media/$filename";

//             if (!isset($mediaImages[$key])) {
//                 $mediaImages[$key] = [
//                     'binary' => null,
//                     'mime' => null,
//                     'path' => null,
//                 ];
//             }

//             $img->setAttribute('src', "PLACEHOLDER:$key");
//         }

//         $body = $dom->getElementsByTagName('body')->item(0);
//         $innerHTML = '';
//         foreach ($body->childNodes as $child) {
//             $innerHTML .= $dom->saveHTML($child);
//         }

//         return $innerHTML;
//     }
// }


class DocxImageExtractor
{
    // Counter gambar (jika ingin memberi nomor urut gambar yang diambil dari dokumen)
    public int $currentImageCounter = 1;

    // Menyimpan hash dari gambar yang sudah pernah diambil
    // → agar gambar yang sama tidak disimpan berkali-kali
    protected array $savedImageHashes = [];

    public function normalizeTextContent(string $html): string {
        // Menghapus semua tag HTML dan mengubah entitas HTML ke teks biasa
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Mengganti banyak spasi/tab/newline menjadi hanya 1 spasi
        $text = preg_replace('/\s+/u', ' ', $text);

        // Menghapus spasi di awal dan akhir string
        return trim($text);
    }

    public function getStyledHtmlFromElement($element, &$mediaImages = [])
    {
        $html = ''; // Variabel untuk menampung hasil HTML

        // Jika elemen adalah teks biasa
        if ($element instanceof Text) {
            $text = $element->getText(); // Ambil teks
            $style = $element->getFontStyle(); // Ambil style font

            $styleAttr = ''; // Menampung CSS inline
            $prefix = '';    // Tag pembuka seperti <strong>, <em>, dll
            $suffix = '';    // Tag penutup seperti </strong>, </em>, dll

            if ($style) {
                // Warna teks → CSS color
                if ($color = $style->getColor()) {
                    $styleAttr .= "color:#{$color};";
                }

                // Ukuran font: konversi pt ke px (1pt ≈ 1.33px)
                if ($size = $style->getSize()) {
                    $styleAttr .= "font-size:" . ($size * 1.33) . "px;";
                }

                // Nama font → CSS font-family
                if ($fontName = $style->getName()) {
                    $styleAttr .= "font-family:'{$fontName}';";
                }

                // Bold → bungkus dengan <strong>
                if ($style->isBold()) {
                    $prefix .= '<strong>';
                    $suffix = '</strong>' . $suffix;
                }

                // Italic → bungkus dengan <em>
                if ($style->isItalic()) {
                    $prefix .= '<em>';
                    $suffix = '</em>' . $suffix;
                }

                // Underline → bungkus dengan <u>
                if ($style->getUnderline() !== 'none') {
                    $prefix .= '<u>';
                    $suffix = '</u>' . $suffix;
                }

                // Strike-through → bungkus dengan <s>
                if ($style->isStrikeThrough()) {
                    $prefix .= '<s>';
                    $suffix = '</s>' . $suffix;
                }

                // Subscript → bungkus dengan <sub>
                if ($style->isSubScript()) {
                    $prefix .= '<sub>';
                    $suffix = '</sub>' . $suffix;
                }

                // Superscript → bungkus dengan <sup>
                if ($style->isSuperScript()) {
                    $prefix .= '<sup>';
                    $suffix = '</sup>' . $suffix;
                }

                // Cek highlight warna background
                $highlight = null;
                if ($style && method_exists($style, 'getStyleValues')) {
                    $values = $style->getStyleValues();
                    if (isset($values['style']['fgColor'])) {
                        $highlight = strtolower($values['style']['fgColor']); // Ambil warna highlight
                    }
                }

                // Jika ada highlight, bungkus dengan <mark>
                if ($highlight) {
                    $highlightMap = [
                        'yellow' => '#ffff00', 'green' => '#00ff00', 'cyan' => '#00ffff',
                        'magenta' => '#ff00ff', 'blue' => '#0000ff', 'red' => '#ff0000',
                        'darkblue' => '#00008b', 'darkred' => '#8b0000', 'darkgreen' => '#006400',
                        'darkcyan' => '#008b8b', 'darkmagenta' => '#8b008b', 'gray' => '#808080',
                        'lightgray' => '#d3d3d3',
                    ];
                    $prefix .= '<mark>';
                    $suffix = '</mark>' . $suffix;
                }

                // Jika ingin bungkus style langsung di span (saat ini dikomentari)
                // if (!empty($styleAttr)) {
                //     $text = "<span style=\"$styleAttr\">$text</span>";
                // }

                // Gabungkan tag pembuka + teks + tag penutup
                $text = $prefix . $text . $suffix;
            }

            // Tambahkan ke HTML akhir
            $html .= $text;

        // Jika elemen adalah TextRun (sekumpulan teks)
        } elseif ($element instanceof TextRun) {
            $content = '';
            foreach ($element->getElements() as $child) {
                // Proses setiap child elemen
                $content .= $this->getStyledHtmlFromElement($child, $mediaImages);
            }
            $html .= "<p>$content</p>"; // Bungkus semua teks dalam paragraf

        // Jika elemen adalah gambar
        } elseif ($element instanceof Image) {
            $binary = base64_decode($element->getImageStringData(true)); // Ambil data gambar biner
            $mime = $this->detectMimeType($binary); // Deteksi tipe MIME gambar

            $hashKey = $this->generateImageHash($binary); // Buat hash unik gambar
            $key = 'media/' . $hashKey; // Buat key unik untuk gambar

            // Jika gambar belum pernah disimpan
            if (!isset($mediaImages[$key])) {
                $imgUrl = $this->saveImage($binary, $mime); // Simpan gambar dan ambil URL-nya
                $mediaImages[$key] = $imgUrl; // Simpan URL di array
                Log::info("🖼 Image extracted - key: $key, url: $imgUrl"); // Log info
            } else {
                // Jika sudah ada, ambil URL yang sudah ada
                $imgUrl = $mediaImages[$key];
            }

            // Simpan placeholder <img> dengan src key (nanti akan diganti URL asli)
            $html .= "<p><img src=\"$key\"></p>";

        // Jika elemen adalah tipe lain (misal tabel, list, dll.)
        } else {
            // Jika bisa ambil teks langsung
            if (method_exists($element, 'getText')) {
                $plain = $element->getText();
                $html .= htmlspecialchars($plain, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); // Escape karakter spesial
            }

            // Jika punya elemen anak, proses satu per satu
            if (method_exists($element, 'getElements')) {
                foreach ($element->getElements() as $child) {
                    $html .= $this->getStyledHtmlFromElement($child, $mediaImages);
                }
            }
        }

        // Kembalikan HTML hasil proses
        return $html;
    }

    /**
     * Fungsi untuk menelusuri elemen PhpWord secara rekursif dan menampilkan struktur + teksnya di log
     * Digunakan untuk debugging saat parsing dokumen Word
     */
    private function debugElement($element, $indent = 0)
    {
        // $prefix digunakan untuk memberi indentasi di log sesuai level kedalaman elemen
        $prefix = str_repeat('  ', $indent);

        // Log class dari elemen saat ini (misal: Table, Text, Image, dsb.)
        Log::info($prefix . 'Class: ' . get_class($element));

        // Jika elemen memiliki method getText(), ambil teksnya dan log
        if (method_exists($element, 'getText')) {
            $text = $element->getText();
            Log::info($prefix . '  getText(): ' . json_encode($text));
        }

        // Jika elemen memiliki child elements (misal Table → Row → Cell → TextRun), telusuri anak-anaknya
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                // Panggil debugElement secara rekursif dengan indentasi +1
                $this->debugElement($child, $indent + 1);
            }
        }
    }

    /**
     * Ekstrak data tabel yang terformat (styled) dari dokumen Word
     * Mengembalikan array $styledData yang berisi pasangan KEY → VALUE untuk setiap soal
     * Menggunakan PhpWord untuk mempertahankan style & media (gambar)
     */
    public function extractStyledTableData($phpWord, &$mediaImages)
    {
        $styledData = [];  // Array hasil ekstraksi
        $soalIndex = 0;    // Indeks soal yang sedang diproses

        // Loop setiap section di dokumen Word
        foreach ($phpWord->getSections() as $sectionIndex => $section) {

            // Loop setiap elemen di section tersebut
            foreach ($section->getElements() as $elementIndex => $element) {

                // Jika elemen adalah tabel
                if ($element instanceof Table) {

                    // Loop setiap baris di tabel
                    foreach ($element->getRows() as $rowIndex => $row) {

                        // Ambil semua cell di baris
                        $cells = $row->getCells();

                        // Lewati baris jika jumlah kolom kurang dari 2 (tidak valid)
                        if (count($cells) < 2) continue;

                        // ======== PROSES KOLOM PERTAMA (KEY) ========
                        // Debug: tampilkan struktur elemen kolom 1
                        $this->debugElement($cells[0]);

                        // Ambil HTML dengan style dari cell pertama
                        $keyHtml = $this->getStyledHtmlFromCell($cells[0], $mediaImages);

                        // Normalisasi teksnya (hapus tag HTML, rapikan spasi, ubah ke uppercase)
                        $keyText = strtoupper($this->normalizeTextContent($keyHtml));

                        // ======== PROSES KOLOM KEDUA (VALUE) ========
                        // Debug: tampilkan struktur elemen kolom 2
                        $this->debugElement($cells[1]);

                        // Ambil HTML dengan style dari cell kedua
                        $valueHtml = $this->getStyledHtmlFromCell($cells[1], $mediaImages);

                        // Normalisasi teksnya
                        $valueText = $this->normalizeTextContent($valueHtml);

                        // ======== SIMPAN HASILNYA ========
                        // Jika key tidak kosong, simpan pasangan KEY → VALUE ke array
                        if ($keyText !== '') {
                            $styledData[$soalIndex][$keyText] = $valueHtml;
                        }
                    }

                    // Setelah memproses semua baris di tabel ini, pindah ke soal berikutnya
                    $soalIndex++;
                }
            }
        }

        // Log total soal yang berhasil diekstrak
        Log::info('[extractStyledTableData] Total soal styled: ' . count($styledData));

        // Log daftar key yang ditemukan untuk setiap soal
        foreach ($styledData as $i => $data) {
            Log::info("  Soal ke-$i, keys: " . implode(', ', array_keys($data)));
        }

        // Kembalikan array hasil ekstraksi
        return $styledData;
    }


    /**
     * Mengubah isi sebuah cell tabel Word menjadi HTML lengkap dengan style
     */
    public function getStyledHtmlFromCell($cell, &$mediaImages = [])
    {
        $html = '';

        // Cek apakah cell punya method getElements() (yaitu elemen-elemen di dalamnya)
        if (method_exists($cell, 'getElements')) {
            // Loop semua elemen dalam cell (bisa berupa teks, gambar, table, dll)
            foreach ($cell->getElements() as $element) {
                // Ambil HTML styled dari setiap elemen dalam cell
                $html .= $this->getStyledHtmlFromElement($element, $mediaImages);
            }
        }

        // Return hasil HTML gabungan semua elemen dalam cell
        return $html;
    }

    /**
     * Mengekstrak semua gambar dari sebuah elemen Word dan menyimpannya ke $mediaImages
     */
    public function extractImages($element, &$mediaImages)
    {
        // Jika elemen adalah gambar langsung
        if ($element instanceof Image) {
            // Ambil data binary gambar dari elemen Image dalam bentuk base64, lalu decode
            $binary = base64_decode($element->getImageStringData(true));

            // Deteksi MIME type (jpg, png, dll)
            $mime = $this->detectMimeType($binary);

            // Buat hash unik untuk gambar agar tidak duplikat
            $hashKey = $this->generateImageHash($binary);

            // Kunci array media, mengikuti format "media/<hash>"
            $key = 'media/' . $hashKey;

            // Jika gambar ini belum ada di array mediaImages, simpan
            if (!isset($mediaImages[$key])) {
                // Simpan gambar ke storage dan dapatkan URL-nya
                $imgUrl = $this->saveImage($binary, $mime);

                // Simpan mapping antara key → URL gambar
                $mediaImages[$key] = $imgUrl;

                // Logging untuk debug proses ekstraksi gambar
                Log::info("🖼 Image extracted - key: $key, url: $imgUrl");
            }

        // Jika elemen adalah TextRun (sekumpulan teks & elemen lain dalam satu paragraf)
        } elseif ($element instanceof TextRun) {
            // Loop semua child element di dalam TextRun
            foreach ($element->getElements() as $child) {
                // Rekursif panggil extractImages untuk setiap child
                $this->extractImages($child, $mediaImages);
            }

        // Jika elemen adalah tabel
        } elseif ($element instanceof Table) {
            // Loop semua baris dalam tabel
            foreach ($element->getRows() as $row) {
                // Loop semua cell dalam baris
                foreach ($row->getCells() as $cell) {
                    // Loop semua elemen dalam cell
                    foreach ($cell->getElements() as $cellElement) {
                        // Rekursif panggil extractImages untuk setiap elemen dalam cell
                        $this->extractImages($cellElement, $mediaImages);
                    }
                }
            }
        }
    }


    public function mergeStyledAndPandocHtml($pandocHtml, $styledHtml, $mediaImages)
    {
        // Buat DOMDocument untuk HTML hasil Pandoc
        $pandocDom = new \DOMDocument();
        libxml_use_internal_errors(true); // Supaya warning/error parsing HTML diabaikan sementara
        $pandocDom->loadHTML(
            mb_convert_encoding($pandocHtml, 'HTML-ENTITIES', 'UTF-8') // Pastikan encoding ke HTML entities agar aman untuk DOM
        );
        libxml_clear_errors(); // Bersihkan error setelah parsing

        // Buat DOMDocument untuk HTML hasil PhpWord (styled)
        $styledDom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $styledDom->loadHTML(
            mb_convert_encoding($styledHtml, 'HTML-ENTITIES', 'UTF-8')
        );
        libxml_clear_errors();

        // 🔹 Sinkronisasi gambar: ganti <img src="..."> dari Pandoc dengan URL hasil extract PhpWord
        foreach ($pandocDom->getElementsByTagName('img') as $img) {
            if (!$img instanceof \DOMElement) continue;
            $src = $img->getAttribute('src'); // Ambil path asli gambar di HTML Pandoc
            $key = strtolower(pathinfo($src, PATHINFO_FILENAME)); // Ambil nama file tanpa ekstensi (jadi key)
            if (isset($mediaImages[$key])) { // Jika key ini ada di daftar gambar hasil extract PhpWord
                $img->setAttribute('src', $mediaImages[$key]); // Ganti src dengan URL hasil simpan
            }
        }

        // Ambil elemen <body> dari kedua HTML
        $pandocBody = $pandocDom->getElementsByTagName('body')->item(0);
        $styledBody = $styledDom->getElementsByTagName('body')->item(0);

        $resultHtml = ''; // Hasil akhir penggabungan

        // Loop semua elemen child di body hasil Pandoc
        for ($i = 0; $i < $pandocBody->childNodes->length; $i++) {
            $pandocChild = $pandocBody->childNodes->item($i); // Ambil elemen child ke-i
            $pandocHtmlChunk = $pandocDom->saveHTML($pandocChild); // Convert ke string HTML

            // Jika chunk mengandung <math> (MathML) → pakai Pandoc HTML (karena Pandoc lebih bagus handle math)
            if (str_contains($pandocHtmlChunk, '<math')) {
                $resultHtml .= $pandocHtmlChunk;
            } else {
                // Ambil chunk styled dari posisi yang sama di HTML PhpWord
                $styledChild = $styledBody->childNodes->item($i);
                $styledHtmlChunk = $styledChild ? $styledDom->saveHTML($styledChild) : null;

                // Jika ada styled version, pakai itu; kalau tidak, fallback ke Pandoc version
                $resultHtml .= $styledHtmlChunk ?: $pandocHtmlChunk;
            }
        }

        return $resultHtml; // Return HTML gabungan
    }


    // nanti ini hapus kalo uda clear semua (kekurangan ini membuat image berada dibawah text, harusnya di antara kedua text)
    // public function combineStyledAndPandoc(string $styledHtml, string $pandocHtml): string
    // {
    //     // Kalau tidak ada fallback dari Pandoc, jangan buang apa-apa dari styledHtml
    //     if (trim($pandocHtml) === '') {
    //         return $styledHtml;
    //     }

    //     preg_match_all('/<math.*?<\/math>/s', $pandocHtml, $mathMatches);
    //     preg_match_all('/<img[^>]+>/s', $pandocHtml, $imgMatches);

    //     $mathHtml = implode('', $mathMatches[0] ?? []);
    //     $imgHtml = implode('', $imgMatches[0] ?? []);

    //     // Hapus hanya math/img dari styled agar tidak duplikat, tapi sisanya tetap
    //     $styledClean = preg_replace([
    //         '/<math.*?<\/math>/s',
    //         '/<img[^>]+>/s'
    //     ], '', $styledHtml);

    //     return $styledClean . $imgHtml . $mathHtml;
    // }

    public function combineStyledAndPandoc(string $styledHtml, string $pandocHtml): string
    {
        // Cek jika HTML hasil Pandoc mengandung <math> (equation) atau <img> (gambar)
        // Kenapa? Karena Pandoc lebih handal dalam mempertahankan struktur MathML dan path gambar
        if (str_contains($pandocHtml, '<math') || str_contains($pandocHtml, '<img')) {
            // Jika ada math atau img, gunakan seluruh pandocHtml
            return $pandocHtml;
        }

        // Jika tidak mengandung math atau gambar, berarti hanya teks biasa → gunakan styledHtml
        // StyledHtml dipakai karena memiliki format/style yang lebih rapi (hasil dari PhpWord)
        return $styledHtml;
    }

    public function extractAllImagesFromPhpWord($phpWord, &$mediaImages)
    {
        // Loop setiap section di dokumen Word
        foreach ($phpWord->getSections() as $section) {
            // Loop setiap elemen dalam section tersebut
            foreach ($section->getElements() as $element) {
                // Panggil fungsi extractImages untuk mengambil gambar dari elemen ini
                // $mediaImages dioper dengan reference (&) agar array terupdate langsung
                $this->extractImages($element, $mediaImages);
            }
        }
    }

    public function isMeaningfullyEmpty(string $html): bool
    {
        // Jika ada <img>, <math>, atau <m:oMath> berarti tidak kosong secara "makna"
        // <m:oMath> adalah tag equation di OOXML Word
        if (preg_match('/<img\b/i', $html) || preg_match('/<math\b/i', $html) || preg_match('/<m:oMath\b/i', $html)) {
            return false; // tidak kosong
        }

        // Hilangkan atribut xml:space="preserve" yang tidak memengaruhi isi teks
        $clean = preg_replace('/xml:space="preserve"/i', '', $html);

        // Normalisasi teks: hapus tag HTML, decode entitas, dan rapikan spasi
        $normalized = $this->normalizeTextContent($clean);

        // Jika hasil akhirnya string kosong → berarti tidak ada konten berarti
        return $normalized === '';
    }


    /**
     * Ekstrak semua gambar dari file DOCX, tapi belum disimpan ke folder publik.
     * Disimpan sementara di array $mediaImages dengan key 'media/<nama_file>'
     */
    public function extractImagesFromDocxFile($docxPath, &$mediaImages)
    {
        $zip = new ZipArchive(); // Class bawaan PHP untuk membaca ZIP (DOCX itu sebenarnya ZIP)
        if ($zip->open($docxPath) === true) { // Cek apakah file DOCX bisa dibuka
            for ($i = 0; $i < $zip->numFiles; $i++) { // Loop semua file di dalam DOCX
                $fileName = $zip->getNameIndex($i); // Ambil nama file berdasarkan index
                // Cek apakah file berada di folder word/media dan berformat gambar (jpg, png, dll)
                if (preg_match('/^word\/media\/(.+\.(jpg|jpeg|png|gif|bmp|webp))$/i', $fileName, $matches)) {
                    $baseName = strtolower(pathinfo($matches[1], PATHINFO_FILENAME)); // Ambil nama file tanpa ekstensi
                    $key = 'media/' . $baseName; // Key standar agar mudah dicocokkan dengan Pandoc

                    // Pastikan gambar ini belum pernah dimasukkan
                    if (!isset($mediaImages[$key])) {
                        $binary = $zip->getFromIndex($i); // Ambil data binary gambar
                        $mime = $this->detectMimeType($binary); // Deteksi tipe MIME
                        $mediaImages[$key] = [
                            'binary' => $binary,     // Simpan data binary
                            'mime' => $mime,         // Simpan tipe MIME
                            'public_url' => null,    // Belum ada URL publik (belum disimpan di folder publik)
                        ];
                        Log::info("🖼 [ZIP] Image extracted (delayed save): $key"); // Logging proses ekstraksi
                    }
                }
            }
            $zip->close(); // Tutup ZIP setelah selesai
        } else {
            Log::error('❌ Gagal membuka file .docx sebagai ZIP'); // Jika gagal buka DOCX
        }
    }

    /**
     * Simpan gambar ke folder publik jika belum pernah disimpan.
     * Mengembalikan URL publik gambar.
     */
    public function persistImageIfNotExists($key, &$mediaImages)
    {
        // Jika key tidak ada di array mediaImages, hentikan
        if (!isset($mediaImages[$key])) return null;

        $item = $mediaImages[$key]; // Ambil data gambar

        // Jika sudah punya URL publik, langsung kembalikan tanpa simpan ulang
        if (!empty($item['public_url'])) {
            return $item['public_url'];
        }

        $binary = $item['binary']; // Data binary gambar
        $mime = $item['mime'];     // MIME type gambar
        $hash = md5($binary);      // Hash unik berdasarkan isi file (untuk menghindari duplikat)
        $ext = $this->mimeToExtension($mime); // Tentukan ekstensi file dari MIME
        $fileName = "img_$hash.$ext"; // Format nama file
        $path = public_path("soal-pembahasan-image/$fileName"); // Lokasi penyimpanan di folder publik

        // Pastikan folder tujuan ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }
        // Simpan file jika belum ada
        if (!file_exists($path)) {
            file_put_contents($path, $binary);
        }

        // Tentukan URL publik yang bisa diakses
        $publicUrl = "/soal-pembahasan-image/$fileName";
        $mediaImages[$key]['public_url'] = $publicUrl; // Simpan URL ke array
        $this->savedImageHashes[$hash] = $publicUrl;   // Simpan hash untuk tracking duplikat

        Log::info("✅ Gambar disimpan saat valid: $key → $publicUrl"); // Logging penyimpanan

        return $publicUrl; // Kembalikan URL publik
    }

    /**
     * Bersihkan HTML hasil parsing.
     * Menghapus tag <span>, atribut style, dan paragraf kosong.
     */
    public function cleanHtml(string $html): string
    {
        // Hapus semua tag <span ...>
        $html = preg_replace('/<span[^>]*>/i', '', $html);
        // Hapus penutup </span>
        $html = preg_replace('/<\/span>/i', '', $html);
        // Hapus atribut style di semua tag
        $html = preg_replace('/\s?style="[^"]*"/i', '', $html);

        // (Opsional) Hapus paragraf kosong <p></p>
        $html = preg_replace('/<p>\s*<\/p>/i', '', $html);

        return $html; // Kembalikan HTML yang sudah dibersihkan
    }


    /**
     * Mengecek apakah file DOCX memiliki persamaan matematika (Equation).
     */
    public function docxHasEquation($docxPath): bool
    {
        $zip = new ZipArchive();

        // Buka file DOCX (yang sebenarnya adalah ZIP berisi XML)
        if ($zip->open($docxPath) === true) {

            // Ambil isi file utama dokumen Word
            $xml = $zip->getFromName('word/document.xml');

            // Tutup ZIP
            $zip->close();

            // Cek apakah XML mengandung elemen MathML Word (<m:oMath> atau <m:oMathPara>)
            return str_contains($xml, '<m:oMath') || str_contains($xml, '<m:oMathPara');
        }

        // Jika gagal membuka file DOCX, kembalikan false
        return false;
    }

    /**
     * Mengecek apakah file DOCX memiliki list (bullet/numbering).
     */
    public function docxHasList($docxPath): bool
    {
        $zip = new ZipArchive();

        // Buka file DOCX
        if ($zip->open($docxPath) === true) {

            // Ambil isi file utama dokumen Word
            $xml = $zip->getFromName('word/document.xml');

            // Tutup ZIP
            $zip->close();

            // Cek apakah ada elemen list (<w:numPr> untuk properties numbering, <w:ilvl> untuk level list)
            return str_contains($xml, '<w:numPr>') || str_contains($xml, '<w:ilvl>');
        }

        return false;
    }

    /**
     * Menyimpan gambar ke folder publik dan mencegah duplikasi.
     */
    public function saveImage($binaryData, $mime)
    {
        // Buat hash unik dari isi file gambar
        $hash = md5($binaryData);

        // Jika gambar dengan hash ini sudah pernah disimpan, langsung kembalikan URL-nya
        if (isset($this->savedImageHashes[$hash])) {
            return $this->savedImageHashes[$hash];
        }

        // Tentukan ekstensi file berdasarkan MIME type
        $ext = $this->mimeToExtension($mime);

        // Buat nama file unik
        $fileName = "img_$hash.$ext";

        // Path penyimpanan fisik di public/
        $path = public_path("soal-pembahasan-image/$fileName");

        // Jika folder belum ada, buat folder dengan izin 0775
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        // Simpan file jika belum ada
        if (!file_exists($path)) {
            file_put_contents($path, $binaryData);
        }

        // URL publik untuk diakses di HTML
        $publicUrl = "/soal-pembahasan-image/$fileName";

        // Simpan di cache hash agar tidak disimpan ulang
        $this->savedImageHashes[$hash] = $publicUrl;

        return $publicUrl;
    }

    /**
     * Mendeteksi MIME type dari data biner gambar.
     */
    public function detectMimeType($binaryData)
    {
        // Gunakan ekstensi FileInfo PHP untuk mendeteksi MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($binaryData);
    }

    // Mengubah MIME type gambar menjadi ekstensi file
    public function mimeToExtension($mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg', // Jika format JPEG atau JPG, kembalikan ekstensi "jpg"
            'image/png' => 'png',               // Jika format PNG, kembalikan "png"
            'image/gif' => 'gif',               // Jika format GIF, kembalikan "gif"
            'image/bmp' => 'bmp',               // Jika format BMP, kembalikan "bmp"
            'image/webp' => 'webp',             // Jika format WEBP, kembalikan "webp"
            default => 'jpg',                   // Default: gunakan "jpg" jika tidak dikenali
        };
    }

    // Membuat hash unik berdasarkan data biner gambar
    public function generateImageHash($binaryData)
    {
        // md5 digunakan untuk menghasilkan string unik berdasarkan isi file
        return md5($binaryData);
    }

    // Mengganti semua atribut src pada <img> di HTML dengan URL publik gambar yang telah diproses
    public function replaceImageSrc($html, &$mediaImages)
    {
        // Jika HTML kosong atau hanya berisi spasi, langsung kembalikan string kosong
        if (empty(trim($html))) return '';

        // Membuat instance DOMDocument untuk memanipulasi struktur HTML
        $dom = new \DOMDocument();

        // Mengabaikan error parsing HTML agar tidak mengganggu proses
        libxml_use_internal_errors(true);

        // Memuat HTML, memastikan encoding ke HTML entities agar aman untuk karakter khusus
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        // Menghapus daftar error yang mungkin tersimpan
        libxml_clear_errors();

        // Loop semua elemen <img> dalam HTML
        foreach ($dom->getElementsByTagName('img') as $img) {
            if (!$img instanceof \DOMElement) continue;
            // Ambil nilai atribut src asli dari tag <img>
            $originalSrc = $img->getAttribute('src');

            // Mengubah nama file menjadi lowercase, lalu hapus ekstensi (misal .jpg, .png, dll.)
            $key = preg_replace('/\.\w+$/', '', strtolower($originalSrc));

            // Mencoba mendapatkan URL publik gambar, sekaligus menyimpannya jika belum ada
            $imgUrl = $this->persistImageIfNotExists($key, $mediaImages);

            if ($imgUrl) {
                // Jika berhasil mendapatkan URL, ganti src gambar dengan URL tersebut
                $img->setAttribute('src', $imgUrl);
            } else {
                // Jika gagal, tulis log peringatan
                Log::warning("❌ Image not found or failed to persist: $key");
            }
        }

        // Ambil elemen <body> dari HTML yang sudah dimodifikasi
        $body = $dom->getElementsByTagName('body')->item(0);

        // Variabel untuk menyimpan isi HTML di dalam <body>
        $innerHTML = '';

        // Loop semua child nodes di dalam <body> dan gabungkan menjadi satu string HTML
        foreach ($body->childNodes as $child) {
            $innerHTML .= $dom->saveHTML($child);
        }

        // Kembalikan HTML yang sudah diperbarui dengan src gambar baru
        return $innerHTML;
    }


}