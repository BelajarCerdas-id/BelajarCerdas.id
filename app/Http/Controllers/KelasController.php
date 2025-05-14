<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\BabFeatureStatus;
use App\Models\Fase;
use App\Models\Kelas;
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
        $kelas = Kelas::where('fase_id', $id)->get();
        return response()->json($kelas);
    }

    public function getMapel($id)
    {
        $mata_pelajaran = Mapel::where('fase_id', $id)->get()->where('status_mata_pelajaran', 'publish');
        return response()->json($mata_pelajaran);
    }

    public function getBab($mapel_id, $fase_id)
    {
        $BabFeaturesStatus = BabFeatureStatus::where('status_bab', 'publish')->pluck('bab_id');
        $bab = Bab::where('mapel_id', $mapel_id)->where('fase_id', $fase_id)->whereIn('id', $BabFeaturesStatus)->get();
        return response()->json($bab);
    }
}