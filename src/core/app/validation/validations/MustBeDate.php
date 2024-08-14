<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\Validation;
use plugse\server\core\app\validation\exceptions\MustBeDateError;

class MustBeDate
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : '1974-05-10';
        $pattern = "/(1|2{1})(\d{3})\-{1}(0|1{1})(\d{1})\-{1}(\d{2})/";

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
            new MustBeDateError("{$attributeName} => {$value}")
        );
    }
}
