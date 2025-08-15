<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\BabFeatureStatus;
use App\Models\Fase;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SubBab;
use App\Models\SubBabFeatureStatus;
use Illuminate\Http\Request;
use App\Models\Tanya;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MasterAcademicController extends Controller
{
    public function fase()
    {
        return view('Auth.daftar', [
            'fase' => Fase::all()
        ]);
    }

    // GET KELAS BY FASE
    public function getKelas($id)
    {
        $kelas = Kelas::where('fase_id', $id)->get();
        return response()->json($kelas);
    }

    // GET KELAS BY KURIKULUM
    public function getKelasByKurikulum($id)
    {
        $kelas = Kelas::where('kurikulum_id', $id)->get();
        return response()->json($kelas);
    }

    // GET MAPEL BY FASE
    public function getMapel($id)
    {
        $user = Auth::user();

        $date = Carbon::now()->format('Y-m-d');

        $mata_pelajaran = Mapel::where('fase_id', $id)->get()->where('status_mata_pelajaran', 'publish');

        // menghitung jumlah tanya harian siswa
        $countTanyaDaily = Tanya::where('user_id', $user->id)->whereDate('created_at', $date)->count();

        // menentukan batas tanya harian
        $maxTanyaDaily = 3;

        // menghitung sisa tanya harian (max 0 untuk mencegah pengurangan minus)
        $remainingTanyaDaily = max(0,$maxTanyaDaily - $countTanyaDaily);

        return response()->json([
            'mata_pelajaran' => $mata_pelajaran,
            'countTanyaDaily' => $countTanyaDaily,
            'remainingTanyaDaily' => $remainingTanyaDaily
        ]);
    }

    // GET MAPEL BY KELAS
    public function getMapelByKelas($id)
    {
        $mata_pelajaran = Mapel::where('kelas_id', $id)->get()->where('status_mata_pelajaran', 'publish');
        return response()->json($mata_pelajaran);
    }

    // GET BAB BY FEATURE (TANYA)
    public function getBabTanyaFeature($mapel_id)
    {
        $BabFeaturesStatus = BabFeatureStatus::where('status_bab', 'publish')->where('feature_id', 1)->pluck('bab_id');
        $bab = Bab::where('mapel_id', $mapel_id)->whereIn('id', $BabFeaturesStatus)->get();
        return response()->json($bab);
    }

    // GET BAB BY FEATURE (SOAL PEMBAHASAN)
    public function getBabSoalPembahasanFeature($mapel_id)
    {
        $BabFeaturesStatus = BabFeatureStatus::where('status_bab', 'publish')->where('feature_id', 2)->pluck('bab_id');
        $bab = Bab::where('mapel_id', $mapel_id)->whereIn('id', $BabFeaturesStatus)->get();
        return response()->json($bab);
    }

    // GET SUB BAB BY FEATURE (SOAL PEMBAHASAN)
    public function getSubBabSoalPembahasanFeature($bab_id)
    {
        $subBabFeaturesStatus = SubBabFeatureStatus::where('status_sub_bab', 'publish')->where('feature_id', 2)->pluck('sub_bab_id');
        $subBab = SubBab::where('bab_id', $bab_id)->whereIn('id', $subBabFeaturesStatus)->get();
        return response()->json($subBab);
    }
}