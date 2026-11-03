<?php

namespace App\Observers\Landing\Informasi\Faq;

use App\Models\Informasi\Faq;
use App\Services\Landing\LandingHomeService;

class FaqObserver
{
    public function __construct(
        private readonly LandingHomeService $home,
    ) {}

    public function saved(Faq $faq): void
    {
        $this->home->forgetFaq();
    }

    public function deleted(Faq $faq): void
    {
        $this->home->forgetFaq();
    }

    public function restored(Faq $faq): void
    {
        $this->home->forgetFaq();
    }
}
