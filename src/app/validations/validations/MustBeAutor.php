<?php

namespace plugse\server\app\validations\validations;

use plugse\server\app\validations\exceptions\MustBeAuthorsError;
use plugse\server\core\app\validation\Validation;

class MustBeAutor
{
    public static function make(string $name, string $value)
    {
        $pattern = "/^([A-Z]{1}[\w|\s]+), ([\w|\s]+)/u";

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
            new MustBeAuthorsError("{$name} => {$value}")
        );
    }
}
