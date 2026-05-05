<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDokter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole(['dokter', 'perawat'])) {
            abort(403, 'Akses ditolak. Anda bukan Dokter atau Perawat.');
        }

        return $next($request);
    }
}
