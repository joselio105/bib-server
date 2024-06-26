<?php

namespace plugse\server\core\errors;

use Exception;

class FileNotFoundError extends Exception
{
    public function __construct(string $filename)
    {
        http_response_code(404);
        parent::__construct("Erro: O arquivo '{$filename}' não foi encontrado");
        
    }
}
