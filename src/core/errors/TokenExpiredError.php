<?php

namespace plugse\server\core\errors;

use Exception;

class TokenExpiredError extends Exception
{
    public function __construct()
    {
        http_response_code(410);
        parent::__construct('Erro: Token expirado');
    }
}
