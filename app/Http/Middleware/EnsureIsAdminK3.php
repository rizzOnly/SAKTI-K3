<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdminK3
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login → ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Login tapi bukan admin_k3 → tolak akses
        if (!Auth::user()->hasRole('admin_k3')) {
            // Kalau dia dokter/perawat, arahkan ke panel klinik
            if (Auth::user()->hasAnyRole(['dokter', 'perawat'])) {
                return redirect('/klinik');
            }

            // Role tidak dikenal
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Anda tidak memiliki akses ke panel Admin K3.']);
        }

        return $next($request);
    }
}
