<?php

namespace plugse\server\app\validations\exceptions;

use plugse\server\core\app\validation\ValidationException;

class MustBeAuthorsError extends ValidationException
{
    public function __construct(string $name)
    {
        parent::__construct("A variável {$name} deve ser uma lista de Autores(Sobrenome, Nome), separados por ; ", 406);
    }
}
