<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustHaveSpecialCharsError;

class mustHaveSpecialChars
{
    public static function make(array $attributes, string $atribiteName)
    {
        $value = key_exists($atribiteName, $attributes) ? $attributes[$atribiteName] : '@';
        $pattern = "/[\!\@\#\$\%\&\*\(\)\[\]\{\}\,\.\;\:\\\?\/\|]+/";

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
            new MustHaveSpecialCharsError("{$atribiteName} => {$value}")
        );
    }
}
