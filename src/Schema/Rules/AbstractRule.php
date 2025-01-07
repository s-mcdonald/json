<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Rules;

abstract class AbstractRule
{
    protected array $rules = [];

    public function addRule(AbstractRule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    abstract public function check(mixed $value);
}
