<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\exceptions\MustBeIntError;
use plugse\server\core\app\validation\Validation;

class MustBeInt
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : 1;

        return new Validation(
            filter_var($value, FILTER_VALIDATE_INT) == $value,
            new MustBeIntError("{$attributeName} => {$value}")
        );
    }
}
