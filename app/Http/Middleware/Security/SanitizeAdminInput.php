<?php

namespace App\Http\Middleware\Security;

use App\Services\Security\JudolDetector;
use App\Support\HtmlSanitizer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dipasang pada grup /superadmin. Untuk setiap POST/PUT/PATCH:
 *  1. Tolak teks yang terindikasi judi online (JudolDetector).
 *  2. Bersihkan field HTML (config security.html_fields) dengan allowlist.
 * Satu tempat untuk semua form, sehingga controller baru otomatis terlindungi.
 */
class SanitizeAdminInput
{
    private const SKIP_KEYS = ['password', 'password_confirmation', 'current_password', '_token', '_method'];

    public function __construct(private JudolDetector $judol) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH'], true)) {
            return $next($request);
        }

        $errors = [];
        $clean = $this->walk($request->input(), '', $errors);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        $request->merge($clean);

        return $next($request);
    }

    /**
     * @param  array<string, mixed>  $errors
     * @return array<int|string, mixed>
     */
    private function walk(array $data, string $path, array &$errors): array
    {
        $htmlFields = (array) config('security.html_fields', []);

        foreach ($data as $key => $value) {
            if (is_string($key) && in_array($key, self::SKIP_KEYS, true)) {
                continue;
            }

            $name = $path === '' ? (string) $key : $path . '.' . $key;

            if (is_array($value)) {
                $data[$key] = $this->walk($value, $name, $errors);
                continue;
            }

            if (! is_string($value) || $value === '') {
                continue;
            }

            if (mb_strlen($value) >= 4 && $this->judol->isBlocked($value)) {
                Log::warning('Judol content rejected', [
                    'field'   => $name,
                    'score'   => $this->judol->score($value),
                    'user_id' => Auth::id(),
                    'ip'      => request()->ip(),
                    'snippet' => mb_substr($value, 0, 80),
                ]);

                $errors[$name] = 'Konten mengandung indikasi perjudian online (judol) yang tidak diizinkan.';
                continue;
            }

            if (is_string($key) && in_array($key, $htmlFields, true)) {
                $data[$key] = HtmlSanitizer::clean($value);
            }
        }

        return $data;
    }
}
