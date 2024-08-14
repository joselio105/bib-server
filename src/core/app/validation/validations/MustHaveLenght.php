<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\exceptions\MustHaveLengthEqualsToError;
use plugse\server\core\app\validation\Validation;

class MustHaveLenght
{
    public const LENGTH_EQUALS = '=';
    public const LENGTH_GREATHER = '>';
    public const LENGTH_SMALLER = '<';
    public const LENGTH_GREATHER_EQUALS = '>=';
    public const LENGTH_SMALLER_EQUALS = '<=';

    public static function make(
        array $attributes,
        string $attributeName,
        int $length,
        string $condition = self::LENGTH_EQUALS
    ) {
        $value = key_exists($attributeName, $attributes)
            ? $attributes[$attributeName]
            : self::getDefaultValue($length, $condition);

        $conditions = [
            self::LENGTH_EQUALS => strlen($value) === $length,
            self::LENGTH_GREATHER => strlen($value) > $length,
            self::LENGTH_SMALLER => strlen($value) < $length,
            self::LENGTH_GREATHER_EQUALS => strlen($value) >= $length,
            self::LENGTH_SMALLER_EQUALS => strlen($value) <= $length,
        ];

        return new Validation(
            $conditions[$condition],
            new MustHaveLengthEqualsToError("{$attributeName} => {$value}", $length, $condition)
        );
    }

    private static function getDefaultValue(int $length, $condition)
    {
        $conditions = [
            self::LENGTH_EQUALS => str_pad('', $length, '#'),
            self::LENGTH_GREATHER => str_pad('', $length + 1, '#'),
            self::LENGTH_SMALLER => str_pad('', $length - 1, '#'),
            self::LENGTH_GREATHER_EQUALS => str_pad('', $length, '#'),
            self::LENGTH_SMALLER_EQUALS => str_pad('', $length, '#'),
        ];

        return $conditions[$condition];
    }
}
