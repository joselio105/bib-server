<?php

namespace plugse\server\core\app\validation\exceptions;

use plugse\server\core\app\validation\ValidationException;

class MustHaveSpecialCharsError extends ValidationException
{
    public function __construct(string $name)
    {
        parent::__construct("A variável {$name} deve ter ao menos um caractere especial", 412);
    }
}
