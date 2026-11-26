<?php

namespace Tests\Feature;

use App\Http\Middleware\Security\RequireOriginSecret;
use Tests\TestCase;

class OriginSecretTest extends TestCase
{
    private string $secret;

    protected function setUp(): void
    {
        parent::setUp();
        $this->secret = str_repeat('a1b2', 16);
    }

    public function test_nonaktif_jika_secret_kosong(): void
    {
        config(['security.origin_secret' => '']);

        $this->get('/up')->assertOk();
    }

    public function test_ditolak_tanpa_header(): void
    {
        config(['security.origin_secret' => $this->secret]);

        $this->get('/up')->assertForbidden();
    }

    public function test_ditolak_dengan_header_salah(): void
    {
        config(['security.origin_secret' => $this->secret]);

        $this->withHeaders([RequireOriginSecret::HEADER => 'salah'])->get('/up')->assertForbidden();
    }

    public function test_lolos_dengan_header_benar(): void
    {
        config(['security.origin_secret' => $this->secret]);

        $this->withHeaders([RequireOriginSecret::HEADER => $this->secret])->get('/up')->assertOk();
    }
}
