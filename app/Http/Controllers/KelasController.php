<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\Fase;
use App\Models\Level;
use App\Models\Mapel;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function fase()
    {
        return view('Auth.daftar', [
            'fase' => Fase::all()
        ]);
    }

    public function getKelas($id)
    {
        $kelas = Level::where('kode_fase', $id)->get();
        return response()->json($kelas);
    }

    public function getMapel($id)
    {
        $mapel = Mapel::where('kode_kelas', $id)->get();
        return response()->json($mapel);
    }
    
    public function getBab($id)
    {
        $bab = Bab::where('kode_mapel', $id)->get();
        return response()->json($bab);
    }
}


