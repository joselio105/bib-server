<?php

namespace plugse\server\core\errors;

use Exception;

class RouteInconcistenceError extends Exception
{
    public function __construct()
    {
        http_response_code(409);
        parent::__construct('Rotas inconsistentes, não pode haver mais de uma rota compatível');
        
    }
}
