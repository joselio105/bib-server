<?php

namespace plugse\server\core\app\validation\exceptions;

use plugse\server\core\app\validation\ValidationException;

class IsUniqueError extends ValidationException
{
    public function __construct(string $name, $value, string $table)
    {
        parent::__construct("O campo {$name} já foi cadastrado como '{$value}' anteriormente na tabela {$table}.", 406);
    }
}
