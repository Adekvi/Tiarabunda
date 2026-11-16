<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Informasi\FaqService;

class FaqPageContent implements PageContentInterface
{
    public function __construct(private FaqService $faqService) {}

    public function getData(?string $slug = null): array
    {
        $faq = $this->faqService->forPage();

        if ($faq->isEmpty()) {
            $faq = collect([
                (object) [
                    'id'        => 1,
                    'pitakon'   => 'Bagaimana cara mendaftar?',
                    'wangsulan' => 'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi sesuai jalur yang dipilih.',
                ],
                (object) [
                    'id'        => 2,
                    'pitakon'   => 'Apakah kampus sudah terakreditasi?',
                    'wangsulan' => 'Informasi akreditasi institusi dan program studi tersedia di menu Akreditasi.',
                ],
            ]);
        }

        return [
            'faqList' => $faq,
        ];
    }
}
