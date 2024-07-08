<?php

namespace plugse\server\core\app\validation\exceptions;

use Exception;

class MustHaveLengthGreatherThanError extends Exception
{
    public function __construct(string $name, int $length)
    {
        http_response_code(411);
        parent::__construct("A variável {$name} deve ter mais de {$length} caracteres");
    }
}
