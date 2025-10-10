<?php

namespace App\Imports;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Imports\SchoolPartnerHandler\SoalPembahasanHandler;
use Exception;

class SchoolPartnerImport implements ToCollection, WithHeadingRow, WithStartRow, WithTitle
{
    protected $userId;
    protected $sheetTitle = '';

    // Konstruktor: akan dijalankan saat class diinisialisasi
    public function __construct($userId, $sheetTitle = '')
    {
        $this->userId = $userId;          // Simpan userId dari parameter
        $this->sheetTitle = $sheetTitle;  // Simpan nama sheet
    }

    // Daftar handler yang digunakan untuk setiap fitur
    protected $handlers = [
        'Soal dan Pembahasan' => SoalPembahasanHandler::class,
        // tambahkan handler fitur lain di sini...
    ];

    // Fungsi untuk mengembalikan nama sheet saat import berjalan
    public function title(): string
    {
        return $this->sheetTitle;
    }

    // Baris ke berapa dianggap sebagai header (judul kolom)
    public function headingRow(): int
    {
        return 2; // artinya baris ke-2 adalah header kolom
    }

    // Baris ke berapa data mulai dibaca
    public function startRow(): int
    {
        return 3; // artinya data dimulai dari baris ke-3 (setelah header)
    }

    // Fungsi wajib dari ToCollection → menerima semua baris dari sheet Excel dalam bentuk Collection
    public function collection(Collection $rows)
    {
        // Panggil fungsi handle untuk memproses data dari setiap baris
        $this->handle($rows);
    }

    // Fungsi utama untuk memproses data dari setiap sheet
    public function handle(Collection $rows)
    {
        // Jika sheet kosong → langsung lempar error
        if ($rows->isEmpty()) {
            throw new Exception("Sheet '{$this->sheetTitle}' kosong atau tidak terbaca.");
        }

        // Array untuk menyimpan data yang akan dikirim ke setiap handler
        $rowsByHandler = [];

        // Menyimpan daftar nama fitur yang ditemukan
        $collectedFeatures = [];

        // Menyimpan daftar error fitur yang tidak valid
        $invalidFeatures = [];

        // Loop semua baris di sheet Excel
        foreach ($rows as $index => $row) {
            // Hitung nomor baris sebenarnya di Excel (karena startRow = 3)
            $rowNumber = $index + 3;

            // Normalisasi nama fitur:
            // 1. Ganti spasi ganda dan karakter tak terlihat (U+00A0) jadi spasi biasa
            // 2. Ubah semua ke huruf kecil agar mudah dibandingkan
            $featureName = preg_replace('/\x{00A0}|\s+/u', ' ', strtolower(($row['pembelian_fitur'] ?? '')));

            // Jika kolom "pembelian_fitur" kosong → lewati baris
            if (empty($featureName)) continue;

            // Simpan nama fitur untuk pengecekan nanti
            $collectedFeatures[] = $featureName;

            // Cek apakah fitur ini cocok dengan salah satu handler yang terdaftar
            $matchedKey = collect($this->handlers)
                ->keys() // ambil semua nama fitur yang ada di daftar handler
                ->first(fn($key) => str_contains($featureName, strtolower(trim($key)))); 
                // cari yang cocok (misal featureName mengandung "soal dan pembahasan")

            // Jika cocok, simpan baris ke handler yang sesuai
            if ($matchedKey) {
                $rowsByHandler[$matchedKey][] = $row;
            } else {
                // Jika tidak cocok, catat error bahwa fitur ini tidak terdaftar
                $invalidFeatures[] = "Sheet {$this->sheetTitle} - Baris {$rowNumber}: Fitur '{$row['pembelian_fitur']}' tidak terdaftar.";
            }
        }

        // Jika tidak ada fitur yang invalid, pastikan semua baris punya fitur yang sama
        if (empty($invalidFeatures)) {
            // Ambil fitur unik dari semua baris
            $uniqueFeatures = collect($collectedFeatures)->unique();

            // Jika dalam satu sheet ditemukan lebih dari satu fitur berbeda, itu tidak diperbolehkan
            if ($uniqueFeatures->count() > 1) {
                $invalidFeatures[] = "Sheet {$this->sheetTitle} memiliki lebih dari satu fitur berbeda (" . implode(', ', $uniqueFeatures->toArray()) . ").";
            }
        }

        // Jika ada fitur yang tidak valid → hentikan proses dan tampilkan pesan error
        if (!empty($invalidFeatures)) {
            throw ValidationException::withMessages(['import' => $invalidFeatures]);
        }

        // Jalankan handler yang sesuai dengan fitur yang ditemukan
        foreach ($rowsByHandler as $featureKey => $featureRows) {
            $handlerClass = $this->handlers[$featureKey];         // Ambil nama class handler
            $handler = new $handlerClass($this->userId, $this->sheetTitle); // Buat instance handler

            // Pastikan handler memiliki metode 'handle'
            if (!method_exists($handler, 'handle')) {
                throw new Exception("Handler '{$handlerClass}' tidak memiliki metode handle().");
            }

            // Jalankan handler untuk memproses semua baris fitur tersebut
            $handler->handle(collect($featureRows));
        }
    }
}