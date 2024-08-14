<?php

namespace plugse\server\core\app\validation\exceptions;

use plugse\server\core\app\validation\ValidationException;

class MustBeEmailError extends ValidationException
{
    public function __construct(string $name)
    {
        parent::__construct("A variável {$name} deve ser do tipo Email", 406);
    }
}
