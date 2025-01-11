<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Assertion;

use InvalidArgumentException;
use stdClass;

abstract class AbstractJsonAsserter
{
    protected static function throwInvalidArgument(string $message): void
    {
        throw new InvalidArgumentException($message);
    }

    protected static function checkHasArray(array|stdClass $value): bool
    {
        foreach ($value as $x) {
            if (is_array($x)) {
                return true;
            }

            if (($x instanceof stdClass) && self::checkHasArray($x)) {
                return true;
            }
        }

        return false;
    }

    protected static function checkCountSubArrays(array|stdClass $value): int
    {
        $count = 0;
        foreach ($value as $x) {
            if (is_array($x)) {
                ++$count;
            }

            if ($x instanceof stdClass) {
                $count += self::checkHasArray($x);
            }
        }

        return $count;
    }
}
