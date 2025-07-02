<?php

namespace App\Http\Middleware;

use App\Models\tanyaAccess as ModelsTanyaAccess;
use App\Models\UserAccount;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TanyaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simulasi tanggal (HANYA UNTUK TESTING)
        // $todayDate = Carbon::createFromFormat('Y-m-d', '2025-01-27')->format('Y-m-d');

        $userTanya = UserAccount::where('id', Auth::user()->id)->first();

        $now  = Carbon::now();
        $todayDate = Carbon::now()->format('Y-m-d');
        $dayOfWeek = now()->format('l');

        // Batas waktu akses tanya (07:00 - 21:00) (student)
        // $startTimeStudent = Carbon::createFromTime(7, 0, 0);
        // $endTimeStudent = Carbon::createFromTime(21, 0, 0);

        // // Batas waktu akses tanya (07:00 - 21:00) (mentor)
        // $startTimeMentor = Carbon::createFromTime(7, 0, 0);
        // $endTimeMentor = Carbon::createFromTime(21, 30, 0);

        // // Cek waktu akses tanya (student)
        // if($userTanya->role == 'Siswa' || $userTanya->role == 'Murid') {
        //     if(!$now->between($startTimeStudent, $endTimeStudent)) {
        //         return redirect()->route('beranda')->with('alertAccess', 'Maaf, halaman ini hanya bisa diakses pada pukul 07:00 - 21:00.');
        //     }
        // }

        // // Cek waktu akses tanya (mentor)
        // if($userTanya->role == 'Mentor') {
        //     if(!$now->between($startTimeMentor, $endTimeMentor)) {
        //         return redirect()->route('beranda')->with('alertAccess', 'Maaf, halaman ini hanya bisa diakses pada pukul 07:00 - 21:30.');
        //     }
        // }

        // $getDateTanyaAccess = ModelsTanyaAccess::where('status_access', 'Aktif')->first();

        // // libur pendidikan (libur semester, kenaikan kelas, awal puasa, dll)
        // if($getDateTanyaAccess) {
        //     return redirect()->route('beranda')->with('alertAccess', 'Maaf, halaman ini tidak bisa diakses pada saat hari libur.');
        // }

        // // libur weekends
        // if (in_array($dayOfWeek, ['Saturday', 'Monday'])) {
        //     return redirect()->route('beranda')->with('alertAccess', 'Maaf, halaman ini hanya bisa diakses pada hari Senin - Jumat.');
        // }

        // // libur nasional
        // // $response = Http::get('https://dayoffapi.vercel.app/api');
        // $response = Http::get('https://api-harilibur.vercel.app/api');

        // $holidays = collect($response->json() ?? [])
        //     ->where('is_national_holiday', true)
        //     ->map(fn($holiday) => Carbon::parse($holiday['holiday_date'])->format('Y-m-d'))
        //     ->toArray();

        // if (in_array($todayDate, $holidays, true)) {
        //     return redirect()->route('beranda')->with('alertAccess', 'Maaf, halaman ini tidak bisa diakses pada hari libur nasional.');
        // }
        return $next($request);
    }
}