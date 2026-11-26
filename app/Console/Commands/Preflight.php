<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class Preflight extends Command
{
    protected $signature = 'app:preflight';

    protected $description = 'Periksa kesiapan keamanan konfigurasi sebelum/sesudah publish (jalankan di server)';

    /** @var list<array{string,string,string}> */
    private array $rows = [];

    public function handle(): int
    {
        $this->checkApp();
        $this->checkDatabase();
        $this->checkSession();
        $this->checkFiles();
        $this->checkOptimization();

        $icons = ['ok' => 'OK  ', 'warn' => 'WARN', 'fail' => 'FAIL'];
        $this->table(
            ['Status', 'Pemeriksaan', 'Catatan'],
            array_map(fn(array $r) => [$icons[$r[0]], $r[1], $r[2]], $this->rows),
        );

        $fails = count(array_filter($this->rows, fn($r) => $r[0] === 'fail'));
        $warns = count(array_filter($this->rows, fn($r) => $r[0] === 'warn'));

        if ($fails > 0) {
            $this->components->error("{$fails} masalah KRITIS harus diperbaiki sebelum publish ({$warns} peringatan).");
            return self::FAILURE;
        }

        $this->components->info($warns > 0 ? "Lolos, dengan {$warns} peringatan." : 'Semua pemeriksaan lolos.');
        return self::SUCCESS;
    }

    private function add(string $status, string $label, string $note = ''): void
    {
        $this->rows[] = [$status, $label, $note];
    }

    private function check(bool $ok, string $label, string $failNote, string $level = 'fail'): void
    {
        $this->add($ok ? 'ok' : $level, $label, $ok ? '' : $failNote);
    }

    private function checkApp(): void
    {
        $this->check(config('app.env') === 'production', 'APP_ENV=production', 'Set APP_ENV=production');
        $this->check(config('app.debug') === false, 'APP_DEBUG=false', 'Debug aktif membocorkan stack trace & konfigurasi');
        $this->check(filled(config('app.key')), 'APP_KEY terisi', 'Jalankan: php artisan key:generate --force');
        $this->check(str_starts_with((string) config('app.url'), 'https://'), 'APP_URL memakai https', 'Ubah APP_URL ke https://domain-anda');
        $this->check(
            ! in_array(config('logging.channels.single.level'), ['debug', 'info'], true),
            'LOG_LEVEL bukan debug/info',
            'Set LOG_LEVEL=warning',
            'warn'
        );
        $secret = (string) config('security.origin_secret');
        $proxies = config('security.trusted_proxies');

        $this->check(
            $secret !== '',
            'ORIGIN_SECRET (origin dikunci ke Cloudflare)',
            'Kosong: server masih bisa ditembak langsung lewat IP (lihat Fase 4)',
            'warn'
        );
        $this->check(
            $secret === '' || strlen($secret) >= 32,
            'ORIGIN_SECRET cukup panjang',
            'Pakai minimal 32 karakter: openssl rand -hex 32'
        );
        $this->check(
            blank($proxies) || $secret !== '',
            'TRUSTED_PROXIES hanya dengan ORIGIN_SECRET',
            'TRUSTED_PROXIES terisi tapi origin belum dikunci: X-Forwarded-For bisa dipalsukan untuk menghindari rate limit'
        );
    }

    private function checkDatabase(): void
    {
        $conn = config('database.default');
        $cfg = config("database.connections.{$conn}", []);

        if (($cfg['driver'] ?? '') === 'mysql') {
            $this->check(
                ! in_array(strtolower((string) ($cfg['username'] ?? '')), ['root', ''], true),
                'DB_USERNAME bukan root',
                'Pakai user khusus aplikasi dengan hak minimal'
            );
            $this->check(filled($cfg['password'] ?? null), 'DB_PASSWORD terisi', 'Password database kosong');
        }

        try {
            DB::connection()->getPdo();
            $this->add('ok', 'Koneksi database');
        } catch (Throwable) {
            $this->add('fail', 'Koneksi database', 'Tidak bisa terhubung; periksa DB_*');
            return;
        }

        try {
            $admins = User::query()->whereIn('role', ['admin', 'superadmin'])->count();
            $this->check($admins > 0, 'Ada akun admin', 'Buat dengan: php artisan admin:akun email@domain --role=superadmin', 'warn');
        } catch (Throwable) {
            $this->add('warn', 'Tabel users', 'Belum ada; jalankan php artisan migrate --force');
        }
    }

    private function checkSession(): void
    {
        $this->check(config('session.secure') === true, 'SESSION_SECURE_COOKIE=true', 'Cookie sesi bisa terkirim lewat HTTP');
        $this->check(config('session.http_only') === true, 'Cookie sesi HttpOnly', 'Set SESSION_HTTP_ONLY=true');
        $this->check(config('session.driver') !== 'array', 'SESSION_DRIVER permanen', 'Driver "array" membuat login tidak bertahan');
        $this->check(config('session.encrypt') === true, 'SESSION_ENCRYPT=true', 'Disarankan true', 'warn');
        $this->check(config('queue.default') === 'sync' || config('queue.default') === 'database', 'QUEUE_CONNECTION valid', 'Periksa nilai QUEUE_CONNECTION', 'warn');
    }

    private function checkFiles(): void
    {
        $this->check(is_writable(storage_path()), 'storage/ dapat ditulis', 'Beri izin tulis (755/775) untuk storage/');
        $this->check(is_writable(base_path('bootstrap/cache')), 'bootstrap/cache dapat ditulis', 'Beri izin tulis untuk bootstrap/cache');
        $this->check(is_link(public_path('storage')) || is_dir(public_path('storage')), 'public/storage tersedia', 'Jalankan: php artisan storage:link', 'warn');

        if (DIRECTORY_SEPARATOR === '/' && is_file(base_path('.env'))) {
            $perms = fileperms(base_path('.env')) & 0777;
            $this->check(($perms & 0004) === 0, '.env tidak bisa dibaca "other"', sprintf('Izin saat ini %o; jalankan chmod 600 .env', $perms), 'warn');
        }

        $this->check(! is_file(public_path('.env')), 'Tidak ada .env di public/', 'HAPUS public/.env sekarang');

        $found = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(public_path(), RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $file) {
            if ($iterator->getDepth() === 0 && $file->getFilename() === 'storage') {
                continue; // symlink ke unggahan; jangan ditelusuri
            }
            if (! $file->isFile()) {
                continue;
            }
            $name = strtolower($file->getFilename());
            $ext = strtolower($file->getExtension());

            if (
                in_array($ext, ['sql', 'bak', 'old', 'swp', 'log', 'zip', 'tar', 'gz', 'env'], true)
                || in_array($name, ['phpinfo.php', 'info.php', 'test.php', 'adminer.php'], true)
            ) {
                $found[] = ltrim(str_replace(public_path(), '', $file->getPathname()), '/\\');
            }
        }

        $this->check(
            $found === [],
            'Tidak ada berkas sensitif/cadangan di public/',
            'Hapus: ' . implode(', ', array_slice($found, 0, 5)),
            'fail'
        );
    }

    private function checkOptimization(): void
    {
        $this->check(app()->configurationIsCached(), 'config:cache aktif', 'Jalankan: php artisan config:cache', 'warn');
        $this->check(app()->routesAreCached(), 'route:cache aktif', 'Jalankan: php artisan route:cache', 'warn');
    }
}
