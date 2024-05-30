<?php

namespace plugse\server\core\app\validation\exceptions;

use Exception;

class MustHaveUpperCaseCharsError extends Exception
{
    public function __construct(string $name)
    {
        http_response_code(412);
        parent::__construct("A variável {$name} deve ter ao menos um caractere maiúsculo");
    }
}
