<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SyllabusSheetImport implements WithMultipleSheets
{
    protected $userId;
    protected $file;

    public function __construct($userId, $file)
    {
        $this->userId = $userId;
        $this->file = $file;
    }

    public function sheets(): array
    {
        // Inisialisasi array kosong untuk menyimpan semua sheet yang akan diimpor
        $sheets = [];

        // Load file Excel (.xlsx) ke dalam objek Spreadsheet
        // $this->file adalah file yang dikirim dari form upload
        // getRealPath() memberikan path file sementara yang bisa dibaca oleh PhpSpreadsheet
        $spreadsheet = IOFactory::load($this->file->getRealPath());

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            // Buat instance SyllabusImport untuk tiap sheet. contoh:
            // Sheet dengan nama 'Bulk_Upload_Math' akan di-handle oleh SyllabusImport($userId, 'Bulk_Upload_Math')
            $sheets[$sheetName] = new SyllabusImport($this->userId, $sheetName);
        }

        return $sheets;
    }
}