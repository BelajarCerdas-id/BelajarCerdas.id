<?php

namespace App\Http\Middleware;

use App\Models\dataSuratPks;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEnglishZone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {

    //     $user = session('user');
    //     // Cek apakah ada paket "englishZone" yang sedang aktif untuk sekolah user
    //     $paketAktif = dataSuratPks::where('status_paket_kerjasama', 'Sedang Aktif')
    //         ->where('paket_kerjasama', 'englishZone')
    //         ->where('sekolah', $user->sekolah) // Pastikan user memiliki 'sekolah'
    //         ->exists();

    //     if (!$paketAktif) {
    //         return redirect()->route('beranda')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    //     }

    //     return $next($request);
    // }
}