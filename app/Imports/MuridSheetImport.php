<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MuridSheetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new MuridImport()
        ];
    }
}
