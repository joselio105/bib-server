<?php

namespace plugse\server\core\errors;

use Exception;

class AttributeClassNotFoundError extends Exception
{
    public function __construct(string $name, string $class, string $origin)
    {
        http_response_code(404);
        parent::__construct("{$origin} => O atributo '{$name}' não foi existe na classe '{$class}'");
    }
}
