<?php

namespace plugse\server\core\app\validation\validations;

use plugse\server\core\app\validation\exceptions\MustBeUrlError;
use plugse\server\core\app\validation\Validation;

class MustBeUrl
{
    public static function make(array $attributes, string $attributeName)
    {
        $value = key_exists($attributeName, $attributes) ? $attributes[$attributeName] : true;
        $protocols = ['http', 'https'];
        [$protocol, $urn] = explode('://', $value);

        return new Validation(
            in_array($protocol, $protocols) and (filter_var($urn, FILTER_VALIDATE_DOMAIN) === $urn),
            new MustBeUrlError("{$attributeName} => {$value}")
        );
    }
}
