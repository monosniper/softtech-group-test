<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Base64 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            base64_decode($value, true) === false ||
            base64_encode(base64_decode($value, true)) !== $value
        ) {
            $fail("Поле {$attribute} должно быть корректной base64-строкой.");
        }
    }
}
