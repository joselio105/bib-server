<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\exceptions\MustBeBoolError;
use plugse\server\core\app\validation\Validation;

class MustBeBool
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : true;

        return new Validation(
            filter_var($value, FILTER_VALIDATE_BOOLEAN) !== $value,
            new MustBeBoolError("{$attributeName} => {$value}")
        );
    }
}
