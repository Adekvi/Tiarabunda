<?php

namespace Tests\Feature;

use App\Http\Middleware\Security\SanitizeAdminInput;
use App\Services\Security\JudolDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SanitizeAdminInputTest extends TestCase
{
    private function jalankan(array $input, string $method = 'POST'): ?Request
    {
        Log::spy();

        /** @var Request|null $captured */
        $captured = null;
        $request = Request::create('/superadmin/uji', $method, $input);

        (new SanitizeAdminInput(new JudolDetector))->handle($request, function (Request $r) use (&$captured) {
            $captured = $r;

            return response('ok');
        });

        return $captured;
    }

    public function test_field_html_disanitasi_dan_field_biasa_tidak_diubah(): void
    {
        $r = $this->jalankan([
            'katrangan' => '<script>alert(1)</script><p onclick="x()">isi</p>',
            'judhul'    => 'Berita <Penting> & Baru',
        ]);

        $this->assertSame('<p>isi</p>', $r->input('katrangan'));
        $this->assertSame('Berita <Penting> & Baru', $r->input('judhul'));
    }

    public function test_konten_judol_ditolak_dengan_error_validasi(): void
    {
        $this->expectException(ValidationException::class);

        $this->jalankan(['isi' => 'SLOT GACOR MAXWIN TOGEL hari ini https://slot-gacor88.xyz']);
    }

    public function test_field_bersarang_ikut_diperiksa(): void
    {
        try {
            $this->jalankan(['blok' => [['isi' => 'daftar slot gacor maxwin https://rtp-slot.site']]]);
            $this->fail('Seharusnya ditolak');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('blok.0.isi', $e->errors());
        }
    }

    public function test_request_get_tidak_disentuh(): void
    {
        $r = $this->jalankan(['katrangan' => '<script>x</script>'], 'GET');

        $this->assertSame('<script>x</script>', $r->input('katrangan'));
    }

    public function test_password_tidak_diperiksa_judol(): void
    {
        $r = $this->jalankan(['password' => 'slot gacor maxwin togel 123!']);

        $this->assertSame('slot gacor maxwin togel 123!', $r->input('password'));
    }
}
