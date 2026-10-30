<?php

namespace App\Rules\Nojudol;

use App\Services\Security\JudolDetector;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoJudolContent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (! is_string($value) || $value === '') {
            return;
        }

        $detector = app(JudolDetector::class);

        if (! $detector->isBlocked($value)) {
            return;
        }

        Log::warning('Judol content rejected', [
            'attribute' => $attribute,
            'score' => $detector->score($value),
            'user_id' => Auth::id(),
            'snippet' => mb_substr($value, 0, 120),
        ]);

        $fail('Konten mengandung indikasi perjudian online (judol) yang tidak diizinkan.');
    }
}
