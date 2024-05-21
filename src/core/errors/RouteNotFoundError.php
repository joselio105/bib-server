<?php

namespace plugse\server\core\errors;

use Exception;

class RouteNotFoundError extends Exception
{
    public function __construct()
    {
        http_response_code(404);
        parent::__construct('Nenhuma rota correspondente a URI passada');
        
    }
}
