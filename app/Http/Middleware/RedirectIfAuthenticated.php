<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login, jika suda maka redirect ke halaman beranda
        if (Auth::check()) {
            return redirect('/beranda');
        }

        $response = $next($request);

        // Rekonstruksi response agar bisa ditambahkan header
        $response = response($response->getContent(), $response->getStatusCode(), $response->headers->all());

        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }

}