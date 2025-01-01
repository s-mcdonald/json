<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Enums;

enum JsonFormat: string
{
    case Compressed = 'compressed';

    case Pretty = 'pretty';
}
