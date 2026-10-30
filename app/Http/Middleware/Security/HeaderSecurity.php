<?php

namespace App\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeaderSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Dashboard masih memakai CDN; hapus dua host ini setelah di-self-host
        // $script = $request->is('superadmin*')
        //     ? "'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net"
        //     : "'self'";

        $response->headers->add([
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "script-src 'self'",   // hanya script dari domain kamu
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
                "font-src 'self' https://fonts.gstatic.com",
                "img-src 'self' data:",
                "connect-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                'upgrade-insecure-requests',
            ]),
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        ]);

        return $response;
    }
}
