<?php

namespace plugse\server\core\errors;

use Exception;

class ClassNotFoundError extends Exception
{
    public function __construct(string $classname)
    {
        http_response_code(404);
        parent::__construct("Erro: a classe '{$classname}' não foi encontrada");
        
    }
}
