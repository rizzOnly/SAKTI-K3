<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDokter
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login → ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Login tapi bukan dokter/perawat → tolak akses
        if (!Auth::user()->hasAnyRole(['dokter', 'perawat'])) {
            // Kalau dia admin_k3, arahkan ke panel yang benar
            if (Auth::user()->hasRole('admin_k3')) {
                return redirect('/admin');
            }

            // Role tidak dikenal
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Anda tidak memiliki akses ke panel Klinik.']);
        }

        return $next($request);
    }
}
