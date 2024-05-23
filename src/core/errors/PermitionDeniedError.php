<?php

namespace plugse\server\core\errors;

use Exception;

class PermitionDeniedError extends Exception
{
    public function __construct(string $uri, string $httpMethod)
    {
        http_response_code(401);
        parent::__construct("Erro: É necessário estar autenticado para executar '{$uri}' pelo método '{$httpMethod}'");
    }
    
}
