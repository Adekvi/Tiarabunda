<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_teks_biasa_tidak_berubah(): void
    {
        $this->assertSame('Halo & selamat datang, IPK > 3.0', HtmlSanitizer::clean('Halo & selamat datang, IPK > 3.0'));
    }

    public function test_tag_aman_dan_unicode_dipertahankan(): void
    {
        $out = HtmlSanitizer::clean('<p>Halo <strong>dunia</strong> é ü 日本 😀 &amp; ok</p>');

        $this->assertStringContainsString('<strong>dunia</strong>', $out);
        $this->assertStringContainsString('é ü 日本 😀', $out);
        $this->assertStringContainsString('&amp;', $out);
    }

    public function test_script_dan_event_handler_dibuang(): void
    {
        $out = HtmlSanitizer::clean('<script>alert(1)</script><p onclick="x()">a</p>');

        $this->assertSame('<p>a</p>', $out);
    }

    public function test_url_javascript_dibuang_termasuk_yang_disamarkan(): void
    {
        $variants = [
            '<a href="javascript:alert(1)">x</a>',
            '<a href="jav&#x61;script:alert(1)">x</a>',
            "<a href=\" JaVa\nScript:alert(1)\">x</a>",
            '<a href="data:text/html;base64,PHNjcmlwdD4=">x</a>',
            '<a href="//evil.test/x">x</a>',
        ];

        foreach ($variants as $html) {
            $out = HtmlSanitizer::clean($html);
            $this->assertStringNotContainsString('href', $out, "Lolos: $html");
            $this->assertStringContainsString('>x</a>', $out);
        }
    }

    public function test_elemen_berbahaya_dibuang_beserta_isinya(): void
    {
        $html = '<img src=x onerror=alert(1)><svg onload=alert(1)></svg>'
            . '<iframe src="//evil.test"></iframe><form action="//e"><input name=a></form>'
            . '<style>@import "x"</style><math><mtext>m</mtext></math>aman';

        $out = HtmlSanitizer::clean($html);

        $this->assertSame('aman', $out);
    }

    public function test_link_https_aman_diberi_rel_dan_handler_dibuang(): void
    {
        $out = HtmlSanitizer::clean('<a href="https://contoh.ac.id/x" target="_blank" onclick="e()">l</a>');

        $this->assertStringContainsString('href="https://contoh.ac.id/x"', $out);
        $this->assertStringContainsString('target="_blank"', $out);
        $this->assertStringContainsString('noopener', $out);
        $this->assertStringContainsString('nofollow', $out);
        $this->assertStringNotContainsString('onclick', $out);
    }

    public function test_link_internal_dan_mailto_diizinkan(): void
    {
        $this->assertStringContainsString('href="/profil/sejarah"', HtmlSanitizer::clean('<a href="/profil/sejarah">i</a>'));
        $this->assertStringContainsString('mailto:humas@tiarabunda.ac.id', HtmlSanitizer::clean('<a href="mailto:humas@tiarabunda.ac.id">m</a>'));
    }

    public function test_serangan_mutation_xss_tidak_lolos(): void
    {
        $payloads = [
            '<math><mtext><table><mglyph><style><!--</style><img title="--&gt;&lt;img src=1 onerror=alert(1)&gt;">',
            '<noscript><p title="</noscript><img src=x onerror=alert(1)>">',
        ];

        foreach ($payloads as $p) {
            $out = HtmlSanitizer::clean($p);
            $this->assertStringNotContainsString('<img', $out);
            $this->assertStringNotContainsString('onerror=', $out);
            $this->assertStringNotContainsString('<script', $out);
        }
    }

    public function test_style_dan_atribut_tak_dikenal_dibuang(): void
    {
        $out = HtmlSanitizer::clean('<p style="background:url(javascript:alert(1))" class="x">s</p><table><tr><td colspan="1;x">c</td></tr></table>');

        $this->assertStringNotContainsString('style', $out);
        $this->assertStringNotContainsString('class', $out);
        $this->assertStringNotContainsString('colspan', $out);
        $this->assertStringContainsString('<td>c</td>', $out);
    }
}
