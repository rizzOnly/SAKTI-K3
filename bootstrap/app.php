<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware alias untuk Laravel 11
        $middleware->alias([
            'ensure.admin_k3' => \App\Http\Middleware\EnsureIsAdminK3::class,
            'ensure.klinik'   => \App\Http\Middleware\EnsureIsDokter::class,
        ]);

        // Opsional: Paksa laravel mengarahkan user yang belum login ke /login custom
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
