<?php

namespace System\Validation\Rules;

use Cake\Chronos\Chronos;
use Somnambulist\Components\Validation\Rule;

class AfterEqualDate extends Rule
{
    protected string $message = 'rule.default';
    protected array $fillableParams = ['field'];

    public function check(mixed $value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $field = $this->parameter('field');
        $timeStart = $this->attribute()->value($field);

        $startTime = Chronos::createFromFormat('Y-m-d', $timeStart);
        $endTime = Chronos::createFromFormat('Y-m-d', $value);

        return $endTime->greaterThanOrEquals($startTime);
    }
}
