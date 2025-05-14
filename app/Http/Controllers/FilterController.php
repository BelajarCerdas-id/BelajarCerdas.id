<?php

namespace App\Http\Controllers;

use App\Models\Star;
use App\Models\Tanya;
use App\Models\Keynote;
use Illuminate\Http\Request;
use App\Models\englishZoneSoal;
use App\Models\Transactions;
use App\Models\userAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class FilterController extends Controller
{
    public function filterHistoryStudent(Request $request)
    {

        $query = Tanya::onlyTrashed()->where('user_id', Auth::user()->id);

        // Apply the status filter if provided
        if ($request->filled('status_soal') && $request->status_soal !== 'semua') {
            $query->where('status_soal', $request->status_soal);
        }

        // Paginate the filtered results
        $data = $query->with('Mapel', 'Bab')->orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(), // Convert pagination links to string
            'restoreUrl' => route('tanya.updateStatusSoalRestore', ':id')
        ]);
    }

    public function filterHistoryTeacher(Request $request)
    {
        $query = Tanya::onlyTrashed()->where('mentor_id', Auth::user()->id);

        if($request->filled('status_soal') && $request->status_soal !== 'semua') {
            $query->where('status_soal', $request->status_soal);
        }

        $data = $query->with('Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab')->orderBy('updated_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $data->items(),
            'links' => (string) $data->links(),
            'restoreUrl' => route('getRestore.edit', ':id')
        ]);
    }


    public function filterTanyaTeacher(Request $request)
    {
        $query = Tanya::query();

        if($request->filled('status_soal') && $request->status !== 'semua') {
            $query->where('status_soal', $request->status);
        }

        $data = $query->with('Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab')->orderBy('created_at', 'desc')->paginate(10);

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

        $query = userAccount::where('status', 'Mentor');
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

    public function paginateHistoryPurchaseSuccess(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->where('transaction_status', 'Berhasil')->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
        ]);
    }

    public function paginateHistoryPurchaseWaiting(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->where('transaction_status', 'Pending')->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
        ]);
    }

    public function paginateHistoryPurchaseFailed(Request $request)
    {
        $user = Auth::user();

        $transactions = Transactions::with(['UserAccount.StudentProfiles','Features', 'FeaturePrices'])->where('user_id', $user->id)
        ->whereIn('transaction_status', ['Gagal', 'Kadaluarsa'])->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $transactions->items(),
            'links' => (string) $transactions->links()->render(),
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
