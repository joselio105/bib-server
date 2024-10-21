<?php

namespace plugse\server\app\validations\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\app\validations\exceptions\MustBeCutterError;

class MustBeCutter
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : 'A123c';
        $pattern = "/[A-Z]\d{2,4}[a-z]?/";

        return new Validation(
            filter_var(
                $value,
                FILTER_VALIDATE_REGEXP,
                [
                    'options' => [
                        'regexp' => $pattern,
                    ],
                ]
            ) === $value,
            new MustBeCutterError("{$attributeName} => {$value}")
        );
    }
}
