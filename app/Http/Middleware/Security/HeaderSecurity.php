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
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        $response = $next($request);

        $csp = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: blob: https: http://localhost:8000 http://127.0.0.1:8000",
            "frame-src 'self' https://maps.google.com https://www.google.com",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];

        // Aktifkan upgrade-insecure-requests hanya di server production (HTTPS)
        if (app()->isProduction()) {
            $csp[] = 'upgrade-insecure-requests';
        }

        $response->headers->add([
            'Content-Security-Policy' => implode('; ', $csp),
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        ]);

        return $response;
    }
}
