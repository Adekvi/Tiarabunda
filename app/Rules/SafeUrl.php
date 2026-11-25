<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * URL aman untuk tombol/tautan: hanya http(s) atau path internal ("/halaman", "#bagian").
 * Menolak javascript:, data:, vbscript:, "//host" (protocol-relative), dan karakter kontrol.
 */
class SafeUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || trim($value) === '') {
            return;
        }

        $v = trim($value);
        $message = 'Kolom :attribute harus berupa URL http/https yang valid atau path diawali "/".';

        if (preg_match('/[\x00-\x1F\x7F\\\\]/', $v)) {
            $fail($message);
            return;
        }

        if ($v[0] === '#' || ($v[0] === '/' && ! str_starts_with($v, '//'))) {
            return;
        }

        $scheme = strtolower((string) parse_url($v, PHP_URL_SCHEME));
        $host = parse_url($v, PHP_URL_HOST);

        if (
            ! in_array($scheme, ['http', 'https'], true) || ! is_string($host) || $host === ''
            || filter_var($v, FILTER_VALIDATE_URL) === false
        ) {
            $fail($message);
        }
    }
}
