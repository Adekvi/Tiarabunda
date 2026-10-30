<?php

use App\Http\Middleware\Security\HeaderSecurity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Halaman publik: tanpa session/cookie, di-cache browser & CDN
            $age = app()->isProduction() ? 300 : 0;

            Route::middleware(['landing', "cache.headers:public;max_age=$age;etag"])
                ->group(base_path('routes/landing.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('landing', [SubstituteBindings::class]);
        $middleware->group('landing', [SubstituteBindings::class, HeaderSecurity::class]);
        $middleware->appendToGroup('web', HeaderSecurity::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
