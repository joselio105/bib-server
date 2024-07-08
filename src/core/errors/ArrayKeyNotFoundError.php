<?php

namespace plugse\server\core\errors;

use Exception;

class ArrayKeyNotFoundError extends Exception
{
    public function __construct(string $key, $arrayName)
    {
        http_response_code(404);
        parent::__construct("Erro: a chave '{$key}' não foi encontrada no array '{$arrayName}'");
        
    }
}
