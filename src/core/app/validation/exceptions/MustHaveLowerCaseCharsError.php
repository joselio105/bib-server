<?php

namespace plugse\server\core\app\validation\exceptions;

use plugse\server\core\app\validation\ValidationException;

class MustHaveLowerCaseCharsError extends ValidationException
{
    public function __construct(string $name)
    {
        parent::__construct("A variável {$name} deve ter ao menos um caractere minúsculo", 412);
    }
}
