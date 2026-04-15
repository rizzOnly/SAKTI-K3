<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdminK3
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin_k3')) {
            abort(403, 'Akses ditolak. Anda bukan Admin K3.');
        }

        return $next($request);
    }
}
