<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FeaturePurchaseStudentOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // memeriksa ada user yang login atau tidak, jika ada maka lanjut
        if ($user) {
            // memeriksa jika user yang login adalah siswa, maka lanjut
            if ($user && $user->role === 'Siswa') {
                return $next($request);
            }

            // Role lain diarahkan kembali
            return redirect()->back()->with('error-access-feature-purchase-view','Maaf, hanya siswa yang dapat mengakses halaman pembelian fitur.');
        }

        return $next($request);
    }
}
