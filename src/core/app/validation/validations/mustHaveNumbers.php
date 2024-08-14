<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustHaveNumbersError;

class mustHaveNumbers
{
    public static function make(array $attributes, string $atribiteName)
    {
        $value = key_exists($atribiteName, $attributes) ? $attributes[$atribiteName] : '1';
        $pattern = '/[0-9]+/';

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
            new MustHaveNumbersError("{$atribiteName} => {$value}")
        );
    }
}
