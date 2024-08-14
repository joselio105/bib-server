<?php

namespace plugse\server\app\validations\validations;

use plugse\server\app\validations\exceptions\MustBeAuthorsError;
use plugse\server\core\app\validation\Validation;

class MusBeAuthorList
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes)
            ? explode('; ', $attributes[$attributeName])
            : [];
        $values = implode('; ', $value);

        $condition = false;

        return new Validation(
            self::getCondition($value),
            new MustBeAuthorsError("{$attributeName} => {$values}")
        );
        foreach ($value as $author) {
            MustBeAutor::make($attributeName, $author);
        }
    }

    private static function getCondition(array $values): bool
    {
        $pattern = "/^([A-Z]{1}[\w|\s]+), ([\w|\s]+)/u";
        foreach ($values as $value) {
            if (filter_var(
                $value,
                FILTER_VALIDATE_REGEXP,
                [
                    'options' => [
                        'regexp' => $pattern,
                    ],
                ]
            ) === false) {
                return false;
            }
        }

        return true;
    }
}
