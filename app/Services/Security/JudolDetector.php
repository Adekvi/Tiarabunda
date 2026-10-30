<?php

namespace App\Services\Security;

use Illuminate\Support\Str;

class JudolDetector
{
    public function score(string $text): int
    {
        $normalized = $this->normalize($text);
        if ($normalized === '') {
            return 0;
        }

        $weights = config('judol.weights', []);
        $score = 0;

        if ($this->looksObfuscated($text, $normalized)) {
            $score += (int) ($weights['obfuscation'] ?? 20);
        }

        foreach ($this->extractUrls($normalized) as $url) {
            $host = $this->hostOf($url);
            $path = $this->pathOf($url);

            if ($host && $this->domainBlocked($host)) {
                return (int) ($weights['domain_exact'] ?? 100);
            }

            if ($host && $this->keywordHit($host)) {
                $score += (int) ($weights['keyword_host'] ?? 40);
            }

            if ($path !== '' && $this->keywordHit($path)) {
                $score += (int) ($weights['keyword_path'] ?? 25);
            }

            if ($host && $this->isShortener($host)) {
                $score += (int) ($weights['shortener'] ?? 10);
            }
        }

        // Teks tanpa URL (judul, isi artikel)
        if ($this->keywordHit($normalized)) {
            $score += (int) ($weights['keyword_body'] ?? 15);
        }

        return min(100, $score);
    }

    public function isBlocked(string $text): bool
    {
        $block = (int) config('judol.thresholds.block', 50);

        return $this->score($text) >= $block;
    }

    public function needsReview(string $text): bool
    {
        $score = $this->score($text);
        $block = (int) config('judol.thresholds.block', 50);
        $review = (int) config('judol.thresholds.review', 30);

        return $score >= $review && $score < $block;
    }

    public function domainBlocked(string $host): bool
    {
        $host = Str::lower(preg_replace('/^www\./', '', $host) ?? $host);
        $list = array_map('strtolower', config('judol.domains', []));

        foreach ($list as $blocked) {
            if ($host === $blocked || Str::endsWith($host, '.' . $blocked)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    public function extractUrls(string $text): array
    {
        if (! preg_match_all(
            '#https?://[^\s<>"\']+|www\.[^\s<>"\']+|[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z]{2,})+(?:/[^\s<>"\']*)?#i',
            $text,
            $m
        )) {
            return [];
        }

        return array_values(array_unique($m[0]));
    }

    public function normalize(string $text): string
    {
        $t = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $t = urldecode($t);
        // Zero-width & soft hyphen
        $t = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}]/u', '', $t) ?? $t;
        // Spasi berlebih di antara huruf (j u d i → jadi tetap ada spasi; keyword multi-kata tetap jalan)
        $t = Str::lower($t);
        $t = preg_replace('/\s+/u', ' ', $t) ?? $t;

        return trim($t);
    }

    private function keywordHit(string $haystack): bool
    {
        $keywords = config('judol.keywords', []);
        foreach ($keywords as $kw) {
            $kw = Str::lower(trim((string) $kw));
            if ($kw !== '' && str_contains($haystack, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function isShortener(string $host): bool
    {
        $host = Str::lower(preg_replace('/^www\./', '', $host) ?? $host);
        $list = array_map('strtolower', config('judol.shorteners', []));

        return in_array($host, $list, true);
    }

    private function hostOf(string $url): ?string
    {
        if (! str_contains($url, '://')) {
            $url = 'https://' . $url;
        }

        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) ? Str::lower($host) : null;
    }

    private function pathOf(string $url): string
    {
        if (! str_contains($url, '://')) {
            $url = 'https://' . $url;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $query = parse_url($url, PHP_URL_QUERY) ?? '';

        return Str::lower(trim($path . ($query ? '?' . $query : ''), '/'));
    }

    private function looksObfuscated(string $raw, string $normalized): bool
    {
        // Banyak karakter non-ASCII mirip huruf Latin / mixed script kasar
        if (preg_match('/[а-яА-ЯёЁ]/u', $raw)) {
            return true;
        }

        // Pola "s.l.o.t" / "j-u-d-i"
        if (preg_match('/\b([a-z])([.\-_])([a-z])(\2[a-z]){2,}\b/i', $normalized)) {
            return true;
        }

        return false;
    }
}
