<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class ValidDniNie implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $v = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)$value));

        // DNI: 8 dígitos + letra
        if (preg_match('/^[0-9]{8}[A-Z]$/', $v)) {
            if (!$this->checkLetter(substr($v, 0, 8), $v[8])) {
                $fail('El :attribute no es un DNI/NIE válido.');
            }
            return;
        }

        // NIE: X/Y/Z + 7 dígitos + letra
        if (preg_match('/^[XYZ][0-9]{7}[A-Z]$/', $v)) {
            $map = ['X' => '0', 'Y' => '1', 'Z' => '2'];
            $num = $map[$v[0]] . substr($v, 1, 7);
            if (!$this->checkLetter($num, $v[8])) {
                $fail('El :attribute no es un DNI/NIE válido.');
            }
            return;
        }

        $fail('El :attribute no es un DNI/NIE válido.');
    }

    private function checkLetter(string $number, string $letter): bool
    {
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $expected = $letters[((int)$number) % 23] ?? '';
        return $expected === $letter;
    }
}

