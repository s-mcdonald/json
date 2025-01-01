<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Validator;

use SamMcDonald\Json\Serializer\Encoding\Validator\Contracts\JsonValidatorInterface;
use SamMcDonald\Json\Serializer\Encoding\Validator\Exceptions\JsonDecodeException;

class JsonValidator implements JsonValidatorInterface
{
    /**
     * Graceful validation.
     */
    public function isValid(string $json): bool
    {
        if (empty($json)) {
            return false;
        }

        \json_decode($json);

        return false === $this->getDecodeResult();
    }

    public function validate(string $json): void
    {
        if (false === $this->isValid($json)) {
            throw new JsonDecodeException(json_last_error_msg());
        }
    }

    public function getLastErrorMessage(): string|null
    {
        $lastMessage = json_last_error();

        if (JSON_ERROR_NONE === $lastMessage) {
            return null;
        }

        return json_last_error_msg();
    }

    public static function getEncodeResult(): false|string
    {
        return match (json_last_error()) {
            JSON_ERROR_NONE => false,
            JSON_ERROR_DEPTH => 'The maximum stack depth was exceeded.',
            JSON_ERROR_INVALID_PROPERTY_NAME => 'The property name is invalid.',
            default => sprintf(
                'The following encoding errors was encountered: `%s`',
                json_last_error_msg(),
            ),
        };
    }

    private function getDecodeResult(): false|string
    {
        return match (json_last_error()) {
            JSON_ERROR_NONE => false,
            JSON_ERROR_DEPTH => 'The maximum stack depth was exceeded.',
            JSON_ERROR_STATE_MISMATCH => 'Malformed Json.',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character was found within the Json string.',
            JSON_ERROR_SYNTAX => 'Syntax error.',
            JSON_ERROR_UTF8 => 'Invalid UTF-8 characters.',
            default => sprintf(
                'The following decoding error was encountered: `%s`',
                json_last_error_msg(),
            ),
        };
    }
}
