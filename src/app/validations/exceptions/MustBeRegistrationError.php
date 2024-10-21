<?php

namespace plugse\server\app\validations\exceptions;

use plugse\server\core\app\validation\ValidationException;

class MustBeRegistrationError extends ValidationException
{
    public function __construct(string $name)
    {
        parent::__construct("A variável {$name} deve ser do tipo tombo patrimonial", 406);
    }
}
