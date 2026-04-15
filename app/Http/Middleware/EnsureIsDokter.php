<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDokter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('dokter')) {
            abort(403, 'Akses ditolak. Anda bukan Dokter.');
        }

        return $next($request);
    }
}
