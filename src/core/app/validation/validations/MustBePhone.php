<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustBePhoneError;

class MustBePhone
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : '0011223334455';
        $pattern = "/\+?(\d{2,3})?\s?(\d{2,3})?\s?(\d{4,5})\s?\-?(\d{4})$/";

        return new Validation(
            filter_var(
                $value,
                FILTER_VALIDATE_REGEXP,
                [
                    'options' => [
                        'regexp' => $pattern,
                    ],
                ]
            ),
            new MustBePhoneError("{$attributeName} => {$value}")
        );
    }
}
