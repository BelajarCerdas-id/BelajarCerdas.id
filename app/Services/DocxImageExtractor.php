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

class DocxImageExtractor
{
    // Mengubah elemen Word menjadi HTML styled (warna, ukuran, gambar)
    public function getStyledHtmlFromElement($element, &$mediaImages = [])
    {
        $html = '';

        if ($element instanceof Text) {
            // Elemen teks biasa
            $text = $element->getText();
            $style = $element->getFontStyle();

            $styleAttr = '';
            $prefix = '';
            $suffix = '';

            if ($style) {
                // Warna teks
                if ($color = $style->getColor()) {
                    $styleAttr .= "color:#{$color};";
                }

                // Ukuran font dari pt ke px (1pt ≈ 1.33px)
                if ($size = $style->getSize()) {
                    $styleAttr .= "font-size:" . ($size * 1.33) . "px;";
                }

                // Font-family
                if ($fontName = $style->getName()) {
                    $styleAttr .= "font-family:'{$fontName}';";
                }

                // Bold dan italic menggunakan tag
                if ($style->isBold()) {
                    $prefix .= '<strong>';
                    $suffix = '</strong>' . $suffix;
                }
                if ($style->isItalic()) {
                    $prefix .= '<em>';
                    $suffix = '</em>' . $suffix;
                }

                // Underline dan strike-through
                if ($style->getUnderline() !== 'none') {
                    $styleAttr .= "text-decoration:underline;";
                }
                if ($style->isStrikeThrough()) {
                    $styleAttr .= "text-decoration:line-through;";
                }

                // Superscript dan subscript
                if ($style->isSubScript()) {
                    $prefix .= '<sub>';
                    $suffix = '</sub>' . $suffix;
                }
                if ($style->isSuperScript()) {
                    $prefix .= '<sup>';
                    $suffix = '</sup>' . $suffix;
                }

                // Highlight warna background
                $highlight = null;
                if ($style && method_exists($style, 'getStyleValues')) {
                    $values = $style->getStyleValues();
                    if (isset($values['style']['fgColor'])) {
                        $highlight = strtolower($values['style']['fgColor']);
                    }
                }

                if ($highlight) {
                    $highlightMap = [
                        'yellow' => '#ffff00', 'green' => '#00ff00', 'cyan' => '#00ffff',
                        'magenta' => '#ff00ff', 'blue' => '#0000ff', 'red' => '#ff0000',
                        'darkblue' => '#00008b', 'darkred' => '#8b0000', 'darkgreen' => '#006400',
                        'darkcyan' => '#008b8b', 'darkmagenta' => '#8b008b', 'gray' => '#808080',
                        'lightgray' => '#d3d3d3',
                    ];
                    if (preg_match('/^[0-9a-f]{6}$/i', $highlight)) {
                        $styleAttr .= "background-color:#{$highlight};";
                    } elseif (isset($highlightMap[$highlight])) {
                        $styleAttr .= "background-color:{$highlightMap[$highlight]};";
                    }
                }

                // Bungkus teks jika ada style
                if (!empty($styleAttr)) {
                    $text = "<span style=\"$styleAttr\">$text</span>";
                }

                $text = $prefix . $text . $suffix;
            }

            $html .= $text;

        } elseif ($element instanceof TextRun) {
            // Elemen yang berisi kumpulan teks/gambar
            foreach ($element->getElements() as $child) {
                $html .= $this->getStyledHtmlFromElement($child, $mediaImages);
            }

        } elseif ($element instanceof Image) {
            // Gambar dari dokumen Word
            $binary = base64_decode($element->getImageStringData(true));
            $key = $this->generateImageHash($binary);

            $imgUrl = "PLACEHOLDER:$key";
            $mediaImages[$key] = [
                'binary' => $binary,
                'mime' => $this->detectMimeType($binary),
                'path' => null,
            ];

            $html .= "<p><img src=\"$imgUrl\"></p>";

        } elseif ($element instanceof Table) {
            // Tabel: proses semua sel
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    foreach ($cell->getElements() as $cellElement) {
                        $html .= $this->getStyledHtmlFromElement($cellElement, $mediaImages);
                    }
                }
            }
        }

        return $html;
    }

    // Proses array elemen dengan deteksi List (ol/ul)
    public function getStyledHtmlFromElements(array $elements, &$mediaImages = [])
    {
        $html = '';
        $listBuffer = [];
        $listTag = null;

        foreach ($elements as $element) {
            if ($element instanceof ListItem) {
                $text = $this->getStyledHtmlFromElement($element->getTextObject(), $mediaImages);
                $styleName = $element->getStyle();
                $isNumbered = is_string($styleName) && str_starts_with(strtolower($styleName), 'number');
                $currentTag = $isNumbered ? 'ol' : 'ul';

                if ($listTag && $listTag !== $currentTag) {
                    // Flush list buffer with previous tag
                    $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
                    $listBuffer = [];
                }

                $listTag = $currentTag;
                $listBuffer[] = $text;

            } else {
                if ($listBuffer && $listTag) {
                    // Flush buffer before non-list element
                    $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
                    $listBuffer = [];
                    $listTag = null;
                }

                $html .= $this->getStyledHtmlFromElement($element, $mediaImages);
            }
        }

        // Flush if ends with list
        if ($listBuffer && $listTag) {
            $html .= "<$listTag><li>" . implode('</li><li>', $listBuffer) . "</li></$listTag>";
        }

        return $html;
    }


    // Ambil data dari tabel Word dengan format HTML styled
    public function extractStyledTableData($phpWord, &$mediaImages)
    {
        $styledData = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof Table) {
                    foreach ($element->getRows() as $row) {
                        $cells = $row->getCells();
                        if (count($cells) < 2) continue;

                        // Ambil key dan value dari tabel (kolom 0 dan 1)
                        $key = strtoupper(trim(strip_tags($this->getStyledHtmlFromCell($cells[0], $mediaImages))));
                        $value = $this->getStyledHtmlFromCell($cells[1], $mediaImages);

                        if (!empty($key)) {
                            $styledData[$key] = $value;
                        }
                    }
                }
            }
        }

        return $styledData;
    }

    // Ambil isi HTML styled dari sebuah sel tabel
    public function getStyledHtmlFromCell($cell, &$mediaImages = [])
    {
        $html = '';

        if (method_exists($cell, 'getElements')) {
            $html .= $this->getStyledHtmlFromElements($cell->getElements(), $mediaImages);
        }

        return $html;
    }

    // Ekstraksi gambar dari elemen Word dan simpan unik berdasarkan hash
    public int $currentImageCounter = 1;
    public function extractImages($element, &$mediaImages)
    {
        if ($element instanceof Image) {
            $binary = base64_decode($element->getImageStringData(true));
            $key = $this->generateImageHash($binary);

            if (!isset($mediaImages[$key])) {
                $mediaImages[$key] = [
                    'binary' => $binary,
                    'mime' => $this->detectMimeType($binary),
                    'path' => null,
                ];
            }

        } elseif ($element instanceof TextRun) {
            foreach ($element->getElements() as $child) {
                $this->extractImages($child, $mediaImages); // Rekursif
            }
        } elseif ($element instanceof Table) {
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    foreach ($cell->getElements() as $cellElement) {
                        $this->extractImages($cellElement, $mediaImages); // Rekursif
                    }
                }
            }
        }
    }

    // Gabungkan HTML dari Pandoc dan PhpWord
    public function mergeStyledAndPandocHtml($pandocHtml, $styledHtml, $mediaImages)
    {
        // Load DOM hasil Pandoc
        $pandocDom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $pandocDom->loadHTML(mb_convert_encoding($pandocHtml, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        // Load DOM styled hasil PhpWord
        $styledDom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $styledDom->loadHTML(mb_convert_encoding($styledHtml, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        // Perbaiki <img src="media/image1.png"> dari hasil Pandoc
        foreach ($pandocDom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');
            $key = preg_replace('/\.\w+$/', '', strtolower($src)); // Contoh: media/image1.jpg → media/image1

            if (isset($mediaImages[$key])) {
                $img->setAttribute('src', $mediaImages[$key]);
            }
        }

        // Ambil isi body dari kedua dokumen
        $pandocBody = $pandocDom->getElementsByTagName('body')->item(0);
        $styledBody = $styledDom->getElementsByTagName('body')->item(0);

        $resultHtml = '';

        for ($i = 0; $i < $pandocBody->childNodes->length; $i++) {
            $pandocChild = $pandocBody->childNodes->item($i);
            $pandocHtmlChunk = $pandocDom->saveHTML($pandocChild);

            // Jika chunk dari Pandoc mengandung MathML, pertahankan chunk Pandoc
            if (str_contains($pandocHtmlChunk, '<math')) {
                $resultHtml .= $pandocHtmlChunk;
            } else {
                // Ambil styledHtml dari index yang sama jika ada
                $styledChild = $styledBody->childNodes->item($i);
                $styledHtmlChunk = $styledChild ? $styledDom->saveHTML($styledChild) : null;

                // Gunakan styledHtml jika ada, fallback ke Pandoc
                $resultHtml .= $styledHtmlChunk ?: $pandocHtmlChunk;
            }
        }

        return $resultHtml;
    }

    // Simpan gambar ke folder public dan kembalikan URL-nya
    public function saveImage($binaryData, $mime)
    {
        $ext = $this->mimeToExtension($mime);
        $hash = $this->generateImageHash($binaryData);
        $fileName = "img_$hash.$ext"; // Gunakan hash sebagai nama file
        $path = public_path("soal-pembahasan-image/$fileName");

        // Buat folder jika belum ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        // Simpan file
        file_put_contents($path, $binaryData);

        // Return URL publik
        return "/soal-pembahasan-image/$fileName";
    }

    // Deteksi mime dari data biner
    public function detectMimeType($binaryData)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($binaryData);
    }

    // Konversi mime ke ekstensi file
    public function mimeToExtension($mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }

    // function agar image yang sama tidak masuk ke folder public duplikat
    public function generateImageHash($binaryData)
    {
        return md5($binaryData);
    }

    // Cek apakah HTML masih kosong (jika kosong hapus tag html)
    public function isHtmlEmpty($html)
    {
        $text = trim(strip_tags($html));

        // Jika tidak ada teks, cek apakah ada <img> tag
        if (empty($text)) {
            return !str_contains($html, '<img');
        }

        return false;
    }

    // Ganti semua placeholder dengan path public
    public function replaceAllPlaceholdersWithPath($html, $mediaImages)
    {
        foreach ($mediaImages as $key => $img) {
            if (!empty($img['path'])) {
                $html = str_replace("src=\"PLACEHOLDER:$key\"", "src=\"{$img['path']}\"", $html);
            }
        }
        return $html;
    }


    // Ganti src pada HTML hasil Pandoc berdasarkan gambar yang sudah diekstrak
    public function replaceImageSrc($html, &$mediaImages)
    {
        // Jika HTML kosong atau hanya berisi spasi, hentikan proses dan kembalikan string kosong
        if (empty(trim($html))) {
            return '';
        }

        // Buat DOMDocument untuk parsing HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // Hindari warning libxml jika HTML tidak valid
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')); // Parse HTML
        libxml_clear_errors();

        // Loop semua elemen <img> di dalam HTML
        foreach ($dom->getElementsByTagName('img') as $img) {
            // Ambil src asli dari tag <img>
            $originalSrc = $img->getAttribute('src');
            // Ambil nama file (tanpa ekstensi), misal: media/image1.jpg → media/image1
            $key = preg_replace('/\.\w+$/', '', strtolower($originalSrc)); // media/image1.jpg → media/image1

            // Jika gambar belum tercatat di $mediaImages, tambahkan entri kosong untuk placeholder
            if (!isset($mediaImages[$key])) {
                $mediaImages[$key] = [
                    'binary' => null,   // hanya sebagai penanda key
                    'mime' => null,
                    'path' => null,
                ];
            }

            // Ganti src asli dengan placeholder agar nanti bisa diganti setelah file disimpan
            $img->setAttribute('src', "PLACEHOLDER:$key");
        }

        // Ambil inner HTML dari body
        $body = $dom->getElementsByTagName('body')->item(0);
        $innerHTML = '';
        foreach ($body->childNodes as $child) {
            $innerHTML .= $dom->saveHTML($child); // Gabungkan semua konten <body>
        }

        // Kembalikan HTML yang sudah diperbarui
        return $innerHTML;
    }

}
