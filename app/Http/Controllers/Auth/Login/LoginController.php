<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /** Maks percobaan gagal per email+IP sebelum throttle keras */
    private const MAX_ATTEMPTS = 5;

    /** Detik lockout setelah MAX_ATTEMPTS */
    private const LOCKOUT_SECONDS = 900; // 15 menit

    /** Decay rate limit percobaan (detik) */
    private const DECAY_SECONDS = 60;

    public function index()
    {
        return view('template.auth.sign-in');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'string',
                // 'email:rfc,dns',
                'email:rfc',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
            ],
            'remember' => ['sometimes', 'boolean'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $email = Str::lower(trim($credentials['email']));
        $throttleKey = $this->throttleKey($email, $request->ip());

        if ($this->isLockedOut($throttleKey)) {
            $seconds = $this->lockoutRemaining($throttleKey);

            Log::warning('Login blocked: lockout active', [
                'email' => $email,
                'ip' => $request->ip(),
                'seconds' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan gagal. Coba lagi dalam {$seconds} detik.",
            ])->status(429);
        }

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->engageLockout($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Akun dikunci sementara {$seconds} detik.",
            ])->status(429);
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt(
            ['email' => $email, 'password' => $credentials['password']],
            $remember
        )) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            Log::notice('Login failed', [
                'email' => $email,
                'ip' => $request->ip(),
                'ua' => Str::limit((string) $request->userAgent(), 200),
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        Cache::forget($this->lockoutCacheKey($throttleKey));

        $request->session()->regenerate();

        $user = Auth::user();

        if (! in_array($user->role ?? 'user', ['superadmin', 'admin'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak memiliki akses ke panel administrasi.',
            ]);
        }

        // 8) Remember me: Laravel set cookie remember_token (DB).
        //    Tambahan cache: tandai sesi “remember” untuk audit / force-logout nanti.
        if ($remember) {
            $this->markRememberedSession($user->id, $request);
        }

        Log::info('Login success', [
            'user_id' => $user->id,
            'role' => $user->role ?? null,
            'ip' => $request->ip(),
            'remember' => $remember,
        ]);

        return match ($user->role) {
            'superadmin' => redirect()->intended(route('superadmin.dashboard')),
            'admin' => redirect()->intended(route('admin.dashboard')),
            default => redirect()->intended('/'),
        };
    }

    public function destroy(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();

        if ($userId) {
            Cache::forget($this->rememberCacheKey($userId));
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // -------------------------------------------------------------------------
    // Helpers keamanan
    // -------------------------------------------------------------------------

    private function throttleKey(string $email, string $ip): string
    {
        // Kunci gabungan: serangan tersebar IP tetap terbatas per email
        return Str::transliterate(Str::lower($email)) . '|' . $ip;
    }

    private function lockoutCacheKey(string $throttleKey): string
    {
        return 'login_lockout:' . sha1($throttleKey);
    }

    private function rememberCacheKey(int $userId): string
    {
        return 'login_remember:' . $userId;
    }

    private function isLockedOut(string $throttleKey): bool
    {
        return Cache::has($this->lockoutCacheKey($throttleKey));
    }

    private function lockoutRemaining(string $throttleKey): int
    {
        $key = $this->lockoutCacheKey($throttleKey);
        $expiresAt = Cache::get($key);

        if (! $expiresAt instanceof \DateTimeInterface && ! is_numeric($expiresAt)) {
            return self::LOCKOUT_SECONDS;
        }

        $ts = $expiresAt instanceof \DateTimeInterface
            ? $expiresAt->getTimestamp()
            : (int) $expiresAt;

        return max(0, $ts - time());
    }

    private function engageLockout(string $throttleKey): void
    {
        $until = now()->addSeconds(self::LOCKOUT_SECONDS);
        Cache::put(
            $this->lockoutCacheKey($throttleKey),
            $until->getTimestamp(),
            self::LOCKOUT_SECONDS
        );
    }

    /**
     * Catat “remember me” di cache (pelengkap remember_token di DB).
     * Berguna untuk: audit, batasi durasi, atau force-logout massal.
     */
    private function markRememberedSession(int $userId, Request $request): void
    {
        $ttlDays = 14; // selaraskan dengan config session / auth remember
        $payload = [
            'user_id' => $userId,
            'ip' => $request->ip(),
            'ua_hash' => hash('sha256', (string) $request->userAgent()),
            'remembered_at' => now()->toIso8601String(),
        ];

        Cache::put(
            $this->rememberCacheKey($userId),
            $payload,
            now()->addDays($ttlDays)
        );
    }
}
