<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoalPembahasanController extends Controller
{
    // CONTROLLER FOR ADMINISTRATOR
    public function bankSoalView()
    {
        return view('Features.soal-pembahasan.bank-soal');
    }
}
