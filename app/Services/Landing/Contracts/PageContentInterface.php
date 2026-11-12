<?php

namespace App\Services\Landing\Contracts;

interface PageContentInterface
{
    /**
     * Mengambil data spesifik untuk halaman tertentu.
     * 
     * @param string|null $prodiSlug Parameter opsional (misal untuk detail prodi)
     * @return array
     */
    public function getData(?string $prodiSlug = null): array;
}
