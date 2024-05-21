<?php

namespace plugse\server\core\errors;

use Exception;

class ActionNotFoundError extends Exception
{
    public function __construct(string $action, string $classname)
    {
        http_response_code(404);
        parent::__construct("Erro: o método '{$action}' não existe na classe '{$classname}'");
        
    }
}
