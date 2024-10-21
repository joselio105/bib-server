<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustBeEmailError;

class MustBeEmail
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : 'name@gmail.com';

        return new Validation(
            filter_var($value, FILTER_VALIDATE_EMAIL),
            new MustBeEmailError("{$attributeName} => {$value}")
        );
    }
}
