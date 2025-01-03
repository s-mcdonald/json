<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;

class JsonTest extends TestCase
{
    public function testSimpleSerialize(): void
    {
        $jsonUgly = '{"name":"bar"}';

        $jsonPretty = <<<JSON
{
    "name": "bar"
}
JSON
        ;

        static::assertEquals(
            $jsonPretty,
            Json::prettify($jsonUgly),
        );
    }
}
