<?php

namespace App\Http\Middleware;

use App\Models\tanyaAccess as ModelsTanyaAccess;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
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
        // $today = Carbon::createFromFormat('Y-m-d', '2025-01-27')->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');
        $dayOfWeek = now()->format('l');

        $getDateTanyaAccess = ModelsTanyaAccess::where('status_access', 'Aktif')->first();

        // libur pendidikan (libur semester, kenaikan kelas, awal puasa, dll)
        if($getDateTanyaAccess) {
            // return redirect()->route('beranda')->with('alertAccess', 'Halaman ini tidak bisa diakses pada tanggal ' . $getDateTanyaAccess[0]->tanggal_mulai->format('d-m-Y') . ' - ' . $getDateTanyaAccess[0]->tanggal_akhir);
            return redirect()->route('beranda')->with('alertAccess', 'Halaman ini tidak bisa diakses pada saat hari libur!.');
        }

        // libur weekends
        // if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
        //     return redirect()->route('beranda')->with('alertAccess', 'Halaman ini hanya bisa diakses pada hari Senin - Jumat.');
        // }

        // libur nasional
        // $response = Http::get('https://dayoffapi.vercel.app/api');
        // $response = Http::get('https://api-harilibur.vercel.app/api');

        // $holidays = collect($response->json() ?? [])
        //     ->where('is_national_holiday', true)
        //     ->map(fn($holiday) => Carbon::parse($holiday['holiday_date'])->format('Y-m-d'))
        //     ->toArray();

        // if (in_array($today, $holidays, true)) {
        //     return redirect()->route('beranda')->with('alertAccess', 'Halaman ini tidak bisa diakses pada hari libur nasional.');
        // }
        return $next($request);
    }
}