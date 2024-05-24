<?php

namespace plugse\server\core\errors;

use Exception;

class PropertyNotFoundError extends Exception
{
    public function __construct(string $filename)
    {
        http_response_code(404);
        parent::__construct("As configurações do banco de dados não foram identificadas no arquivo '{$filename}'");
        
    }
}
