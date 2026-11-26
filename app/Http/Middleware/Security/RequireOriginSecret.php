<?php

namespace App\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kunci server asal (origin): hanya terima request yang membawa header rahasia
 * yang ditambahkan Cloudflare (Transform Rule "Set static"). Penyerang yang
 * menembak IP server langsung tidak akan membawa rahasia ini, sehingga ditolak
 * sebelum menyentuh sesi/database. Nonaktif jika ORIGIN_SECRET kosong.
 */
class RequireOriginSecret
{
    public const HEADER = 'X-Origin-Auth';

    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('security.origin_secret', '');

        if ($secret !== '' && ! hash_equals($secret, (string) $request->header(self::HEADER, ''))) {
            // Respons minimal: jangan bocorkan apa pun tentang aplikasi.
            return response('Forbidden', 403, ['Cache-Control' => 'no-store']);
        }

        return $next($request);
    }
}
