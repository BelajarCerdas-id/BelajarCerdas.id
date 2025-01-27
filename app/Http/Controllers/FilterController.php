<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\Star;
use App\Models\Tanya;
use App\Models\Keynote;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use Illuminate\Support\Facades\Log;

class FilterController extends Controller
{
public function filterHistoryStudent(Request $request)
{
    $user = session('user');

    if (!isset($user->email)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $query = Tanya::onlyTrashed()->where('email', $user->email);

    // Apply the status filter if provided
    if ($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    // Paginate the filtered results
    $data = $query->orderBy('created_at', 'desc')->paginate(5);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(), // Convert pagination links to string
        'restoreUrl' => route('getRestore.edit', ':id')
    ]);
}

public function filterHistoryTeacher(Request $request)
{
    $user = session('user');

    if (!isset($user->email)) {
        return redirect('/login');
    }

    $query = Tanya::onlyTrashed()->where('email_mentor', $user->email);

    if($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'restoreUrl' => route('getRestore.edit', ':id')
    ]);
}


public function filterTanyaTeacher(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
        return redirect('/login');
    }

    $query = Tanya::query();

    if($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'restoreUrl' => route('tanya.edit', ':id')
    ]);
}

public function filterTanyaTL(Request $request)
{
    $user = session('user');

    if(!isset($user)) {
        return redirect('/login');
    }

    $query = Tanya::query();

    if($request->filled('status') && $request->status !== 'semua'){
        $query->where('status', $request->status );
    }

    $data = $query->onlyTrashed()->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'restoreUrl' => route('tanya.edit', ':id')
    ]);
}

public function filterClassNote(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
            return redirect('/login');
    }

    $query = Keynote::query();

    if ($request->filled('kelas_catatan') && $request->kelas_catatan !== 'semua') {
        $query->where('kelas_catatan', $request->kelas_catatan)->orWhere('mapel', $request->mapel);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(12);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
    ]);
}

public function filterMapelNote(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
            return redirect('/login');
    }

    $query = Keynote::query();

    if($request->filled('mapel') && $request->mapel != 'semua') {
            $query->where('mapel', $request->mapel);
    };

    $data = $query->orderBy('created_at', 'desc')->paginate(12);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
    ]);
}

public function filterListMentor()
{
    $user = session('user');

    $query = Crud::where('status', 'Mentor');
    $countData = [];

    $countData = $query->get()->mapWithKeys(function ($item) {
        return [$item->email => Tanya::onlyTrashed()->where('email_mentor', $item->email)->count()];
    });

    $data = $query->orderBy('created_at', 'desc')->paginate(5);

    return response()->json([
        'countData' => $countData,
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'url' => route('laporan.edit', ':id')
    ]);
}

public function questionStatus(Request $request)
{
    $user = session('user');

    $query = englishZoneSoal::query()->groupBy('soal');

    // filtering by status_soal
    if ($request->filled('status_soal') && $request->status_soal !== 'semua') {
    $query->where('status_soal', $request->status_soal);
    }

    // filtering by modul
    if ($request->filled('modul_soal') && $request->modul_soal !== 'semua') {
        $query->where('modul_soal', $request->modul_soal);
    }

    // filtering by jenjang
    if($request->filled('jenjang') && $request->jenjang !== 'semua') {
        $query->where('jenjang', $request->jenjang);
    }

    // Paginate the filtered results
    $data = $query->orderBy('created_at', 'desc')->paginate(20);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links()
    ]);
}

// public function filterViewLaporanTL()
// {
//     $mentor = Crud::where('status', 'Mentor');

//         // Ambil semua pertanyaan yang di-trashed berdasarkan email_mentor
//         $query = Tanya::onlyTrashed()->where('email_mentor', $mentor->email);

//         $data = $query->orderBy('created_at', 'desc')->paginate(5);
//         // Mengambil status "Diterima" dan "Ditolak" dari tabel Star
//         $statusStar = Star::whereIn('id_tanya', $data->pluck('id'))->get()->keyBy('id_tanya');

//         return response()->json([
//             'data' => $data->items(),
//             'links' => (string) $data->links(),
//             'statusStar' => $statusStar
//         ]);
// }





}