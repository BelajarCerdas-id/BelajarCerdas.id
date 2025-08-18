<?php

namespace App\Http\Middleware;

use App\Models\MentorFeatureStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TanyaMentorAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // memeriksa jika user yang login bukan mentor maka lanjut
        if ($user->role !== 'Mentor') {
            return $next($request);
        }

        // memeriksa jika user yang login adalah mentor, maka cek apakah memiliki akses atau tidak
        $hasAccess = MentorFeatureStatus::where('feature_id', 1)->where('mentor_id', $user->id)->where('status_mentor', 'aktif')->exists();

        if (!$hasAccess) {
            return redirect()->back()->with('error-tanya-access-mentor', 'Maaf, kamu tidak memiliki akses untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}