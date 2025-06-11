<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !preg_match('/^[\pL\pN _\-.]+\.[a-zA-Z0-9]{1,10}$/u', $value)) {
            $fail("Поле {$attribute} должно содержать имя файла с расширением.");
        }
    }
}
