<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\IsRequiredError;

class IsRequired
{
    public static function make(array $attributes, string $attributeName)
    {
        return new Validation(
            key_exists($attributeName, $attributes),
            new IsRequiredError($attributeName)
        );
    }
}
