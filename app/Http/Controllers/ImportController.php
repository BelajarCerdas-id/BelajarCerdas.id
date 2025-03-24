<?php

namespace App\Http\Controllers;

use App\Imports\MuridImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importDataMurid(Request $request)
    {
        Excel::import(new MuridImport, $request->file('file'));
        return back()->with('success', 'Data Berhasil Diimport!');
    }
}