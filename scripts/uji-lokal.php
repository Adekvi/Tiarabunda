<?php

/**
 * Uji keamanan lokal — mensimulasikan Cloudflare dengan header manual.
 *
 * Pakai:  php scripts/uji-lokal.php [BASE_URL] [ORIGIN_SECRET]
 * Contoh: php scripts/uji-lokal.php http://127.0.0.1:8000 rahasia-uji-lokal
 *
 * Prasyarat .env lokal:  ORIGIN_SECRET=<rahasia>  TRUSTED_PROXIES=*
 * lalu: php artisan config:clear && php artisan serve
 * Ini HANYA untuk lokal; jangan arahkan ke situs produksi.
 */

$base   = rtrim($argv[1] ?? 'http://127.0.0.1:8000', '/');
$secret = $argv[2] ?? '';

$pass = 0;
$fail = 0;

function http(string $url, array $headers = []): array
{
    $ctx = stream_context_create(['http' => [
        'method'          => 'GET',
        'header'          => implode("\r\n", $headers),
        'ignore_errors'   => true,
        'timeout'         => 20,
        'follow_location' => 0,
        'max_redirects'   => 0,
    ]]);

    $body = @file_get_contents($url, false, $ctx);
    $raw  = function_exists('http_get_last_response_headers')
        ? (http_get_last_response_headers() ?? [])
        : ($http_response_header ?? []);

    $status = 0;
    $h = [];
    foreach ($raw as $line) {
        if (preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $m)) {
            $status = (int) $m[1];
            $h = [];            // reset bila ada redirect/100-continue
        } elseif (str_contains($line, ':')) {
            [$k, $v] = explode(':', $line, 2);
            $h[strtolower(trim($k))][] = trim($v);
        }
    }

    return ['status' => $status, 'headers' => $h, 'body' => (string) $body];
}

function check(string $label, bool $ok, string $hint = ''): void
{
    global $pass, $fail;
    $ok ? $pass++ : $fail++;
    printf("  %s  %s%s\n", $ok ? 'PASS' : 'FAIL', $label, $ok || $hint === '' ? '' : "\n        -> $hint");
}

function section(string $t): void
{
    echo "\n== $t ==\n";
}

$auth = $secret !== '' ? ["X-Origin-Auth: $secret"] : [];

// Pastikan server hidup
$ping = http($base . '/up', $auth);
if ($ping['status'] === 0) {
    fwrite(STDERR, "Server tidak terjangkau di $base. Jalankan: php artisan serve\n");
    exit(2);
}

if (in_array($ping['status'], [301, 302, 307, 308], true)) {
    $loc = $ping['headers']['location'][0] ?? '(tidak ada)';
    fwrite(STDERR, "Server mengalihkan ke: $loc\n"
        . "Jika ini alamat lokal (mis. *.test), perbarui public/.htaccess dari patch terbaru (host lokal dikecualikan dari redirect HTTPS),\n"
        . "atau gunakan alamat https:// yang valid.\n");
    exit(2);
}

/* ------------------------------------------------------------------ */
section('A. Kunci origin (ORIGIN_SECRET)');
if ($secret === '') {
    echo "  (dilewati: tidak ada ORIGIN_SECRET di argumen)\n";
} else {
    $r = http($base . '/up');
    check('Tanpa header -> 403', $r['status'] === 403, "status {$r['status']}; pastikan ORIGIN_SECRET terisi di .env lalu php artisan config:clear");
    $r = http($base . '/up', ['X-Origin-Auth: salah-' . bin2hex(random_bytes(4))]);
    check('Header salah -> 403', $r['status'] === 403, "status {$r['status']}");
    $r = http($base . '/up', $auth);
    check('Header benar -> 200', $r['status'] === 200, "status {$r['status']}; nilai rahasia di argumen harus sama dengan ORIGIN_SECRET di .env");
}

/* ------------------------------------------------------------------ */
section('B. Rate limit membaca IP pengunjung asli (TRUSTED_PROXIES)');
$ipA = '198.51.100.' . random_int(2, 250);
do {
    $ipB = '203.0.113.' . random_int(2, 250);
} while ($ipB === $ipA);

$statuses = [];
for ($i = 1; $i <= 31; $i++) {
    $r = http($base . '/cari', array_merge($auth, ["X-Forwarded-For: $ipA"]));
    $statuses[] = $r['status'];
}
$okCount = count(array_filter(array_slice($statuses, 0, 30), fn($s) => $s === 200));
check(
    '30 request pertama /cari lolos (200)',
    $okCount === 30,
    'hanya ' . $okCount . ' dari 30 lolos. Status: ' . implode(',', array_unique($statuses)) .
        ' — jika 429 terlalu dini, penghitung throttle bercampur (pastikan routes/landing.php terbaru)'
);
check('Request ke-31 /cari -> 429', $statuses[30] === 429, "status {$statuses[30]}");

$r = http($base . '/cari', array_merge($auth, ["X-Forwarded-For: $ipB"]));
check(
    'IP pengunjung lain tidak ikut terblokir',
    $r['status'] === 200,
    "status {$r['status']}; jika 429, TRUSTED_PROXIES belum '*' (semua dianggap satu IP)"
);

/* ------------------------------------------------------------------ */
section('C. Header keamanan & cookie halaman publik');
$r = http($base . '/', array_merge($auth, ['X-Forwarded-For: 203.0.113.250']));
$csp = $r['headers']['content-security-policy'][0] ?? '';
check('Beranda terbuka (200)', $r['status'] === 200, "status {$r['status']}");
check('CSP memakai nonce & object-src none', str_contains($csp, "'nonce-") && str_contains($csp, "object-src 'none'"), $csp ?: 'header CSP tidak ada');
check('CSP tidak mengizinkan script inline liar', ! str_contains($csp, "script-src 'self' 'unsafe-inline'") && ! str_contains($csp, 'unsafe-eval'), $csp);
check('X-Content-Type-Options: nosniff', ($r['headers']['x-content-type-options'][0] ?? '') === 'nosniff');
check('Referrer-Policy terpasang', isset($r['headers']['referrer-policy']));
check(
    'Halaman publik TANPA Set-Cookie (aman di-cache CDN)',
    empty($r['headers']['set-cookie']),
    'ada cookie: ' . implode(' | ', $r['headers']['set-cookie'] ?? []) . ' — pastikan require landing.php di routes/web.php sudah dihapus (Fase 1)'
);

/* ------------------------------------------------------------------ */
section('D. Berkas sensitif tidak terbuka');
foreach (['/.env', '/.git/config', '/vendor/autoload.php', '/artisan', '/composer.json', '/storage/logs/laravel.log', '/database/database.sqlite', '/storage/uji-shell.php'] as $path) {
    $r = http($base . $path, array_merge($auth, ['X-Forwarded-For: 203.0.113.251']));
    check("$path -> 403/404", in_array($r['status'], [403, 404], true), "status {$r['status']} (BAHAYA bila 200)");
}

/* ------------------------------------------------------------------ */
section('E. Halaman login');
$r = http($base . '/login', array_merge($auth, ['X-Forwarded-For: 203.0.113.252']));
check('/login terbuka (200)', $r['status'] === 200, "status {$r['status']}");
$cookies = implode("\n", $r['headers']['set-cookie'] ?? []);
$sess = '';
foreach ($r['headers']['set-cookie'] ?? [] as $c) {
    if (stripos($c, 'session') !== false && stripos($c, 'XSRF') === false) {
        $sess = $c;
    }
}
check('Cookie sesi HttpOnly', $sess !== '' && stripos($sess, 'httponly') !== false, $sess ?: 'cookie sesi tidak ditemukan');
check('Cookie sesi SameSite', $sess !== '' && stripos($sess, 'samesite') !== false, $sess);
check('Form login memuat token CSRF', str_contains($r['body'], '_token'), 'token _token tidak ada di HTML');

/* ------------------------------------------------------------------ */
echo "\n----------------------------------------\n";
printf("Hasil: %d lolos, %d gagal\n", $pass, $fail);
exit($fail > 0 ? 1 : 0);
