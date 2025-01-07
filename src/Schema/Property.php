<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema;

use SamMcDonald\Json\Schema\Rules\AbstractRule;

readonly class Property
{
    public function __construct(
        private PropertyName $name,
        private AbstractRule $rule,
        private bool $isRequried = false,
    ) {
    }

    public function getName(): PropertyName
    {
        return $this->name;
    }

    public function getRule(): AbstractRule
    {
        return $this->rule;
    }

    public function assertValue(mixed $value): void
    {
        $this->rule->check($value);
    }
}
