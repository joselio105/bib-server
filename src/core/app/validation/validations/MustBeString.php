<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustBeStringError;

class MustBeString
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : '';

        return new Validation(
            is_string($value),
            new MustBeStringError("{$attributeName} => {$value}")
        );
    }
}
