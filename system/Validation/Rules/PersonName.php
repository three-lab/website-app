<?php

namespace System\Validation\Rules;

use Somnambulist\Components\Validation\Rule;

class PersonName extends Rule
{
    protected string $message = 'rule.default';

    public function check(mixed $value): bool
    {
        return is_string($value) && preg_match("/^[a-zA-Z, .'\s]+$/", $value);
    }
}
