<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Sanitizer HTML berbasis allowlist, tanpa dependensi tambahan.
 *
 * Alur: parse (DOM) -> buang tag/atribut yang tidak diizinkan -> serialisasi ulang.
 * Pendekatan ini jauh lebih kuat daripada strip_tags + regex, karena atribut diperiksa
 * setelah entity didecode (mis. "jav&#x61;script:" tetap terdeteksi).
 */
final class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p',
        'br',
        'hr',
        'div',
        'span',
        'strong',
        'b',
        'em',
        'i',
        'u',
        's',
        'strike',
        'small',
        'sub',
        'sup',
        'blockquote',
        'code',
        'pre',
        'ul',
        'ol',
        'li',
        'a',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'table',
        'thead',
        'tbody',
        'tfoot',
        'tr',
        'th',
        'td',
    ];

    private const ALLOWED_ATTRS = [
        'a'  => ['href', 'title', 'target', 'rel'],
        'th' => ['colspan', 'rowspan'],
        'td' => ['colspan', 'rowspan'],
    ];

    /** Tag yang dibuang beserta isinya (bukan sekadar dilepas). */
    private const DROP_WITH_CONTENT = [
        'script',
        'style',
        'iframe',
        'frame',
        'frameset',
        'object',
        'embed',
        'applet',
        'form',
        'input',
        'button',
        'textarea',
        'select',
        'option',
        'svg',
        'math',
        'link',
        'meta',
        'base',
        'noscript',
        'template',
        'title',
        'head',
        'img',
        'picture',
        'video',
        'audio',
        'source',
        'canvas',
    ];

    private const SAFE_SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        // Teks biasa (tanpa tag) tidak perlu diproses ulang.
        if ($html === '' || ! preg_match('/<\/?[a-z!]/i', $html)) {
            return $html;
        }

        $previous = libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><div id="r">'
                . $html . '</div></body></html>',
            LIBXML_NONET | LIBXML_COMPACT
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('r');

        if (! $root instanceof DOMElement) {
            return htmlspecialchars(strip_tags($html), ENT_QUOTES, 'UTF-8');
        }

        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }

    private static function walk(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof DOMElement) {
                $tag = strtolower($node->tagName);

                if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                    $parent->removeChild($node);
                    continue;
                }

                self::walk($node);

                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    // Tag tidak dikenal: lepas pembungkusnya, pertahankan teks di dalamnya.
                    while ($node->firstChild) {
                        $parent->insertBefore($node->firstChild, $node);
                    }
                    $parent->removeChild($node);
                    continue;
                }

                self::filterAttributes($node, $tag);
            } elseif ($node->nodeType !== XML_TEXT_NODE) {
                // Komentar, CDATA, processing instruction.
                $parent->removeChild($node);
            }
        }
    }

    private static function filterAttributes(DOMElement $el, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRS[$tag] ?? [];

        foreach (iterator_to_array($el->attributes) as $attr) {
            if (! in_array($attr->name, $allowed, true)) {
                $el->removeAttribute($attr->name);
            }
        }

        if (in_array($tag, ['td', 'th'], true)) {
            foreach (['colspan', 'rowspan'] as $a) {
                if ($el->hasAttribute($a) && ! ctype_digit($el->getAttribute($a))) {
                    $el->removeAttribute($a);
                }
            }
        }

        if ($tag !== 'a') {
            return;
        }

        if ($el->hasAttribute('href') && ! self::isSafeUrl($el->getAttribute('href'))) {
            $el->removeAttribute('href');
        }

        if ($el->hasAttribute('href')) {
            $el->setAttribute('rel', 'noopener noreferrer nofollow ugc');
            if ($el->getAttribute('target') !== '_blank') {
                $el->removeAttribute('target');
            }
        } else {
            $el->removeAttribute('target');
            $el->removeAttribute('rel');
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        // Spasi/karakter kontrol sering disisipkan untuk mengelabui filter ("java\nscript:").
        $url = preg_replace('/[\x00-\x20\x7F]+/u', '', $url);

        if ($url === null || $url === '') {
            return false;
        }

        if ($url[0] === '#') {
            return true;
        }

        if ($url[0] === '/') {
            return ! str_starts_with($url, '//') && ! str_contains($url, '\\');
        }

        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $url, $m)) {
            return in_array(strtolower($m[1]), self::SAFE_SCHEMES, true);
        }

        return ! str_contains($url, ':');
    }
}
