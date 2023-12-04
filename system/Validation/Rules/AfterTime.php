<?php

namespace System\Validation\Rules;

use Cake\Chronos\Chronos;
use Somnambulist\Components\Validation\Rule;

class AfterTime extends Rule
{
    protected string $message = 'rule.default';
    protected array $fillableParams = ['field'];

    public function check(mixed $value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $field = $this->parameter('field');
        $timeStart = $this->attribute()->value($field);

        $startTime = Chronos::createFromFormat('H:i', $timeStart);
        $endTime = Chronos::createFromFormat('H:i', $value);

        return $endTime->greaterThan($startTime);
    }
}
