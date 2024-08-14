<?php

namespace plugse\server\app\validations\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\app\validations\exceptions\MustBeRegistrationError;

class MustBeRegistration
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : 'bib.1234.56';
        $pattern = "/bib\.\d{4}\.\d{1,3}/";

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
            new MustBeRegistrationError("{$attributeName} => {$value}")
        );
    }
}
