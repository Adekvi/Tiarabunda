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

        $urls = $this->extractUrls($normalized);

        foreach ($urls as $url) {
            $host = $this->hostOf($url);
            $path = $this->pathOf($url);

            if ($host && $this->domainBlocked($host)) {
                return (int) ($weights['domain_exact'] ?? 100);
            }

            // Mode compact: "slot-gacor88.xyz" dan "slotgacor.com" sama-sama terdeteksi.
            if ($host && $this->keywordHits($host, true) > 0) {
                $score += (int) ($weights['keyword_host'] ?? 40);
            }

            if ($path !== '' && $this->keywordHits($path, true) > 0) {
                $score += (int) ($weights['keyword_path'] ?? 25);
            }

            if ($host && $this->isShortener($host)) {
                $score += (int) ($weights['shortener'] ?? 10);
            }
        }

        // Teks (judul, isi): tiap kata kunci berbeda menambah skor.
        $hits = $this->keywordHits($normalized);
        if ($hits > 0) {
            $score += $hits * (int) ($weights['keyword_body'] ?? 15);

            if ($hits >= 2 && $urls !== []) {
                $score += (int) ($weights['spam_with_link'] ?? 20);
            }
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

    /** Jumlah kata kunci BERBEDA yang ditemukan. $compact=true: abaikan tanda baca (untuk host/path). */
    private function keywordHits(string $haystack, bool $compact = false): int
    {
        if ($compact) {
            $haystack = $this->compact($haystack);
        } else {
            // "s.l.o.t" / "g-a-c-o-r" -> "slot" / "gacor", lalu tanda baca lain jadi spasi.
            $haystack = preg_replace_callback(
                '/\b(?:[a-z][.\-_]){2,}[a-z]\b/',
                fn(array $m) => preg_replace('/[.\-_]/', '', $m[0]),
                $haystack
            ) ?? $haystack;
            $haystack = preg_replace('/[\-_.]+/', ' ', $haystack) ?? $haystack;
        }

        $hits = 0;
        foreach (config('judol.keywords', []) as $kw) {
            $kw = Str::lower(trim((string) $kw));
            $needle = $compact ? $this->compact($kw) : $kw;

            if ($needle !== '' && str_contains($haystack, $needle)) {
                $hits++;
            }
        }

        return $hits;
    }

    private function compact(string $text): string
    {
        return preg_replace('/[^a-z0-9]+/', '', Str::lower($text)) ?? '';
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
