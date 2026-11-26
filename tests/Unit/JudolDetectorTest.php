<?php

namespace Tests\Unit;

use App\Services\Security\JudolDetector;
use Tests\TestCase;

class JudolDetectorTest extends TestCase
{
    public function test_konten_kampus_yang_wajar_tidak_diblokir(): void
    {
        $detector = app(JudolDetector::class);

        $aman = [
            'Pendaftaran mahasiswa baru 2026 https://pmb.tiarabunda.ac.id',
            'Jadwal slot waktu konsultasi dosen pembimbing',
            'Sosialisasi bahaya judi online bagi mahasiswa',
            'Seminar anti judi online dan literasi digital, hubungi humas@tiarabunda.ac.id',
            'Prof.Dr.Ir. Budi Santoso, M.T. memberikan sambutan',
            'https://www.google.com/maps/embed?pb=abc',
        ];

        foreach ($aman as $teks) {
            $this->assertFalse($detector->isBlocked($teks), "Salah blokir: $teks");
        }
    }

    public function test_spam_judol_diblokir(): void
    {
        $detector = app(JudolDetector::class);

        $spam = [
            'https://slot-gacor88.xyz/daftar',
            'https://slotgacor.top',
            'SLOT GACOR MAXWIN TOGEL hari ini, bocoran slot',
            'Daftar situs judi slot online terpercaya https://bit.ly/abc',
            's.l.o.t g.a.c.o.r maxwin deposit',
            '<a href="https://rtp-slot-hari-ini.site">klik</a>',
        ];

        foreach ($spam as $teks) {
            $this->assertTrue($detector->isBlocked($teks), "Lolos: $teks");
        }
    }
}
