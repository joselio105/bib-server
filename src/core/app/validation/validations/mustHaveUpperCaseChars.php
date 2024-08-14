<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustHaveUpperCaseCharsError;

class mustHaveUpperCaseChars
{
    public static function make(array $attributes, string $atribiteName)
    {
        $value = key_exists($atribiteName, $attributes) ? $attributes[$atribiteName] : 'I';
        $pattern = '/[A-Z]+/';

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
            new MustHaveUpperCaseCharsError("{$atribiteName} => {$value}")
        );
    }
}
