<?php

namespace plugse\server\app\validations\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\app\validations\exceptions\MustBeForeignKeyError;

class MustBeForeignKey
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : 'Aa, a';
        $pattern = "/\w+\.\w+/";

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
            new MustBeForeignKeyError("{$attributeName} => {$value}")
        );
    }
}
